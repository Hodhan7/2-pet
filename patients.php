<?php
require 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'veterinarian') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Handle search and filters
$search = $_GET['search'] ?? '';
$species_filter = $_GET['species'] ?? '';
$status_filter = $_GET['status'] ?? '';
$sort_by = $_GET['sort'] ?? 'name';
$view_mode = $_GET['view'] ?? 'grid'; // grid or list

// Get all patients for this veterinarian with search and filters
$where_conditions = [];
$params = [];
$param_types = "";

// Base query to get all pets that this veterinarian has treated
$base_query = "
    SELECT DISTINCT p.id, p.name, p.species, p.breed, p.dob, p.weight, p.color, p.photo_url,
           p.allergies, p.medications, p.special_needs, p.microchip_id,
           u.first_name as owner_first_name, u.last_name as owner_last_name, 
           u.email as owner_email, u.phone as owner_phone, u.address as owner_address,
           MAX(a.appointment_date) as last_appointment,
           MAX(hr.record_date) as last_health_record,
           COUNT(DISTINCT a.id) as total_appointments,
           COUNT(DISTINCT hr.id) as total_health_records,
           COUNT(DISTINCT CASE WHEN a.appointment_date >= CURDATE() AND a.status IN ('confirmed', 'pending') THEN a.id END) as upcoming_appointments,
           COUNT(DISTINCT CASE WHEN v.next_due_date >= CURDATE() THEN v.id END) as due_vaccinations,
           CASE 
               WHEN MAX(a.appointment_date) > DATE_SUB(NOW(), INTERVAL 6 MONTH) 
                 OR MAX(hr.record_date) > DATE_SUB(NOW(), INTERVAL 6 MONTH) 
               THEN 'active'
               WHEN MAX(a.appointment_date) > DATE_SUB(NOW(), INTERVAL 12 MONTH) 
                 OR MAX(hr.record_date) > DATE_SUB(NOW(), INTERVAL 12 MONTH) 
               THEN 'inactive'
               ELSE 'archived'
           END as patient_status
    FROM pets p 
    JOIN users u ON p.owner_id = u.id
    LEFT JOIN appointments a ON p.id = a.pet_id AND a.veterinarian_id = ?
    LEFT JOIN health_records hr ON p.id = hr.pet_id AND hr.veterinarian_id = ?
    LEFT JOIN vaccinations v ON p.id = v.pet_id AND v.veterinarian_id = ?
    WHERE (a.id IS NOT NULL OR hr.id IS NOT NULL OR v.id IS NOT NULL)
";

$params = [$user_id, $user_id, $user_id];
$param_types = "iii";

// Apply search filter
if (!empty($search)) {
    $base_query .= " AND (p.name LIKE ? OR u.first_name LIKE ? OR u.last_name LIKE ? OR p.species LIKE ? OR p.breed LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param, $search_param]);
    $param_types .= "sssss";
}

// Apply species filter
if (!empty($species_filter)) {
    $base_query .= " AND p.species = ?";
    $params[] = $species_filter;
    $param_types .= "s";
}

$base_query .= " GROUP BY p.id";

// Apply status filter
if (!empty($status_filter)) {
    $base_query .= " HAVING patient_status = ?";
    $params[] = $status_filter;
    $param_types .= "s";
}

// Apply sorting
switch ($sort_by) {
    case 'name':
        $base_query .= " ORDER BY p.name ASC";
        break;
    case 'last_visit':
        $base_query .= " ORDER BY GREATEST(COALESCE(MAX(a.appointment_date), '1900-01-01'), COALESCE(MAX(hr.record_date), '1900-01-01')) DESC";
        break;
    case 'owner':
        $base_query .= " ORDER BY u.last_name ASC, u.first_name ASC";
        break;
    case 'species':
        $base_query .= " ORDER BY p.species ASC, p.name ASC";
        break;
    default:
        $base_query .= " ORDER BY p.name ASC";
}

$stmt = $conn->prepare($base_query);
$stmt->bind_param($param_types, ...$params);
$stmt->execute();
$patients = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get statistics
$total_patients = count($patients);
$active_patients = count(array_filter($patients, fn($p) => $p['patient_status'] === 'active'));
$inactive_patients = count(array_filter($patients, fn($p) => $p['patient_status'] === 'inactive'));
$upcoming_appointments = array_sum(array_column($patients, 'upcoming_appointments'));

// Get unique species for filter dropdown
$species_query = "SELECT DISTINCT p.species FROM pets p 
                  JOIN (SELECT DISTINCT pet_id FROM appointments WHERE veterinarian_id = ? 
                        UNION SELECT DISTINCT pet_id FROM health_records WHERE veterinarian_id = ?) 
                  AS patient_pets ON p.id = patient_pets.pet_id 
                  ORDER BY p.species";
$species_stmt = $conn->prepare($species_query);
$species_stmt->bind_param("ii", $user_id, $user_id);
$species_stmt->execute();
$species_list = $species_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Patients - Pet Health Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans">
    <?php include 'includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">My Patients</h1>
                    <p class="text-gray-600">Manage and view all your patient records</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                        Veterinarian Dashboard
                    </div>
                    <a href="vet_dashboard.php" class="text-blue-600 hover:text-blue-700 font-medium">
                        ← Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <?php if ($success_message): ?>
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 flex items-center">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="grid md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Patients</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo $total_patients; ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Patients</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo $active_patients; ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Upcoming Appointments</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo $upcoming_appointments; ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Inactive Patients</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo $inactive_patients; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <form method="GET" class="space-y-4">
                <div class="grid md:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Patients</label>
                        <div class="relative">
                            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                                   placeholder="Search by pet name, owner name, species..."
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Species Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Species</label>
                        <select name="species" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Species</option>
                            <?php foreach ($species_list as $species): ?>
                                <option value="<?php echo htmlspecialchars($species['species']); ?>" 
                                        <?php echo $species_filter === $species['species'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars(ucfirst($species['species'])); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Patients</option>
                            <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active (Recent)</option>
                            <option value="inactive" <?php echo $status_filter === 'inactive' ? 'selected' : ''; ?>>Inactive (6+ months)</option>
                            <option value="archived" <?php echo $status_filter === 'archived' ? 'selected' : ''; ?>>Archived (1+ year)</option>
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="name" <?php echo $sort_by === 'name' ? 'selected' : ''; ?>>Pet Name</option>
                            <option value="last_visit" <?php echo $sort_by === 'last_visit' ? 'selected' : ''; ?>>Last Visit</option>
                            <option value="owner" <?php echo $sort_by === 'owner' ? 'selected' : ''; ?>>Owner Name</option>
                            <option value="species" <?php echo $sort_by === 'species' ? 'selected' : ''; ?>>Species</option>
                        </select>
                    </div>
                </div>

                <!-- Hidden view mode input -->
                <input type="hidden" name="view" value="<?php echo $view_mode; ?>">

                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="flex items-center space-x-4">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search
                        </button>
                        <a href="patients.php" class="text-gray-600 hover:text-gray-800 px-4 py-2">Clear Filters</a>
                    </div>

                    <!-- View Mode Toggle -->
                    <div class="flex items-center bg-gray-100 rounded-lg p-1">
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['view' => 'grid'])); ?>" 
                           class="px-3 py-1 rounded <?php echo $view_mode === 'grid' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-600 hover:text-gray-800'; ?>">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                        </a>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['view' => 'list'])); ?>" 
                           class="px-3 py-1 rounded <?php echo $view_mode === 'list' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-600 hover:text-gray-800'; ?>">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Results Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">
                    Patient List 
                    <?php if (!empty($search) || !empty($species_filter) || !empty($status_filter)): ?>
                        <span class="text-gray-500 font-normal">- Filtered Results</span>
                    <?php endif; ?>
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Showing <?php echo count($patients); ?> of <?php echo $total_patients; ?> patients
                </p>
            </div>
        </div>

        <!-- Patients Display -->
        <?php if (empty($patients)): ?>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No patients found</h3>
                <p class="text-gray-500 mb-4">
                    <?php if (!empty($search) || !empty($species_filter) || !empty($status_filter)): ?>
                        Try adjusting your search criteria or clearing the filters.
                    <?php else: ?>
                        Patients will appear here after you see them for appointments or add health records.
                    <?php endif; ?>
                </p>
                <?php if (!empty($search) || !empty($species_filter) || !empty($status_filter)): ?>
                    <a href="patients.php" class="text-blue-600 hover:text-blue-700 font-medium">Clear all filters</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <?php if ($view_mode === 'grid'): ?>
                <!-- Grid View -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($patients as $patient): ?>
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                            <!-- Patient Header -->
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-green-500 rounded-full flex items-center justify-center">
                                            <span class="text-white font-bold text-lg"><?php echo strtoupper(substr($patient['name'], 0, 1)); ?></span>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($patient['name']); ?></h3>
                                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($patient['species']); ?> • <?php echo htmlspecialchars($patient['breed'] ?? 'Mixed'); ?></p>
                                        </div>
                                    </div>
                                    
                                    <!-- Status Badge -->
                                    <?php
                                    $status_colors = [
                                        'active' => 'bg-green-100 text-green-800',
                                        'inactive' => 'bg-yellow-100 text-yellow-800',
                                        'archived' => 'bg-gray-100 text-gray-800'
                                    ];
                                    ?>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo $status_colors[$patient['patient_status']]; ?>">
                                        <?php echo ucfirst($patient['patient_status']); ?>
                                    </span>
                                </div>

                                <!-- Owner Info -->
                                <div class="text-sm text-gray-600">
                                    <p><strong>Owner:</strong> <?php echo htmlspecialchars($patient['owner_first_name'] . ' ' . $patient['owner_last_name']); ?></p>
                                    <?php if ($patient['owner_phone']): ?>
                                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($patient['owner_phone']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Patient Stats -->
                            <div class="p-6 bg-gray-50">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">Last Visit:</span>
                                        <p class="font-medium text-gray-900">
                                            <?php 
                                            $last_visit = max($patient['last_appointment'] ?? '1900-01-01', $patient['last_health_record'] ?? '1900-01-01');
                                            echo $last_visit && $last_visit !== '1900-01-01' ? date('M j, Y', strtotime($last_visit)) : 'Never';
                                            ?>
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Records:</span>
                                        <p class="font-medium text-gray-900"><?php echo $patient['total_health_records']; ?></p>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Appointments:</span>
                                        <p class="font-medium text-gray-900"><?php echo $patient['total_appointments']; ?></p>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Upcoming:</span>
                                        <p class="font-medium text-gray-900"><?php echo $patient['upcoming_appointments']; ?></p>
                                    </div>
                                </div>

                                <!-- Health Alerts -->
                                <?php if ($patient['allergies'] || $patient['special_needs'] || $patient['due_vaccinations'] > 0): ?>
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <div class="flex flex-wrap gap-2">
                                            <?php if ($patient['allergies']): ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Allergies
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($patient['special_needs']): ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Special Needs
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($patient['due_vaccinations'] > 0): ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Vaccination Due
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Action Buttons -->
                            <div class="p-6 bg-white border-t border-gray-200">
                                <div class="flex space-x-2">
                                    <a href="vet_pet_details.php?pet_id=<?php echo $patient['id']; ?>" 
                                       class="flex-1 bg-blue-600 text-white text-center px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm font-medium">
                                        View Details
                                    </a>
                                    <a href="vet_pet_details.php?pet_id=<?php echo $patient['id']; ?>&add_record=1" 
                                       class="flex-1 bg-green-600 text-white text-center px-3 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200 text-sm font-medium">
                                        Add Record
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <!-- List View -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Visit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Records</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alerts</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($patients as $patient): ?>
                                    <tr class="hover:bg-gray-50">
                                        <!-- Patient -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-green-500 rounded-full flex items-center justify-center">
                                                    <span class="text-white font-medium text-sm"><?php echo strtoupper(substr($patient['name'], 0, 1)); ?></span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($patient['name']); ?></div>
                                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($patient['species']); ?> • <?php echo htmlspecialchars($patient['breed'] ?? 'Mixed'); ?></div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Owner -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($patient['owner_first_name'] . ' ' . $patient['owner_last_name']); ?></div>
                                            <?php if ($patient['owner_phone']): ?>
                                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($patient['owner_phone']); ?></div>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Status -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo $status_colors[$patient['patient_status']]; ?>">
                                                <?php echo ucfirst($patient['patient_status']); ?>
                                            </span>
                                        </td>

                                        <!-- Last Visit -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php 
                                            $last_visit = max($patient['last_appointment'] ?? '1900-01-01', $patient['last_health_record'] ?? '1900-01-01');
                                            echo $last_visit && $last_visit !== '1900-01-01' ? date('M j, Y', strtotime($last_visit)) : 'Never';
                                            ?>
                                        </td>

                                        <!-- Records -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="flex space-x-4">
                                                <span><?php echo $patient['total_health_records']; ?> records</span>
                                                <span class="text-gray-400">•</span>
                                                <span><?php echo $patient['total_appointments']; ?> appointments</span>
                                            </div>
                                        </td>

                                        <!-- Alerts -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex space-x-1">
                                                <?php if ($patient['allergies']): ?>
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800" title="Allergies">
                                                        ⚠️
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($patient['special_needs']): ?>
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800" title="Special Needs">
                                                        ℹ️
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($patient['due_vaccinations'] > 0): ?>
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800" title="Vaccination Due">
                                                        💉
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($patient['upcoming_appointments'] > 0): ?>
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800" title="Upcoming Appointments">
                                                        📅
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="vet_pet_details.php?pet_id=<?php echo $patient['id']; ?>" 
                                                   class="text-blue-600 hover:text-blue-900">View</a>
                                                <a href="vet_pet_details.php?pet_id=<?php echo $patient['id']; ?>&add_record=1" 
                                                   class="text-green-600 hover:text-green-900">Add Record</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Quick Actions Footer -->
        <?php if (!empty($patients)): ?>
            <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid md:grid-cols-3 gap-4">
                    <a href="manage_appointments.php" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Manage Appointments</h4>
                            <p class="text-sm text-gray-500">View and manage upcoming appointments</p>
                        </div>
                    </a>

                    <a href="vet_dashboard.php" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2m-6 4h4"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Dashboard</h4>
                            <p class="text-sm text-gray-500">Return to main veterinarian dashboard</p>
                        </div>
                    </a>

                    <a href="profile.php" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">My Profile</h4>
                            <p class="text-sm text-gray-500">Update professional information</p>
                        </div>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Auto-submit form on filter changes (except search)
        document.querySelectorAll('select[name="species"], select[name="status"], select[name="sort"]').forEach(select => {
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });

        // Search form submission on Enter
        document.querySelector('input[name="search"]').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.form.submit();
            }
        });

        // Highlight search terms
        const searchTerm = '<?php echo addslashes($search); ?>';
        if (searchTerm) {
            const regex = new RegExp(`(${searchTerm})`, 'gi');
            document.querySelectorAll('[data-searchable]').forEach(element => {
                element.innerHTML = element.innerHTML.replace(regex, '<mark class="bg-yellow-200">$1</mark>');
            });
        }
    </script>

    <?php include 'includes/footer.php'; ?>
</body>
</html>

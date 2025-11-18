<?php
require 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pet_owner') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$selected_pet_id = $_GET['pet_id'] ?? null;

// Get user's pets
$pets_query = "SELECT * FROM pets WHERE owner_id = ? ORDER BY name";
$pets_stmt = $conn->prepare($pets_query);
$pets_stmt->bind_param("i", $user_id);
$pets_stmt->execute();
$pets = $pets_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get veterinarians
$vets = $conn->query("SELECT id, first_name, last_name, email FROM users WHERE role = 'veterinarian' ORDER BY first_name, last_name")->fetch_all(MYSQLI_ASSOC);

// Get user's appointments
$appointments_query = "
    SELECT a.*, 
           p.name as pet_name, p.species, p.breed,
           v.first_name as vet_first_name, v.last_name as vet_last_name, v.email as vet_email
    FROM appointments a
    JOIN pets p ON a.pet_id = p.id
    JOIN users v ON a.veterinarian_id = v.id
    WHERE a.owner_id = ?
    ORDER BY a.appointment_date DESC
";
$appointments_stmt = $conn->prepare($appointments_query);
$appointments_stmt->bind_param("i", $user_id);
$appointments_stmt->execute();
$all_appointments = $appointments_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Separate appointments into upcoming and past
$upcoming_appointments = [];
$past_appointments = [];
$current_datetime = date('Y-m-d H:i:s');

foreach ($all_appointments as $appointment) {
    if ($appointment['appointment_date'] >= $current_datetime && in_array($appointment['status'], ['pending', 'confirmed'])) {
        $upcoming_appointments[] = $appointment;
    } else {
        $past_appointments[] = $appointment;
    }
}

$success_message = '';
$error_message = '';
$debug_info = '';

// Enable error reporting for debugging
if (isset($_GET['debug']) && $_GET['debug'] == '1') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pet_id = $_POST['pet_id'];
    $veterinarian_id = $_POST['veterinarian_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $appointment_type = $_POST['appointment_type'] ?? 'checkup';
    $purpose = $_POST['purpose'] ?? '';
    $notes = $_POST['notes'] ?? '';
    
    // Debug information
    if (isset($_GET['debug'])) {
        $debug_info = "POST Data: " . print_r($_POST, true);
    }
    
    // Validate required fields
    if (empty($pet_id) || empty($veterinarian_id) || empty($appointment_date) || empty($appointment_time)) {
        $missing_fields = [];
        if (empty($pet_id)) $missing_fields[] = "Pet";
        if (empty($veterinarian_id)) $missing_fields[] = "Veterinarian";
        if (empty($appointment_date)) $missing_fields[] = "Date";
        if (empty($appointment_time)) $missing_fields[] = "Time";
        $error_message = "Please fill in all required fields: " . implode(", ", $missing_fields);
    } else {
        // Combine date and time into a datetime string
        $appointment_datetime = $appointment_date . ' ' . $appointment_time . ':00';
        
        // Validate that the pet belongs to the owner
        $pet_check = $conn->prepare("SELECT id FROM pets WHERE id = ? AND owner_id = ?");
        $pet_check->bind_param("ii", $pet_id, $user_id);
        $pet_check->execute();
        $pet_result = $pet_check->get_result();
        
        if ($pet_result->num_rows > 0) {
            // Check if the appointment datetime is in the future
            if (strtotime($appointment_datetime) <= time()) {
                $error_message = "Please select a future date and time for the appointment.";
            } else {
                // Check for conflicting appointments (same vet, same time)
                $conflict_check = $conn->prepare("SELECT id FROM appointments WHERE veterinarian_id = ? AND appointment_date = ? AND status IN ('pending', 'confirmed')");
                $conflict_check->bind_param("is", $veterinarian_id, $appointment_datetime);
                $conflict_check->execute();
                $conflict_result = $conflict_check->get_result();
                
                if ($conflict_result->num_rows > 0) {
                    $error_message = "This time slot is already booked. Please select a different time.";
                } else {
                    $stmt = $conn->prepare("INSERT INTO appointments (pet_id, veterinarian_id, owner_id, appointment_date, appointment_type, purpose, notes, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
                    $stmt->bind_param("iiissss", $pet_id, $veterinarian_id, $user_id, $appointment_datetime, $appointment_type, $purpose, $notes);
                    
                    if ($stmt->execute()) {
                        $success_message = "Appointment scheduled successfully! Your appointment is pending confirmation.";
                        // Clear form data after successful submission
                        $_POST = array();
                    } else {
                        $error_message = "Error scheduling appointment: " . $conn->error . " (SQL: " . $stmt->error . ")";
                    }
                }
            }
        } else {
            $error_message = "Invalid pet selection. The selected pet does not belong to your account.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Appointment - Pet Health Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="assets/css/tailwind.css" rel="stylesheet">
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
    <!-- Removed main.js to prevent conflicts with appointment form -->
</head>
<body class="bg-gray-100 min-h-screen">
    <?php include 'includes/header.php'; ?>
    
    <div class="container py-8">
        <div class="max-w-3xl mx-auto">
            <!-- Page Header -->
            <div class="mb-8">
                <nav class="text-sm text-gray-500 mb-2">
                    <a href="owner_dashboard.php" class="hover:text-blue-600">Dashboard</a> 
                    <span class="mx-2">></span> 
                    <span>Appointments</span>
                </nav>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">My Appointments</h1>
                <p class="text-gray-600">Schedule new appointments and view your appointment history</p>
            </div>

            <!-- Success/Error Messages -->
            <?php if ($success_message): ?>
                <div class="card border-l-4 border-l-green-400 mb-6">
                    <div class="card-body">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <p class="text-green-800"><?php echo htmlspecialchars($success_message); ?></p>
                        </div>
                        <div class="mt-4">
                            <a href="owner_dashboard.php" class="btn btn-primary">Return to Dashboard</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="card border-l-4 border-l-red-400 mb-6">
                    <div class="card-body">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-red-800"><?php echo htmlspecialchars($error_message); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($debug_info) && isset($_GET['debug'])): ?>
                <div class="card border-l-4 border-l-yellow-400 mb-6">
                    <div class="card-body">
                        <h4 class="font-bold text-yellow-800 mb-2">Debug Information:</h4>
                        <pre class="text-sm text-yellow-700 bg-yellow-50 p-3 rounded"><?php echo htmlspecialchars($debug_info); ?></pre>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Check if user has pets -->
            <?php if (empty($pets)): ?>
                <div class="card">
                    <div class="card-body text-center py-12">
                        <svg class="mx-auto w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <h3 class="text-xl font-medium text-gray-700 mb-2">No pets registered</h3>
                        <p class="text-gray-500 mb-6">You need to add a pet before scheduling an appointment</p>
                        <a href="add_pet.php" class="btn btn-primary">Add Your First Pet</a>
                    </div>
                </div>
            <?php else: ?>
            <!-- Schedule Form -->
            <div class="card">
                <div class="card-header">
                    <h2 class="text-xl font-bold text-gray-800">Appointment Details</h2>
                </div>
                <div class="card-body">
                    <form action="schedule_appointment.php" method="POST" class="space-y-6">
                        <!-- Pet Selection -->
                        <div>
                            <label for="pet_id" class="block text-sm font-medium text-gray-700 mb-2">Select Pet *</label>
                            <select id="pet_id" name="pet_id" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <option value="">Choose a pet</option>
                                <?php foreach ($pets as $pet): ?>
                                    <?php 
                                    $is_selected = false;
                                    if (isset($_POST['pet_id']) && $_POST['pet_id'] == $pet['id']) {
                                        $is_selected = true;
                                    } elseif (!isset($_POST['pet_id']) && $selected_pet_id == $pet['id']) {
                                        $is_selected = true;
                                    }
                                    ?>
                                    <option value="<?php echo $pet['id']; ?>" <?php echo $is_selected ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($pet['name']); ?> - <?php echo htmlspecialchars($pet['species']); ?> (<?php echo htmlspecialchars($pet['breed'] ?? 'Mixed'); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Veterinarian Selection -->
                        <div>
                            <label for="veterinarian_id" class="block text-sm font-medium text-gray-700 mb-2">Select Veterinarian *</label>
                            <select id="veterinarian_id" name="veterinarian_id" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <option value="">Choose a veterinarian</option>
                                <?php foreach ($vets as $vet): ?>
                                    <option value="<?php echo $vet['id']; ?>" <?php echo (isset($_POST['veterinarian_id']) && $_POST['veterinarian_id'] == $vet['id']) ? 'selected' : ''; ?>>
                                        Dr. <?php echo htmlspecialchars($vet['first_name'] . ' ' . $vet['last_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Date Selection -->
                            <div>
                                <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">Appointment Date *</label>
                                <input type="date" id="appointment_date" name="appointment_date" 
                                       value="<?php echo isset($_POST['appointment_date']) ? htmlspecialchars($_POST['appointment_date']) : ''; ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       min="<?php echo date('Y-m-d'); ?>" required>
                            </div>

                            <!-- Time Selection -->
                            <div>
                                <label for="appointment_time" class="block text-sm font-medium text-gray-700 mb-2">Preferred Time *</label>
                                <select id="appointment_time" name="appointment_time" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    <option value="">Select time</option>
                                    <?php 
                                    $time_slots = [
                                        '09:00' => '9:00 AM',
                                        '09:30' => '9:30 AM',
                                        '10:00' => '10:00 AM',
                                        '10:30' => '10:30 AM',
                                        '11:00' => '11:00 AM',
                                        '11:30' => '11:30 AM',
                                        '13:00' => '1:00 PM',
                                        '13:30' => '1:30 PM',
                                        '14:00' => '2:00 PM',
                                        '14:30' => '2:30 PM',
                                        '15:00' => '3:00 PM',
                                        '15:30' => '3:30 PM',
                                        '16:00' => '4:00 PM',
                                        '16:30' => '4:30 PM'
                                    ];
                                    foreach ($time_slots as $value => $label): 
                                    ?>
                                        <option value="<?php echo $value; ?>" <?php echo (isset($_POST['appointment_time']) && $_POST['appointment_time'] == $value) ? 'selected' : ''; ?>>
                                            <?php echo $label; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Appointment Type -->
                        <div>
                            <label for="appointment_type" class="block text-sm font-medium text-gray-700 mb-2">Appointment Type</label>
                            <select id="appointment_type" name="appointment_type" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <?php 
                                $appointment_types = [
                                    'checkup' => 'General Checkup',
                                    'vaccination' => 'Vaccination',
                                    'emergency' => 'Emergency',
                                    'surgery' => 'Surgery Consultation',
                                    'consultation' => 'Consultation',
                                    'follow_up' => 'Follow-up'
                                ];
                                $selected_type = $_POST['appointment_type'] ?? 'checkup';
                                foreach ($appointment_types as $value => $label): 
                                ?>
                                    <option value="<?php echo $value; ?>" <?php echo ($selected_type == $value) ? 'selected' : ''; ?>>
                                        <?php echo $label; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Purpose of Visit -->
                        <div>
                            <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">Purpose of Visit</label>
                            <input type="text" id="purpose" name="purpose" 
                                   value="<?php echo isset($_POST['purpose']) ? htmlspecialchars($_POST['purpose']) : ''; ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   placeholder="Brief description of the visit purpose">
                        </div>

                        <!-- Additional Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                            <textarea id="notes" name="notes" rows="4" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                      placeholder="Please describe your pet's symptoms or any additional information for the veterinarian"><?php echo isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : ''; ?></textarea>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="owner_dashboard.php" class="btn btn-outline">
                                Cancel
                            </a>
                            <button type="submit" data-no-js-handling class="btn btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Schedule Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            <!-- Appointments History Section -->
            <div class="mt-12">
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <button id="upcoming-tab" class="appointment-tab active py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600" onclick="showTab('upcoming')">
                            Upcoming Appointments (<?php echo count($upcoming_appointments); ?>)
                        </button>
                        <button id="past-tab" class="appointment-tab py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" onclick="showTab('past')">
                            Past Appointments (<?php echo count($past_appointments); ?>)
                        </button>
                    </nav>
                </div>

                <!-- Upcoming Appointments -->
                <div id="upcoming-appointments" class="tab-content">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Upcoming Appointments</h2>
                    
                    <?php if (empty($upcoming_appointments)): ?>
                        <div class="card">
                            <div class="card-body text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-700 mb-2">No Upcoming Appointments</h3>
                                <p class="text-gray-500">You don't have any scheduled appointments. Book one above!</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($upcoming_appointments as $appointment): ?>
                                <div class="card border-l-4 border-l-green-400">
                                    <div class="card-body">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h4 class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($appointment['pet_name']); ?></h4>
                                                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($appointment['species']); ?> • <?php echo htmlspecialchars($appointment['breed'] ?? 'Mixed'); ?></p>
                                                    </div>
                                                </div>
                                                
                                                <div class="grid md:grid-cols-2 gap-4 mb-3">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-700">Date & Time</p>
                                                        <p class="text-sm text-gray-900"><?php echo date('l, F j, Y', strtotime($appointment['appointment_date'])); ?></p>
                                                        <p class="text-sm text-gray-900"><?php echo date('g:i A', strtotime($appointment['appointment_date'])); ?></p>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-700">Veterinarian</p>
                                                        <p class="text-sm text-gray-900">Dr. <?php echo htmlspecialchars($appointment['vet_first_name'] . ' ' . $appointment['vet_last_name']); ?></p>
                                                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($appointment['vet_email']); ?></p>
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <p class="text-sm font-medium text-gray-700">Appointment Type</p>
                                                    <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                                        <?php echo htmlspecialchars(ucfirst($appointment['appointment_type'])); ?>
                                                    </span>
                                                </div>
                                                
                                                <?php if ($appointment['purpose']): ?>
                                                    <div class="mb-3">
                                                        <p class="text-sm font-medium text-gray-700">Purpose</p>
                                                        <p class="text-sm text-gray-900"><?php echo htmlspecialchars($appointment['purpose']); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if ($appointment['notes']): ?>
                                                    <div class="mb-3">
                                                        <p class="text-sm font-medium text-gray-700">Notes</p>
                                                        <p class="text-sm text-gray-900"><?php echo htmlspecialchars($appointment['notes']); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="ml-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $appointment['status'] === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                                    <?php echo htmlspecialchars(ucfirst($appointment['status'])); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Past Appointments -->
                <div id="past-appointments" class="tab-content hidden">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Past Appointments</h2>
                    
                    <?php if (empty($past_appointments)): ?>
                        <div class="card">
                            <div class="card-body text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-8a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-700 mb-2">No Past Appointments</h3>
                                <p class="text-gray-500">Your appointment history will appear here after visits.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($past_appointments as $appointment): ?>
                                <div class="card border-l-4 border-l-gray-400">
                                    <div class="card-body">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-8a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h4 class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($appointment['pet_name']); ?></h4>
                                                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($appointment['species']); ?> • <?php echo htmlspecialchars($appointment['breed'] ?? 'Mixed'); ?></p>
                                                    </div>
                                                </div>
                                                
                                                <div class="grid md:grid-cols-2 gap-4 mb-3">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-700">Date & Time</p>
                                                        <p class="text-sm text-gray-900"><?php echo date('l, F j, Y', strtotime($appointment['appointment_date'])); ?></p>
                                                        <p class="text-sm text-gray-900"><?php echo date('g:i A', strtotime($appointment['appointment_date'])); ?></p>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-700">Veterinarian</p>
                                                        <p class="text-sm text-gray-900">Dr. <?php echo htmlspecialchars($appointment['vet_first_name'] . ' ' . $appointment['vet_last_name']); ?></p>
                                                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($appointment['vet_email']); ?></p>
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <p class="text-sm font-medium text-gray-700">Appointment Type</p>
                                                    <span class="inline-block px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">
                                                        <?php echo htmlspecialchars(ucfirst($appointment['appointment_type'])); ?>
                                                    </span>
                                                </div>
                                                
                                                <?php if ($appointment['purpose']): ?>
                                                    <div class="mb-3">
                                                        <p class="text-sm font-medium text-gray-700">Purpose</p>
                                                        <p class="text-sm text-gray-900"><?php echo htmlspecialchars($appointment['purpose']); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if ($appointment['notes']): ?>
                                                    <div class="mb-3">
                                                        <p class="text-sm font-medium text-gray-700">Notes</p>
                                                        <p class="text-sm text-gray-900"><?php echo htmlspecialchars($appointment['notes']); ?></p>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($appointment['cost']): ?>
                                                    <div class="mb-3">
                                                        <p class="text-sm font-medium text-gray-700">Cost</p>
                                                        <p class="text-sm text-gray-900">$<?php echo number_format($appointment['cost'], 2); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="ml-4">
                                                <?php
                                                $status_colors = [
                                                    'completed' => 'bg-green-100 text-green-800',
                                                    'cancelled' => 'bg-red-100 text-red-800',
                                                    'no_show' => 'bg-orange-100 text-orange-800',
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'confirmed' => 'bg-blue-100 text-blue-800'
                                                ];
                                                $status_class = $status_colors[$appointment['status']] ?? 'bg-gray-100 text-gray-800';
                                                ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $status_class; ?>">
                                                    <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $appointment['status']))); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Information Card -->
            <div class="card border-l-4 border-l-blue-400 mt-8">
                <div class="card-body">
                    <h3 class="text-lg font-semibold text-blue-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Appointment Information
                    </h3>
                    <ul class="text-sm text-blue-700 space-y-2">
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            <span>Appointments are available Monday to Friday, 9:00 AM to 5:00 PM</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            <span>Emergency appointments may be available outside regular hours</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            <span>Please arrive 15 minutes early for your appointment</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            <span>Cancellations must be made at least 24 hours in advance</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-2 h-2 bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                            <span>Bring your pet's vaccination records and any previous medical documents</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-select pet if coming from a specific pet page
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const petId = urlParams.get('pet_id');
            if (petId) {
                const petSelect = document.getElementById('pet_id');
                if (petSelect) {
                    petSelect.value = petId;
                }
            }

            // Simple form validation for appointment form only
            const appointmentForm = document.querySelector('form[action*="schedule_appointment"]');
            if (appointmentForm) {
                appointmentForm.addEventListener('submit', function(e) {
                    const requiredFields = [
                        { field: document.getElementById('pet_id'), name: 'Pet' },
                        { field: document.getElementById('veterinarian_id'), name: 'Veterinarian' },
                        { field: document.getElementById('appointment_date'), name: 'Date' },
                        { field: document.getElementById('appointment_time'), name: 'Time' }
                    ];

                    let missingFields = [];
                    let isValid = true;

                    requiredFields.forEach(item => {
                        if (item.field && !item.field.value.trim()) {
                            isValid = false;
                            missingFields.push(item.name);
                            item.field.classList.add('border-red-500');
                            item.field.classList.remove('border-gray-300');
                        } else if (item.field) {
                            item.field.classList.remove('border-red-500');
                            item.field.classList.add('border-gray-300');
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        alert('Please fill in all required fields: ' + missingFields.join(', '));
                        requiredFields[0].field.focus();
                        return false;
                    }

                    // Disable submit button to prevent double submission
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Scheduling...';
                        
                        // Re-enable if there's an error (page doesn't redirect)
                        setTimeout(() => {
                            if (submitBtn.disabled) {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>Schedule Appointment';
                            }
                        }, 10000);
                    }

                    return true;
                });
            }
        });

        // Tab functionality for appointments
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.appointment-tab').forEach(tab => {
                tab.classList.remove('active', 'border-blue-500', 'text-blue-600');
                tab.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            const selectedContent = document.getElementById(tabName + '-appointments');
            if (selectedContent) {
                selectedContent.classList.remove('hidden');
            }
            
            // Add active class to selected tab
            const selectedTab = document.getElementById(tabName + '-tab');
            if (selectedTab) {
                selectedTab.classList.add('active', 'border-blue-500', 'text-blue-600');
                selectedTab.classList.remove('border-transparent', 'text-gray-500');
            }
        }

        // Initialize tabs
        window.showTab = showTab; // Make function globally available
    });
    </script>

    <style>
        .appointment-tab.active {
            border-bottom-color: #3B82F6;
            color: #2563EB;
        }
        
        .appointment-tab:hover {
            color: #374151;
            border-bottom-color: #D1D5DB;
        }
        
        .tab-content {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid #E5E7EB;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background-color: #3B82F6;
            color: white;
            border: 1px solid #3B82F6;
        }
        
        .btn-primary:hover {
            background-color: #2563EB;
            border-color: #2563EB;
        }
        
        .btn-outline {
            background-color: white;
            color: #374151;
            border: 1px solid #D1D5DB;
        }
        
        .btn-outline:hover {
            background-color: #F9FAFB;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
    </style>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
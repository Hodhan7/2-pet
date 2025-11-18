<?php
require_once '../db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Get overall statistics
$stats_query = "
    SELECT 
        (SELECT COUNT(*) FROM users WHERE role='pet_owner') as total_owners,
        (SELECT COUNT(*) FROM users WHERE role='veterinarian') as total_vets,
        (SELECT COUNT(*) FROM pets) as total_pets,
        (SELECT COUNT(*) FROM appointments) as total_appointments,
        (SELECT COUNT(*) FROM health_records) as total_health_records,
        (SELECT COUNT(*) FROM appointments WHERE status='pending') as pending_appointments,
        (SELECT COUNT(*) FROM appointments WHERE status='confirmed') as confirmed_appointments,
        (SELECT COUNT(*) FROM appointments WHERE status='completed') as completed_appointments,
        (SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)) as new_users_30days
";

$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();

// Get recent activity
$recent_appointments = $conn->query("
    SELECT a.*, p.name as pet_name, 
           CONCAT(owner.first_name, ' ', owner.last_name) as owner_name,
           CONCAT(vet.first_name, ' ', vet.last_name) as vet_name
    FROM appointments a 
    JOIN pets p ON a.pet_id = p.id
    JOIN users owner ON a.owner_id = owner.id
    JOIN users vet ON a.veterinarian_id = vet.id
    ORDER BY a.created_at DESC 
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

$recent_users = $conn->query("
    SELECT id, first_name, last_name, email, role, created_at
    FROM users 
    WHERE role IN ('pet_owner', 'veterinarian')
    ORDER BY created_at DESC 
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

$recent_pets = $conn->query("
    SELECT p.*, CONCAT(u.first_name, ' ', u.last_name) as owner_name
    FROM pets p
    JOIN users u ON p.owner_id = u.id
    ORDER BY p.id DESC
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

// Get appointment statistics by status
$appointment_stats = $conn->query("
    SELECT status, COUNT(*) as count
    FROM appointments
    GROUP BY status
")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Pet Health Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <?php include '../includes/header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Admin Dashboard</h1>
            <p class="text-gray-600">Overview of your Pet Health Tracker system</p>
        </div>

        <!-- Main Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $stats['total_owners'] + $stats['total_vets']; ?></p>
                        <p class="text-sm text-gray-500"><?php echo $stats['total_owners']; ?> owners, <?php echo $stats['total_vets']; ?> vets</p>
                    </div>
                </div>
            </div>

            <!-- Total Pets -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Pets</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $stats['total_pets']; ?></p>
                        <p class="text-sm text-gray-500">Registered pets</p>
                    </div>
                </div>
            </div>

            <!-- Total Appointments -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Appointments</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $stats['total_appointments']; ?></p>
                        <p class="text-sm text-gray-500"><?php echo $stats['pending_appointments']; ?> pending</p>
                    </div>
                </div>
            </div>

            <!-- Health Records -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Health Records</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $stats['total_health_records']; ?></p>
                        <p class="text-sm text-gray-500">Total records</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="manage_users.php" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="p-2 bg-blue-100 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Manage Users</h3>
                        <p class="text-sm text-gray-500">View and manage all users</p>
                    </div>
                </a>

                <a href="../manage_appointments.php" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="p-2 bg-green-100 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Manage Appointments</h3>
                        <p class="text-sm text-gray-500">View all appointments</p>
                    </div>
                </a>

                <a href="manage_vet_application.php" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="p-2 bg-purple-100 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Vet Applications</h3>
                        <p class="text-sm text-gray-500">Review vet applications</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Recent Activity -->
            <div class="space-y-6">
                <!-- Recent Appointments -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Recent Appointments</h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <?php if (empty($recent_appointments)): ?>
                        <div class="px-6 py-4 text-center text-gray-500">
                            No recent appointments
                        </div>
                        <?php else: ?>
                            <?php foreach ($recent_appointments as $appointment): ?>
                            <div class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($appointment['pet_name']); ?> - <?php echo htmlspecialchars($appointment['owner_name']); ?>
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            Dr. <?php echo htmlspecialchars($appointment['vet_name']); ?> • 
                                            <?php echo date('M j, Y g:i A', strtotime($appointment['appointment_date'])); ?>
                                        </p>
                                    </div>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        <?php 
                                        echo match($appointment['status']) {
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'confirmed' => 'bg-blue-100 text-blue-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                        ?>">
                                        <?php echo ucfirst($appointment['status']); ?>
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Users -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Recent Users</h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <?php if (empty($recent_users)): ?>
                        <div class="px-6 py-4 text-center text-gray-500">
                            No recent users
                        </div>
                        <?php else: ?>
                            <?php foreach ($recent_users as $user): ?>
                            <div class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            <?php echo htmlspecialchars($user['email']); ?>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            <?php echo $user['role'] === 'veterinarian' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $user['role'])); ?>
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <?php echo date('M j, Y', strtotime($user['created_at'])); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Charts and Analytics -->
            <div class="space-y-6">
                <!-- Appointment Status Chart -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Appointment Status</h2>
                    <div class="space-y-3">
                        <?php foreach ($appointment_stats as $stat): ?>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full mr-3
                                    <?php 
                                    echo match($stat['status']) {
                                        'pending' => 'bg-yellow-400',
                                        'confirmed' => 'bg-blue-400',
                                        'completed' => 'bg-green-400',
                                        'cancelled' => 'bg-red-400',
                                        default => 'bg-gray-400'
                                    };
                                    ?>"></div>
                                <span class="text-sm font-medium text-gray-700">
                                    <?php echo ucfirst(str_replace('_', ' ', $stat['status'])); ?>
                                </span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900"><?php echo $stat['count']; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Recent Pets -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Recently Added Pets</h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <?php if (empty($recent_pets)): ?>
                        <div class="px-6 py-4 text-center text-gray-500">
                            No recent pets
                        </div>
                        <?php else: ?>
                            <?php foreach ($recent_pets as $pet): ?>
                            <div class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-green-500 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($pet['name']); ?>
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            <?php echo htmlspecialchars($pet['species'] . ' • ' . $pet['breed']); ?> - 
                                            Owner: <?php echo htmlspecialchars($pet['owner_name']); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- System Health -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">System Health</h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Database Status</span>
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-1"></div>
                                Online
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Active Users (30 days)</span>
                            <span class="text-sm font-semibold text-gray-900"><?php echo $stats['new_users_30days']; ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total Data Records</span>
                            <span class="text-sm font-semibold text-gray-900">
                                <?php echo $stats['total_pets'] + $stats['total_appointments'] + $stats['total_health_records']; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        // Auto-refresh dashboard every 5 minutes
        setTimeout(function() {
            location.reload();
        }, 300000);
    </script>
</body>
</html>

<?php
require_once '../db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$success_message = '';
$error_message = '';

// Handle veterinarian application approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $user_id = (int)$_POST['user_id'];
    
    if ($_POST['action'] === 'approve') {
        // Update user status to approved veterinarian
        $stmt = $conn->prepare("UPDATE users SET status = 'approved' WHERE id = ? AND role = 'veterinarian'");
        $stmt->bind_param("i", $user_id);
        
        if ($stmt->execute()) {
            $success_message = "Veterinarian application approved successfully.";
        } else {
            $error_message = "Error approving application.";
        }
    } elseif ($_POST['action'] === 'reject') {
        // Update user status to rejected
        $stmt = $conn->prepare("UPDATE users SET status = 'rejected' WHERE id = ? AND role = 'veterinarian'");
        $stmt->bind_param("i", $user_id);
        
        if ($stmt->execute()) {
            $success_message = "Veterinarian application rejected.";
        } else {
            $error_message = "Error rejecting application.";
        }
    }
}

// Get all veterinarian applications
$pending_vets = $conn->query("
    SELECT id, first_name, last_name, email, license_number, specialization, experience_years, 
           education, created_at, status
    FROM users 
    WHERE role = 'veterinarian' 
    ORDER BY created_at DESC
")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Veterinarian Applications - Pet Health Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">

    <?php include '../includes/header.php'; ?>

    <main class="min-h-screen py-8">
        <div class="container mx-auto px-4">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Veterinarian Applications</h1>
                        <p class="mt-2 text-gray-600">Review and manage veterinarian registration applications</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="admin_dashboard.php" class="text-blue-600 hover:text-blue-800">
                            ← Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            <?php if ($success_message): ?>
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-lg p-4">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-lg p-4">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <!-- Applications Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">All Veterinarian Applications</h2>
                </div>

                <?php if (empty($pending_vets)): ?>
                    <div class="p-6 text-center text-gray-500">
                        No veterinarian applications found.
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Veterinarian
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        License
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Specialization
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Experience
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Applied
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($pending_vets as $vet): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($vet['first_name'] . ' ' . $vet['last_name']); ?>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($vet['email']); ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo htmlspecialchars($vet['license_number'] ?? 'N/A'); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo htmlspecialchars($vet['specialization'] ?? 'General'); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo htmlspecialchars($vet['experience_years'] ?? '0'); ?> years
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php
                                            $status = $vet['status'] ?? 'pending';
                                            $status_class = 'bg-yellow-100 text-yellow-800';
                                            if ($status === 'approved') {
                                                $status_class = 'bg-green-100 text-green-800';
                                            } elseif ($status === 'rejected') {
                                                $status_class = 'bg-red-100 text-red-800';
                                            }
                                            ?>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $status_class; ?>">
                                                <?php echo ucfirst($status); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo date('M j, Y', strtotime($vet['created_at'])); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <?php if ($status === 'pending'): ?>
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="user_id" value="<?php echo $vet['id']; ?>">
                                                    <button type="submit" name="action" value="approve" 
                                                            class="text-green-600 hover:text-green-900 mr-3"
                                                            onclick="return confirm('Approve this veterinarian application?')">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="user_id" value="<?php echo $vet['id']; ?>">
                                                    <button type="submit" name="action" value="reject" 
                                                            class="text-red-600 hover:text-red-900"
                                                            onclick="return confirm('Reject this veterinarian application?')">
                                                        Reject
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-gray-400">No actions</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>

</body>
</html>

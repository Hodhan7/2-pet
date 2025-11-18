<?php
// Script to update user passwords with proper hashes

require 'db.php';

// Default passwords for testing
$passwords = [
    'admin@pethealthtracker.com' => 'admin123',
    'dr.smith@vetclinic.com' => 'vet123',
    'dr.johnson@animalcare.com' => 'vet123',
    'owner1@email.com' => 'owner123',
    'owner2@email.com' => 'owner123'
];

foreach ($passwords as $email => $password) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $email);
    
    if ($stmt->execute()) {
        echo "Updated password for $email (password: $password)\n";
    } else {
        echo "Failed to update password for $email\n";
    }
}

echo "\nPassword update complete!\n";
echo "Test login credentials:\n";
echo "Admin: admin@pethealthtracker.com / admin123\n";
echo "Vet: dr.smith@vetclinic.com / vet123\n";
echo "Owner: owner1@email.com / owner123\n";
?>

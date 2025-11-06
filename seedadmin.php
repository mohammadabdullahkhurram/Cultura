<?php
require 'db.php';
$u = 'admin';
$h = password_hash('admin-123', PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT IGNORE INTO auth_users(username,password_hash) VALUES (?,?)");
$stmt->bind_param("ss", $u, $h);
$stmt->execute();
echo "Seeded admin (admin/admin123). Delete seed_admin.php now.";
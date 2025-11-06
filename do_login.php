<?php
require 'db.php';
session_start();

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$valid_user = 'admin';
$valid_pass = 'admin-123';

if ($username === $valid_user && $password === $valid_pass) {
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $username;
    header('Location: maintenance.html'); 
	//redirect after login
    exit;
} else {
    header('Location: login.php?err=1');
    exit;
}
<?php
require_once "../../db/config.php";

// Check if user is already logged in
if (isset($_SESSION['user'])) {
    header('Location: ../../staff-management/cashier/dashboard.php'); 
    exit; 
}

// Preparing an error message
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password === $user['password']) {
        // Check if user is cashier
        if ($user['role'] === 'cashier') {
            $_SESSION['user'] = $user;
            header('Location: ../../staff-management/cashier/dashboard.php');
            exit;
        } else {
            $err = 'Access denied. Cashier privileges required.';
        }
    } else {
        $err = 'Invalid credentials';
    }
}
?>
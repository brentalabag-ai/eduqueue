<?php
require_once "../db/config.php";

// Check if student is logged in
if (!isset($_SESSION['student'])) {
    header("Location: ../student-management/student_login.php");
    exit;
}

$student = $_SESSION['student'];
$error = "";
$success = "";

// Process payment slip form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_payment_slip'])) {
    // Validate required fields
    $amount = trim($_POST['amount'] ?? '');
    $payment_for = $_POST['payment_for'] ?? [];
    $other_purpose = trim($_POST['other_purpose'] ?? '');
    
    if (empty($amount) || !is_numeric($amount) || $amount <= 0) {
        $error = "Please enter a valid amount.";
    } elseif (empty($payment_for)) {
        $error = "Please select at least one payment purpose.";
    } elseif (in_array('others', $payment_for) && empty($other_purpose)) {
        $error = "Please specify the purpose for 'Others'.";
    } else {
        // Store payment slip data in session
        $_SESSION['payment_slip'] = [
            'amount' => $amount,
            'payment_for' => $payment_for,
            'other_purpose' => $other_purpose,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Redirect to dashboard to get queue number
        header("Location: ../student-management/student_dashboard.php");
        exit();
    }
}
?>
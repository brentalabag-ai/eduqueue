<?php
require_once '../../db/config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if student session exists
if (!isset($_SESSION['student'])) {
    header('Location: ../../student-management/student_login.php');
    exit();
}

$student = $_SESSION['student'];

// Only process if it's a POST request (payment slip form submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Check if student already has active queue
    $checkQuery = "SELECT * FROM queue WHERE student_id = :student_id AND status IN ('waiting', 'serving') AND DATE(time_in) = CURDATE()";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bindParam(':student_id', $student['student_id']);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        header('Location: ../../student-management/student_dashboard.php?error=You already have an active queue number');
        exit();
    }

    $amount = $_POST['amount'] ?? '';
    $payment_for = $_POST['payment_for'] ?? [];
    $other_specify = $_POST['other_specify'] ?? '';
    
    // Validate required fields
    if (empty($amount) || $amount <= 0) {
        header('Location: ../../student-management/payment_slip.php?error=Please enter a valid amount');
        exit();
    }

    if (empty($payment_for)) {
        header('Location: ../../student-management/payment_slip.php?error=Please select at least one payment type');
        exit();
    }

    if (in_array("others", $payment_for) && empty($other_specify)) {
        header('Location: ../../student-management/payment_slip.php?error=Please specify the other payment type');
        exit();
    }
    
    try {
        // Get the last queue number for today
        $lastQuery = "SELECT queue_number FROM queue WHERE DATE(time_in) = CURDATE() ORDER BY queue_id DESC LIMIT 1";
        $lastStmt = $conn->prepare($lastQuery);
        $lastStmt->execute();
        $last = $lastStmt->fetch(PDO::FETCH_COLUMN);
        
        $nextQueueNumber = $last ? $last + 1 : 1;
        
        // Insert into queue table - using the columns that exist in your database
        $insertQuery = "INSERT INTO queue (
                        student_id, 
                        queue_number, 
                        status, 
                        time_in, 
                        amount, 
                        payment_amount,
                        payment_for, 
                        payment_type,
                        other_specify
                    ) VALUES (
                        :student_id, 
                        :queue_number, 
                        'waiting', 
                        NOW(), 
                        :amount, 
                        :payment_amount,
                        :payment_for, 
                        :payment_type,
                        :other_specify
                    )";
        
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bindParam(':student_id', $student['student_id']);
        $insertStmt->bindParam(':queue_number', $nextQueueNumber);
        $insertStmt->bindParam(':amount', $amount);
        $insertStmt->bindParam(':payment_amount', $amount); // Store in both columns
        
        // Prepare payment type as string for payment_type column
        $paymentTypeString = implode(', ', $payment_for);
        if (!empty($other_specify) && in_array('others', $payment_for)) {
            $paymentTypeString .= " (" . $other_specify . ")";
        }
        $insertStmt->bindParam(':payment_type', $paymentTypeString);
        
        // Prepare payment_for as JSON string
        $paymentForJson = json_encode($payment_for);
        $insertStmt->bindParam(':payment_for', $paymentForJson);
        $insertStmt->bindParam(':other_specify', $other_specify);
        
        if ($insertStmt->execute()) {
            // Success - redirect to dashboard with success message
            header('Location: ../../student-management/student_dashboard.php?success=Queue number #' . $nextQueueNumber . ' generated successfully!');
            exit();
        } else {
            header('Location: ../../student-management/payment_slip.php?error=Failed to generate queue number. Please try again.');
            exit();
        }
        
    } catch (PDOException $e) {
        error_log("Payment Slip Error: " . $e->getMessage());
        header('Location: ../../student-management/payment_slip.php?error=Database error: ' . $e->getMessage());
        exit();
    }
} else {
    // If not POST request, redirect back to payment slip
    header('Location: ../../student-management/payment_slip.php');
    exit();
}
?>
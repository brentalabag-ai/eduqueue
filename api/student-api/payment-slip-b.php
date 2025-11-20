<?php
session_start();
require_once '../../db/config.php';

if (!isset($_SESSION['student'])) {
    header('Location: ../../student-management/student_dashboard.php');
    exit();
}

$student = $_SESSION['student'];

// Check if student already has active queue
$database = new Database();
$db = $database->getConnection();

$checkQuery = "SELECT * FROM queue WHERE student_id = :student_id AND status IN ('waiting', 'serving')";
$checkStmt = $db->prepare($checkQuery);
$checkStmt->bindParam(':student_id', $student['student_id']);
$checkStmt->execute();

if ($checkStmt->rowCount() > 0) {
    header('Location: ../../student-management/student_dashboard.php?error=You already have an active queue number');
    exit();
}
?>
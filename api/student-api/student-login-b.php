<?php
require_once "../db/config.php";

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ? LIMIT 1");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        // If you want to add password checking later, add it here
        // For now, just check if student exists
        $_SESSION['student'] = $student;
        header("Location: ../student-management/student_dashboard.php");
        exit;
    } else {
        $error = "Invalid Student ID.";
    }
}
?>
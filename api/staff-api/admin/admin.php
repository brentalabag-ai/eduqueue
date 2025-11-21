<?php
require_once "../../db/config.php";

//Check if user is already logged in
if (!isset($_SESSION['user'])) { 
    header("Location: ../../../staff-management/admin/admin_login.php"); 
    exit; 
}

// Fetch active queues
$queues = $conn->query("
    SELECT q.queue_number, q.status, q.time_in, s.name AS student_name
    FROM queue q
    JOIN students s ON q.student_id = s.student_id
    WHERE DATE(q.time_in) = CURDATE()
    ORDER BY q.queue_number ASC
")->fetchAll(PDO::FETCH_ASSOC);

?>
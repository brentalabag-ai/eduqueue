<?php
require_once "../db/config.php";

//Check if user is already logged in
if (!isset($_SESSION['user'])) { 
    header("Location: ../staff-management/index.php"); 
    exit; 
}

// ACTIONS
//
if ($_SERVER['REQUEST_METHOD'] === 'POST') { //-> Check if login form was submitted

    //someone presses call next and it looks for the first waiting queue number and If found → update its status to serving
    if (isset($_POST['call_next'])) {
        $next = $conn->query("SELECT queue_id FROM queue WHERE status='waiting' ORDER BY queue_id ASC LIMIT 1")
                ->fetchColumn();

        if ($next) {
            $stmt = $conn->prepare("UPDATE queue SET status='serving' WHERE queue_id=?");
            $stmt->execute([$next]);
        }
    }

    if (isset($_POST['served'])) {
        $id = intval($_POST['queue_id']); //-> 'intval' (integer value)-> this converts an a value to integer
        $stmt = $conn->prepare("UPDATE queue SET status='served', time_out = NOW() WHERE queue_id=?"); //-> Updates status: 'serving' → 'served'
                                                                                                       //-> Records completion time with time_out = NOW()
        $stmt->execute([$id]);
    }

    if (isset($_POST['voided'])) {
        $id = intval($_POST['queue_id']);
        $stmt = $conn->prepare("UPDATE queue SET status='voided' WHERE queue_id=?"); //-> Updates status to 'voided'
        $stmt->execute([$id]);
    }

    header('Location: ../staff-management/dashboard.php');
    exit;
}

// QUERY DATA
//Gets: The single customer currently being served (if any)
$serving = $conn->query("
    SELECT q.*, s.name 
    FROM queue q 
    JOIN students s ON q.student_id = s.student_id 
    WHERE q.status = 'serving' 
    ORDER BY q.queue_id ASC 
    LIMIT 1
")->fetch(PDO::FETCH_ASSOC);


//Gets: All waiting customers in arrival order (oldest first)
$waiting = $conn->query("
    SELECT q.*, s.name 
    FROM queue q 
    JOIN students s ON q.student_id = s.student_id 
    WHERE q.status = 'waiting' 
    ORDER BY q.queue_id ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>
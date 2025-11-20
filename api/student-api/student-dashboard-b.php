
<?php
require_once "../db/config.php";

// Check if student is logged in
if (!isset($_SESSION['student'])) {
    header("Location: ../student-management/student_login.php");
    exit;
}

$student = $_SESSION['student'];
$message = "";
$myQueueData = null;
$nowServing = null;
$queuePosition = null;

// AUTO-CREATE QUEUE AFTER PAYMENT SLIP
if (isset($_SESSION['payment_slip']) && (!isset($_SESSION['queue_created_after_payment']) || $_SESSION['queue_created_after_payment'] !== true)) {
    // Check if student already has an active queue number today
    $existingQueue = $conn->prepare("
        SELECT queue_number, status, queue_id 
        FROM queue 
        WHERE student_id = ? AND DATE(time_in) = CURDATE() AND status IN ('waiting', 'serving')
        LIMIT 1
    ");
    $existingQueue->execute([$student['student_id']]);
    $existing = $existingQueue->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        $message = "You already have an active queue number: <strong>#{$existing['queue_number']}</strong> (Status: " . ucfirst($existing['status']) . ")";
        $myQueueData = $existing;
        
        // Clear payment slip since queue already exists
        unset($_SESSION['payment_slip']);
        unset($_SESSION['queue_created_after_payment']);
    } else {
        // Get the last queue number for today
        $last = $conn->query("
            SELECT queue_number FROM queue 
            WHERE DATE(time_in) = CURDATE() 
            ORDER BY queue_id DESC LIMIT 1
        ")->fetchColumn();

        $next = $last ? $last + 1 : 1;

        $stmt = $conn->prepare("
            INSERT INTO queue (student_id, queue_number, status, time_in) 
            VALUES (?, ?, 'waiting', NOW())
        ");
        
        if ($stmt->execute([$student['student_id'], $next])) {
            $message = "Your queue number is: <strong>#{$next}</strong>";
            
            // Get the newly created queue data
            $myQueueData = [
                'queue_number' => $next,
                'status' => 'waiting',
                'queue_id' => $conn->lastInsertId()
            ];
            
            // Mark that queue was created after payment
            $_SESSION['queue_created_after_payment'] = true;
        } else {
            $message = "Error getting queue number. Please try again.";
        }
    }
}

// MANUAL QUEUE CREATION (if user comes directly without payment slip)
if (isset($_POST['take_queue'])) {
    // Check if payment slip is completed
    if (!isset($_SESSION['payment_slip'])) {
        // Redirect to payment slip page
        header("Location: payment_slip.php");
        exit();
    } else {
        // If payment slip exists but queue wasn't created, redirect to refresh and auto-create
        if (!isset($_SESSION['queue_created_after_payment'])) {
            header("Location: student_dashboard.php");
            exit();
        }
    }
}

// GET STUDENT'S CURRENT QUEUE (if any)
if (!$myQueueData) {
    $myQueueQuery = $conn->prepare("
        SELECT queue_number, status, queue_id 
        FROM queue 
        WHERE student_id = ? AND DATE(time_in) = CURDATE() AND status IN ('waiting', 'serving')
        LIMIT 1
    ");
    $myQueueQuery->execute([$student['student_id']]);
    $myQueueData = $myQueueQuery->fetch(PDO::FETCH_ASSOC);
    
    // If we found an existing queue, clear any payment slip data
    if ($myQueueData) {
        unset($_SESSION['payment_slip']);
        unset($_SESSION['queue_created_after_payment']);
    }
}

// CALCULATE QUEUE POSITION (if student has a waiting queue)
if ($myQueueData && $myQueueData['status'] === 'waiting') {
    $positionQuery = $conn->prepare("
        SELECT COUNT(*) as position 
        FROM queue 
        WHERE status = 'waiting' 
        AND queue_id < ? 
        AND DATE(time_in) = CURDATE()
    ");
    $positionQuery->execute([$myQueueData['queue_id']]);
    $queuePosition = $positionQuery->fetchColumn();
    $queuePosition = $queuePosition ? $queuePosition + 1 : 1; // Add 1 because position starts from next after current serving
}

// NOW SERVING — ALWAYS show current serving number
$nowServing = $conn->query("
    SELECT q.queue_number, s.name 
    FROM queue q
    JOIN students s ON q.student_id = s.student_id
    WHERE q.status = 'serving' AND DATE(q.time_in) = CURDATE()
    ORDER BY q.queue_id ASC 
    LIMIT 5
")->fetch(PDO::FETCH_ASSOC);

// GET QUEUE STATISTICS
$waitingCount = $conn->query("
    SELECT COUNT(*) FROM queue 
    WHERE status = 'waiting' AND DATE(time_in) = CURDATE()
")->fetchColumn();

$servedCount = $conn->query("
    SELECT COUNT(*) FROM queue 
    WHERE status = 'served' AND DATE(time_in) = CURDATE()
")->fetchColumn();
?>
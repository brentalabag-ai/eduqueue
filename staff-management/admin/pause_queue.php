<?php
require_once "../../db/config.php";

$action = $_POST['action'] ?? null;

if ($action === 'pause') {
    $conn->query("UPDATE queue SET status = 'paused' WHERE status = 'waiting'");
    echo json_encode(['success' => true, 'message' => 'Queue paused successfully.']);
} elseif ($action === 'resume') {
    $conn->query("UPDATE queue SET status = 'waiting' WHERE status = 'paused'");
    echo json_encode(['success' => true, 'message' => 'Queue resumed successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action.']);
}
?>
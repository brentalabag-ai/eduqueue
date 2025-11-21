<?php
require_once "../../db/config.php";

// Calculate Average Wait Time (AWT)
$awt = $conn->query("
    SELECT AVG(TIMESTAMPDIFF(MINUTE, time_in, time_served)) AS awt
    FROM queue
    WHERE status = 'served' AND DATE(time_in) = CURDATE()
")->fetchColumn();

// Calculate Average Service Time (AST)
$ast = $conn->query("
    SELECT AVG(TIMESTAMPDIFF(MINUTE, time_served, time_out)) AS ast
    FROM queue
    WHERE status = 'served' AND DATE(time_in) = CURDATE()
")->fetchColumn();

// Fetch total transaction volume
$transactionVolume = $conn->query("
    SELECT COUNT(*) FROM queue
    WHERE status = 'served' AND DATE(time_in) = CURDATE()
")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
</head>
<body>
    <h1>Reports</h1>
    <p>Average Wait Time: <?= htmlspecialchars($awt) ?> minutes</p>
    <p>Average Service Time: <?= htmlspecialchars($ast) ?> minutes</p>
    <p>Total Transactions: <?= htmlspecialchars($transactionVolume) ?></p>
</body>
</html>
<?php
require_once "../../api/staff-api/admin/admin.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../css/admin.css">
    <script src="../../js/admin_dashboard.js"></script>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <section>
        <h2>Live Queue Monitoring</h2>
        <table>
            <thead>
                <tr>
                    <th>Queue Number</th>
                    <th>Student Name</th>
                    <th>Status</th>
                    <th>Registration Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($queues as $queue): ?>
                    <tr>
                        <td><?= htmlspecialchars($queue['queue_number']) ?></td>
                        <td><?= htmlspecialchars($queue['student_name']) ?></td>
                        <td><?= htmlspecialchars($queue['status']) ?></td>
                        <td><?= htmlspecialchars($queue['time_in']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
    <!-- <section>
        <h2>Cashier Status</h2>
        <table>
            <thead>
                <tr>
                    <th>Cashier Name</th>
                    <th>Status</th>
                </tr>
            </thead>
        </table>
    </section> -->
</body>
</html>
<?php
require_once "../../api/staff-api/admin/admin.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- <link rel="stylesheet" href="../../css/common.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/admin.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>
<body>
    <div class="main-content">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="../../api/staff-api/admin/admin-logout.php" class="btn btn-outline-danger">
            <span class="material-symbols-outlined" style="vertical-align:middle">logout</span>
            Logout
        </a>
    </div>
    </div>
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
    <script src="../../js/admin_dashboard.js"></script>
</body>
</html>
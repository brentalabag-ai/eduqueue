<?php
require_once "api/student-dashboard-b.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Dashboard - Queue System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/student.css">
</head>
<body>
    <!-- Dark Mode Toggle -->
    <button class="dark-toggle btn btn-outline-secondary position-fixed top-0 end-0 m-3">
        <i class="bi bi-moon-stars"></i>
    </button>

    <p class="mt-3"><a href="api/student-logout-b.php">Logout</a></p>

    <div class="container-fluid">
        <div class="student-box card fade-in">
            <!-- Header -->
            <div class="text-center mb-4">
                <i class="bi bi-person-circle display-1 text-primary"></i>
                <h1 class="h3 mt-2">Welcome, <?= htmlspecialchars($student['name']) ?></h1>
                <p class="text-muted">Queue Management System</p>
            </div>

            <!-- Success Message -->
            <?php if ($message): ?>
                <div class="alert alert-info alert-dismissible fade show">
                    <?= $message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- My Queue Information -->
            <?php if ($myQueueData): ?>
                <div class="alert alert-primary">
                    <h5 class="alert-heading">
                        <i class="bi bi-ticket-perforated"></i> Your Queue
                    </h5>
                    <div class="queue-number">#<?= $myQueueData['queue_number'] ?></div>
                    <div class="text-center">
                        <span class="badge status-badge 
                            <?= $myQueueData['status'] === 'serving' ? 'bg-success' : 'bg-warning' ?>">
                            Status: <?= strtoupper($myQueueData['status']) ?>
                        </span>
                    </div>
                    
                    <?php if ($myQueueData['status'] === 'waiting' && $queuePosition): ?>
                        <div class="text-center mt-2">
                            <small class="text-muted">
                                Your position in queue: <strong><?= $queuePosition ?></strong>
                            </small>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($myQueueData['status'] === 'serving'): ?>
                        <div class="text-center mt-2">
                            <div class="badge bg-success">
                                <i class="bi bi-megaphone"></i> Please proceed to the counter!
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Now Serving Section -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-broadcast"></i> Now Serving
                    </h5>
                </div>
                <div class="card-body text-center">
                    <?php if ($nowServing): ?>
                        <div class="display-6 text-primary fw-bold">
                            #<?= $nowServing['queue_number'] ?>
                        </div>
                        <p class="card-text"><?= htmlspecialchars($nowServing['name']) ?></p>
                    <?php else: ?>
                        <div class="text-muted">
                            <i class="bi bi-info-circle"></i> No one is currently being served
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Queue Statistics -->
            <div class="stats-box">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="h4 text-primary"><?= $waitingCount ?: 0 ?></div>
                        <small class="text-muted">Waiting</small>
                    </div>
                    <div class="col-6">
                        <div class="h4 text-success"><?= $servedCount ?: 0 ?></div>
                        <small class="text-muted">Served Today</small>
                    </div>
                </div>
            </div>

            <!-- Take Queue Button (only show if no active queue) -->
            <?php if (!$myQueueData || $myQueueData['status'] === 'served'): ?>
                <form method="post" class="text-center">
                    <button name="take_queue" class="btn btn-primary btn-lg">
                        <i class="bi bi-ticket-perforated"></i> Take Queue Number
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/darkmode.js"></script>
    <script src="js/autorefresh.js"></script>
</body>
</html>
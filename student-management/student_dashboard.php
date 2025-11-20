
<?php
require_once "../api/student-api/student-dashboard-b.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Dashboard - Queue System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/student.css">
</head>
<body>
    <!-- Dark Mode Toggle -->
    <button class="dark-toggle btn btn-outline-secondary position-fixed top-0 end-0 m-3">
        <i class="bi bi-moon-stars"></i>
    </button>

    <p class="mt-3"><a href="../api/student-api/student-logout-b.php">Logout</a></p>

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

            <!-- Payment Slip Preview Section -->
            <?php if ($paymentSlipData && (!isset($_SESSION['queue_created_after_payment']) || !$_SESSION['queue_created_after_payment'])): ?>
                <div class="payment-slip-preview">
                    <div class="payment-slip-header">
                        <h4 class="mb-1">Saint Louis College</h4>
                        <p class="mb-1">City of San Fernando, 2500 La Union</p>
                        <h5 class="mb-0">PAYMENT SLIP PREVIEW</h5>
                    </div>

                    <!-- Student Information -->
                    <div class="mb-3">
                        <h6><i class="bi bi-person-badge"></i> Student Information</h6>
                        <div class="payment-detail">
                            <strong>NAME:</strong> <?= htmlspecialchars($student['name']) ?>
                        </div>
                        <div class="payment-detail">
                            <strong>ID NO:</strong> <?= htmlspecialchars($student['student_id']) ?>
                        </div>
                        <div class="payment-detail">
                            <strong>COURSE & YEAR:</strong> <?= htmlspecialchars($student['course']) ?> - <?= htmlspecialchars($student['year_level']) ?>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="mb-3">
                        <h6><i class="bi bi-cash-coin"></i> Payment Details</h6>
                        <div class="payment-detail">
                            <strong>AMOUNT:</strong> ₱<?= number_format($paymentSlipData['amount'], 2) ?>
                        </div>
                        <div class="payment-detail">
                            <strong>IN PAYMENT OF:</strong><br>
                            <?php
                            $paymentForLabels = [
                                'tuition' => 'Tuition Fee',
                                'transcript' => 'Transcript',
                                'overdue' => 'Overdue',
                                'others' => 'Others'
                            ];
                            
                            foreach ($paymentSlipData['payment_for'] as $paymentType): 
                                $label = $paymentForLabels[$paymentType] ?? ucfirst($paymentType);
                            ?>
                                <span class="badge bg-primary payment-for-badge">
                                    <?= htmlspecialchars($label) ?>
                                    <?php if ($paymentType === 'others' && !empty($paymentSlipData['other_purpose'])): ?>
                                        : <?= htmlspecialchars($paymentSlipData['other_purpose']) ?>
                                    <?php endif; ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Date -->
                    <div class="payment-detail">
                        <strong>DATE:</strong> <?= date('F j, Y') ?>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> 
                            Your payment slip is ready. Click "Take Queue Number" below to proceed.
                        </small>
                    </div>
                </div>
            <?php endif; ?>

            <!-- My Queue Information -->
            <?php if ($myQueueData): ?>
                <div class="alert alert-primary">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="alert-heading mb-0">
                            <i class="bi bi-ticket-perforated"></i> Your Queue
                        </h5>
                        <?php if ($paymentSlipData): ?>
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#paymentSlipModal">
                                <i class="bi bi-receipt"></i> View Payment Slip
                            </button>
                        <?php endif; ?>
                    </div>
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
                <div class="text-center">
                    <?php if ($paymentSlipData && (!isset($_SESSION['queue_created_after_payment']) || !$_SESSION['queue_created_after_payment'])): ?>
                        <!-- Show form to create queue with existing payment slip -->
                        <form method="post">
                            <button type="submit" name="take_queue" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle"></i> Take Queue Number with Payment Slip
                            </button>
                        </form>
                        <small class="text-muted d-block mt-2">
                            Your payment slip information will be used for your queue.
                        </small>
                    <?php else: ?>
                        <!-- Show link to payment slip page -->
                        <a href="payment_slip.php" class="btn btn-primary btn-lg">
                            <i class="bi bi-ticket-perforated"></i> Take Queue Number
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Edit Payment Slip Button (if payment slip exists but queue not created) -->
            <?php if ($paymentSlipData && (!isset($_SESSION['queue_created_after_payment']) || !$_SESSION['queue_created_after_payment'])): ?>
                <div class="text-center mt-2">
                    <a href="payment_slip.php" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-pencil"></i> Edit Payment Slip
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Payment Slip Modal -->
    <?php if ($paymentSlipData): ?>
    <div class="modal fade" id="paymentSlipModal" tabindex="-1" aria-labelledby="paymentSlipModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentSlipModalLabel">
                        <i class="bi bi-receipt"></i> Payment Slip
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-payment-slip-container">
                        <!-- Header -->
                        <div class="payment-slip-header text-center">
                            <h1 class="h3 mb-1">Saint Louis College</h1>
                            <p class="mb-1">City of San Fernando, 2500 La Union</p>
                            <h2 class="h4 mb-0">PAYMENT SLIP</h2>
                        </div>

                        <!-- Student Information -->
                        <div class="modal-form-section">
                            <h5 class="mb-3"><i class="bi bi-person-badge"></i> Student Information</h5>
                            
                            <div class="mb-3">
                                <label class="form-label"><strong>NAME:</strong></label>
                                <div class="form-control-plaintext border-bottom pb-2">
                                    <?= htmlspecialchars($student['name']) ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><strong>ID NO:</strong></label>
                                <div class="form-control-plaintext border-bottom pb-2">
                                    <?= htmlspecialchars($student['student_id']) ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><strong>COURSE & YEAR:</strong></label>
                                <div class="form-control-plaintext border-bottom pb-2">
                                    <?= htmlspecialchars($student['course']) ?> - <?= htmlspecialchars($student['year_level']) ?>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Details -->
                        <div class="modal-form-section">
                            <h5 class="mb-3"><i class="bi bi-cash-coin"></i> Payment Details</h5>
                            
                            <div class="mb-3">
                                <label class="form-label"><strong>AMOUNT:</strong></label>
                                <div class="form-control-plaintext border-bottom pb-2 fw-bold">
                                    ₱<?= number_format($paymentSlipData['amount'], 2) ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><strong>IN PAYMENT OF:</strong></label>
                                <div class="payment-options">
                                    <?php
                                    $paymentForLabels = [
                                        'tuition' => 'Tuition Fee',
                                        'transcript' => 'Transcript',
                                        'overdue' => 'Overdue',
                                        'others' => 'Others'
                                    ];
                                    
                                    foreach ($paymentForLabels as $key => $label): 
                                        $isChecked = in_array($key, $paymentSlipData['payment_for']);
                                    ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   <?= $isChecked ? 'checked' : '' ?> disabled>
                                            <label class="form-check-label">
                                                <?= $label ?>
                                                <?php if ($key === 'others' && !empty($paymentSlipData['other_purpose'])): ?>
                                                    : <?= htmlspecialchars($paymentSlipData['other_purpose']) ?>
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Date and Queue Info -->
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label"><strong>DATE:</strong></label>
                                <div class="form-control-plaintext border-bottom pb-2">
                                    <?= date('F j, Y') ?>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label"><strong>QUEUE NUMBER:</strong></label>
                                <div class="form-control-plaintext border-bottom pb-2 fw-bold text-primary">
                                    #<?= $myQueueData['queue_number'] ?? 'Pending' ?>
                                </div>
                            </div>
                        </div>

                        <!-- Reference Code -->
                        <div class="modal-reference-code">
                            <div class="row">
                                <div class="col-4">
                                    <strong>Reference Code</strong><br>
                                    FM-TREA-001
                                </div>
                                <div class="col-4">
                                    <strong>Revision No.</strong><br>
                                    0
                                </div>
                                <div class="col-4">
                                    <strong>Effectivity Date</strong><br>
                                    August 1, 2019
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="window.print()">
                        <i class="bi bi-printer"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/darkmode.js"></script>
    <script src="../js/autorefresh.js"></script>
</body>
</html>
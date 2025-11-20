<!-- Take Queue Button (only show if no active queue) -->
            <?php if (!$myQueueData || $myQueueData['status'] === 'served'): ?>
                <form method="post" class="text-center">
                    <button name="take_queue" class="btn btn-primary btn-lg">
                        <i class="bi bi-ticket-perforated" href="payment_slip.php"></i> Take Queue Number
                    </button>
                </form>
            <?php endif; ?>

            <!-- Success Message -->
            <?php if ($message): ?>
                <div class="alert alert-info alert-dismissible fade show">
                    <?= $message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Take Queue Button (only show if no active queue) -->
            <?php if (!$myQueueData || $myQueueData['status'] === 'served'): ?>
                <div class="text-center">
                    <a href="payment_slip.php" class="btn btn-primary btn-lg">
                        <i class="bi bi-ticket-perforated"></i> FIll up Payment Slip
                    </a>
                </div>
            <?php endif; ?>
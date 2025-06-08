<?php
// Get batches for each category
foreach ($categories as $category):
    // Fetch batches for this category
    $stmt = $conn->prepare("SELECT b.*, 
                          (SELECT COUNT(*) FROM enrollments e WHERE e.batch_id = b.id) AS enrolled_seats
                          FROM batches b WHERE b.category = ?");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
    $batches = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    if (count($batches) > 0):
?>
<section class="exam-batches">
    <div class="section-header">
        <h2><?= htmlspecialchars($category) ?></h2>
        <p>Join our specialized batches to ace your upcoming exams</p>
    </div>

    <div class="batches-grid">
        <?php foreach ($batches as $batch): 
            $availableSeats = $batch['total_seats'] - $batch['enrolled_seats'];
            $discount = $batch['original_price'] > 0 ? 
                round((1 - $batch['discounted_price'] / $batch['original_price']) * 100) : 0;
        ?>
        <div class="batch-card">
            <div class="batch-image">
                <img src="<?= htmlspecialchars($batch['image_url']) ?>" alt="<?= htmlspecialchars($batch['title']) ?>">
            </div>
            <div class="batch-content">
                <h3><?= htmlspecialchars($batch['title']) ?></h3>
                <p><?= htmlspecialchars($batch['description']) ?></p>
                <div class="batch-meta">
                    <span class="start-date">Starts: <?= date('F j', strtotime($batch['start_date'])) ?></span>
                    <span class="seats">Seats: <?= $availableSeats ?>/<?= $batch['total_seats'] ?></span>
                </div>
                <div class="batch-price">
                    <span class="original-price">৳<?= number_format($batch['original_price'], 2) ?></span>
                    <span class="discounted-price">৳<?= number_format($batch['discounted_price'], 2) ?></span>
                    <?php if ($discount > 0): ?>
                    <span class="discount-badge"><?= $discount ?>% OFF</span>
                    <?php endif; ?>
                </div>

                    <?php 
                        $alreadyEnrolled = false;

                        if (isset($_SESSION['user_id'])) {
                            $check = $conn->prepare("SELECT id FROM enrollments WHERE batch_id = ? AND user_id = ? AND payment_status = 'completed'");
                            $check->bind_param("ii", $batch['id'], $_SESSION['user_id']);
                            $check->execute();
                            $check->store_result();
                            if ($check->num_rows > 0) {
                                $alreadyEnrolled = true;
                            }
                            $check->close();
                        }
                    ?>

                    <?php if ($alreadyEnrolled): ?>
                    <button class="enroll-btn enrolled" disabled>Already Enrolled</button>
                    <?php else: ?>
                    <!-- <button class="enroll-btn" data-batch-id="<?= $batch['id'] ?>">Enroll Now</button> -->
                      <button class="enroll-btn" onclick="initiatePayment(<?= $batch['id'] ?>)">Enroll Now</button>
                    <?php endif; ?>

            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php 
    endif;
endforeach;
?>

<link rel="stylesheet" href="/creativityfreaks/assets/css/exam_batch.css">

<script>

function initiatePayment(batchId) {

     fetch('../includes/sslcommerz_ipn.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'batch_id=' + batchId
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            window.location.href = data.url;
        } else {
            alert(data.message || 'Payment failed');
        }
    })
    .catch(error => {
    console.error('Error:', error);
    alert('Something went wrong. Please try again.');
});

}
</script>
<section class="course-details container" data-aos="fade-up">
    <!-- Creative Banner Section -->
    <div class="course-banner">
        <h1>Explore Your Learning Journey</h1>
        <div class="course-categories">
            <span><?= htmlspecialchars($course['category']) ?></span>
            <span><?= htmlspecialchars($course['subcategory']) ?></span>
        </div>
        <div class="contact-info">
            <p>Need Help? <strong>Contact Us</strong></p>
            <p>support@creativityfreaks.com</p>
        </div>
    </div>

    <!-- Course Header Section -->
    <div class="course-header">
        <img src="../uploads/thumbnail/<?= htmlspecialchars($course['thumbnail']) ?>"
            alt="<?= htmlspecialchars($course['title']) ?>">
        <div class="course-summary">
            <h1><?= htmlspecialchars($course['title']) ?></h1>
            <p><?= htmlspecialchars($course['description']) ?></p>
            <div class="course-meta">
                <p><strong>Instructor:</strong> <?= htmlspecialchars($course['instructor_name']) ?></p>
                <p><strong>Category:</strong> <?= htmlspecialchars($course['category']) ?> →
                    <?= htmlspecialchars($course['subcategory']) ?></p>
                <p><strong>Price:</strong>
                    <?= $course['price'] == 0 ? 'Free' : '$' . number_format($course['price'], 2) ?></p>
            </div>

            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'student'): ?>

            <?php if ($course['price'] == 0): ?>
            <form action="enroll_course.php" method="POST">
                <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                <button type="submit" class="enroll-btn">Enroll for Free</button>
            </form>
            <?php else: ?>
            <!-- <button class="enroll-btn" onclick="openPaymentModal()">Enroll Now</button> -->
          <button class="enroll-btn" onclick="initiatePayment(<?= $course['id'] ?>)">Enroll Now</button>

            <?php endif; ?>
            <?php if (isset($_GET['enrolled']) && $_GET['enrolled'] == '1'): ?>
            <div class="enroll-success-box">
                <div class="success-icon"><i class="fas fa-check-circle"></i></div>
                <h3>You're successfully enrolled in this course!</h3>
                <a href="dashboard.php" class="dashboard-link">Go to Dashboard</a>
            </div>
            <?php elseif (isset($_GET['enrolled']) && $_GET['enrolled'] == 'exists'): ?>
            <div class="enroll-success-box">
                <div class="success-icon"><i class="fas fa-info-circle"></i></div>
                <h3>You are already enrolled in this course.</h3>
                <a href="dashboard.php" class="dashboard-link">Go to Dashboard</a>
            </div>
            <?php endif; ?>

            <?php else: ?>
            <a href="../auth/login.php" onclick="openLoginModal()" class="enroll-btn">Login to Enroll</a>
            <?php endif; ?>
        </div>
    </div>
</section>


<!-- Payment Modal -->
<!-- <div id="paymentModal" class="modal-overlay">
  <div class="modal-content">
    <span class="close-btn" onclick="closePaymentModal()">&times;</span>
    <h2>Complete Payment</h2>
    <p>You're enrolling in: <strong><?= htmlspecialchars($course['title']) ?></strong></p>
    <p>Price: <strong>$<?= number_format($course['price'], 2) ?></strong></p>
     -->
    <!-- Simulate payment -->
    <!-- <form action="process_payment.php" method="POST">
      <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
      <label>Card Number</label>
      <input type="text" name="card_number" placeholder="**** **** **** 1234" required>

      <label>Expiry Date</label>
      <input type="text" name="expiry_date" placeholder="MM/YY" required>

      <label>CVV</label>
      <input type="text" name="cvv" placeholder="123" required>

      <button type="submit" class="enroll-btn">Pay & Enroll</button>
    </form>
  </div>
</div> -->

<!-- <script>
  function openPaymentModal() {
  document.getElementById('paymentModal').style.display = 'flex';
}

function closePaymentModal() {
  document.getElementById('paymentModal').style.display = 'none';
}

</script> -->

<script>
function initiatePayment(courseId) {
    fetch('sslcz_pay.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'course_id=' + courseId
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
        alert('Already enrolled in this Course!');
    });
}

</script>

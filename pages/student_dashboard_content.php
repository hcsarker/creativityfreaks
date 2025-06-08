<?php 
require_once '../includes/db.php';
$user_id = $_SESSION['user_id']; // ✅ This line was missing
?>


<div class="student-dashboard">
    <h2 class="dashboard-heading">Welcome, <?php echo $_SESSION['user_name']; ?> 👋</h2>


    <!-- Enrolled Courses Section -->
    <section class="enrolled-courses" data-aos="fade-up">
        <h2 class="section-title">🎓 Your Enrolled Courses</h2>
        <div class="course-grid">

            <?php
        $sql = "SELECT c.* FROM courses c
                JOIN enrollments e ON c.id = e.course_id
                WHERE e.user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0):
        while ($course = $result->fetch_assoc()):
        ?>
            <a href="course_details.php?id=<?= $course['id'] ?>" class="course-card-link">
                <div class="course-card" data-aos="zoom-in">
                    <img src="../uploads/thumbnail/<?= htmlspecialchars($course['thumbnail']) ?>"
                        alt="<?= htmlspecialchars($course['title']) ?>" />
                    <div class="course-info">
                        <h3><?= htmlspecialchars($course['title']) ?></h3>
                        <p><?= substr($course['description'], 0, 90) ?>...</p>
                        <div class="meta">
                            <span class="category"><?= htmlspecialchars($course['category']) ?></span>
                            <span
                                class="price"><?= $course['price'] == 0 ? 'Free' : '$' . number_format($course['price'], 2) ?></span>
                        </div>
                    </div>
                </div>
            </a>
            <?php
            endwhile;
            else:
            echo "<p class='no-course-message'>You haven't enrolled in any courses yet.</p>";
            endif;
            ?>

        </div>

        <?php if ($result->num_rows > 0): ?>
        <div class="continue-wrapper">
            <a href="my_courses.php" class="view-btn">📘 Continue Learning</a>
        </div>
        <?php endif; ?>
    </section>


</div>

<!-- Enrolled Exam Batches Section -->
<section class="enrolled-batches-section" data-aos="fade-up">
    <div class="enrolled-batches">
        <h2>🧪 Enrolled Exam Batches</h2>
        <div class="batch-grid">
            <?php
              $stmt = $conn->prepare("
                SELECT 
                  b.id AS batch_id,
                  b.title AS batch_title,
                  b.description,
                  b.image_url
                FROM enrollments e
                JOIN batches b ON e.batch_id = b.id
                WHERE e.user_id = ? AND e.batch_id IS NOT NULL AND e.payment_status = 'completed'
              ");
              $stmt->bind_param("i", $user_id);
              $stmt->execute();
              $result = $stmt->get_result();

              while ($batch = $result->fetch_assoc()):
            ?>
            <div class="batch-card">
                <div class="batch-image">
                    <img src="<?= htmlspecialchars($batch['image_url']) ?>"
                        alt="<?= htmlspecialchars($batch['batch_title']) ?>">
                </div>
                <div class="batch-content">
                    <h3><?= htmlspecialchars($batch['batch_title']) ?></h3>
                    <p><?= htmlspecialchars($batch['description']) ?></p>
                    <a href="take_exam.php?batch_id=<?= $batch['batch_id'] ?>" class="take-exam-btn">🎯 Take Exam</a>
                </div>
            </div>
            <?php endwhile; 
            
            ?>


        </div>
    </div>
</section>

    <!-- Progress Bar -->
    <div class="dashboard-progress" data-aos="fade-right">
        <h2> <i class="fas fa-chart-line"></i> Your Progress</h2>
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress-fill" style="width: 60%;"></div>
            </div>
            <p>60% Completed</p>
        </div>
    </div>


    <link rel="stylesheet" href="/creativityfreaks/assets/css/dashboard.css">
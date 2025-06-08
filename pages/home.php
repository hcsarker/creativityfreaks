<?php
require_once __DIR__ . '/../includes/db.php';  // This makes $conn available

 // Fetch featured courses

$query = "
   SELECT c.*, 
    u.name AS instructor_name,
    ROUND(AVG(r.rating), 1) AS average_rating
  FROM courses c
  JOIN users u ON c.instructor_id = u.id
  LEFT JOIN course_reviews r ON c.id = r.course_id
  GROUP BY c.id
  ORDER BY c.created_at DESC
  LIMIT 6
";
$result = $conn->query($query);

// Get course count (remove is_active condition if column doesn't exist)
$course_count = 0;
$course_query = "SELECT COUNT(*) as count FROM courses"; // Removed WHERE is_active = TRUE
$course_result = $conn->query($course_query);
if ($course_result) {
    $course_data = $course_result->fetch_assoc();
    $course_count = $course_data['count'];
}

// Get active learner count (use status column if it exists instead of is_active)
$learner_count = 0;
$learner_query = "SELECT COUNT(*) as count FROM users WHERE role = 'student'"; // Removed AND is_active = TRUE
$learner_result = $conn->query($learner_query);
if ($learner_result) {
    $learner_data = $learner_result->fetch_assoc();
    $learner_count = $learner_data['count'];
}

// Get instructor count
$instructor_count = 0;
$instructor_query = "SELECT COUNT(*) as count FROM users WHERE role = 'instructor'"; // Removed AND is_active = TRUE
$instructor_result = $conn->query($instructor_query);
if ($instructor_result) {
    $instructor_data = $instructor_result->fetch_assoc();
    $instructor_count = $instructor_data['count'];
}

?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <div class="hero-text">
            <h1>Unleash Your <span class="highlight">Creativity</span></h1>
            <p>Explore engaging courses and fuel your curiosity with interactive learning experiences tailored for
                creative minds.</p>
            <a href="pages/courses.php" class="hero-btn">Explore Courses</a>
        </div>
        <div class="hero-image">
            <img src="assets/images/hero_student.jpg" alt="Student Holding Notebook" class="responsive-img">
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features">
    <h2>What We Offer</h2>
    <div class="feature-grid">
        <div class="feature-card" data-aos="zoom-in">
            <i class="fas fa-paint-brush fa-2x"></i>
            <h3>Creative Courses</h3>
            <p>Master art, design, animation, and more.</p>
        </div>
        <div class="feature-card" data-aos="zoom-in">
            <i class="fas fa-users fa-2x"></i>
            <h3>Vibrant Community</h3>
            <p>Join creatives, collaborate and grow together.</p>
        </div>
        <div class="feature-card" data-aos="zoom-in">
            <i class="fas fa-video fa-2x"></i>
            <h3>Live Workshops</h3>
            <p>Attend interactive sessions hosted by experts.</p>
        </div>
    </div>
</section>

<!-- Featured Courses -->
<!-- <section class="featured-courses" id="courses">
    <h2>Featured Courses</h2>
    <p>Discover popular and trending courses designed to enhance your skills.</p>

    <div class="course-grid">
      <div class="course-card">
        <img src="images/course1.jpg" alt="Web Development">
        <div class="course-info">
          <h3>Web Development</h3>
          <p>Build modern websites and applications using HTML, CSS, JS, and more.</p>
        </div>
      </div>

      <div class="course-card">
        <img src="images/course2.jpg" alt="Graphic Design">
        <div class="course-info">
          <h3>Graphic Design</h3>
          <p>Master design tools like Photoshop, Illustrator, and Figma.</p>
        </div>
      </div>

      <div class="course-card">
        <img src="images/course3.jpg" alt="Digital Marketing">
        <div class="course-info">
          <h3>Digital Marketing</h3>
          <p>Learn SEO, social media marketing, and ad strategies to grow brands.</p>
        </div>
      </div>

      <div class="course-card">
        <img src="images/course4.jpg" alt="Cyber Security">
        <div class="course-info">
          <h3>Cyber Security</h3>
          <p>Learn the fundamentals of cybersecurity and protect your digital assets.</p>
        </div>
      </div>
    </div>
  </section> -->

<!-- Featured Courses -->
<section class="featured-courses section" data-aos="fade-up">
    <div class="container">
        <h2 class="center"><i class="fas fa-fire fa-2x"></i> Featured Courses</h2>
        <div class="grid-3">
            <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($course = $result->fetch_assoc()): ?>
            <a href="pages/course_details.php?id=<?= urlencode($course['id']) ?>" class="card hover-card" data-aos="zoom-in">
                <?php if ($course['price'] == 0): ?>
                <div class="ribbon bg-success">Free</div>
                <?php elseif ($course['average_rating'] >= 4.8): ?>
                <div class="ribbon">Best Seller</div>
                <?php endif; ?>

                <img src="uploads/thumbnail/<?= htmlspecialchars($course['thumbnail']) ?>"
                    alt="<?= htmlspecialchars($course['title']) ?>">
                <h3><?= htmlspecialchars($course['title']) ?></h3>
                <h4><?= htmlspecialchars($course['description']) ?></h4>
                <p class="text-muted"><?= htmlspecialchars($course['category']) ?></p>
                <p>By <strong><?= htmlspecialchars($course['instructor_name']) ?></strong></p>
                <p><strong>$<?= number_format($course['price'], 2) ?></strong></p>
                <!-- Star Rating -->
                <div class="course-rating">
                    <?php
                            $rating = isset($course['average_rating']) ? round($course['average_rating']) : 0;
                            // for ($i = 1; $i <= 5; $i++) {
                            //   echo $i <= $rating 
                            //     ? '<i class="fas fa-star text-gold"></i>' 
                            //     : '<i class="far fa-star text-muted"></i>';
                            // }
                            echo "<span class='rating-score'>($rating)</span>";
                        ?>
                </div>
            </a>
            <?php endwhile; ?>
            <?php else: ?>
            <p class="center">No featured courses found.</p>
            <?php endif; ?>
        </div>
    </div>
</section>



<!-- Team Section -->
<section class="team" data-aos="fade-up">
    <h2>Meet Our Instructors</h2>
    <div class="team-grid">
        <div class="team-member">
            <img src="assets/images/instructor1.jpg" alt="Shahriar Hossain">
            <h3>Shahriar Hossain</h3>
            <p>Founder & Math Mentor</p>
        </div>
        <div class="team-member">
            <img src="assets/images/instructor2.jpg" alt="Nazifa Ahmed">
            <h3>Nazifa Ahmed</h3>
            <p>Data Science Instructor</p>
        </div>
        <div class="team-member">
            <img src="assets/images/instructor3.jpg" alt="Arman Rahman">
            <h3>Arman Rahman</h3>
            <p>UI/UX Mentor</p>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section" data-aos="fade-up">
    <h2> <i class="fas fa-chart-line text-purple"></i> Our Impact</h2>
    <div class="stats-grid">
        <div class="stat-card">
            <h3><?php echo number_format($learner_count); ?>+</h3>
            <p>Students Enrolled</p>
        </div>
        <div class="stat-card">
            <h3><?php echo $course_count; ?>+</h3>
            <p>Courses Offered</p>
        </div>
        <div class="stat-card">
            <h3><?php echo $instructor_count; ?>+</h3>
            <p>Expert Instructors</p>
        </div>
        <div class="stat-card">
            <h3><span class="counter" data-target="18">0</span>+</h3>
            <p>Countries Reached</p>
        </div>
    </div>
</section>

<!-- Partners Section -->
<!-- <section class="partners-section" data-aos="fade-up">
    <h2>Trusted by Leading Institutions</h2>
    <div class="partners-logos">
      <img src="assets/images/partner1.png" alt="Partner 1">
      <img src="assets/images/partner2.png" alt="Partner 2">
      <img src="assets/images/partner3.png" alt="Partner 3">
      <img src="assets/images/partner4.png" alt="Partner 4">
    </div>
  </section> -->

<!-- Partners Section -->
<section class="partners-section" data-aos="fade-up">
    <h2>Trusted by Leading Institutions</h2>
    <div class="partners-logos">
        <!-- University Partner -->
        <img src="https://images.unsplash.com/photo-1541178735493-479c1a27ed24?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
            alt="University Partner" class="university-logo">

        <!-- School Partner -->
        <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
            alt="School Partner" class="school-logo">

        <!-- Education Foundation -->
        <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
            alt="Education Foundation" class="foundation-logo">

        <!-- Tech Institute -->
        <img src="https://images.unsplash.com/photo-1550439062-609e1531270e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
            alt="Tech Institute" class="institute-logo">
    </div>
</section>

<!-- Testimonials -->
<section class="testimonials">
    <h2>What Creators Say</h2>
    <div class="testimonial-slider">
        <div class="testimonial-slide">
            <p>"I never imagined learning art online could be this fun. Love the courses!"</p>
            <span>– Samira, Illustrator</span>
        </div>
        <div class="testimonial-slide">
            <p>"The community support is fantastic. I feel more connected and confident."</p>
            <span>– Rahul, Animator</span>
        </div>
        <div class="testimonial-slide">
            <p>"Workshops are highly interactive. Great way to learn from professionals."</p>
            <span>– Ayesha, Designer</span>
        </div>
    </div>
</section>

<!-- Blog Section -->
<section class="blog-section" data-aos="fade-up">
    <h2>From Our Blog</h2>
    <div class="blog-grid">
        <div class="blog-card">
            <img src="assets/images/blog1.jpg" alt="5 Creative Study Hacks">
            <h3>5 Creative Study Hacks</h3>
            <p>Boost your learning with these unconventional yet effective techniques. #CreativityFreaks</p>
            <a href="#" class="read-more">Read More</a>
        </div>
        <div class="blog-card">
            <img src="assets/images/blog2.jpg" alt="Why Creativity Matters">
            <h3>Why Creativity Matters in Learning</h3>
            <p>Discover how creativity fuels better understanding and student engagement.</p>
            <a href="#" class="read-more">Read More</a>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq" data-aos="fade-up">
    <h2>Frequently Asked Questions</h2>
    <div class="faq-item">
        <button class="faq-question">What is Creativity Freaks?</button>
        <div class="faq-answer">
            <p>Creativity Freaks is an e-learning platform focused on developing skills in math, design, and
                technology with engaging and practical content.</p>
        </div>
    </div>
    <div class="faq-item">
        <button class="faq-question">Are the courses beginner friendly?</button>
        <div class="faq-answer">
            <p>Yes, most of our courses are designed for complete beginners. We also have advanced courses for
                experienced learners.</p>
        </div>
    </div>
    <div class="faq-item">
        <button class="faq-question">How can I join the community?</button>
        <div class="faq-answer">
            <p>You can join our Facebook group or our private forum after signing up.</p>
        </div>
    </div>
</section>

<!-- Newsletter Signup -->
<section class="newsletter">
    <h2>Stay in the Loop</h2>
    <p>Get course updates, inspiration, and exclusive content.</p>
    <form class="newsletter-form">
        <input type="email" placeholder="Enter your email" required>
        <button type="submit">Subscribe</button>
    </form>
</section>
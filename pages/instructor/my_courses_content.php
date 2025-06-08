
<div class="instructor-courses">
  <h2>📚 My Courses</h2>
  <div class="course-grid">
    <?php while ($course = $result->fetch_assoc()): ?>
      <div class="course-card">
        <img src="../../uploads/thumbnail/<?= htmlspecialchars($course['thumbnail']) ?>" alt="Course Thumbnail">
        <div class="course-info">
          <h3><?= htmlspecialchars($course['title']) ?></h3>
          <p>Category: <?= htmlspecialchars($course['category']) ?></p>
          <p>Subcategory: <?= htmlspecialchars($course['subcategory']) ?></p>
          <p>Price: $<?= htmlspecialchars($course['price']) ?></p>
          <!-- <p>Status: <span class="status <?= $course['status'] ?>"><?= ucfirst($course['status']) ?></span></p> -->
          <p>Enrolled: <?= $course['enrolled_students'] ?></p>
          <div class="actions">
            <a href="edit_course.php?id=<?= $course['id'] ?>" class="btn btn-warning">Edit</a>
            <a href="view_course.php?id=<?= $course['id'] ?>" class="btn btn-info">View</a>
            <a href="students.php?course_id=<?= $course['id'] ?>" class="btn btn-success">Students</a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
    
  </div>
  <a href="index.php" class="btn btn-primary">Back</a>
</div>

<link rel="stylesheet" href="/creativityfreaks/assets/css/dashboard.css">

<style>

  .instructor-courses .btn-primary {
  display: inline-block;
  margin-top: 2rem;
  padding: 10px 18px;
  font-size: 1rem;
  font-weight: 500;
  border: none;
  border-radius: 8px;
  background-color: #007bff;
  color: #fff;
  text-decoration: none;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.instructor-courses .btn-primary:hover {
  background-color: #0056b3;
  transform: translateY(-2px);
}

</style>
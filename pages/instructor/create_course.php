<?php
session_start();
include '../../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'instructor') {
    header("Location: /creativityfreaks/index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    $price = floatval($_POST['price']);
    $instructor_id = $_SESSION['user_id'];

    if (empty($title) || empty($description) || empty($category) || empty($subcategory)) {
        $_SESSION['error'] = "All fields except thumbnail are required.";
        header("Location: new_course.php");
        exit;
    }

    // Handle thumbnail upload
    $thumbnail = 'default-course.jpg';
    if (!empty($_FILES['thumbnail']['name']) && $_FILES['thumbnail']['error'] === 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 100 * 1024 * 1024; // 100MB

        if (in_array($_FILES['thumbnail']['type'], $allowedTypes) && $_FILES['thumbnail']['size'] <= $maxSize) {
            $ext = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
            $imageName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
            $targetPath = '../../uploads/thumbnail/' . $imageName;
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetPath)) {
                $thumbnail = $imageName;
            } else {
                $_SESSION['error'] = "Failed to upload course thumbnail.";
                header("Location: create_course.php");
                exit;
            }
        } else {
            $_SESSION['error'] = "Invalid thumbnail file or size too large (max 100MB).";
            header("Location: create_course.php");
            exit;
        }
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO courses (title, description, category, subcategory, thumbnail, price, instructor_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssdi", $title, $description, $category, $subcategory, $thumbnail, $price, $instructor_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Course created successfully!";
        header("Location: my_courses.php");
        exit;
    } else {
        $_SESSION['error'] = "Database error: " . $stmt->error;
        header("Location: dashboard.php");
        exit;
    }
}
?>

<div class="new-course-container">
  <a href="my_courses.php" class="btn-back">← Back to My Courses</a>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="error-message"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <form action="" method="post" enctype="multipart/form-data" class="new-course-form">
    <input type="text" name="title" placeholder="Course title" required><br>
    <textarea name="description" placeholder="Course description..." rows="5" required></textarea><br>

    <select name="category" required>
      <option value="">Select Category</option>
      <option value="Academic">Academic</option>
      <option value="Admission">Admission</option>
      <option value="Skill">Skill</option>
      <option value="Language">Language</option>
    </select><br>

    <input type="text" name="subcategory" placeholder="Subcategory (e.g. Class 10, Medical, etc.)" required><br>
    <input type="value" name="price" step="0.01" placeholder="Course price ($)" required><br>

    <label>Course Thumbnail (optional)</label>
    <input type="file" name="thumbnail" accept="image/*"><br>

    <button type="submit">Create Course</button>
  </form>
</div>

<style>
.new-course-container {
  max-width: 600px;
  margin: 2rem auto;
  padding: 1rem;
}
.btn-back {
  display: inline-block;
  margin-bottom: 1rem;
  color: #6C63FF;
  text-decoration: none;
}
.new-course-form input[type="text"],
.new-course-form input[type="value"],
.new-course-form select,
.new-course-form textarea {
  width: 100%;
  padding: 0.5rem;
  margin-bottom: 1rem;
  border-radius: 6px;
  border: 1px solid #ccc;
  font-size: 1rem;
}
.new-course-form button {
  background-color: #6C63FF;
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  cursor: pointer;
  font-size: 1.1rem;
}
.error-message {
  background: #fdd;
  color: #900;
  padding: 0.75rem;
  border-radius: 6px;
  margin-bottom: 1rem;
  font-weight: bold;
}


</style>

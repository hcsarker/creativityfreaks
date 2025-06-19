<?php
session_start();
require_once '../../includes/db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $course_id = $_POST['course_id'];
  $title = $_POST['title'];
  $type = $_POST['type'];
  $video_url = $_POST['video_url'] ?? null;

  $file_path = null;

  if ($type !== 'video') {
    $upload_dir = '../../uploads/course_content/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
      $filename = time() . '_' . basename($_FILES['file']['name']);
      $target_path = $upload_dir . $filename;

      if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
        $file_path = $filename;
      } else {
        die("❌ Failed to upload file.");
      }
    } else {
      die("❌ No valid file uploaded.");
    }
  }

  $instructor_id = $_SESSION['user_id']; // Instructor's ID

$stmt = $conn->prepare("INSERT INTO course_contents (course_id, instructor_id, title, type, file_path, video_url)
                        VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iissss", $course_id, $instructor_id, $title, $type, $file_path, $video_url);

  $stmt->execute();

  header("Location: view_course.php?id=" . $course_id);
  exit;
}

$course_id = $_GET['id'] ?? null;
if (!$course_id || $_SESSION['user_role'] !== 'instructor') {
  die("Unauthorized access.");
} 
?>
 <section class="dashboard-content">
 
<h2>📝 Upload Content for Course ID: <?= htmlspecialchars($course_id) ?></h2>

<form method="POST" enctype="multipart/form-data">
  <input type="hidden" name="course_id" value="<?= $course_id ?>">

  <label>Title:</label>
  <input type="text" name="title" required>

  <label>Type:</label>
  <select name="type" required onchange="toggleFields(this.value)">
    <option value="video">Video (YouTube)</option>
    <option value="pdf">PDF</option>
    <option value="ppt">PPT</option>
    <option value="doc">DOC</option>
    <option value="sheet">Lecture Sheet</option>
  </select>

  <div id="video-url-field" style="display:none;">
    <label>YouTube Link:</label>
    <input type="text" name="video_url">
  </div>

  <div id="file-upload-field" style="display:block;">
    <label>Upload File:</label>
    <input type="file" name="file">
  </div>

  <button type="submit">Upload</button>
</form>

<a href="my_courses.php" class="btn btn-primary">Back to My Courses</a>
</section>

<script>
  function toggleFields(type) {
    if (type === 'video') {
      document.getElementById('video-url-field').style.display = 'block';
      document.getElementById('file-upload-field').style.display = 'none';
    } else {
      document.getElementById('video-url-field').style.display = 'none';
      document.getElementById('file-upload-field').style.display = 'block';
    }
  }
</script>

<style>
    .dashboard-content {
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        max-width: 800px;
        margin: auto;
    }

  form {
    max-width: 600px;
    margin: auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
  }

  label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
  }

  input[type="text"],
  input[type="file"],
  select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  button {
    padding: 10px 15px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  button:hover {
    background-color: #0056b3;
  }
  #video-url-field,
  #file-upload-field {
    margin-bottom: 15px;
  }
  #video-url-field input,
  #file-upload-field input {
    width: calc(100% - 20px);
    padding: 10px;
    margin-top: 5px;
  }
  #video-url-field label,
  #file-upload-field label {
    margin-bottom: 5px;
  }
  #video-url-field {
    display: none;
  }
  #file-upload-field {
    display: block;
  }

  .btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007BFF;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 20px;
  }
  .btn:hover {
    background-color: #0056b3;
  }
  .btn-primary {
    background-color: #007BFF;
  }
  .btn-primary:hover {
    background-color: #0056b3;
  }
</style>
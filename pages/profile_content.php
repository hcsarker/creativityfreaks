<div class="container profile-page">
  <h2>Your Profile</h2>

  <form method="POST" enctype="multipart/form-data">
    <label for="name">Full Name:</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" required>

    <label>Profile Picture:</label>
    <img src="/creativityfreaks/uploads/avatars/<?php echo $_SESSION['user_avatar']; ?>" alt="Avatar" class="avatar-preview">
    <input type="file" name="avatar" accept="image/*">

    <button type="submit">Update Profile</button>
  </form>
</div>
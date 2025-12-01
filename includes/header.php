<header class="main-header">
    <div class="container header-content">

        <!-- Logo -->
        <div class="logo">
            <a href="/creativityfreaks/index.php">
                <img src="/creativityfreaks/assets/images/logo.png" alt="Creativity Freaks">
            </a>
        </div>

        <!-- Navigation -->
        <nav class="nav-links" id="navLinks">
            <a href="/creativityfreaks/index.php" class="active">Home</a>
            <a href="/creativityfreaks/pages/courses.php" class="active">Courses</a>
            <a href="/creativityfreaks/pages/about.php" class="active">About</a>
            <a href="/creativityfreaks/pages/community/community.php" class="active">Community</a>
            <a href="/creativityfreaks/pages/contact.php" class="active">Contact</a>
            <a href="/creativityfreaks/pages/exam_batch.php" class="active">Exam Batch</a>
        </nav>

        <!-- Right Section -->
        <div class="header-right">
            <!-- Hamburger (Mobile) -->
            <div class="hamburger" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </div>
            
            <?php if (!isset($_SESSION['user_id'])): ?>
            <button class="login-btn" onclick="toggleModal()">Login</button>
            <?php else: ?>
            <!-- Add to your header -->
            <div class="notification-icon">
                <i class="fa fa-bell"></i>
                <span class="badge" id="notification-count">0</span>
                <div class="notification-dropdown" id="notification-dropdown">
                    <div class="notification-list" id="notification-list">
                        <!-- Notifications will be loaded here -->
                    </div>
                </div>
            </div>
            <div class="profile-menu">
                <img src="/creativityfreaks/uploads/avatars/<?php echo $_SESSION['user_avatar'] ?? 'default.png'; ?>"
                    alt="Profile Picture" class="avatar" onclick="toggleProfileMenu()">


                <div id="dropdown" class="dropdown hidden animate">
                    <p class="user-name"><?php echo $_SESSION['user_name']; ?></p>
                    <a href="/creativityfreaks/pages/profile.php"><i class="fas fa-user"></i> Profile</a>
                    <a href="/creativityfreaks/pages/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    <a href="/creativityfreaks/auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
            <?php endif; ?>
        </div>

    </div>
</header>
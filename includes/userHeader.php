<header class="header-section">
    <div class="user-info">
        <div class="user-image">
            <?php echo strtoupper(substr($_SESSION['name'], 0, 1)); ?>
        </div>
        <p class="user-name"><?php echo htmlspecialchars($_SESSION['name']) ?><br> <span style="font-size:0.8em"><?php echo date("Y-m-d")?></span></p>
    </div>
    <nav class="navigation">
        <a href="../public/user.php"><p>Dashboard</p></a>
        <a href="../public/userCourse.php"><p>Submit Course</p></a>
        <a href="../public/userAttendance.php"><p>My Attendance</p></a>
        <a href="../public/userProfile.php"><p>Profile</p></a>
    </nav>
    <a href="logout.php"><p class="logout">Log Out </p></a>
</header>
<header class="header-section">
    <div class="user-info">
        <div class="user-image">A</div>
        <p class="user-name"><?php echo htmlspecialchars($_SESSION['name']) ?><br> <span style="font-size:0.8em"><?php echo date("Y-m-d")?></span></p>
    </div>
    <nav class="navigation">
        <a href="../public/home.php"><p>Dashboard</p></a>
        <a href="../public/student.php"><p>Students</p></a>
        <a href="../public/attendance.php"><p>Attendance</p></a>
        <a href="../public/course.php"><p>Records</p></a>
    </nav>
    <a href="logout.php"><p class="logout">Log Out </p></a>
</header>
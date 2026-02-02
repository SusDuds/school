<?php
    include "../config/db.php";
    include "../config/session.php";
    include "../includes/functions.php";

    if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'student') {
        header("location:login.php");
        exit;
    }
    
    // Initial status check (kept PHP for first load speed, button handles logic)
    $id = $_SESSION['studentId'];
    $status = todaysAttendance($id);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Dashboard</title>
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    <link rel="stylesheet" type="text/css" href="../assets/css/home.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/user.css">
</head>
<body>
    <section class="dashboard">
        <?php include "../includes/userHeader.php" ?>
        <main class="main-right">
            <p class="home-welcome">Namaste, <?php echo htmlspecialchars($_SESSION['name']) ?> </p>
            <article>
                <div class="attendance">
                    <p class="leave-text" id="attendance-status">Today's Attendance: <?php echo htmlspecialchars($status); ?></p>
                    
                    <?php if ($status == 'Not punched in'): ?>
                        <button class="attendance-btn" id="checkin-btn" onclick="markAttendance()">Check In</button>
                    <?php endif; ?>
                </div>
                <div class="req-leave">
                    <p>Course Completed?</p>
                    <a href="userCourse.php"><button class="attendance-btn">Submit Record</button></a>
                </div>
            </article>
        </main>
    </section>
    <script src="../assets/js/user.js"></script>
</body>
</html>
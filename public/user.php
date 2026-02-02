<?php
    include "../config/db.php";
    include "../config/session.php";
    include "../includes/functions.php";

    $id = $_SESSION['studentId'];
    if ($_SESSION['role'] != 'student') {
        header("location:login.php");
    }
    $status = todaysAttendance($id);

    if (isset($_POST['attendance-btn'])) {
        try {
            $pdo->query("Insert into attendance(studentId) values ($id);");
            header("Refresh:0");
        } catch (PDOException $e) { }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Dashboard</title>
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
                    <p class="leave-text">Today's Attendance: <?php echo $status; ?></p>
                    <?php if ($status == 'Not punched in'): ?>
                        <form method="POST"><button class="attendance-btn" name="attendance-btn" type="Submit">Check In</button></form>
                    <?php endif; ?>
                </div>
                <div class="req-leave">
                    <p>Course Completed?</p>
                    <a href="userCourse.php"><button class="attendance-btn">Submit Record</button></a>
                </div>
            </article>
        </main>
    </section>
</body>
</html>
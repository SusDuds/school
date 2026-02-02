<?php
    include "../config/session.php";
    include "../config/db.php";
    $id = $_SESSION['studentId'];
    if ($_SESSION['role'] != 'student') {
        header("location:login.php");
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Submit Record</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/home.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/userCourse.css">
</head>
<body>
    <section class="dashboard">
        <?php include "../includes/userHeader.php" ?>
        <main class="main-right">
            <p class="home-welcome">Submit Course Completion</p>
            <div class="leave-form">
                <label>Course Name: </label>
                <input type="text" id="course-name">
                <label>Date:</label>
                <input type="Date" id="course-date">
                <label>Grade Obtained:</label>
                <input type="text" id="course-grade">
                <input type="hidden" id="student-id" value="<?php echo $_SESSION['studentId'] ?>">
                <input type="hidden" id="student-name" value="<?php echo $_SESSION['name'] ?>">
                <button class="leave-submit" onclick="addCourse()">Submit</button>
            </div>
        </main>
    </section>
    <script src="../assets/js/user.js"></script>
</body>
</html>
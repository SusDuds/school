<?php
    include "../config/session.php";
    if ($_SESSION['role'] != 'admin') {
        header("location:login.php");
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Course Records</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/home.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/course.css">
</head>
<body>
    <section class="dashboard">
        <?php include "../includes/header.php" ?>
        <main class="main-right">
            <p class="home-welcome"> Course Completions </p>
            <article class="leave-table">
                <table class="table" id="course_table">
                    <tr>
                        <th>Student</th><th>Course</th><th>Date</th><th>Grade</th><th>Status</th><th>Action</th>
                    </tr>
                </table>            
            </article>
        </main>
    </section>
    <script src="../assets/js/course.js"></script>
</body>
</html>
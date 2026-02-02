<?php
    include "../config/session.php";
    if ($_SESSION['role'] != 'admin') {
        header("location:login.php");
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Students</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/home.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/student.css">
</head>
<body>
    <section class="dashboard">
        <?php include "../includes/header.php" ?>
        <main class="main-right">
            <p class="home-welcome"> Student Directory </p>
            <article class="employee-table">
                <table class="table" id="student_table">
                    <tr>
                        <th>ID</th><th>Name</th><th>Email</th><th>Program</th><th>Action</th>
                    </tr>        
                </table>
            </article>
        </main>
    </section>
    <script src="../assets/js/student.js"></script>
</body>
</html>
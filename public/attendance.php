<?php
    include "../config/session.php";
    if ($_SESSION['role'] != 'admin') header("location:login.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Attendance</title>
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    <link rel="stylesheet" type="text/css" href="../assets/css/home.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/attendance.css">
</head>
<body>
    <section class="dashboard">
        <?php include "../includes/header.php" ?>
        <main class="main-right">
            <p class="home-welcome"> Attendance Register </p>
            <article class="attendance-table">
                <div class="date-search">
                    <label>Date: </label>
                    <input type="date" id="attendance-date" class="date-input" value="<?php echo date('Y-m-d')?>" onchange="searchDate()">
                </div>
                <table class="table" id="attendance_table">
                    </table>
            </article>
        </main>
    </section>
    <script src="../assets/js/attendance.js"></script>
</body>
</html>
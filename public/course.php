<?php
    include "../config/session.php";
    if ($_SESSION['role'] != 'admin') header("location:login.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Course Records</title>
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    <link rel="stylesheet" type="text/css" href="../assets/css/home.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/course.css"> 
</head>
<body>
    <section class="dashboard">
        <?php include "../includes/header.php" ?>
        <main class="main-right">
            <p class="home-welcome">Pending Course Verifications</p>
            <article class="leave-table">
                <table class="table" id="course_table">
                    </table>
            </article>
        </main>
    </section>
    <script src="../assets/js/course.js?v=<?php echo time(); ?>"></script>
</body>
</html>
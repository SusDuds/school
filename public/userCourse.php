<?php
    include "../config/session.php";
    if (!isset($_SESSION['logged_in'])) header("location:login.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Submit Course</title>
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    
    <link rel="stylesheet" type="text/css" href="../assets/css/home.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/userCourse.css">
</head>
<body>
    <section class="dashboard">
        <?php include "../includes/userHeader.php" ?>
        <main class="main-right">
            <p class="home-welcome">Submit Course Record</p>
            <article class="course-form-container">
                <form onsubmit="event.preventDefault(); addCourse();" class="course-form">
                    <label>Course Name</label>
                    <input type="text" id="course-name" required>
                    
                    <label>Completion Date</label>
                    <input type="date" id="course-date" required>
                    
                    <label>Grade/Score</label>
                    <input type="text" id="course-grade" required>
                    
                    <button type="submit" class="submit-btn">Submit Record</button>
                </form>
            </article>
        </main>
    </section>
    <script src="../assets/js/user.js?v=<?php echo time(); ?>"></script>
</body>
</html>
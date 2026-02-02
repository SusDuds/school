<?php
    include "../config/session.php";
    include "../config/db.php";
    $id = $_SESSION['studentId'];
    $stmt = $pdo->prepare("SELECT * FROM students WHERE studentId=?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/home.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/userProfile.css">
</head>
<body>
    <section class="dashboard">
        <?php include "../includes/userHeader.php" ?>
        <main class="main-right">
            <p class="home-welcome">My Profile</p>
            <div class="user-details">
                <h3><?php echo $user['fullname']; ?></h3>
                <p>Email: <?php echo $user['email']; ?></p>
                <p>Program: <?php echo $user['program']; ?></p>
            </div>
        </main>
    </section>
</body>
</html>
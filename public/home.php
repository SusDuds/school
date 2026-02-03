<?php
    include "../includes/functions.php";
    include "../config/session.php";

    if (isset($_SESSION['logged_in']) && $_SESSION['role'] === 'admin') {
        $name = $_SESSION['name'];
        $stdLen = count(json_decode(infoStudent(), true)); 
        $progLen = programNum();
        $pendLen = pendingNum();
        $presLen = present();
        
        // Debug: Log the count
        error_log("Admin dashboard - Present today: " . $presLen);
    } else {
        header("Location:login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/home.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css">
    <!-- Auto refresh every 30 seconds to show updated counts -->
    <meta http-equiv="refresh" content="30">
</head>
<body>
    <section class="dashboard">
        <?php include "../includes/header.php" ?>
        <main class="main-right">
            <p class="home-welcome">Namaste, Admin <?php echo htmlspecialchars($name) ?></p>
            <p style="color: #666; font-size: 12px; margin-bottom: 20px;">
                Last updated: <?php echo date('Y-m-d H:i:s'); ?> (refreshes every 30s)
            </p>
            <article class="feature-card">
                <div class="feature blue">
                    <p>Students</p>
                    <p class="numbers"><?php echo intval($stdLen); ?> </p>
                </div>
                <div class="feature orange">
                    <p>Programs</p>
                    <p class="numbers"><?php echo intval($progLen); ?></p>
                </div>
                <div class="feature red">
                    <p>Pending Records</p>
                    <p class="numbers"><?php echo intval($pendLen); ?></p>
                </div>
                <div class="feature green">
                    <p>Present Today</p>
                    <p class="numbers" id="present-count"><?php echo intval($presLen); ?></p>
                </div>
            </article>
        </main>
    </section>
    <?php include "../includes/footer.php" ?>
</body>
</html>
<?php
    include "../includes/functions.php";
    include "../config/session.php";

    if (isset($_SESSION['logged_in']) && $_SESSION['role'] === 'admin') {
        $name = $_SESSION['name'];
        $stdLen = count(json_decode(infoStudent(), true)); 
        $totalRec = totalRecords(); // Changed from programNum()
        $pendLen = pendingNum();
        $presLen = present(); // Fixed to only count active students today
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
</head>
<body>
    <section class="dashboard">
        <?php include "../includes/header.php" ?>
        <main class="main-right">
            <p class="home-welcome">Namaste, Admin <?php echo htmlspecialchars($name) ?></p>
            <article class="feature-card">
                <div class="feature blue">
                    <p>Students</p>
                    <p class="numbers"><?php echo $stdLen ?> </p>
                </div>
                <div class="feature orange">
                    <p>Total Records</p> <!-- Changed from Programs -->
                    <p class="numbers"><?php echo $totalRec ?></p>
                </div>
                <div class="feature red">
                    <p>Pending Records</p>
                    <p class="numbers"><?php echo $pendLen ?></p>
                </div>
                <div class="feature green">
                    <p>Present Today</p>
                    <p class="numbers"><?php echo $presLen ?></p>
                </div>
            </article>
        </main>
    </section>
    <?php include "../includes/footer.php" ?>
</body>
</html>
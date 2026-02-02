<?php
    include "../config/session.php";
    include "../config/db.php";
    $id = $_SESSION['studentId'];
    $stmt = $pdo->query("SELECT * from attendance where studentId=$id ORDER BY attendance_date DESC");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Attendance</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/home.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/userCourse.css">
</head>
<body>
    <section class="dashboard">
        <?php include "../includes/userHeader.php" ?>
        <main class="main-right">
            <p class="home-welcome">History</p>
            <table class="table">
                <tr>
                    <th>Date</th><th>Status</th>
                </tr>
                <?php foreach ($result as $row) { ?>
                    <tr>
                        <td><?php echo $row['attendance_date'] ?></td>
                        <td>Present</td>
                    </tr>
                <?php } ?>
            </table>    
        </main>
    </section>
</body>
</html>
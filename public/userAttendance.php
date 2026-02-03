<?php
    include "../config/session.php";
    include "../config/db.php";
    $id = $_SESSION['studentId'] ?? 0;
    if ($_SESSION['role'] != 'student') {
        header("location:login.php");
        exit;
    }
    
    $stmt = $pdo->prepare("SELECT * FROM attendance WHERE studentId = ? ORDER BY attendance_date DESC");
    $stmt->execute([$id]);
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
                        <td><?php echo htmlspecialchars($row['attendance_date']) ?></td>
                        <td>Present</td>
                    </tr>
                <?php } ?>
                <?php if (count($result) === 0): ?>
                    <tr>
                        <td colspan="2" style="text-align:center;padding:40px;">No attendance records found</td>
                    </tr>
                <?php endif; ?>
            </table>    
        </main>
    </section>
</body>
</html>
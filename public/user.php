<?php
    include "../config/db.php";
    include "../config/session.php";
    include "../includes/functions.php";

    $id = $_SESSION['studentId'] ?? 0;
    if ($_SESSION['role'] != 'student') {
        header("location:login.php");
        exit;
    }
    
    $message = '';
    $messageClass = '';
    $justCheckedIn = false;
    
    // Handle attendance check-in FIRST
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkin'])) {
        // Validate CSRF
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
            header("Location: user.php?error=csrf");
            exit;
        }
        
        // Check if already present before inserting
        $currentStatus = todaysAttendance($id);
        
        if ($currentStatus === 'Not punched in') {
            try {
                $stmt = $pdo->prepare("INSERT INTO attendance (studentId, attendance_date) VALUES (?, CURDATE())");
                $stmt->execute([$id]);
                $justCheckedIn = true;
                // Redirect to clean URL with success flag
                header("Location: user.php?success=1");
                exit;
            } catch (PDOException $e) { 
                error_log("Check-in error: " . $e->getMessage());
                header("Location: user.php?error=db");
                exit;
            }
        } else {
            // Already checked in, just redirect without error
            header("Location: user.php?already=1");
            exit;
        }
    }
    
    // NOW check status for display (after any potential insert)
    $status = todaysAttendance($id);
    
    // Handle messages AFTER status check
    if (isset($_GET['success']) && $status === 'Present') {
        $message = '✓ Checked in successfully!';
        $messageClass = 'success';
    } else if (isset($_GET['already']) || (isset($_GET['success']) && $status !== 'Present')) {
        // If success flag but not present in DB, or explicitly already flag
        if ($status === 'Present') {
            $message = 'You are already checked in for today.';
            $messageClass = 'info';
        }
    } else if (isset($_GET['error'])) {
        $message = 'Error checking in. Please try again.';
        $messageClass = 'error';
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/home.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/user.css">
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    <style>
        .message-success { color: #059669; font-weight: 600; padding: 12px; background: #d1fae5; border-radius: 8px; margin: 10px 0; border: 1px solid #059669; }
        .message-info { color: #d97706; font-weight: 600; padding: 12px; background: #fef3c7; border-radius: 8px; margin: 10px 0; border: 1px solid #d97706; }
        .message-error { color: #dc2626; font-weight: 600; padding: 12px; background: #fee2e2; border-radius: 8px; margin: 10px 0; border: 1px solid #dc2626; }
        .status-present { color: #059669; font-weight: 600; }
        .status-not-punched { color: #d97706; font-weight: 600; }
        .checked-in-info { 
            background: #f3f4f6; 
            padding: 20px; 
            border-radius: 12px; 
            text-align: center;
            margin-top: 10px;
        }
        .checked-in-info .big-check {
            font-size: 48px;
            color: #059669;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <section class="dashboard">
        <?php include "../includes/userHeader.php" ?>
        <main class="main-right">
            <p class="home-welcome">Namaste, <?php echo htmlspecialchars($_SESSION['name']) ?> </p>
            <article>
                <div class="attendance">
                    <p class="leave-text">
                        Today's Attendance: 
                        <span class="<?php echo $status === 'Present' ? 'status-present' : 'status-not-punched'; ?>">
                            <?php echo htmlspecialchars($status); ?>
                        </span>
                    </p>
                    
                    <?php if ($message): ?>
                        <div class="message-<?php echo $messageClass; ?>">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($status === 'Present'): ?>
                        <div class="checked-in-info">
                            <div class="big-check">✓</div>
                            <p style="color: #059669; font-weight: 600; font-size: 18px; margin: 0;">
                                You're checked in!
                            </p>
                            <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">
                                Great job! See you tomorrow.
                            </p>
                        </div>
                    <?php else: ?>
                        <form method="POST" action="" style="margin-top: 15px;">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <button class="attendance-btn" name="checkin" type="submit" value="1" style="width: 100%; padding: 16px; font-size: 16px;">
                                Check In Now
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
                <div class="req-leave">
                    <p>Course Completed?</p>
                    <a href="userCourse.php"><button class="attendance-btn">Submit Record</button></a>
                </div>
            </article>
        </main>
    </section>
</body>
</html>
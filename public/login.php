<?php
    include "../config/db.php";
    include "../config/session.php";
    $error = "";
    global $pdo;

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['login_button'])) {
            if (trim($_POST['login_email']) != "" & trim($_POST['login_password']) != "") {
                try {
                    $email = $_POST['login_email'];
                    $stmt = $pdo->prepare("SELECT studentId,email,fullname,password,role from students where email=?");
                    $stmt->execute([$email]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($result) {
                        if (password_verify(trim($_POST['login_password']), $result['password'])) {
                            $_SESSION['logged_in'] = true;
                            $_SESSION['studentId'] = $result['studentId'];
                            $_SESSION['name'] = $result['fullname'];
                            $_SESSION['role'] = $result['role'];

                            if ($_SESSION['role'] == "admin") {
                                header("Location:home.php");
                            } else {
                                header("Location:user.php");
                            }
                        } else { $error = "User not found"; }
                    } else { $error = "User not found"; }
                } catch (Exception $e) { $error = $e->getMessage(); }
            } else { $error = "All Fields need to be filled"; }
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - NamasteVidyalaya</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/login.css">
</head>
<body>
    <section class="login-page">
        <main class="login-section">
            <h1 class="login-header">NamasteVidyalaya</h1>
            <article class="login-place">
                <h3>Student Portal</h3>
                <form class="login-form" method="POST">
                    <label>Email ID</label>
                    <input type="Email" name="login_email" placeholder="student@namaste.edu">
                    <label>Password</label>
                    <input type="password" name="login_password" placeholder="••••••••">
                    <p class="message" style="color:red;"><?php echo $error ?></p>
                    <input type="submit" name="login_button" value="Secure Login" class="login-submit">
                </form>
                <p class="new-login">New Admission? <a href="signup.php" style="color: #ff6f00;">Apply Now</a></p>
            </article>
        </main>
        <aside class="side-section">
            <div class="side-image">
                <h2>Education for Everyone</h2>
            </div>
        </aside>
    </section>
</body>
</html>
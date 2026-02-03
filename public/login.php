<?php
    include "../config/db.php";
    include "../config/session.php";
    $error = "";
    global $pdo;

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['login_button'])) {
            // CSRF Validation
            if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
                $error = "Security token invalid";
            } else if (trim($_POST['login_email']) != "" && trim($_POST['login_password']) != "") {
                try {
                    $email = filter_var(trim($_POST['login_email']), FILTER_SANITIZE_EMAIL);
                    $stmt = $pdo->prepare("SELECT studentId, email, fullname, password, role FROM students WHERE email=?");
                    $stmt->execute([$email]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($result) {
                        if (password_verify(trim($_POST['login_password']), $result['password'])) {
                            $_SESSION['logged_in'] = true;
                            $_SESSION['studentId'] = $result['studentId'];
                            $_SESSION['name'] = $result['fullname'];
                            $_SESSION['role'] = $result['role'];

                            // Regenerate session ID on login
                            session_regenerate_id(true);

                            if ($_SESSION['role'] == "admin") {
                                header("Location:home.php");
                            } else {
                                header("Location:user.php");
                            }
                            exit;
                        } else {
                            $error = "Invalid password";
                        }
                    } else {
                        $error = "User not found";
                    }
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            } else {
                $error = "All Fields need to be filled";
            }
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - NamasteVidyalaya</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/login.css">
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
</head>
<body>
    <section class="login-page">
        <main class="login-section">
            <h1 class="login-header">NamasteVidyalaya</h1>
            <article class="login-place">
                <h3>Student Portal</h3>
                <form class="login-form" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <label>Email ID</label>
                    <input type="email" name="login_email" placeholder="student@gmail.com" required>
                    
                    <label>Password</label>
                    <input type="password" name="login_password" placeholder="••••••••" required>
                    
                    <p class="message" style="color:red;"><?php echo htmlspecialchars($error) ?></p>
                    
                    <input type="submit" name="login_button" value="Secure Login" class="login-submit">
                </form>
                <p class="new-login">New Admission? <a href="signup.php" style="color: #ff6f00;">Apply Now</a></p>
            </article>
        </main>
        <aside class="side-section">
            <div class="side-image">
                <h2>NamasteVidyalaya</h2>
                <p>Where Tradition Meets Excellence in Education</p>
            </div>
        </aside>
    </section>
</body>
</html>
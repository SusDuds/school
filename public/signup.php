<?php
    include "../config/db.php";
    include "../config/session.php";
    global $pdo;
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // CSRF Validation
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
            $error = "Security token invalid";
        } else if (isset($_POST['signup_button'])) {
            $name = htmlspecialchars(trim($_POST['signup_name']));
            $email = filter_var(trim($_POST['signup_email']), FILTER_SANITIZE_EMAIL);
            $prog = htmlspecialchars(trim($_POST['signup_program']));
            $password = trim($_POST['signup_password']);
            
            if (strlen($password) < 6) {
                $error = "Password must be at least 6 characters long";
            } else {
                $password = password_hash($password, PASSWORD_DEFAULT);
                try {
                    $stmt = $pdo->prepare("INSERT INTO students(fullname, email, program, password) VALUES (?,?,?,?)");
                    $stmt->execute([$name, $email, $prog, $password]);
                    header("Refresh:0; url=login.php");
                    exit;
                } catch (PDOException $e) { 
                    $error = "Email already registered"; 
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - NamasteVidyalaya</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/signup.css">
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
</head>
<body>
    <section class="login-page">
        <main class="login-section">
            <h1 class="login-header">Admission</h1>
            <article class="login-place">
                <h3>Student Registration</h3>
                <form class="login-form" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <label>Full Name</label>
                    <input type="text" name="signup_name" required placeholder="Full Name">
                    
                    <label>Email</label>
                    <input type="email" name="signup_email" required placeholder="student@gmail.com">
                    
                    <label>Program / Class</label>
                    <select name="signup_program" required>
                        <option>Grade 11 - Science</option>
                        <option>Grade 11 - Management</option>
                        <option>Grade 12 - Science</option>
                    </select>
                    
                    <label>Create Password</label>
                    <input type="password" name="signup_password" minlength="6" required placeholder="••••••••">
                    <p class="password-hint">Password must be at least 6 characters long</p>
                    
                    <p class="error-message"><?php echo htmlspecialchars($error) ?></p>
                    
                    <input type="submit" value="Register" class="login-submit" name="signup_button">
                </form>
                <p class="new-login"><a href="login.php">Back to Login</a></p>
            </article>
        </main>
        <aside class="side-section">
            <h2>Join NamasteVidyalaya<br>Begin Your Journey</h2>
        </aside>
    </section>
</body>
</html>
<?php
    include "../config/db.php";
    global $pdo;
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['signup_button'])) {
            $name = trim($_POST['signup_name']);
            $email = trim($_POST['signup_email']);
            $prog = trim($_POST['signup_program']);
            $password = password_hash(trim($_POST['signup_password']), PASSWORD_DEFAULT);
            try {
                $stmt = $pdo->prepare("INSERT INTO students(fullname,email,program,password) values (?,?,?,?)");
                $stmt->execute([$name, $email, $prog, $password]);
                header("Refresh:0;url=login.php");
            } catch (PDOException $e) { $error = "Email already registered"; }
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - NamasteVidyalaya</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/signup.css">
</head>
<body>
    <section class="login-page">
        <main class="login-section">
            <h1 class="login-header">Admission</h1>
            <article class="login-place">
                <h3>Student Registration</h3>
                <form class="login-form" method="POST">
                    <label>Full Name</label>
                    <input type="text" name="signup_name">
                    <label>Email</label>
                    <input type="Email" name="signup_email">
                    <label>Program / Class</label>
                    <select name="signup_program">
                        <option>Grade 11 - Science</option>
                        <option>Grade 11 - Management</option>
                        <option>Grade 12 - Science</option>
                    </select>
                    <label>Create Password</label>
                    <input type="password" name="signup_password">
                    <p class="error-message"><?php echo $error ?></p>
                    <input type="submit" value="Register" class="login-submit" name="signup_button">
                </form>
                <p class="new-login"><a href="login.php">Back to Login</a></p>
            </article>
        </main>
        <aside class="side-section">
            <h2>Join the Family</h2>
        </aside>
    </section>
</body>
</html>
<?php 
    $servername = "localhost";
    $username = "NP03CS4A240201";
    $password = "ZtfP5QJlrL";
    $database = "NP03CS4A240201";

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$database;charset=utf8mb4", $username, $password);
        // Throw exceptions on errors
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Disable emulation of prepared statements (security)
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        // Default fetch as array
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        // Ensure tables exist (kept your original schema logic)
        $pdo->exec("CREATE TABLE IF NOT EXISTS students (
                        studentId INT PRIMARY KEY AUTO_INCREMENT,
                        fullname VARCHAR(100) NOT NULL,
                        email VARCHAR(100) NOT NULL UNIQUE,
                        password VARCHAR(255) NOT NULL,
                        program VARCHAR(50),
                        role VARCHAR(50) DEFAULT 'student',
                        joined_at DATE DEFAULT CURRENT_DATE
                    )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS courses (
                        recordId INT PRIMARY KEY AUTO_INCREMENT,
                        studentId INT,
                        fullname VARCHAR(100) NOT NULL,
                        completion_date DATE NOT NULL,
                        course_name VARCHAR(100) NOT NULL,
                        grade VARCHAR(10),
                        status VARCHAR(20) DEFAULT 'Pending'
                    )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS attendance (
                        id INT PRIMARY KEY AUTO_INCREMENT,
                        studentId INT NOT NULL,
                        attendance_date DATE DEFAULT CURRENT_DATE,
                        UNIQUE KEY unique_attendance (studentId, attendance_date)
                    )");

        // Admin check
        $stmt = $pdo->prepare("SELECT studentId FROM students WHERE email = ?");
        $stmt->execute(['admin@namaste.edu']);
        if ($stmt->rowCount() == 0) {
            $pass = password_hash("admin123", PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO students (fullname, email, password, program, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute(['Principal', 'admin@namaste.edu', $pass, 'Administration', 'admin']);
        }

    } catch (PDOException $e) {
        // Log error instead of echoing in production
        error_log($e->getMessage());
        die("Database Connection Failed.");
    }
?>
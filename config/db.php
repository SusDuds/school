<?php 

    $servername = "localhost";
    $username = "NP03CS4A240201";
    $password = "ZtfP5QJlrL";
    $database = "NP03CS4A240201";

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  
        $pdo->query("CREATE TABLE IF NOT EXISTS students (
                        studentId INT PRIMARY KEY AUTO_INCREMENT,
                        fullname VARCHAR(100) NOT NULL,
                        email VARCHAR(100) NOT NULL UNIQUE,
                        password VARCHAR(255) NOT NULL,
                        program VARCHAR(50),
                        role VARCHAR(50) DEFAULT 'student',
                        joined_at DATE DEFAULT CURRENT_DATE
                    );");

  
        $pdo->query("CREATE TABLE IF NOT EXISTS courses (
                        recordId INT PRIMARY KEY AUTO_INCREMENT,
                        studentId INT,
                        fullname VARCHAR(100) NOT NULL,
                        completion_date DATE NOT NULL,
                        course_name VARCHAR(100) NOT NULL,
                        grade VARCHAR(10),
                        status VARCHAR(20) DEFAULT 'Pending'
                    );");

  
        $pdo->query("CREATE TABLE IF NOT EXISTS attendance (
                        studentId INT NOT NULL,
                        attendance_date DATE DEFAULT CURRENT_DATE,
                        PRIMARY KEY (studentId, attendance_date)
                    );");

 
        $check = $pdo->query("SELECT * FROM students WHERE email='admin@namaste.edu'");
        if ($check->rowCount() == 0) {
            $pass = password_hash("admin123", PASSWORD_DEFAULT);
            $pdo->query("INSERT INTO students (fullname, email, password, program, role) VALUES ('Principal', 'admin@namaste.edu', '$pass', 'Administration', 'admin')");
        }

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
?>
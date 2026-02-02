<?php
    require_once "session.php";
    require_once "db.php";
    header("Content-Type: application/json");

    function checkCSRF() {
        $headers = getallheaders();
        $token = $headers['X-CSRF-Token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'], $token)) {
            http_response_code(403);
            echo json_encode(['error' => 'Invalid CSRF Token']);
            exit;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === "GET") {
        $date = $_GET['date'] ?? date('Y-m-d');
        
        // Optimized JOIN query
        $sql = "SELECT s.studentId AS id, s.fullname AS name, s.program AS department 
                FROM students s 
                JOIN attendance a ON s.studentId = a.studentId 
                WHERE a.attendance_date = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$date]);
        $result = $stmt->fetchAll();
        
        echo json_encode($result);
    }
    
    // Check-in Logic (Moved from user.php)
    elseif ($_SERVER['REQUEST_METHOD'] === "POST") {
        checkCSRF();
        if (!isset($_SESSION['studentId'])) {
            http_response_code(401);
            exit;
        }
        
        $id = $_SESSION['studentId'];
        $date = date("Y-m-d");
        
        try {
            $stmt = $pdo->prepare("INSERT IGNORE INTO attendance (studentId, attendance_date) VALUES (?, ?)");
            $stmt->execute([$id, $date]);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'status' => 'Present']);
            } else {
                echo json_encode(['success' => true, 'status' => 'Already Checked In']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
?>
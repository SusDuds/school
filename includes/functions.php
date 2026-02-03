<?php
    include "../config/db.php"; 

    function infoStudent() {
        try {
           global $pdo;
            $stmt = $pdo->query("Select * from students where role='student'");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $allStudents = [];
            foreach ($rows as $std) {
                $allStudents[] = [
                    'ID' => $std['studentId'],
                    'name' => $std['fullname'],
                    'email' => $std['email'],
                    'dept' => $std['program'],
                    'role' => $std['role'],
                    'joined_at' => $std['joined_at'],
                ];
            }
            return json_encode($allStudents);
        } catch (PDOException $e) {
            return json_encode([]);
        }
    }

    function programNum() {
        try {
            global $pdo;
            $dept = $pdo->query("Select distinct program from students");
            $rows = $dept->fetchAll(PDO::FETCH_ASSOC);
            return count($rows);
        } catch (PDOException $e) {
            return 0;
        }
    }

    function pendingNum() {
        try {
            global $pdo;
            $dept = $pdo->query("Select recordId from courses where status='Pending'");
            $rows = $dept->fetchAll(PDO::FETCH_ASSOC);
            return count($rows);
        } catch (PDOException $e) {
            return 0;
        }
    }

    // FIXED: Total records count (all courses)
    function totalRecords() {
        try {
            global $pdo;
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM courses");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] ?? 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    // FIXED: Present today - only count active students
    function present() {
        try {
            global $pdo;
            // FIXED: Use prepared statement with CURDATE() and JOIN to exclude deleted students
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as count 
                FROM attendance a 
                JOIN students s ON a.studentId = s.studentId 
                WHERE a.attendance_date = CURDATE() 
                AND s.role = 'student'
            ");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] ?? 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    function todaysAttendance($id) {
        try {
            global $pdo;
            $stmt = $pdo->prepare("
                SELECT studentId 
                FROM attendance 
                WHERE attendance_date = CURDATE() 
                AND studentId = ?
            ");
            $stmt->execute([$id]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) > 0) {
                return "Present";
            } else {
                return "Not punched in";
            }
        } catch (PDOException $e) {
            return "Error";
        }
    }

    // NEW: Get student's records for management
    function getStudentRecords($studentId) {
        try {
            global $pdo;
            $stmt = $pdo->prepare("
                SELECT * FROM courses 
                WHERE studentId = ? 
                ORDER BY recordId DESC
            ");
            $stmt->execute([$studentId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
?>
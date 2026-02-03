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
                    'ID' => htmlspecialchars($std['studentId']),
                    'name' => htmlspecialchars($std['fullname']),
                    'email' => htmlspecialchars($std['email']),
                    'dept' => htmlspecialchars($std['program']),
                    'role' => htmlspecialchars($std['role']),
                    'joined_at' => htmlspecialchars($std['joined_at']),
                ];
            }
            return json_encode($allStudents);
        } catch (PDOException $e) {
            return json_encode(['error' => $e->getMessage()]);
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

    function present() {
        try {
            global $pdo;
            // Use MySQL CURDATE() to ensure server timezone consistency
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM attendance WHERE attendance_date = CURDATE()");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $count = intval($result['count'] ?? 0);
            error_log("Present count for today: " . $count); // Debug log
            return $count;
        } catch (PDOException $e) {
            error_log("Present count error: " . $e->getMessage());
            return 0;
        }
    }

    function todaysAttendance($id) {
        try {
            global $pdo;
            // Use MySQL CURDATE() for consistency
            $stmt = $pdo->prepare("SELECT studentId FROM attendance WHERE attendance_date = CURDATE() AND studentId = ?");
            $stmt->execute([$id]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) > 0) {
                return "Present";
            } else {
                return "Not punched in";
            }
        } catch (PDOException $e) {
            error_log("Todays attendance error: " . $e->getMessage());
            return "Error";
        }
    }
?>
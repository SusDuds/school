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
            echo $e->getMessage();
        }
    }

    function programNum() {
        try {
            global $pdo;
            $dept = $pdo->query("Select distinct program from students");
            $rows = $dept->fetchAll(PDO::FETCH_ASSOC);
            return count($rows);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function pendingNum() {
        try {
            global $pdo;
            // FIXED: Column name is recordId, not courseId
            $dept = $pdo->query("Select recordId from courses where status='Pending'");
            $rows = $dept->fetchAll(PDO::FETCH_ASSOC);
            return count($rows);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function present() {
        try {
            global $pdo;
            $date = date("Y-m-d");
            $pre = $pdo->query("Select studentId from attendance where attendance_date='{$date}';");
            $rows = $pre->fetchAll(PDO::FETCH_ASSOC);
            return count($rows);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function todaysAttendance($id) {
        try {
            global $pdo;
            $date = date("Y-m-d");
            $pre = $pdo->query("Select studentId from attendance where attendance_date='{$date}' and studentId=$id;");
            $rows = $pre->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) > 0) {
                return "Present";
            } else {
                return "Not punched in";
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
?>
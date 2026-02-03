<?php
    include "db.php";
    include "session.php";

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        echo viewAttendance();
    }

    function viewAttendance() {
        global $pdo;
        if (isset($_REQUEST['date'])) {
            $date = $_REQUEST['date'];
            // Validate date format
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                return json_encode(['error' => 'Invalid date format']);
            }
            
            try {
                // Use prepared statement with JOIN for better performance
                $stmt = $pdo->prepare("
                    SELECT s.studentId, s.fullname, s.program, s.role 
                    FROM attendance a 
                    JOIN students s ON a.studentId = s.studentId 
                    WHERE a.attendance_date = ?
                ");
                $stmt->execute([$date]);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                $allAttendance = [];
                if (count($result) > 0) {
                    foreach ($result as $std) {
                        $allAttendance[] = [
                            'id' => htmlspecialchars($std['studentId']),
                            'name' => htmlspecialchars($std['fullname']),
                            'department' => htmlspecialchars($std['program']),
                            'role' => htmlspecialchars($std['role']),
                            'date' => htmlspecialchars($date)
                        ];
                    }
                    return json_encode($allAttendance);
                } else {
                    return json_encode([[
                        'id' => 'NO',
                        'name' => 'Records',
                        'department' => 'Found',
                        'role' => 'For Date',
                        'date' => htmlspecialchars($date)
                    ]]);
                }
            } catch (PDOException $e) {
                return json_encode(['error' => $e->getMessage()]);
            }
        }
        return json_encode([]);
    }
?>
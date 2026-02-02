<?php
    include "db.php";

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");

    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        echo viewAttendance();
    }

    function viewAttendance() {
        global $pdo;
        if (isset($_REQUEST['date'])) {
            $date = $_REQUEST['date'];
            try {
                $stmt = $pdo->prepare("Select studentId from attendance where attendance_date=?");
                $stmt->execute([$date]);    
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $allAttendance = [];

                if (count($result) > 0) {
                    foreach ($result as $value) {
                        foreach ($value as $id) {
                            // Exact nested query logic from sample
                            $fetch = $pdo->query("Select studentId, fullname, program, role from students where studentId=$id");
                            $info = $fetch->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($info as $std) {
                                $allAttendance[] = [
                                    'id' => $std['studentId'],
                                    'name' => $std['fullname'],
                                    'department' => $std['program'],
                                    'role' => $std['role'],
                                    'date' => $date
                                ];    
                            }
                        }    
                    }
                    return json_encode($allAttendance);
                } else {
                    return json_encode($allAttendance[] = [
                        'id' => 'NO',
                        'name' => 'Records',
                        'department' => 'Found',
                        'role' => 'For Date',
                        'date' => 'Date'
                    ]);
                }
            } catch (PDOException $e) { }
        }
    }
?>
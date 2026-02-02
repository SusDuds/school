<?php
    include "db.php";
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");

    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        echo viewCourse();
    }
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        addCourse();
    }
    if ($_SERVER['REQUEST_METHOD'] == "PUT") {
        editCourse();
    }

    function addCourse() {
        global $pdo;
        $data = json_decode(file_get_contents("php://input"), true);
        
        try {
            $stmt = $pdo->prepare("Insert into courses(studentId, fullname, grade, completion_date, course_name) values (?,?,?,?,?);");
            $stmt->execute([$data['studentId'], $data['name'], $data['grade'], $data['date'], $data['course_name']]);    
            echo viewCourse();
        } catch (PDOException $e) {
            http_response_code(500);
            echo $e->getMessage();
        }
    }

    function viewCourse() {
        global $pdo;
        $stmt = $pdo->query("Select * from courses ORDER BY recordId DESC");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $allCourses = [];
        
        if (isset($result)) {
            foreach ($result as $course) {
                $allCourses[] = [
                    'Id' => $course['recordId'], // FIXED: Matches DB column name
                    'name' => $course['fullname'],
                    'leave_date' => $course['completion_date'],
                    'leave_type' => $course['course_name'],
                    'status' => $course['status'],
                    'reason' => $course['grade'], 
                ];
            }
            return json_encode($allCourses);
        }
    }

    function editCourse() {
        global $pdo;
        if (isset($_REQUEST['recordId'])) {
            try {
                $stmt = $pdo->prepare("UPDATE courses SET status = ? WHERE recordId = ?");
                $stmt->execute([$_REQUEST['status'], $_REQUEST['recordId']]);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
?>
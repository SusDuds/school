<?php
    error_reporting(0);
    ini_set('display_errors', 0);
    header("Content-Type: application/json; charset=UTF-8");

    require_once "session.php";
    require_once "db.php";

    function sendResponse($success, $message = '') {
        echo json_encode(['success' => $success, 'message' => $message]);
        exit;
    }

    function checkCSRF() {
        $headers = getallheaders();
        $token = $headers['X-CSRF-Token'] ?? '';
        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            sendResponse(false, 'Security Token Mismatch (Refresh Page)');
        }
    }

    $method = $_SERVER['REQUEST_METHOD'];

    try {
        if ($method === "GET") {
            $stmt = $pdo->query("SELECT * FROM courses ORDER BY recordId DESC");
            $courses = $stmt->fetchAll();
            $output = [];
            foreach($courses as $c) {
                $output[] = [
                    'Id' => $c['recordId'],
                    'name' => $c['fullname'],
                    'leave_type' => $c['course_name'],
                    'leave_date' => $c['completion_date'],
                    'reason' => $c['grade'],
                    'status' => $c['status']
                ];
            }
            echo json_encode($output);
            exit;
        }

        elseif ($method === "POST") {
            checkCSRF();
            $data = json_decode(file_get_contents("php://input"), true);
            
            $stmt = $pdo->prepare("INSERT INTO courses (studentId, fullname, grade, completion_date, course_name, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
            $stmt->execute([
                $_SESSION['studentId'] ?? 0, 
                $_SESSION['name'] ?? 'Unknown', 
                $data['grade'], 
                $data['date'], 
                $data['course_name']
            ]);

            sendResponse(true, 'Course submitted');
        }

        elseif ($method === "PUT") {
            checkCSRF();
            $data = json_decode(file_get_contents("php://input"), true);
            $id = $data['recordId'] ?? null;
            $status = $data['status'] ?? null;

            if (!$id) sendResponse(false, "ID required");

            $stmt = $pdo->prepare("UPDATE courses SET status = ? WHERE recordId = ?");
            $stmt->execute([$status, $id]);
            
            sendResponse(true, "Status updated");
        }
    } catch (Exception $e) {
        sendResponse(false, $e->getMessage());
    }
?>
<?php
    // 1. SILENCE HTML ERRORS
    error_reporting(0);
    ini_set('display_errors', 0);

    // 2. ALWAYS SEND JSON HEADER
    header("Content-Type: application/json; charset=UTF-8");

    require_once "session.php";
    require_once "db.php";

    // Helper to send JSON and stop
    function sendResponse($success, $message = '', $data = []) {
        echo json_encode(['success' => $success, 'message' => $message, 'data' => $data]);
        exit;
    }

    // CSRF Check
    function checkCSRF() {
        $headers = getallheaders();
        // Check for header OR standard POST field
        $token = $headers['X-CSRF-Token'] ?? ($_POST['csrf_token'] ?? '');
        
        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            // Return JSON error instead of standard 403 HTML page
            http_response_code(200); // Send 200 OK but with success: false to handle in JS gracefully
            sendResponse(false, 'Security Token Mismatch. Please refresh the page.');
        }
    }

    $method = $_SERVER['REQUEST_METHOD'];

    try {
        // --- SEARCH (GET) ---
        if ($method === "GET") {
            $search = $_GET['search'] ?? '';
            $program = $_GET['program'] ?? '';
            
            $sql = "SELECT studentId AS ID, fullname AS name, email, program AS dept, role FROM students WHERE role='student'";
            $params = [];

            if (!empty($search)) {
                $sql .= " AND fullname LIKE ?";
                $params[] = "%$search%";
            }
            if (!empty($program)) {
                $sql .= " AND program LIKE ?";
                $params[] = "%$program%";
            }
            
            $sql .= " ORDER BY studentId DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            echo json_encode($stmt->fetchAll());
            exit;
        }

        // --- ADD STUDENT (POST) ---
        elseif ($method === "POST") {
            checkCSRF();
            $data = json_decode(file_get_contents("php://input"), true);
            
            if (empty($data['name']) || empty($data['email'])) {
                sendResponse(false, "Name and Email are required");
            }

            // Check duplicate email
            $check = $pdo->prepare("SELECT studentId FROM students WHERE email = ?");
            $check->execute([$data['email']]);
            if($check->rowCount() > 0) {
                sendResponse(false, "Email already exists");
            }

            $pass = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO students (fullname, email, program, password, role) VALUES (?, ?, ?, ?, 'student')");
            $stmt->execute([$data['name'], $data['email'], $data['program'], $pass]);
            
            sendResponse(true, "Student added successfully");
        }

        // --- EDIT STUDENT (PUT) ---
        elseif ($method === "PUT") {
            checkCSRF();
            $id = $_GET['studentId'] ?? 0;
            $data = json_decode(file_get_contents("php://input"), true);
            
            if(!$id) sendResponse(false, "Invalid ID");

            $stmt = $pdo->prepare("UPDATE students SET fullname=?, email=?, program=? WHERE studentId=?");
            $stmt->execute([$data['name'], $data['email'], $data['program'], $id]);
            
            sendResponse(true, "Student updated");
        }

        // --- DELETE STUDENT (DELETE) ---
        elseif ($method === "DELETE") {
            checkCSRF();
            $id = $_GET['id'] ?? 0;
            
            if(!$id) sendResponse(false, "Invalid ID");

            $stmt = $pdo->prepare("DELETE FROM students WHERE studentId=?");
            $stmt->execute([$id]);
            
            sendResponse(true, "Student removed");
        }

    } catch (Exception $e) {
        sendResponse(false, "Server Error: " . $e->getMessage());
    }
?>
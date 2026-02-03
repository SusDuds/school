<?php
    include "db.php";
    include "session.php";
    
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header('Content-Type: application/json');

    // CSRF Validation
    function validateCSRF($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    // Get CSRF from header or POST data
    $headers = getallheaders();
    $csrfToken = $headers['X-CSRF-Token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['csrf_token'] ?? '';
    
    // Also check JSON body for CSRF
    $rawInput = file_get_contents("php://input");
    $jsonData = json_decode($rawInput, true);
    if (empty($csrfToken) && isset($jsonData['csrf_token'])) {
        $csrfToken = $jsonData['csrf_token'];
    }

    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        echo viewCourse();
        exit;
    }

    // Validate CSRF for state-changing operations
    if (!validateCSRF($csrfToken)) {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid CSRF token']);
        exit;
    }

    // Handle based on presence of parameters
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if it's an update (has recordId and status)
        if (isset($_REQUEST['recordId']) && isset($_REQUEST['status'])) {
            editCourse();
        } else {
            addCourse();
        }
    }

    function addCourse() {
        global $pdo;
        
        // Get data from JSON or POST
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true);
        
        if (empty($data)) {
            $data = $_POST;
        }
        
        $studentId = intval($data['studentId'] ?? 0);
        $name = htmlspecialchars(trim($data['name'] ?? ''));
        $grade = htmlspecialchars(trim($data['grade'] ?? ''));
        $date = $data['date'] ?? '';
        $course_name = htmlspecialchars(trim($data['course_name'] ?? ''));
        
        // Validation
        if (empty($studentId) || empty($name) || empty($course_name) || empty($date)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }
        
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid date format']);
            return;
        }
        
        try {
            $stmt = $pdo->prepare("INSERT INTO courses(studentId, fullname, grade, completion_date, course_name) VALUES (?,?,?,?,?)");
            $stmt->execute([$studentId, $name, $grade, $date, $course_name]);    
            echo json_encode(['success' => true, 'message' => 'Course added successfully']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    function viewCourse() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM courses ORDER BY recordId DESC");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $allCourses = [];
        
        foreach ($result as $course) {
            $allCourses[] = [
                'Id' => htmlspecialchars($course['recordId']),
                'name' => htmlspecialchars($course['fullname']),
                'leave_date' => htmlspecialchars($course['completion_date']),
                'leave_type' => htmlspecialchars($course['course_name']),
                'status' => htmlspecialchars($course['status']),
                'reason' => htmlspecialchars($course['grade']), 
            ];
        }
        return json_encode($allCourses);
    }

    function editCourse() {
        global $pdo;
        $recordId = intval($_REQUEST['recordId']);
        $status = htmlspecialchars($_REQUEST['status']);
        
        $allowedStatuses = ['Pending', 'Verified', 'Rejected'];
        if (!in_array($status, $allowedStatuses)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid status']);
            return;
        }
        
        try {
            $stmt = $pdo->prepare("UPDATE courses SET status = ? WHERE recordId = ?");
            $stmt->execute([$status, $recordId]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
?>
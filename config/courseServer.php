<?php
    include "db.php";
    include "session.php";
    
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header('Content-Type: application/json');

    function validateCSRF($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    $headers = getallheaders();
    $csrfToken = $headers['X-CSRF-Token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['csrf_token'] ?? '';
    $rawInput = file_get_contents("php://input");
    $jsonData = json_decode($rawInput, true);
    if (empty($csrfToken) && isset($jsonData['csrf_token'])) {
        $csrfToken = $jsonData['csrf_token'];
    }

    $method = $_SERVER['REQUEST_METHOD'];
    $action = $_REQUEST['action'] ?? '';

    if ($method == "GET") {
        if (isset($_GET['studentId'])) {
            echo getStudentCourses($_GET['studentId']);
        } else {
            echo viewCourse();
        }
        exit;
    }

    if (!validateCSRF($csrfToken)) {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid CSRF token']);
        exit;
    }

    if ($method === 'POST') {
        if ($action === 'updateDraft') {
            updateDraft();
        } else if ($action === 'submitToAdmin') {
            submitToAdmin();
        } else if ($action === 'deleteDraft') {
            deleteDraft();
        } else if (isset($_REQUEST['recordId']) && isset($_REQUEST['status'])) {
            editCourse();
        } else {
            addCourse();
        }
    }

    function getStudentCourses($studentId) {
        global $pdo;
        try {
            $stmt = $pdo->prepare("
                SELECT * FROM courses 
                WHERE studentId = ? 
                ORDER BY FIELD(status, 'Draft', 'Pending', 'Rejected', 'Verified'), recordId DESC
            ");
            $stmt->execute([$studentId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $courses = [];
            foreach ($result as $course) {
                $courses[] = [
                    'Id' => $course['recordId'],
                    'name' => $course['fullname'],
                    'leave_date' => $course['completion_date'],
                    'leave_type' => $course['course_name'],
                    'status' => $course['status'],
                    'reason' => $course['grade'],
                ];
            }
            return json_encode($courses);
        } catch (PDOException $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    function addCourse() {
        global $pdo;
        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data)) $data = $_POST;
        
        try {
            // NEW: Save as Draft initially
            $stmt = $pdo->prepare("
                INSERT INTO courses(studentId, fullname, grade, completion_date, course_name, status) 
                VALUES (?,?,?,?,?,'Draft')
            ");
            $stmt->execute([
                $data['studentId'], 
                $data['name'], 
                $data['grade'], 
                $data['date'], 
                $data['course_name']
            ]);    
            echo json_encode(['success' => true, 'message' => 'Saved as Draft']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // NEW: Update draft (student can edit freely)
    function updateDraft() {
        global $pdo;
        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data)) $data = $_POST;
        
        $recordId = $data['recordId'] ?? 0;
        $studentId = $data['studentId'] ?? 0;
        
        try {
            // Only allow update if status is Draft
            $stmt = $pdo->prepare("
                UPDATE courses 
                SET course_name = ?, grade = ?, completion_date = ? 
                WHERE recordId = ? AND studentId = ? AND status = 'Draft'
            ");
            $stmt->execute([
                $data['course_name'],
                $data['grade'],
                $data['date'],
                $recordId,
                $studentId
            ]);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(403);
                echo json_encode(['error' => 'Can only edit draft records']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // NEW: Submit to admin (change from Draft to Pending)
    function submitToAdmin() {
        global $pdo;
        $recordId = $_REQUEST['recordId'] ?? 0;
        $studentId = $_REQUEST['studentId'] ?? 0;
        
        try {
            $stmt = $pdo->prepare("
                UPDATE courses 
                SET status = 'Pending' 
                WHERE recordId = ? AND studentId = ? AND status = 'Draft'
            ");
            $stmt->execute([$recordId, $studentId]);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Submitted to admin for verification']);
            } else {
                http_response_code(403);
                echo json_encode(['error' => 'Can only submit draft records']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // NEW: Delete draft
    function deleteDraft() {
        global $pdo;
        $recordId = $_REQUEST['recordId'] ?? 0;
        $studentId = $_REQUEST['studentId'] ?? 0;
        
        try {
            $stmt = $pdo->prepare("
                DELETE FROM courses 
                WHERE recordId = ? AND studentId = ? AND status = 'Draft'
            ");
            $stmt->execute([$recordId, $studentId]);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(403);
                echo json_encode(['error' => 'Can only delete draft records']);
            }
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
                'Id' => $course['recordId'],
                'name' => $course['fullname'],
                'leave_date' => $course['completion_date'],
                'leave_type' => $course['course_name'],
                'status' => $course['status'],
                'reason' => $course['grade'], 
            ];
        }
        return json_encode($allCourses);
    }

    function editCourse() {
        global $pdo;
        if (isset($_REQUEST['recordId']) && isset($_REQUEST['status'])) {
            try {
                $stmt = $pdo->prepare("UPDATE courses SET status = ? WHERE recordId = ?");
                $stmt->execute([$_REQUEST['status'], $_REQUEST['recordId']]);
                echo json_encode(['success' => true]);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(['error' => $e->getMessage()]);
            }
        }
    }
?>
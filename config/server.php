<?php
    include "db.php";
    include "session.php";

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header('Content-Type: application/json');

    // CSRF Protection
    function validateCSRF($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    // Get CSRF from header or POST data
    $headers = getallheaders();
    $csrfToken = $headers['X-CSRF-Token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['csrf_token'] ?? $_REQUEST['csrf_token'] ?? '';

    if($_SERVER['REQUEST_METHOD']==="GET"){
        echo infoStudent();
        exit;
    }

    // Validate CSRF for all state-changing operations
    if (!validateCSRF($csrfToken)) {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid CSRF token']);
        exit;
    }

    // Handle all actions via POST or action parameter
    $method = $_SERVER['REQUEST_METHOD'];
    $action = $_REQUEST['action'] ?? '';

    if ($method === 'POST' || $method === 'PUT' || $method === 'DELETE') {
        if ($action === 'create') {
            createStudent();
        } else if ($action === 'update' || $method === 'PUT') {
            editStudent();
        } else if ($action === 'delete' || $method === 'DELETE') {
            deleteStudent();
        } else {
            // Default to update if ID present
            if (isset($_REQUEST['studentId'])) {
                editStudent();
            } else if (isset($_REQUEST['id'])) {
                deleteStudent();
            } else {
                createStudent();
            }
        }
    }

    function infoStudent(){
        try{
            global $pdo;
            $stmt = $pdo->query("Select * from students where role='student'");
            $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $allStudents = [];
            if(count($rows)>0){
                foreach ($rows as $std) {
                    $allStudents[] = ['ID'=>$std['studentId'],
                                      'name'=>htmlspecialchars($std['fullname']),
                                      'email'=>htmlspecialchars($std['email']),
                                      'dept'=>htmlspecialchars($std['program']), 
                                      'role'=>htmlspecialchars($std['role']),
                                      'joined_at'=>htmlspecialchars($std['joined_at']),
                                     ];
                }
                return json_encode($allStudents);
            }
            return json_encode([]); 
        }catch (PDOException $e){
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    function createStudent() {
        global $pdo;
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true);
        
        // Also check POST data
        if (empty($data)) {
            $data = $_POST;
        }
        
        $name = htmlspecialchars(trim($data['name'] ?? ''));
        $email = filter_var(trim($data['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $program = htmlspecialchars(trim($data['program'] ?? ''));
        $password = trim($data['password'] ?? '');
        
        if (strlen($password) < 6) {
            http_response_code(400);
            echo json_encode(['error' => 'Password must be at least 6 characters']);
            return;
        }
        
        $hashedPass = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO students (fullname, email, program, password, role) VALUES (?, ?, ?, ?, 'student')");
            $stmt->execute([$name, $email, $program, $hashedPass]);
            echo infoStudent();
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Email already registered']);
        }
    }

    function editStudent(){
        if(isset($_REQUEST['studentId'])){
            $id = intval($_REQUEST['studentId']);
            $rawData = file_get_contents("php://input");
            $data = json_decode($rawData,true);
            
            // Also check POST data
            if (empty($data)) {
                $data = $_POST;
            }
            
            $name=htmlspecialchars(trim($data['name'] ?? ''));
            $email=filter_var(trim($data['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            $program=htmlspecialchars(trim($data['program'] ?? ''));
            
            try{
                global $pdo;
                $stmt=$pdo->prepare("Update students set fullname=?, email=?, program=? where studentId=?");
                $stmt->execute([$name, $email, $program, $id]);
                echo infoStudent();
            }catch(PDOException $e){
                http_response_code(500);
                echo json_encode(['error' => $e->getMessage()]);
            }
        }
    }

    function deleteStudent(){
        global $pdo;
        if(isset($_REQUEST['id'])){
            $id = intval($_REQUEST['id']);
            try{
                $stmt = $pdo->prepare("Delete from students where studentId=?");
                $stmt->execute([$id]);
                echo infoStudent();
            }catch(PDOException $e){
                http_response_code(500);
                echo json_encode(['error' => $e->getMessage()]);
            }
        }
    }
?>
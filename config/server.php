<?php
    include "db.php";

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    
    if($_SERVER['REQUEST_METHOD']==="GET"){
        echo infoStudent();
    }

    if($_SERVER['REQUEST_METHOD']==="PUT"){
        editStudent();
    }

    if($_SERVER['REQUEST_METHOD']==="DELETE"){
        deleteStudent();
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
                                    'name'=>$std['fullname'],
                                    'email'=>$std['email'],
                                    'dept'=>$std['program'], 
                                    'role'=>$std['role'],
                                    'joined_at'=>$std['joined_at'],
                                    ];
                }
                return json_encode($allStudents);
            }
            return json_encode([]); 
        }catch (PDOException $e){
            echo $e->getMessage();
        }
    }

    function editStudent(){
        if(isset($_REQUEST['studentId'])){
            $id = $_REQUEST['studentId'];
            $rawData = file_get_contents("php://input");
            $data = json_decode($rawData,true);
            $name=$data['name'];
            $email=$data['email'];
            $program=$data['program'];
            $role=$data['role'];
            try{
                global $pdo;
                $stmt=$pdo->prepare("Update students set fullname=?,email=?,program=?,role=? where studentId=?");
                $stmt->execute([$name,$email,$program,$role,$id]);
                echo infoStudent();
            }catch(PDOException $e){
                echo $e->getMessage();
            }
        }
    }

    function deleteStudent(){
        global $pdo;
        if(isset($_REQUEST['id'])){
            $id = $_REQUEST['id'];
            try{
                $stmt = $pdo->prepare("Delete from students where studentId=?");
                $stmt->execute([$id]);
                echo infoStudent();
            }catch(PDOException $e){
                echo $e->getMessage();
            }
        }
    }
?>
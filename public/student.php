<?php
    include "../config/session.php";
    if ($_SESSION['role'] != 'admin') {
        header("location:login.php");
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Students</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/home.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/student.css">
</head>
<body>
    <section class="dashboard">
        <?php include "../includes/header.php" ?>
        <main class="main-right">
            <p class="home-welcome">Student Directory</p>
            
            <div class="search-section">
                <div class="search-box">
                    <label>Search by Name</label>
                    <input type="text" id="searchName" placeholder="Enter student name...">
                </div>
                <div class="search-box">
                    <label>Filter by Program</label>
                    <select id="searchProgram">
                        <option value="">All Programs</option>
                        <option value="Science">Science</option>
                        <option value="Management">Management</option>
                    </select>
                </div>
                <button class="search-btn" onclick="filterStudents()">Search</button>
                <button class="clear-btn" onclick="clearFilter()">Clear</button>
            </div>
            
            <article class="employee-table">
                <table class="table" id="student_table">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Program</th>
                        <th>Action</th>
                    </tr>        
                </table>
            </article>
        </main>
    </section>
    
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Edit Student</h3>
            <form id="editForm">
                <input type="hidden" id="edit-id">
                <label>Name</label>
                <input type="text" id="edit-name" required>
                <label>Email</label>
                <input type="email" id="edit-email" required>
                <label>Program</label>
                <select id="edit-program" required>
                    <option>Grade 11 - Science</option>
                    <option>Grade 11 - Management</option>
                    <option>Grade 12 - Science</option>
                </select>
                <button type="button" class="save-btn" onclick="saveStudent()">Save Changes</button>
            </form>
        </div>
    </div>
    
    <script src="../assets/js/student.js"></script>
</body>
</html>
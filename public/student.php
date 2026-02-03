<?php
    include "../config/session.php";
    if ($_SESSION['role'] != 'admin') {
        header("location:login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Students</title>
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
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
                    <label>Search by Name (Live)</label>
                    <input type="text" id="searchName" placeholder="Type to search students..." onkeyup="filterStudents()">
                </div>
                <div class="search-box">
                    <label>Filter by Program</label>
                    <select id="searchProgram" onchange="filterStudents()">
                        <option value="">All Programs</option>
                        <option value="Science">Science</option>
                        <option value="Management">Management</option>
                    </select>
                </div>
                <button class="add-btn" onclick="openAddModal()">+ Add Student</button>
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
    
    <!-- Add Student Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddModal()">&times;</span>
            <h3>Add New Student</h3>
            <form id="addForm">
                <label>Name</label>
                <input type="text" id="add-name" required>
                
                <label>Email</label>
                <input type="email" id="add-email" required>
                
                <label>Program</label>
                <select id="add-program" required>
                    <option value="Grade 11 - Science">Grade 11 - Science</option>
                    <option value="Grade 11 - Management">Grade 11 - Management</option>
                    <option value="Grade 12 - Science">Grade 12 - Science</option>
                </select>
                
                <label>Password</label>
                <input type="password" id="add-password" minlength="6" required placeholder="Min 6 characters">
                
                <button type="button" class="save-btn" onclick="saveNewStudent()">Add Student</button>
            </form>
        </div>
    </div>
    
    <!-- Edit Modal -->
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
                    <option value="Grade 11 - Science">Grade 11 - Science</option>
                    <option value="Grade 11 - Management">Grade 11 - Management</option>
                    <option value="Grade 12 - Science">Grade 12 - Science</option>
                </select>
                <button type="button" class="save-btn" onclick="saveStudent()">Save Changes</button>
            </form>
        </div>
    </div>
    
    <script src="../assets/js/student.js"></script>
</body>
</html>
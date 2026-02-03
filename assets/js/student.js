const API_URL = "../config/server.php";
const table = document.getElementById("student_table");
const editModal = document.getElementById("editModal");
const addModal = document.getElementById("addModal");

let allStudents = [];

// Get CSRF Token from meta tag
function getCSRFToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.content : '';
}

document.addEventListener("DOMContentLoaded", loadStudents);

async function loadStudents() {
    try {
        const response = await fetch(API_URL);
        const data = await response.json();
        if (data.error) {
            console.error(data.error);
            return;
        }
        allStudents = data;
        renderTable(data);
    } catch (error) {
        console.error(error);
    }
}

function renderTable(data) {
    let html = `<tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Program</th>
        <th>Action</th>
    </tr>`;
    
    if (data.length === 0) {
        html += `<tr><td colspan="5" style="text-align:center;padding:40px;">No students found</td></tr>`;
    } else {
        data.forEach(std => {
            html += `<tr>
                <td>${escapeHtml(std.ID)}</td>
                <td>${escapeHtml(std.name)}</td>
                <td>${escapeHtml(std.email)}</td>
                <td>${escapeHtml(std.dept)}</td>
                <td>
                    <button class="edit-btn" onclick='openEditModal(${std.ID}, "${escapeHtml(std.name)}", "${escapeHtml(std.email)}", "${escapeHtml(std.dept)}")'>Edit</button>
                    <button class="delete-btn" onclick="deleteStudent(${std.ID})">Remove</button>
                </td>
            </tr>`;
        });
    }
    table.innerHTML = html;
}

function filterStudents() {
    const nameSearch = document.getElementById("searchName").value.toLowerCase();
    const programFilter = document.getElementById("searchProgram").value;
    
    let filtered = allStudents;
    
    if (nameSearch) {
        filtered = filtered.filter(s => s.name.toLowerCase().includes(nameSearch));
    }
    
    if (programFilter) {
        filtered = filtered.filter(s => s.dept.includes(programFilter));
    }
    
    renderTable(filtered);
}

function clearFilter() {
    document.getElementById("searchName").value = "";
    document.getElementById("searchProgram").value = "";
    renderTable(allStudents);
}

function escapeHtml(text) {
    if (!text) return '';
    return text
        .toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Add Student Modal
function openAddModal() {
    document.getElementById("add-name").value = "";
    document.getElementById("add-email").value = "";
    document.getElementById("add-program").value = "Grade 11 - Science";
    document.getElementById("add-password").value = "";
    addModal.style.display = "block";
}

function closeAddModal() {
    addModal.style.display = "none";
}

async function saveNewStudent() {
    const name = document.getElementById("add-name").value.trim();
    const email = document.getElementById("add-email").value.trim();
    const program = document.getElementById("add-program").value;
    const password = document.getElementById("add-password").value;
    
    if (!name || !email || !password) {
        alert("Please fill all fields");
        return;
    }
    
    if (password.length < 6) {
        alert("Password must be at least 6 characters");
        return;
    }
    
    const data = { 
        name, 
        email, 
        program, 
        password,
        csrf_token: getCSRFToken()  // Include CSRF in body
    };
    
    try {
        // Use POST instead of PUT/DELETE
        const response = await fetch(`${API_URL}?action=create`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-Token': getCSRFToken()  // Also in header
            },
            body: JSON.stringify(data)
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.error || 'Server error');
        }
        
        const students = await response.json();
        if (students.error) {
            alert(students.error);
            return;
        }
        allStudents = students;
        renderTable(students);
        closeAddModal();
        alert("Student added successfully!");
    } catch (error) {
        alert("Error adding student: " + error.message);
    }
}

// Edit Modal
function openEditModal(id, name, email, program) {
    document.getElementById("edit-id").value = id;
    document.getElementById("edit-name").value = name;
    document.getElementById("edit-email").value = email;
    document.getElementById("edit-program").value = program;
    editModal.style.display = "block";
}

function closeModal() {
    editModal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == editModal) closeModal();
    if (event.target == addModal) closeAddModal();
}

async function saveStudent() {
    const id = document.getElementById("edit-id").value;
    const data = {
        name: document.getElementById("edit-name").value,
        email: document.getElementById("edit-email").value,
        program: document.getElementById("edit-program").value,
        csrf_token: getCSRFToken()
    };
    
    try {
        // Use POST with action=update instead of PUT
        const response = await fetch(`${API_URL}?action=update&studentId=${id}`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-Token': getCSRFToken()
            },
            body: JSON.stringify(data)
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.error || 'Server error');
        }
        
        const students = await response.json();
        if (students.error) {
            alert(students.error);
            return;
        }
        allStudents = students;
        renderTable(students);
        closeModal();
    } catch (error) {
        alert("Error saving changes: " + error.message);
    }
}

async function deleteStudent(id) {
    if (!confirm("Are you sure you want to remove this student?")) return;
    
    try {
        // Use POST with action=delete instead of DELETE
        const response = await fetch(`${API_URL}?action=delete&id=${id}`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-Token': getCSRFToken()
            },
            body: JSON.stringify({ csrf_token: getCSRFToken() })
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.error || 'Server error');
        }
        
        const students = await response.json();
        if (students.error) {
            alert(students.error);
            return;
        }
        allStudents = students;
        renderTable(students);
    } catch (error) {
        alert("Error deleting student: " + error.message);
    }
}
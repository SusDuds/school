const API_URL = "../config/server.php";
const table = document.getElementById("student_table");
const modal = document.getElementById("editModal");

let allStudents = [];

document.addEventListener("DOMContentLoaded", loadStudents);

async function loadStudents() {
    try {
        const response = await fetch(API_URL);
        const data = await response.json();
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
                <td>${std.ID}</td>
                <td>${std.name}</td>
                <td>${std.email}</td>
                <td>${std.dept}</td>
                <td>
                    <button class="edit-btn" onclick='openEditModal(${std.ID}, "${std.name}", "${std.email}", "${std.dept}")'>Edit</button>
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

function openEditModal(id, name, email, program) {
    document.getElementById("edit-id").value = id;
    document.getElementById("edit-name").value = name;
    document.getElementById("edit-email").value = email;
    document.getElementById("edit-program").value = program;
    modal.style.display = "block";
}

function closeModal() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        closeModal();
    }
}

async function saveStudent() {
    const id = document.getElementById("edit-id").value;
    const data = {
        name: document.getElementById("edit-name").value,
        email: document.getElementById("edit-email").value,
        program: document.getElementById("edit-program").value
    };
    
    try {
        const response = await fetch(`${API_URL}?studentId=${id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const students = await response.json();
        allStudents = students;
        renderTable(students);
        closeModal();
    } catch (error) {
        alert("Error saving changes");
    }
}

async function deleteStudent(id) {
    if (!confirm("Are you sure you want to remove this student?")) return;
    
    try {
        const response = await fetch(`${API_URL}?id=${id}`, {
            method: 'DELETE'
        });
        
        const students = await response.json();
        allStudents = students;
        renderTable(students);
    } catch (error) {
        alert("Error deleting student");
    }
}
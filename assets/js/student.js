const API_URL = "../config/server.php";
let searchTimer;
let isEditMode = false;

// Safe Token Retrieval
const csrfMeta = document.querySelector('meta[name="csrf-token"]');
const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

document.addEventListener("DOMContentLoaded", () => {
    if(!csrfToken) console.warn("CSRF Token missing");
    loadStudents();
});

// Force global scope
window.debouncedSearch = function() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => loadStudents(), 300);
}

window.loadStudents = async function() {
    const name = document.getElementById("searchName").value || '';
    const prog = document.getElementById("searchProgram").value || '';
    
    try {
        const response = await fetch(`${API_URL}?search=${encodeURIComponent(name)}&program=${encodeURIComponent(prog)}`);
        const data = await response.json();
        renderTable(data);
    } catch (error) {
        console.error("Load Error:", error);
    }
}

function renderTable(data) {
    const tbody = document.querySelector("#student_table tbody");
    tbody.innerHTML = "";

    // Handle case where API returns error object instead of array
    if (data.error || !Array.isArray(data)) {
        console.error(data);
        return;
    }

    if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" style="text-align:center;padding:20px;">No students found</td></tr>`;
        return;
    }

    data.forEach(std => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${escapeHtml(std.ID)}</td>
            <td>${escapeHtml(std.name)}</td>
            <td>${escapeHtml(std.email)}</td>
            <td>${escapeHtml(std.dept)}</td>
            <td>
                <button class="edit-btn" onclick='openEditModal(${JSON.stringify(std)})'>Edit</button>
                <button class="delete-btn" onclick="deleteStudent(${std.ID})">Remove</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// Modal Functions
window.openAddModal = function() {
    isEditMode = false;
    document.getElementById("modalTitle").textContent = "Add New Student";
    document.getElementById("editForm").reset();
    document.getElementById("password-group").style.display = "block";
    document.getElementById("editModal").style.display = "block";
}

window.openEditModal = function(std) {
    isEditMode = true;
    document.getElementById("modalTitle").textContent = "Edit Student";
    document.getElementById("edit-id").value = std.ID;
    document.getElementById("edit-name").value = std.name;
    document.getElementById("edit-email").value = std.email;
    document.getElementById("edit-program").value = std.dept;
    document.getElementById("password-group").style.display = "none";
    document.getElementById("editModal").style.display = "block";
}

window.closeModal = function() {
    document.getElementById("editModal").style.display = "none";
}

// Save Function
window.saveStudent = async function() {
    const id = document.getElementById("edit-id").value;
    const name = document.getElementById("edit-name").value;
    const email = document.getElementById("edit-email").value;
    const program = document.getElementById("edit-program").value;
    const password = document.getElementById("edit-password").value;

    const method = isEditMode ? "PUT" : "POST";
    const url = isEditMode ? `${API_URL}?studentId=${id}` : API_URL;
    
    const payload = { name, email, program };
    if (!isEditMode) payload.password = password;

    try {
        const response = await fetch(url, {
            method: method,
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken 
            },
            body: JSON.stringify(payload)
        });
        
        // This line used to crash if response was HTML. Now server guarantees JSON.
        const res = await response.json();
        
        if (res.success) {
            closeModal();
            loadStudents();
        } else {
            alert(res.message || "Operation failed");
        }
    } catch (error) {
        console.error(error);
        alert("System Error: Check console");
    }
}

window.deleteStudent = async function(id) {
    if (!confirm("Are you sure?")) return;
    
    try {
        const response = await fetch(`${API_URL}?id=${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-Token': csrfToken }
        });
        
        const res = await response.json();
        if(res.success) {
            loadStudents();
        } else {
            alert(res.message);
        }
    } catch (error) {
        alert("Delete failed");
    }
}

function escapeHtml(text) {
    if (!text) return "";
    return text.toString().replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
}
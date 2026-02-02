const API_URL = "../config/server.php";
const table = document.getElementById("student_table");

document.addEventListener("DOMContentLoaded", async () => {
    await loadStudents();
});

async function loadStudents() {
    try {
        const response = await fetch(API_URL);
        if (response.ok) {
            const data = await response.json();
            let html = `<tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Program</th>
                            <th>Joined</th>
                            <th>Action</th>
                        </tr>`;
            
            data.forEach(std => {
                html += `<tr>
                    <td>${std.ID}</td>
                    <td>${std.name}</td>
                    <td>${std.email}</td>
                    <td>${std.dept}</td>
                    <td>${std.joined_at}</td>
                    <td><button id="delete-btn" onclick="deleteStudent('${std.ID}')">Remove</button></td>
                </tr>`;
            });
            table.innerHTML = html;
        }
    } catch (error) {
        console.error(error);
    }
}

async function deleteStudent(id) {
    if (confirm("Are you sure you want to expel this student?")) {
        try {
            // Using URL parameters for DELETE as per standard simple PHP APIs
            const response = await fetch(`${API_URL}?id=${id}`, {
                method: 'DELETE'
            });

            if (response.ok) {
                await loadStudents();
            } else {
                alert("Failed to delete.");
            }
        } catch (error) {
            alert("Connection error.");
        }
    }
}
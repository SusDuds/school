const API_URL = "../config/courseServer.php";
const table = document.getElementById("course_table");

document.addEventListener("DOMContentLoaded", async () => {
    await loadRecords();
});

async function loadRecords() {
    try {
        const response = await fetch(API_URL);
        if (response.ok) {
            const data = await response.json();
            let html = `<tr>
                            <th>Student</th>
                            <th>Course Name</th>
                            <th>Completion Date</th>
                            <th>Grade</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>`;

            data.forEach(row => {
                let actionBtn = "";
                // Logic to show buttons only if pending
                if (row.status === "Pending") {
                    actionBtn = `<button class="approve-button" onclick="updateStatus('${row.Id}', 'Verified')">Verify</button>
                                 <button class="reject-button" onclick="updateStatus('${row.Id}', 'Rejected')">Reject</button>`;
                } else {
                    // Uses IDs like #verified, #rejected for CSS styling
                    actionBtn = `<span class="status-text" id="${row.status.toLowerCase()}">${row.status}</span>`;
                }

                html += `<tr>
                    <td>${row.name}</td>
                    <td>${row.leave_type}</td>
                    <td>${row.leave_date}</td>
                    <td>${row.reason}</td>
                    <td><span class="status-text" id="${row.status.toLowerCase()}">${row.status}</span></td>
                    <td>${actionBtn}</td>
                </tr>`;
            });
            table.innerHTML = html;
        }
    } catch (error) {
        console.error(error);
    }
}

async function updateStatus(id, status) {
    try {
        const response = await fetch(`${API_URL}?recordId=${id}&status=${status}`, {
            method: 'PUT'
        });
        if (response.ok) {
            await loadRecords();
        }
    } catch (error) {
        alert("Update failed.");
    }
}
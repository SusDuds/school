const API_URL = "../config/attendanceServer.php";

async function searchDate() {
    let dateVal = document.getElementById("attendance-date").value;
    const table = document.getElementById("attendance_table");

    if(!dateVal) { 
        alert("Select a date"); 
        return; 
    }

    try {
        const response = await fetch(`${API_URL}?date=${encodeURIComponent(dateVal)}`);
        if (response.ok) {
            const data = await response.json();
            if (data.error) {
                console.error(data.error);
                return;
            }
            
            let html = `<tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Program</th>
                <th>Status</th>
            </tr>`;

            if (data.length > 0 && data[0].id !== "NO") {
                data.forEach(row => {
                    html += `<tr>
                        <td>${escapeHtml(row.id)}</td>
                        <td>${escapeHtml(row.name)}</td>
                        <td>${escapeHtml(row.department)}</td>
                        <td style="color: #2e7d32; font-weight: bold;">Present</td>
                    </tr>`;
                });
            } else {
                html += `<tr><td colspan="4" style="text-align:center;padding:40px;">No records found for this date</td></tr>`;
            }
            table.innerHTML = html;
        }
    } catch (error) {
        console.error(error);
        alert("Error loading attendance data");
    }
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

// Load today's attendance on page load
document.addEventListener("DOMContentLoaded", searchDate);
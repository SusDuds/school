const API_URL = "../config/attendanceServer.php";

document.addEventListener("DOMContentLoaded", searchDate);

async function searchDate() {
    let dateVal = document.getElementById("attendance-date").value;
    const table = document.getElementById("attendance_table");

    if(!dateVal) return;

    try {
        const response = await fetch(`${API_URL}?date=${dateVal}`);
        if (response.ok) {
            const data = await response.json();
            let html = `<tr><th>ID</th><th>Name</th><th>Program</th><th>Status</th></tr>`;

            if (data.length > 0) {
                data.forEach(row => {
                    html += `<tr>
                        <td>${row.id}</td>
                        <td>${row.name}</td>
                        <td>${row.department}</td>
                        <td style="color: #059669; font-weight: bold;">Present</td>
                    </tr>`;
                });
            } else {
                html += `<tr><td colspan="4" style="text-align:center; padding:20px;">No attendance records found for this date.</td></tr>`;
            }
            table.innerHTML = html;
        }
    } catch (error) {
        console.error(error);
    }
}
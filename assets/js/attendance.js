const API_URL = "../config/attendanceServer.php";

async function searchDate() {
    let dateVal = document.getElementById("attendance-date").value;
    const table = document.getElementById("attendance_table");

    if(!dateVal) { alert("Select a date"); return; }

    try {
        const response = await fetch(`${API_URL}?date=${dateVal}`);
        if (response.ok) {
            const data = await response.json();
            let html = `<tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Program</th>
                            <th>Status</th>
                        </tr>`;

            if (data.length > 0 && data[0].id !== "NO") {
                data.forEach(row => {
                    html += `<tr>
                        <td>${row.id}</td>
                        <td>${row.name}</td>
                        <td>${row.department}</td>
                        <td style="color: #2e7d32; font-weight: bold;">Present</td>
                    </tr>`;
                });
            } else {
                html += `<tr><td colspan="4">No records found.</td></tr>`;
            }
            table.innerHTML = html;
        }
    } catch (error) {
        console.error(error);
    }
}
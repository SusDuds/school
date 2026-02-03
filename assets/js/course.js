const API_URL = "../config/courseServer.php";
const table = document.getElementById("course_table");  // ADD THIS LINE

// Get CSRF Token from meta tag
function getCSRFToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.content : '';
}

document.addEventListener("DOMContentLoaded", async () => {
    await loadRecords();
});

async function loadRecords() {
    try {
        const response = await fetch(API_URL);
        if (response.ok) {
            const data = await response.json();
            if (data.error) {
                console.error(data.error);
                return;
            }
            let html = `<tr>
                <th>Student</th>
                <th>Course Name</th>
                <th>Completion Date</th>
                <th>Grade</th>
                <th>Status</th>
                <th>Action</th>
            </tr>`;

            if (!data || data.length === 0) {
                html += `<tr><td colspan="6" style="text-align:center;padding:40px;">No records found</td></tr>`;
            } else {
                data.forEach(row => {
                    let actionBtn = "";
                    if (row.status === "Pending") {
                        actionBtn = `<button class="approve-button" onclick="updateStatus('${escapeHtml(row.Id)}', 'Verified')">Verify</button>
                                     <button class="reject-button" onclick="updateStatus('${escapeHtml(row.Id)}', 'Rejected')">Reject</button>`;
                    } else {
                        actionBtn = `<span class="status-text" id="${row.status.toLowerCase()}">${escapeHtml(row.status)}</span>`;
                    }

                    html += `<tr>
                        <td>${escapeHtml(row.name)}</td>
                        <td>${escapeHtml(row.leave_type)}</td>
                        <td>${escapeHtml(row.leave_date)}</td>
                        <td>${escapeHtml(row.reason)}</td>
                        <td><span class="status-text" id="${row.status.toLowerCase()}">${escapeHtml(row.status)}</span></td>
                        <td>${actionBtn}</td>
                    </tr>`;
                });
            }
            table.innerHTML = html;
        }
    } catch (error) {
        console.error(error);
    }
}

async function updateStatus(id, status) {
    if (!confirm(`Are you sure you want to ${status.toLowerCase()} this record?`)) return;
    
    try {
        const response = await fetch(`${API_URL}?action=update&recordId=${encodeURIComponent(id)}&status=${encodeURIComponent(status)}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': getCSRFToken()
            },
            body: JSON.stringify({ 
                csrf_token: getCSRFToken(),
                recordId: id,
                status: status 
            })
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.error || 'Server error');
        }
        
        const result = await response.json();
        if (result.error) {
            alert(result.error);
            return;
        }
        await loadRecords();
    } catch (error) {
        alert("Update failed: " + error.message);
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
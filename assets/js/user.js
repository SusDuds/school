const COURSE_API = "../config/courseServer.php";
const ATT_API = "../config/attendanceServer.php";

// Safe Token Retrieval
const csrfMeta = document.querySelector('meta[name="csrf-token"]');
const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

window.addCourse = async function() {
    let courseName = document.getElementById("course-name").value;
    let date = document.getElementById("course-date").value;
    let grade = document.getElementById("course-grade").value;

    if(!csrfToken) { alert("Security Token Missing. Please refresh."); return; }

    if(courseName && date && grade){
        const payload = { course_name: courseName, date: date, grade: grade };

        try {
            const response = await fetch(COURSE_API, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrfToken 
                },
                body: JSON.stringify(payload)
            });
            
            const data = await response.json();
            if(data.success){
                alert(data.message);
                window.location.href = "userCourse.php"; // Refresh to clear form
            } else {
                alert("Error: " + (data.message || "Unknown error"));
            }
        } catch(error){
            console.error(error);
            alert("Network Error");
        }
    } else {
        alert("Please fill all fields");
    }
}

window.markAttendance = async function() {
    try {
        const response = await fetch(ATT_API, { 
            method: 'POST',
            headers: { 'X-CSRF-Token': csrfToken }
        });
        const data = await response.json();
        
        if (data.success) {
            document.getElementById("attendance-status").innerText = "Today's Attendance: Present";
            const btn = document.getElementById("checkin-btn");
            if(btn) btn.remove();
        }
    } catch (error) {
        alert("Failed to check in");
    }
}
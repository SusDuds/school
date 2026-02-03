const API_URL = "../config/courseServer.php";

// Get CSRF Token from meta tag
function getCSRFToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.content : '';
}

async function addCourse() {
    let courseName = document.getElementById("course-name").value.trim();
    let date = document.getElementById("course-date").value;
    let grade = document.getElementById("course-grade").value.trim();
    let studentId = document.getElementById("student-id").value;
    let name = document.getElementById("student-name").value;

    if(courseName === "" || date === "" || grade === "") {
        alert("Please fill all fields");
        return;
    }

    const payload = {
        studentId: studentId,
        name: name,
        course_name: courseName,
        date: date,
        grade: grade,
        csrf_token: getCSRFToken()
    };

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-Token': getCSRFToken()
            },
            body: JSON.stringify(payload)
        });

        const result = await response.json();
        
        if(!response.ok || result.error){
            throw new Error(result.error || 'Server error');
        }
        
        alert("Course record submitted successfully");
        window.location.href = "user.php";
    } catch(error){
        alert("Error: " + error.message);
    }
}
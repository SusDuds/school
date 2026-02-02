const API_URL = "../config/courseServer.php";

async function addCourse() {
    let courseName = document.getElementById("course-name").value;
    let date = document.getElementById("course-date").value;
    let grade = document.getElementById("course-grade").value;
    let studentId = document.getElementById("student-id").value;
    let name = document.getElementById("student-name").value;

    if(courseName != "" && date != "" && grade != ""){
        const payload = {
            studentId: studentId,
            name: name,
            course_name: courseName,
            date: date,
            grade: grade
        };

        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            if(response.ok){
                alert("Course record submitted successfully");
                window.location.href = "userCourse.php";
            } else {
                alert("Server Error");
            }
        } catch(error){
            alert("Network Error");
        }
    } else {
        alert("Please fill all fields");
    }
}
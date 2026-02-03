<?php
    include "../config/db.php";
    include "../config/session.php";
    include "../includes/functions.php";

    $id = $_SESSION['studentId'] ?? 0;
    if ($_SESSION['role'] != 'student') {
        header("location:login.php");
        exit;
    }
    
    $records = getStudentRecords($id);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Records</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/home.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/course.css">
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    <style>
        .my-records-section {
            background: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 16px;
            padding: 28px;
            margin-top: 24px;
        }
        .record-card {
            background: #fafafa;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            transition: all 0.2s;
        }
        .record-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .record-card.draft {
            border-left: 4px solid #6b7280;
            background: #f9fafb;
        }
        .record-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        .record-course {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
        }
        .record-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-draft { background: #f3f4f6; color: #6b7280; border: 1px solid #d1d5db; }
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-verified { background: #d1fae5; color: #059669; }
        .status-rejected { background: #fee2e2; color: #dc2626; }
        .record-details {
            color: #666;
            font-size: 14px;
            margin-bottom: 16px;
        }
        .record-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn-edit {
            background: #e0f2fe;
            color: #0369a1;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-delete {
            background: #fee2e2;
            color: #dc2626;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-submit {
            background: #059669;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-edit:hover { background: #0369a1; color: white; }
        .btn-delete:hover { background: #dc2626; color: white; }
        .btn-submit:hover { background: #047857; }
        .edit-form {
            display: none;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e0e0e0;
        }
        .edit-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }
        .edit-form button {
            margin-right: 10px;
        }
        .no-records {
            text-align: center;
            padding: 60px;
            color: #666;
        }
        .add-new-btn {
            background: #059669;
            color: white;
            border: none;
            padding: 14px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .add-new-btn:hover {
            background: #047857;
        }
        .draft-badge {
            display: inline-block;
            background: #6b7280;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            margin-left: 8px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <section class="dashboard">
        <?php include "../includes/userHeader.php" ?>
        <main class="main-right">
            <p class="home-welcome">My Course Records</p>
            
            <div class="my-records-section">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <h3 style="margin: 0;">My Records</h3>
                    <a href="userCourse.php"><button class="add-new-btn">+ Create New Record</button></a>
                </div>
                
                <div id="records-container">
                    <?php if (empty($records)): ?>
                        <div class="no-records">
                            <p>No records yet.</p>
                            <p style="font-size: 14px; margin-top: 10px;">Click "Create New Record" to add a course completion.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($records as $record): 
                            $statusClass = strtolower($record['status']);
                            $isDraft = $record['status'] == 'Draft';
                        ?>
                            <div class="record-card <?php echo $isDraft ? 'draft' : ''; ?>" id="record-<?php echo $record['recordId']; ?>">
                                <div class="record-header">
                                    <span class="record-course">
                                        <?php echo htmlspecialchars($record['course_name']); ?>
                                        <?php if ($isDraft): ?>
                                            <span class="draft-badge">DRAFT</span>
                                        <?php endif; ?>
                                    </span>
                                    <span class="record-status status-<?php echo $statusClass; ?>">
                                        <?php echo htmlspecialchars($record['status']); ?>
                                    </span>
                                </div>
                                <div class="record-details">
                                    <strong>Grade:</strong> <?php echo htmlspecialchars($record['grade']); ?> | 
                                    <strong>Date:</strong> <?php echo htmlspecialchars($record['completion_date']); ?>
                                </div>
                                <div class="record-actions">
                                    <?php if ($isDraft): ?>
                                        <!-- DRAFT: Can Edit, Delete, or Submit to Admin -->
                                        <button class="btn-edit" onclick="toggleEdit(<?php echo $record['recordId']; ?>)">Edit</button>
                                        <button class="btn-delete" onclick="deleteDraft(<?php echo $record['recordId']; ?>)">Delete</button>
                                        <button class="btn-submit" onclick="submitToAdmin(<?php echo $record['recordId']; ?>)">Submit to Admin →</button>
                                    <?php elseif ($record['status'] == 'Pending'): ?>
                                        <!-- PENDING: Waiting for admin -->
                                        <span style="color: #d97706; font-size: 13px; font-style: italic;">Waiting for admin verification...</span>
                                    <?php elseif ($record['status'] == 'Rejected'): ?>
                                        <!-- REJECTED: Can create new or contact admin -->
                                        <span style="color: #dc2626; font-size: 13px; font-style: italic;">Rejected. Create a new record with corrections.</span>
                                    <?php else: ?>
                                        <!-- VERIFIED: Done -->
                                        <span style="color: #059669; font-size: 13px; font-style: italic;">✓ Verified by admin</span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Edit Form (only for Draft) -->
                                <?php if ($isDraft): ?>
                                    <div class="edit-form" id="edit-form-<?php echo $record['recordId']; ?>">
                                        <input type="text" id="course-<?php echo $record['recordId']; ?>" 
                                               value="<?php echo htmlspecialchars($record['course_name']); ?>" 
                                               placeholder="Course Name">
                                        <input type="text" id="grade-<?php echo $record['recordId']; ?>" 
                                               value="<?php echo htmlspecialchars($record['grade']); ?>" 
                                               placeholder="Grade">
                                        <input type="date" id="date-<?php echo $record['recordId']; ?>" 
                                               value="<?php echo htmlspecialchars($record['completion_date']); ?>">
                                        <div>
                                            <button class="btn-edit" onclick="saveEdit(<?php echo $record['recordId']; ?>)">Save Changes</button>
                                            <button class="btn-delete" onclick="toggleEdit(<?php echo $record['recordId']; ?>)">Cancel</button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </section>
    
    <script>
        const API_URL = "../config/courseServer.php";
        const STUDENT_ID = <?php echo $id; ?>;
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';
        
        function toggleEdit(recordId) {
            const form = document.getElementById(`edit-form-${recordId}`);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
        
        // Save draft edits
        async function saveEdit(recordId) {
            const data = {
                recordId: recordId,
                studentId: STUDENT_ID,
                course_name: document.getElementById(`course-${recordId}`).value,
                grade: document.getElementById(`grade-${recordId}`).value,
                date: document.getElementById(`date-${recordId}`).value,
                csrf_token: CSRF_TOKEN
            };
            
            try {
                const response = await fetch(`${API_URL}?action=updateDraft`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': CSRF_TOKEN
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                if (result.success) {
                    alert('Draft saved!');
                    location.reload();
                } else {
                    alert('Error: ' + (result.error || 'Failed to save'));
                }
            } catch (error) {
                alert('Error saving draft');
            }
        }
        
        // Delete draft
        async function deleteDraft(recordId) {
            if (!confirm('Delete this draft?')) return;
            
            try {
                const response = await fetch(`${API_URL}?action=deleteDraft&recordId=${recordId}&studentId=${STUDENT_ID}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token': CSRF_TOKEN
                    }
                });
                
                const result = await response.json();
                if (result.success) {
                    location.reload();
                } else {
                    alert('Error: ' + (result.error || 'Failed to delete'));
                }
            } catch (error) {
                alert('Error deleting draft');
            }
        }
        
        // Submit to admin (Draft → Pending)
        async function submitToAdmin(recordId) {
            if (!confirm('Submit this record to admin for verification? You cannot edit it after submission.')) return;
            
            try {
                const response = await fetch(`${API_URL}?action=submitToAdmin&recordId=${recordId}&studentId=${STUDENT_ID}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token': CSRF_TOKEN
                    }
                });
                
                const result = await response.json();
                if (result.success) {
                    alert('Submitted to admin successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (result.error || 'Failed to submit'));
                }
            } catch (error) {
                alert('Error submitting to admin');
            }
        }
    </script>
</body>
</html>
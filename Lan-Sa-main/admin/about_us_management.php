<?php
session_start();
include('dbcon.php');

// Fetch general information
$general_info_result = $conn->query("SELECT key_name, content FROM general_info WHERE key_name IN ('slogan', 'big_sentence', 'section_heading')");
$general_info = [];
while ($row = $general_info_result->fetch_assoc()) {
    $general_info[$row['key_name']] = $row['content'];
}

// Fetch team members
$team_members_result = $conn->query("SELECT id, name, role, description, image, team FROM team_members");

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['edit_general_info'])) {
        $slogan = $conn->real_escape_string($_POST['slogan']);
        $big_sentence = $conn->real_escape_string($_POST['big_sentence']);
        $section_heading = $conn->real_escape_string($_POST['section_heading']);

        $conn->query("UPDATE general_info SET content='$slogan' WHERE key_name='slogan'");
        $conn->query("UPDATE general_info SET content='$big_sentence' WHERE key_name='big_sentence'");
        $conn->query("UPDATE general_info SET content='$section_heading' WHERE key_name='section_heading'");
        header("Location: " . $_SERVER['PHP_SELF']);
    }

    if (isset($_POST['edit_team_member'])) {
        $id = (int)$_POST['team_member_id'];
        $name = $conn->real_escape_string($_POST['name']);
        $role = $conn->real_escape_string($_POST['role']);
        $description = $conn->real_escape_string($_POST['description']);
        $team = $conn->real_escape_string($_POST['team']);
    
        $currentImage = $conn->real_escape_string($_POST['current_image']);
        $newImage = $_FILES['image']['name'];
        $imagePath = '';
    
        if ($newImage) {
            // Handle image upload
            $baseDir = 'Lan-Sa-main/web/images/team_members/';

            // Check if the directory exists, if not, create it
            if (!is_dir($baseDir)) {
                mkdir($baseDir, 0777, true);
            }

            $imagePath = $baseDir . basename($newImage);
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    
            // Delete old image if a new one is uploaded
            if ($currentImage && file_exists($currentImage)) {
                unlink($currentImage);
            }
        } else {
            $imagePath = $currentImage; // Keep current image if no new one is uploaded
        }
    
        $updateQuery = "UPDATE team_members SET name='$name', role='$role', description='$description', team='$team', image='$imagePath' WHERE id=$id";
        $conn->query($updateQuery);
        header("Location: " . $_SERVER['PHP_SELF']);
    }
    
    if (isset($_POST['add_team_member'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $role = $conn->real_escape_string($_POST['role']);
        $description = $conn->real_escape_string($_POST['description']);
        $team = $conn->real_escape_string($_POST['team']);
        
        // Handle image upload
        $imagePath = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $baseDir = 'Lan-Sa-main/web/images/team_members/';

            // Check if the directory exists, if not, create it
            if (!is_dir($baseDir)) {
                mkdir($baseDir, 0777, true);
            }

            $imagePath = $baseDir . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        }
    
        $conn->query("INSERT INTO team_members (name, role, description, team, image) VALUES ('$name', '$role', '$description', '$team', '$imagePath')");
        header("Location: " . $_SERVER['PHP_SELF']);
    }
    
    if (isset($_POST['delete_team_member'])) {
        $id = (int)$_POST['team_member_id'];
        $result = $conn->query("SELECT image FROM team_members WHERE id=$id");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $currentImage = $row['image'];
            if ($currentImage && file_exists($currentImage)) {
                unlink($currentImage); // Delete the image file
            }
        }
        $conn->query("DELETE FROM team_members WHERE id=$id");
        header("Location: " . $_SERVER['PHP_SELF']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">
    <style>
    body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .wrapper {
            display: flex;
            flex-direction: column;
            width: 100%;
            height: 100vh;
        }
 .sidebar {
    background: linear-gradient(135deg, #6f42c1, #512da8);
    color: #fff;
    min-width: 250px;
    height: 100%;
    padding-top: 20px;
    position: fixed;
    top: 0;
    left: 0;
    transition: all 0.3s;
    z-index: 1050;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.sidebar h3 {
    color: #fff;
    text-align: center;
    padding: 20px 0;
    font-size: 1.6em;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar a {
    color: #fff;
    display: flex;
    align-items: center;
    padding: 15px 20px;
    text-decoration: none;
    transition: background 0.3s, padding-left 0.3s;
}

.sidebar a:hover {
    background: rgba(255, 255, 255, 0.1);
    padding-left: 30px;
}

.sidebar a .bi {
    margin-right: 15px;
}
        .content {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 20px;
            transition: all 0.3s;
        }
        .top-navbar {
            background: #6f42c1;
            color: #fff;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        @media (max-width: 992px) {
            .sidebar {
                left: -250px;
            }
            .sidebar.active {
                left: 0;
            }
            .content {
                margin-left: 0;
                width: 100%;
                padding: 10px;
            }
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040;
            }
            .overlay.active {
                display: block;
            }
        }
        .hamburger {
            display: none;
            font-size: 2rem;
            cursor: pointer;
        }
        @media (max-width: 992px) {
            .hamburger {
                display: block;
            }
        }
        @media (max-width: 576px) {
            .table-wrapper {
                overflow-x: auto;
            }
        }
        .form-inline {
    display: none;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    padding: 25px;
    position: fixed;
    z-index: 1050;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    max-width: 500px;
    overflow: auto;
}

.form-inline.active {
    display: block;
}

.form-inline h4 {
    margin-bottom: 20px;
    font-size: 1.5rem;
    color: #333;
}

.form-inline .form-group {
    margin-bottom: 15px;
}

.form-inline .form-group label {
    margin-bottom: 5px;
    font-weight: 500;
}

.form-inline .form-control {
    border-radius: 4px;
    border: 1px solid #ced4da;
    padding: 10px;
    font-size: 1rem;
    width: 100%;
}

.form-inline .btn {
    padding: 10px 20px;
    font-size: 1rem;
    border-radius: 4px;
    margin-right: 10px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.form-inline .btn-danger {
    background-color: #dc3545;
    color: #fff;
    border: none;
}

.form-inline .btn-danger:hover {
    background-color: #c82333;
}

.form-inline .btn-primary {
    background-color: #007bff;
    color: #fff;
    border: none;
}

.form-inline .btn-primary:hover {
    background-color: #0056b3;
}

        .table-wrapper {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table-title {
            background: #6f42c1;
            color: #fff;
            padding: 16px 30px;
            margin: -20px -20px 10px;
            border-radius: 5px 5px 0 0;
        }
        .table-title h2 {
            margin: 0;
            font-size: 24px;
        }
        .table-title .btn {
            float: right;
            font-size: 13px;
            background: #ff7e5f;
            border: none;
            min-width: 50px;
            border-radius: 2px;
            margin-left: 10px;
        }
        .table-title .btn i {
            margin-right: 5px;
        }
        .table-title .btn:hover, .table-title .btn:focus {
            background: #feb47b;
        }
        table.table {
            border-color: #e9e9e9;
            width: 100%;
            table-layout: fixed;
        }
        table.table th, table.table td {
            padding: 12px 15px;
            vertical-align: middle;
            border-color: #e9e9e9;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        table.table-striped tbody tr:nth-of-type(odd) {
            background-color: #fcfcfc;
        }
        table.table-striped.table-hover tbody tr:hover {
            background: #f5f5f5;
        }
        table.table td a {
            color: #a0a5b1;
            margin: 0 5px;
        }
        table.table td a:hover {
            color: #2196f3;
        }
        table.table td a.edit {
            color: #ffc107;
        }
        table.table td a.delete {
            color: #f44336;
        }
        table.table td i {
            font-size: 19px;
        }
        .table-wrapper-scroll-y {
            display: block;
            max-height: 200px;
            overflow-y: auto;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .table-wrapper-scroll-y::-webkit-scrollbar {
            display: none;
        }
        
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="wrapper">
      <!-- Sidebar -->
         <div class="sidebar">
    <h3 class="text-center py-3">Admin Panel</h3>
    <a href="dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a>
    <a href="users_edit.php"><i class="bi bi-people"></i> User Management</a>
    <a href="booking_edit.php"><i class="bi bi-globe"></i> Booking Management</a>
    <a href="booking_history.php"><i class="bi bi-clock-history"></i> Booking History</a>
    <a href="services_edit.php"><i class="bi bi-tools"></i> Services Management</a>
    <a href="design_edit.php"><i class="bi bi-gear"></i> Design Management</a>
    <a href="webinar_edit.php"><i class="bi bi-camera-video"></i> Webinar Management</a>
    <a href="about_us_management.php"><i class="bi bi-info-circle"></i> About Management</a>
    <a href="review_management.php"><i class="bi bi-chat-square-text"></i> Review Management</a>
    <a href="contact_us_management.php"><i class="bi bi-chat-square-text"></i> Contact Us Management</a>
    <a href="call_us_management.php"><i class="bi bi-phone"></i> Call Us Management</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

        <div class="content">
        <div class="top-navbar">
            <div class="container">
                <span class="hamburger" onclick="toggleSidebar()">☰</span>
                <h2>About Us Management</h2>
            </div>
        </div>
            <div class="table-wrapper">
                <div class="table-title">
                    <h2>General Information</h2>
                    <button class="btn" id="editGeneralInfoBtn"><i class="bi bi-pencil-square"></i> <span>Edit</span></button>
                </div>
                <div class="table-wrapper-scroll-y my-custom-scrollbar">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Key</th>
                                <th>Content</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($general_info): ?>
                                <?php foreach ($general_info as $key => $content): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($key); ?></td>
                                        <td><?php echo htmlspecialchars($content); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2">No data available</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
  <!-- Team Members Table -->
  <div class="table-wrapper">
            <div class="table-title">
                <h2>Team Members</h2>
                <button class="btn" id="addTeamMemberBtn"><i class="bi bi-plus-circle"></i> <span>Add New</span></button>
            </div>
            <div class="table-wrapper-scroll-y my-custom-scrollbar">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Image</th>
                            <th>Description</th>
                            <th>Team</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($team_member = $team_members_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($team_member['name']); ?></td>
                                <td><?php echo htmlspecialchars($team_member['role']); ?></td>
                                <td><img src="<?php echo htmlspecialchars($team_member['image']); ?>" alt="Image" style="width: 50px; height: auto;"></td>
                                <td><?php echo htmlspecialchars($team_member['description']); ?></td>
                                <td><?php echo htmlspecialchars($team_member['team']); ?></td>
                                <td>
                                    <a href="#" class="edit" data-id="<?php echo $team_member['id']; ?>" data-name="<?php echo htmlspecialchars($team_member['name']); ?>" data-role="<?php echo htmlspecialchars($team_member['role']); ?>" data-image="<?php echo htmlspecialchars($team_member['image']); ?>" data-description="<?php echo htmlspecialchars($team_member['description']); ?>" data-team="<?php echo htmlspecialchars($team_member['team']); ?>"><i class="bi bi-pencil-square"></i></a>
                                    <a href="#" class="delete" data-id="<?php echo $team_member['id']; ?>"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

<!-- General Info Edit Form -->
<div class="form-inline" id="editGeneralInfoForm">
    <h4>Edit General Information</h4>
    <form method="post">
        <input type="hidden" name="edit_general_info" value="1">
        <div class="form-group">
            <label for="slogan">Slogan</label>
            <input type="text" class="form-control" name="slogan" id="slogan" value="<?php echo htmlspecialchars($general_info['slogan'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="big_sentence">Big Sentence</label>
            <input type="text" class="form-control" name="big_sentence" id="big_sentence" value="<?php echo htmlspecialchars($general_info['big_sentence'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="section_heading">Section Heading</label>
            <input type="text" class="form-control" name="section_heading" id="section_heading" value="<?php echo htmlspecialchars($general_info['section_heading'] ?? ''); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-danger" id="cancelGeneralInfoEdit">Cancel</button>
    </form>
</div>
<!-- Add Team Member Form -->
<div class="form-inline" id="addTeamMemberForm">
    <h4>Add New Team Member</h4>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="add_team_member" value="1">
        <div class="form-group">
            <label for="add_name">Name</label>
            <input type="text" class="form-control" name="name" id="add_name" required>
        </div>
        <div class="form-group">
            <label for="add_role">Role</label>
            <input type="text" class="form-control" name="role" id="add_role" required>
        </div>
        <div class="form-group">
            <label for="add_image">Image</label>
            <input type="file" class="form-control" name="image" id="add_image" required>
        </div>
        <div class="form-group">
            <label for="add_description">Description</label>
            <textarea class="form-control" name="description" id="add_description" required></textarea>
        </div>
        <div class="form-group">
            <label for="add_team">Team</label>
            <input type="text" class="form-control" name="team" id="add_team" required>
        </div>
        <button type="submit" class="btn btn-primary">Add</button>
        <button type="button" class="btn btn-danger" id="cancelAddTeamMember">Cancel</button>
    </form>
</div>
<!-- Team Member Edit Form -->
<div class="form-inline" id="editTeamMemberForm">
    <h4>Edit Team Member</h4>
    <form method="post" enctype="multipart/form-data"> <!-- Make sure to include enctype for file upload -->
        <input type="hidden" name="edit_team_member" value="1">
        <input type="hidden" name="team_member_id" id="team_member_id">
        <input type="hidden" name="current_image" id="current_image"> <!-- Add this here -->
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" id="name" required>
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <input type="text" class="form-control" name="role" id="role" required>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" class="form-control" name="image" id="image">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" name="description" id="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="team">Team</label>
            <input type="text" class="form-control" name="team" id="team" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-danger" id="cancelTeamMemberEdit">Cancel</button>
    </form>
</div>

        <div class="overlay"></div>
        </div>
    
    <script>
       document.getElementById('addTeamMemberBtn').addEventListener('click', function () {
    document.getElementById('addTeamMemberForm').classList.add('active');
    document.querySelector('.overlay').classList.add('active');
});

document.getElementById('cancelAddTeamMember').addEventListener('click', function () {
    document.getElementById('addTeamMemberForm').classList.remove('active');
    document.querySelector('.overlay').classList.remove('active');
});


        document.getElementById('cancelGeneralInfoEdit').addEventListener('click', function() {
            document.getElementById('editGeneralInfoForm').classList.remove('active');
            document.querySelector('.overlay').classList.remove('active');
        });

        document.getElementById('cancelTeamMemberEdit').addEventListener('click', function() {
            document.getElementById('editTeamMemberForm').classList.remove('active');
            document.querySelector('.overlay').classList.remove('active');
        });

        document.querySelectorAll('.edit').forEach(function(editBtn) {
            editBtn.addEventListener('click', function(e) {
                e.preventDefault();
                var id = this.getAttribute('data-id');
                var name = this.getAttribute('data-name');
                var role = this.getAttribute('data-role');
                var image = this.getAttribute('data-image');
                var description = this.getAttribute('data-description');
                var team = this.getAttribute('data-team');

                document.getElementById('team_member_id').value = id;
                document.getElementById('name').value = name;
                document.getElementById('role').value = role;
                document.getElementById('image').value = image;
                document.getElementById('description').value = description;
                document.getElementById('team').value = team;

                document.getElementById('editTeamMemberForm').classList.add('active');
                document.querySelector('.overlay').classList.add('active');
            });
        });

        document.querySelectorAll('.delete').forEach(function(deleteBtn) {
            deleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this team member?')) {
                    var id = this.getAttribute('data-id');
                    var form = document.createElement('form');
                    form.method = 'post';
                    form.innerHTML = '<input type="hidden" name="delete_team_member" value="1"><input type="hidden" name="team_member_id" value="' + id + '">';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
        document.getElementById('editGeneralInfoBtn').addEventListener('click', function () {
    document.getElementById('editGeneralInfoForm').classList.add('active');
    document.querySelector('.overlay').classList.add('active');
});

document.querySelectorAll('.edit').forEach(function (editBtn) {
    editBtn.addEventListener('click', function (e) {
        e.preventDefault();
        var id = this.getAttribute('data-id');
        var name = this.getAttribute('data-name');
        var role = this.getAttribute('data-role');
        var image = this.getAttribute('data-image');
        var description = this.getAttribute('data-description');
        var team = this.getAttribute('data-team');

        document.getElementById('team_member_id').value = id;
        document.getElementById('name').value = name;
        document.getElementById('role').value = role;
        document.getElementById('current_image').value = image; // Add this line
        document.getElementById('description').value = description;
        document.getElementById('team').value = team;

        document.getElementById('editTeamMemberForm').classList.add('active');
        document.querySelector('.overlay').classList.add('active');
    });
});
function toggleSidebar() {
        document.querySelector('.sidebar').classList.toggle('active');
        document.querySelector('.overlay').classList.toggle('active');
    }
    document.querySelector('.overlay').addEventListener('click', toggleSidebar);

    </script>
</body>
</html>

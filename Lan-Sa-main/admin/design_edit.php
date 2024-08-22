<?php
session_start();
include('dbcon.php');

// Handle deletion
// Handle deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    
    // Fetch the image path for the content to be deleted
    $sql_fetch = "SELECT image_path FROM content_management WHERE id=?";
    $stmt_fetch = $conn->prepare($sql_fetch);
    $stmt_fetch->bind_param('i', $delete_id);
    $stmt_fetch->execute();
    $stmt_fetch->bind_result($imagePath);
    $stmt_fetch->fetch();
    $stmt_fetch->close();

    // Delete the file from the server
    if ($imagePath && file_exists($imagePath)) {
        unlink($imagePath); // Deletes the file
    }

    // Now delete the record from the database
    $sql_delete = "DELETE FROM content_management WHERE id=?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param('i', $delete_id);

    if ($stmt_delete->execute()) {
        echo "<script>alert('Content deleted successfully'); window.location.href='design_edit.php';</script>";
    } else {
        echo "Error: " . $stmt_delete->error;
    }
    $stmt->close();
}

// Handle content editing
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    $new_content = isset($_POST['edit_content']) ? $_POST['edit_content'] : null;
    $new_image = null;

    if (!empty($_FILES['new_image']['name'])) {
        $baseDir = 'Lan-Sa-main/web/images/carousel/';

        // Check if the directory exists, if not, create it
        if (!is_dir($baseDir)) {
            mkdir($baseDir, 0777, true);
        }

        // Define the target file path
        $targetFile = $baseDir . basename($_FILES["new_image"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Allow only certain file formats
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["new_image"]["tmp_name"], $targetFile)) {
                // Success, save the path in the database
                $new_image = $targetFile;
            } else {
                echo "<script>alert('Error uploading file. Please check directory permissions.');</script>";
            }
        } else {
            echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
        }
    }

    // Prepare SQL query with placeholders
    $sql_update = "UPDATE content_management SET ";
    $params = [];
    $types = '';

    if ($new_content !== null) {
        $sql_update .= "content=?, ";
        $params[] = $new_content;
        $types .= 's';
    }

    if ($new_image !== null) {
        $sql_update .= "image_path=?, ";
        $params[] = $new_image;
        $types .= 's';
    }

    // Trim trailing comma and space
    $sql_update = rtrim($sql_update, ', ');

    // Add WHERE clause
    $sql_update .= " WHERE id=?";
    $params[] = $edit_id;
    $types .= 'i';

    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo "<script>alert('Content updated successfully'); window.location.href='design_edit.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Handle new content with image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_content'])) {
    $type = $_POST['type'];
    $content = isset($_POST['content']) ? $_POST['content'] : null;
    $position = isset($_POST['position']) ? $_POST['position'] : null;
    $imagePath = null;

    if ($type == 'carousel_image' && !empty($_FILES['image']['name'])) {
        $baseDir = 'Lan-Sa-main/web/images/carousel/';

        // Check if the directory exists, if not, create it
        if (!is_dir($baseDir)) {
            mkdir($baseDir, 0777, true);
        }

        // Define the target file path
        $targetFile = $baseDir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Allow only certain file formats
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                // Success, save the path in the database
                $imagePath = $targetFile;
            } else {
                echo "<script>alert('Error uploading file. Please check directory permissions.');</script>";
            }
        } else {
            echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
        }
    }

    $sql_insert = "INSERT INTO content_management (type, content, image_path, position) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param('sssi', $type, $content, $imagePath, $position);

    if ($stmt->execute()) {
        echo "<script>alert('New content added successfully'); window.location.href='design_edit.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all content for display
$sql = "SELECT * FROM content_management ORDER BY type, position";
$result = $conn->query($sql);

// Handle logo update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_logo'])) {
    $new_logo = null;

    if (!empty($_FILES['new_logo']['name'])) {
        $baseDir = 'Lan-Sa-main/web/images/Logo/';

        // Check if the directory exists, if not, create it
        if (!is_dir($baseDir)) {
            mkdir($baseDir, 0777, true);
        }

        // Define the target file path
        $targetFile = $baseDir . basename($_FILES["new_logo"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Allow only certain file formats
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["new_logo"]["tmp_name"], $targetFile)) {
                // Success, save the path in the database
                $new_logo = $targetFile;

                // Update the logo path in the database
                $sql_update_logo = "UPDATE logo_settings SET logo_path=? WHERE id=1";
                $stmt_update_logo = $conn->prepare($sql_update_logo);
                $stmt_update_logo->bind_param('s', $new_logo);

                if ($stmt_update_logo->execute()) {
                    echo "<script>alert('Logo updated successfully'); window.location.href='design_edit.php';</script>";
                } else {
                    echo "<script>alert('Error: " . $stmt_update_logo->error . "');</script>";
                }
                $stmt_update_logo->close();
            } else {
                echo "<script>alert('Error uploading file. Please check directory permissions.');</script>";
            }
        } else {
            echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
        }
    }
}
// Fetch the current logo path
$sql_logo = "SELECT logo_path FROM logo_settings WHERE id=1";
$result_logo = $conn->query($sql_logo);
$current_logo = $result_logo->fetch_assoc();


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Design Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
   
#openAddForm {
    position: absolute;
    background: #28a745;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 1.2rem;
    text-decoration: none;
    transition: background 0.3s;
}

#openAddForm:hover {
    background: #218838;
}

@media (max-width: 576px) {
    #openAddForm {
        position: static;
        display: block;
        margin: 10px 0;
        text-align: center;
    }
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
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1050;
            width: 90%;
            max-width: 500px;
        }
        .form-inline.active {
            display: block;
        }
        .close {
            font-size: 1.5rem;
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 15px;
        }
    </style>
</head>
<body>

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

<div class="overlay"></div>
    <div class="content">
        <div class="top-navbar">
            <span class="hamburger" id="hamburger">&#9776;</span>
            <span>Content Management</span>
        </div>
        <div class="container-fluid">
         <div class="table-wrapper">
          <table class="table table-striped table-bordered">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Content</th>
                <th>Image</th>
                <th>Position</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['type']; ?></td>
                    <td><?php echo $row['content']; ?></td>
                    <td><?php if($row['image_path']) echo '<img src="'.$row['image_path'].'" width="100" height="60">'; ?></td>
                    <td><?php echo $row['position']; ?></td>
                    <td>
                        <button class="btn btn-primary btn-sm edit-btn" data-id="<?php echo $row['id']; ?>" data-type="<?php echo $row['type']; ?>" data-content="<?php echo $row['content']; ?>" data-image="<?php echo $row['image_path']; ?>" data-position="<?php echo $row['position']; ?>"><i class="bi bi-pencil"></i> Edit</button>
                        <form method="post" style="display:inline-block;">
                            <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <a href="#" id="openAddForm" class="btn btn-success btn-lg"><i class="bi bi-plus-circle"></i> Add New Content</a> <br><br>
        <div class="container mt-5">
    <h4>Update Logo</h4>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="new_logo" class="form-label">Upload New Logo</label>
            <input type="file" class="form-control" name="new_logo" required>
        </div>
        <button type="submit" class="btn btn-primary" name="update_logo">Update Logo</button>
    </form>
</div>

    </div>
</div>

    </div>
</div>

<!-- Add Content Form -->
<div class="form-inline" id="addForm">
    <span class="close">&times;</span>
    <h4>Add New Content</h4>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select class="form-select" name="type" required>
                <option value="carousel_image">Carousel Image</option>
                <option value="text_content">Text Content</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" name="content"></textarea>
        </div>
        <div class="mb-3">
            <label for="position" class="form-label">Position</label>
            <input type="number" class="form-control" name="position" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" name="image">
        </div>
        <button type="submit" class="btn btn-primary" name="add_content">Add Content</button>
        <button type="button" class="btn btn-secondary cancel">Cancel</button>
    </form>
</div>

<!-- Edit Content Form -->
<div class="form-inline" id="editForm">
    <span class="close">&times;</span>
    <h4>Edit Content</h4>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="edit_id" id="edit_id">
        <div class="mb-3">
            <label for="edit_content" class="form-label">Content</label>
            <textarea class="form-control" name="edit_content" id="edit_content"></textarea>
        </div>
        <div class="mb-3">
            <label for="new_image" class="form-label">Image</label>
            <input type="file" class="form-control" name="new_image" id="new_image">
            <div id="current_image" class="mt-2"></div>
        </div>
        <button type="submit" class="btn btn-primary" name="update_content">Update Content</button>
        <button type="button" class="btn btn-secondary cancel">Cancel</button>
    </form>
</div>

<script>
    const overlay = document.querySelector('.overlay');
    const addForm = document.getElementById('addForm');
    const editForm = document.getElementById('editForm');
    const openAddFormBtn = document.getElementById('openAddForm');
    const closeAddFormBtn = addForm.querySelector('.close');
    const closeEditFormBtn = editForm.querySelector('.close');
    const editButtons = document.querySelectorAll('.edit-btn');
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.querySelector('.sidebar');

    // Open and close sidebar
    hamburger.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    });

    // Open add content form
    openAddFormBtn.addEventListener('click', () => {
        addForm.classList.add('active');
        overlay.classList.add('active');
    });

    // Close add content form
    closeAddFormBtn.addEventListener('click', () => {
        addForm.classList.remove('active');
        overlay.classList.remove('active');
    });

    // Open edit content form
    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const content = button.getAttribute('data-content');
            const image = button.getAttribute('data-image');

            document.getElementById('edit_id').value = id;
            document.getElementById('edit_content').value = content;

            if (image) {
                document.getElementById('current_image').innerHTML = `<img src="${image}" width="100" height="60">`;
            } else {
                document.getElementById('current_image').innerHTML = '';
            }

            editForm.classList.add('active');
            overlay.classList.add('active');
        });
    });

    // Close edit content form
    closeEditFormBtn.addEventListener('click', () => {
        editForm.classList.remove('active');
        overlay.classList.remove('active');
    });

    // Close forms using cancel buttons
    document.querySelectorAll('.cancel').forEach(cancelBtn => {
        cancelBtn.addEventListener('click', () => {
            document.querySelectorAll('.form-inline.active').forEach(form => form.classList.remove('active'));
            overlay.classList.remove('active');
        });
    });

    // Hide overlay and sidebar on click outside
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        addForm.classList.remove('active');
        editForm.classList.remove('active');
        overlay.classList.remove('active');
    });
</script>
</body>
</html>

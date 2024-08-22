<?php
$page_title = "Webinar Management";
include 'dbcon.php'; // Include your database connection file

// Handle form submission for adding or updating the webinar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $link = $_POST['link'];

    // Handle file upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $baseDir = 'Lan-Sa-main/web/images/webinar/';

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
            // Fetch the current webinar data
            $result = $conn->query("SELECT * FROM webinars LIMIT 1");
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $currentImagePath = $row['image_path'];

                // Check if a new image is uploaded
                if ($currentImagePath !== $targetFile && file_exists($currentImagePath)) {
                    unlink($currentImagePath); // Delete the old image file
                }
            }

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

    // Fetch the current webinar data
    $result = $conn->query("SELECT * FROM webinars LIMIT 1");
    if ($result->num_rows > 0) {
        // Update the existing webinar
        $row = $result->fetch_assoc();

        // If no new image is uploaded, keep the existing image path
        if ($imagePath === null) {
            $imagePath = $row['image_path'];
        }

        $sql = "UPDATE webinars SET link = ?, image_path = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi', $link, $imagePath, $row['id']);
    } else {
        // Insert a new webinar
        $sql = "INSERT INTO webinars (link, image_path) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $link, $imagePath);
    }

    $stmt->execute();
    $stmt->close();
    header('Location: webinar_edit.php');
    exit;
}

// Fetch the current webinar data
$result = $conn->query("SELECT * FROM webinars LIMIT 1");
$webinar = $result->num_rows > 0 ? $result->fetch_assoc() : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webinar Management</title>
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
/* Sidebar */
.sidebar {
    transition: left 0.3s;
}

.sidebar.active {
    left: 0;
}

/* Overlay */
.overlay {
    display: none;
}

.overlay.active {
    display: block;
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
        /* Styling for the form */
        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        .form-container .form-group label {
            font-weight: 500;
        }
        .form-container .btn {
            background-color: #6f42c1;
            border-color: #6f42c1;
        }
        .form-container .btn:hover {
            background-color: #5a3390;
            border-color: #5a3390;
        }
        .form-container img {
            margin-top: 10px;
            border-radius: 5px;
            max-width: 100%;
        }
        /* Styling for the table */
        .table-container {
            max-width: 100%;
            margin: 20px auto;
            overflow-x: auto;
        }
        .table {
            margin-bottom: 0;
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
<!-- Overlay -->
<div class="overlay"></div>


    <!-- Page Content -->
    <div class="content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white px-lg-3 py-lg-2 shadow-sm sticky-top">
            <div class="container-fluid">
                <span class="hamburger" onclick="toggleSidebar()">&#9776;</span>
                <a class="navbar-brand me-5 fw-bold fs-3 h-font" href="#">Webinar Management</a>
            </div>
        </nav>

        <div class="form-container">
            <h4 class="mb-3">Update Webinar Details</h4>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group mb-3">
                    <label for="link">Webinar Link</label>
                    <input type="text" name="link" id="link" class="form-control" value="<?= $webinar['link'] ?? '' ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="image">Webinar Image</label>
                    <input type="file" name="image" id="image" class="form-control">
                    <?php if ($webinar && !empty($webinar['image_path'])): ?>
                        <img src="<?= $webinar['image_path'] ?>" alt="Current Webinar Image">
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>

        <div class="table-container">
            <h4 class="mt-5 mb-3">Webinar Details</h4>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Webinar Link</th>
                    <th scope="col">Webinar Image</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($webinar): ?>
                    <tr>
                        <td><?= $webinar['link'] ?></td>
                        <td><img src="<?= $webinar['image_path'] ?>" alt="Webinar Image" style="max-width: 100px;"></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="text-center">No webinar data available.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.querySelector('.hamburger').addEventListener('click', function () {
    document.querySelector('.sidebar').classList.toggle('active');
    document.querySelector('.overlay').classList.toggle('active');
});

document.querySelector('.overlay').addEventListener('click', function () {
    document.querySelector('.sidebar').classList.remove('active');
    document.querySelector('.overlay').classList.remove('active');
});

</script>
</body>
</html>

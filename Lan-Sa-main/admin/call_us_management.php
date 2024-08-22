<?php
session_start();
include('dbcon.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $linkedin_url = $_POST['linkedin_url'];
    $facebook_url = $_POST['facebook_url'];
    $instagram_url = $_POST['instagram_url'];

    // Update the contact information in the database
    $stmt = $conn->prepare("UPDATE contact_info SET phone_number=?, email=?, linkedin_url=?, facebook_url=?, instagram_url=? WHERE id=1");
    $stmt->bind_param("sssss", $phone_number, $email, $linkedin_url, $facebook_url, $instagram_url);

    if ($stmt->execute()) {
        $message = "Contact information updated successfully.";
    } else {
        $message = "Error updating contact information: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch contact information
$sql = "SELECT phone_number, email, linkedin_url, facebook_url, instagram_url FROM contact_info WHERE id = 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us Management</title>
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
        .form-inline {
            display: none;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            position: fixed;
            z-index: 1050;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 400px;
            overflow: auto;
        }
        .form-inline.active {
            display: block;
        }
        .form-inline .form-group {
            margin-bottom: 1rem;
        }
        .form-inline .form-group label {
            margin-bottom: 0.5rem;
        }
        .form-inline .form-control {
            border-radius: 0.25rem;
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
        .hint-text {
            float: left;
            margin-top: 10px;
            font-size: 13px;
        }
        .modal-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }
        .modal-backdrop.active {
            display: block;
        }
        @media (max-width: 768px) {
            table.table thead {
                display: none;
            }
            table.table, table.table tbody, table.table tr, table.table td {
                display: block;
                width: 100%;
            }
            table.table tr {
                margin-bottom: 15px;
            }
            table.table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }
            table.table td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 15px;
                font-weight: bold;
                text-align: left;
                background-color: #f8f9fa;
            }
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
    <!-- Content -->
    <div class="content">
        <div class="top-navbar">
            <div class="container">
                <span class="hamburger" onclick="toggleSidebar()">☰</span>
                <h2>Contact Us Management</h2>
            </div>
        </div>
        
        <div class="container">
            <div class="table-wrapper">
                <div class="table-title">
                    <h2>Manage Contact Information</h2>
                </div>
                <?php if (!empty($message)) { echo "<div class='alert alert-info'>$message</div>"; } ?>
                <form method="POST">
                    <div class="form-group">
                        <label for="phone_number">Phone Number:</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($row['phone_number']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="linkedin_url">LinkedIn URL:</label>
                        <input type="url" class="form-control" id="linkedin_url" name="linkedin_url" value="<?php echo htmlspecialchars($row['linkedin_url']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="facebook_url">Facebook URL:</label>
                        <input type="url" class="form-control" id="facebook_url" name="facebook_url" value="<?php echo htmlspecialchars($row['facebook_url']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="instagram_url">Instagram URL:</label>
                        <input type="url" class="form-control" id="instagram_url" name="instagram_url" value="<?php echo htmlspecialchars($row['instagram_url']); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
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

<div class="overlay"></div>
</body>
</html>


<?php
$page_title = "User Management";

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "lan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_user'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
        $otp = ''; 

        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, otp) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssss", $name, $email, $phone, $password, $otp);
            if ($stmt->execute()) {
                echo "<script>alert('New record created successfully');</script>";
            } else {
                echo "<script>alert('Error executing statement: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Error preparing statement: " . $conn->error . "');</script>";
        }
    }

    if (isset($_POST['edit_user'])) {
        $id = $_POST['user_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

        if ($password) {
            $sql = "UPDATE users SET name='$name', email='$email', phone='$phone', password='$password' WHERE id='$id'";
        } else {
            $sql = "UPDATE users SET name='$name', email='$email', phone='$phone' WHERE id='$id'";
        }

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Record updated successfully');</script>";
        } else {
            echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
        }
    }

    if (isset($_POST['delete_user'])) {
        $id = $_POST['user_id'];

        $sql = "DELETE FROM users WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Record deleted successfully');</script>";
        } else {
            echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
        }
    }
}

$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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
        @media (max-width: 576px) {
            .table-wrapper {
                overflow-x: auto;
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

    <!-- Page Content -->
    <div class="content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white px-lg-3 py-lg-2 shadow-sm sticky-top">
            <div class="container-fluid">
                <span class="hamburger" onclick="toggleSidebar()">&#9776;</span>
                <a class="navbar-brand me-5 fw-bold fs-3 h-font" href="#">User Management</a>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container">
            <h2 class="my-4 text-center">Manage Users</h2>
            <div class="table-responsive table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-sm-6 p-0 flex justify-content-lg-start justify-content-center">
                            <h2 class="ml-lg-2">Manage Users</h2>
                        </div>
                        <div class="col-sm-6 p-0 flex justify-content-lg-end justify-content-center">
                            <a href="#addUserForm" class="btn btn-success" onclick="toggleForm('addUserForm')">
                                <i class="bi bi-plus-circle"></i> Add New User
                            </a>
                        </div>
                    </div>
                </div>

                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td data-label='#'>" . $row['id'] . "</td>";
                                echo "<td data-label='Name'>" . $row['name'] . "</td>";
                                echo "<td data-label='Email'>" . $row['email'] . "</td>";
                                echo "<td data-label='Phone'>" . $row['phone'] . "</td>";
                                echo "<td data-label='Actions'>
                                    <a href='#editUserForm" . $row['id'] . "' class='edit' onclick=\"toggleForm('editUserForm" . $row['id'] . "')\">
                                        <i class='bi bi-pencil-square' data-toggle='tooltip' title='Edit'></i>
                                    </a>
                                    <a href='#deleteUserForm" . $row['id'] . "' class='delete' onclick=\"toggleForm('deleteUserForm" . $row['id'] . "')\">
                                        <i class='bi bi-trash' data-toggle='tooltip' title='Delete'></i>
                                    </a>
                                    <div id='editUserForm" . $row['id'] . "' class='form-inline'>
                                        <form method='post' action='' enctype='multipart/form-data'>
                                            <div class='modal-header'>
                                                <h4 class='modal-title'>Edit User</h4>
                                                <button type='button' class='close' aria-hidden='true' onclick=\"toggleForm('editUserForm" . $row['id'] . "')\">&times;</button>
                                            </div>
                                            <div class='modal-body'>
                                                <div class='form-group'>
                                                    <label>Name</label>
                                                    <input type='text' name='name' class='form-control' value='" . $row['name'] . "' required>
                                                </div>
                                                <div class='form-group'>
                                                    <label>Email</label>
                                                    <input type='email' name='email' class='form-control' value='" . $row['email'] . "' required>
                                                </div>
                                                <div class='form-group'>
                                                    <label>Phone</label>
                                                    <input type='text' name='phone' class='form-control' value='" . $row['phone'] . "' required>
                                                </div>
                                                <div class='form-group'>
                                                    <label>Password (Leave blank if not changing)</label>
                                                    <input type='password' name='password' class='form-control'>
                                                </div>
                                            </div>
                                            <div class='modal-footer'>
                                                <input type='hidden' name='user_id' value='" . $row['id'] . "'>
                                                <input type='button' class='btn btn-default' value='Cancel' onclick=\"toggleForm('editUserForm" . $row['id'] . "')\">
                                                <input type='submit' class='btn btn-info' name='edit_user' value='Save'>
                                            </div>
                                        </form>
                                    </div>
                                    <div id='deleteUserForm" . $row['id'] . "' class='form-inline'>
                                        <form method='post' action=''>
                                            <div class='modal-header'>
                                                <h4 class='modal-title'>Delete User</h4>
                                                <button type='button' class='close' aria-hidden='true' onclick=\"toggleForm('deleteUserForm" . $row['id'] . "')\">&times;</button>
                                            </div>
                                            <div class='modal-body'>
                                                <p>Are you sure you want to delete this user?</p>
                                            </div>
                                            <div class='modal-footer'>
                                                <input type='hidden' name='user_id' value='" . $row['id'] . "'>
                                                <input type='button' class='btn btn-default' value='Cancel' onclick=\"toggleForm('deleteUserForm" . $row['id'] . "')\">
                                                <input type='submit' class='btn btn-danger' name='delete_user' value='Delete'>
                                            </div>
                                        </form>
                                    </div>
                                  </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>No users found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Add User Form -->
                <div id="addUserForm" class="form-inline">
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h4 class="modal-title">Add User</h4>
                            <button type="button" class="close" aria-hidden="true" onclick="toggleForm('addUserForm')">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="button" class="btn btn-default" value="Cancel" onclick="toggleForm('addUserForm')">
                            <input type="submit" class="btn btn-success" name="add_user" value="Add">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Backdrop -->
<div class="overlay" onclick="closeForms()"></div>

<!-- Optional JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });

    function toggleForm(formId) {
        var form = document.getElementById(formId);
        form.classList.toggle('active');
        document.querySelector('.modal-backdrop').classList.toggle('active');
    }

    function closeForms() {
        document.querySelectorAll('.form-inline').forEach(function(form) {
            form.classList.remove('active');
        });
        document.querySelector('.modal-backdrop').classList.remove('active');
    }

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

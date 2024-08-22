<?php
// Include database connection
include('dbcon.php');

// Fetch existing countries and services
$countriesQuery = "SELECT * FROM countries";
$countriesResult = mysqli_query($conn, $countriesQuery);

$servicesQuery = "SELECT services.id, services.service_name, services.image_path AS service_image, services.price, countries.name AS country_name FROM services JOIN countries ON services.country_id = countries.id";
$servicesResult = mysqli_query($conn, $servicesQuery);
// Handle adding new country
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_country'])) {
    $country_name = $_POST['country_name'];
    
    // Handle file upload for country image
    $target_dir = "Lan-Sa-main/web/images/countries/"; // Define the directory to save uploaded images
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
    }

    $target_file = $target_dir . basename($_FILES["country_image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a real image
    $check = getimagesize($_FILES["country_image"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["country_image"]["tmp_name"], $target_file)) {
            // File is valid and was successfully uploaded
            $country_image = $target_file;

            // Insert into database
            $addCountryQuery = "INSERT INTO countries (name, image_path) VALUES ('$country_name', '$country_image')";
            mysqli_query($conn, $addCountryQuery);

            header("Location: services_edit.php");
            exit();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "File is not an image.";
    }
}


// Handle adding new service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
    $service_name = $_POST['service_name'];
    $service_price = $_POST['service_price'];
    $country_id = $_POST['country_id'];

    // Handle file upload
    $target_dir = "Lan-Sa-main/web/images/services/"; // Define the directory to save uploaded images
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
    }

    $target_file = $target_dir . basename($_FILES["service_image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a real image
    $check = getimagesize($_FILES["service_image"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["service_image"]["tmp_name"], $target_file)) {
            // File is valid and was successfully uploaded
            $service_image = $target_file;

            // Insert into database
            $addServiceQuery = "INSERT INTO services (service_name, image_path, price, country_id) VALUES ('$service_name', '$service_image', '$service_price', '$country_id')";
            mysqli_query($conn, $addServiceQuery);

            header("Location: services_edit.php");
            exit();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "File is not an image.";
    }
}

// Handle delete service
if (isset($_GET['delete_service'])) {
    $service_id = $_GET['delete_service'];

    $deleteServiceQuery = "DELETE FROM services WHERE id='$service_id'";
    mysqli_query($conn, $deleteServiceQuery);

    header("Location: services_edit.php");
    exit();
}

// Handle delete country
if (isset($_GET['delete_country'])) {
    $country_id = $_GET['delete_country'];

    $deleteCountryQuery = "DELETE FROM countries WHERE id='$country_id'";
    mysqli_query($conn, $deleteCountryQuery);

    header("Location: services_edit.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
        }
        table.table th, table.table td {
            padding: 12px 15px;
            vertical-align: middle;
            border-color: #e9e9e9;
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
            font-size: 19px
        }
        .modal .modal-dialog {
            max-width: 400px;
        }
        .modal .modal-header, .modal .modal-body, .modal .modal-footer {
            padding: 20px 30px;
        }
        .modal .modal-content {
            border-radius: 3px;
        }
        .modal .modal-footer {
            background: #ecf0f1;
            border-color: #e9e9e9;
            text-align: right;
            margin-top: 0;
        }
        .modal .modal-footer .btn {
            min-width: 100px;
            border-radius: 2px;
            border: none;
            margin-left: 5px;
        }
        .modal .modal-footer .btn-secondary {
            background: #ccc;
        }
        .modal .modal-footer .btn-danger {
            background: #f44336;
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

    <div class="content">
        <div class="top-navbar">
            <span class="hamburger"><i class="bi bi-list"></i></span>
            <h4>Edit Services</h4>
        </div>

        <div class="table-wrapper">
            <div class="table-title">
                <h2>Manage <b>Services & Countries</b></h2>
                <button class="btn btn-primary" onclick="openForm('countryForm')"><i class="bi bi-plus-circle"></i> Add Country</button>
                <button class="btn btn-primary" onclick="openForm('serviceForm')"><i class="bi bi-plus-circle"></i> Add Service</button>
            </div>

            <table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Country</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($countriesResult) {
            while ($row = mysqli_fetch_assoc($countriesResult)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td><img src='" . $row['image_path'] . "' alt='Country Image' width='100'></td>";
                echo "<td>";
                echo "<a href='edit_country.php?id=" . $row['id'] . "' class='edit'><i class='bi bi-pencil-square'></i></a>";
                echo "<a href='services_edit.php?delete_country=" . $row['id'] . "' class='delete'><i class='bi bi-trash'></i></a>";
                echo "</td>";
                echo "</tr>";
            }
        }
        ?>
    </tbody>
</table>

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Service Name</th>
                        <th>Country</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($servicesResult) {
                        while ($row = mysqli_fetch_assoc($servicesResult)) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['service_name'] . "</td>";
                            echo "<td>" . $row['country_name'] . "</td>";
                            echo "<td>" . $row['price'] . "</td>";
                            echo "<td><img src='" . $row['service_image'] . "' alt='Service Image' width='100'></td>";
                            echo "<td>";
                            echo "<a href='edit_service.php?id=" . $row['id'] . "' class='edit'><i class='bi bi-pencil-square'></i></a>";
                            echo "<a href='services_edit.php?delete_service=" . $row['id'] . "' class='delete'><i class='bi bi-trash'></i></a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Country Form -->
<div id="countryForm" class="form-inline">
    <form method="POST" action="services_edit.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="country_name">Country Name</label>
            <input type="text" class="form-control" id="country_name" name="country_name" required>
        </div>
        <div class="form-group">
            <label for="country_image">Country Image</label>
            <input type="file" class="form-control" id="country_image" name="country_image" required>
        </div>
        <button type="submit" name="add_country" class="btn btn-success">Add Country</button>
        <button type="button" class="btn btn-secondary" onclick="closeForm('countryForm')">Cancel</button>
    </form>
</div>

<!-- Add Service Form -->
<div id="serviceForm" class="form-inline">
    <form method="POST" action="services_edit.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="service_name">Service Name</label>
            <input type="text" class="form-control" id="service_name" name="service_name" required>
        </div>
        <div class="form-group">
            <label for="service_price">Service Price</label>
            <input type="number" class="form-control" id="service_price" name="service_price" required>
        </div>
        <div class="form-group">
            <label for="country_id">Country</label>
            <select class="form-control" id="country_id" name="country_id" required>
                <?php
                mysqli_data_seek($countriesResult, 0); // Reset pointer to fetch countries again
                if ($countriesResult) {
                    while ($row = mysqli_fetch_assoc($countriesResult)) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="service_image">Service Image</label>
            <input type="file" class="form-control" id="service_image" name="service_image" required>
        </div>
        <button type="submit" name="add_service" class="btn btn-success">Add Service</button>
        <button type="button" class="btn btn-secondary" onclick="closeForm('serviceForm')">Cancel</button>
    </form>
</div>

<script>
    // Toggle the display of the form
    function openForm(formId) {
        document.getElementById(formId).classList.add('active');
    }

    function closeForm(formId) {
        document.getElementById(formId).classList.remove('active');
    }

    // Hamburger menu toggle
    document.querySelectorAll('.hamburger').forEach(function(element) {
        element.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.overlay').classList.toggle('active');
        });
    });

    document.querySelector('.overlay').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.remove('active');
        document.querySelector('.overlay').classList.remove('active');
    });
</script>

</body>
</html>

<?php
// Include database connection
include('dbcon.php');

// Fetch existing counselors, dates, and times
$counselorsQuery = "SELECT * FROM service_counselors";
$counselorsResult = mysqli_query($conn, $counselorsQuery);

$datesQuery = "SELECT * FROM service_dates";
$datesResult = mysqli_query($conn, $datesQuery);

$timesQuery = "SELECT * FROM service_times";
$timesResult = mysqli_query($conn, $timesQuery);

// Handle adding new counselor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_counselor'])) {
    $counselor_name = $_POST['counselor_name'];
    $service_time_id = $_POST['service_time_id'];

    // Insert into database
    $addCounselorQuery = "INSERT INTO service_counselors (counselor_name, service_time_id) VALUES ('$counselor_name', '$service_time_id')";
    mysqli_query($conn, $addCounselorQuery);

    header("Location: booking_edit.php");
    exit();
}

// Handle adding new date
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_date'])) {
    $available_date = $_POST['available_date'];
    $service_id = $_POST['service_id'];

    // Insert into database
    $addDateQuery = "INSERT INTO service_dates (available_date, service_id) VALUES ('$available_date', '$service_id')";
    mysqli_query($conn, $addDateQuery);

    header("Location: booking_edit.php");
    exit();
}

// Handle adding new time
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_time'])) {
    $available_time = $_POST['available_time'];
    $service_date_id = $_POST['service_date_id'];

    // Insert into database
    $addTimeQuery = "INSERT INTO service_times (available_time, service_date_id) VALUES ('$available_time', '$service_date_id')";
    mysqli_query($conn, $addTimeQuery);

    header("Location: booking_edit.php");
    exit();
}

// Handle delete counselor
if (isset($_GET['delete_counselor'])) {
    $counselor_id = $_GET['delete_counselor'];

    $deleteCounselorQuery = "DELETE FROM service_counselors WHERE id='$counselor_id'";
    mysqli_query($conn, $deleteCounselorQuery);

    header("Location: booking_edit.php");
    exit();
}

// Handle delete date
if (isset($_GET['delete_date'])) {
    $date_id = $_GET['delete_date'];

    $deleteDateQuery = "DELETE FROM service_dates WHERE id='$date_id'";
    mysqli_query($conn, $deleteDateQuery);

    header("Location:booking_edit.php");
    exit();
}

// Handle delete time
if (isset($_GET['delete_time'])) {
    $time_id = $_GET['delete_time'];

    $deleteTimeQuery = "DELETE FROM service_times WHERE id='$time_id'";
    mysqli_query($conn, $deleteTimeQuery);

    header("Location: booking_edit.php");
    exit();
}

// Handle edit counselor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_counselor'])) {
    $counselor_id = $_POST['counselor_id'];
    $counselor_name = $_POST['counselor_name'];
    $service_time_id = $_POST['service_time_id'];

    $editCounselorQuery = "UPDATE service_counselors SET counselor_name='$counselor_name', service_time_id='$service_time_id' WHERE id='$counselor_id'";
    mysqli_query($conn, $editCounselorQuery);

    header("Location: booking_edit.php");
    exit();
}

// Handle edit date
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_date'])) {
    $date_id = $_POST['date_id'];
    $available_date = $_POST['available_date'];
    $service_id = $_POST['service_id'];

    $editDateQuery = "UPDATE service_dates SET available_date='$available_date', service_id='$service_id' WHERE id='$date_id'";
    mysqli_query($conn, $editDateQuery);

    header("Location: booking_edit.php");
    exit();
}

// Handle edit time
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_time'])) {
    $time_id = $_POST['time_id'];
    $available_time = $_POST['available_time'];
    $service_date_id = $_POST['service_date_id'];

    $editTimeQuery = "UPDATE service_times SET available_time='$available_time', service_date_id='$service_date_id' WHERE id='$time_id'";
    mysqli_query($conn, $editTimeQuery);

    header("Location: booking_edit.php");
    exit();
}

// Fetch the specific counselor, date, or time if edit is requested
$counselorToEdit = $dateToEdit = $timeToEdit = null;
if (isset($_GET['edit_counselor'])) {
    $counselor_id = $_GET['edit_counselor'];
    $counselorToEdit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM service_counselors WHERE id='$counselor_id'"));
}

if (isset($_GET['edit_date'])) {
    $date_id = $_GET['edit_date'];
    $dateToEdit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM service_dates WHERE id='$date_id'"));
}

if (isset($_GET['edit_time'])) {
    $time_id = $_GET['edit_time'];
    $timeToEdit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM service_times WHERE id='$time_id'"));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
   body, html {
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
    font-family: 'Poppins', sans-serif;
    background-color: #f0f2f5;
    overflow-x: hidden;
}

.wrapper {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    background-color: #ffffff;
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
    padding: 30px;
    transition: all 0.3s;
    background-color: #f8f9fa;
    min-height: 100vh;
}

.top-navbar {
    background: #6f42c1;
    color: #fff;
    padding: 15px 20px;
    margin-bottom: 30px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.hamburger {
    font-size: 1.8rem;
    cursor: pointer;
    display: none;
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
        padding: 15px;
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
    .hamburger {
        display: block;
    }
}

.table-wrapper {
    margin: 20px auto;
    padding: 15px;
    border-radius: 8px;
    background-color: #f8f9fa;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    max-width: 1000px;
}

.table-wrapper h5 {
    font-size: 1.25rem;
    margin-bottom: 15px;
    color: #333;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
}

.table thead tr {
    background-color: #343a40;
    color: #fff;
    text-align: left;
}

.table th, .table td {
    padding: 12px 15px;
    border: 1px solid #dee2e6;
}

.table tbody tr:nth-child(even) {
    background-color: #f2f2f2;
}

.table tbody tr:hover {
    background-color: #e9ecef;
}

.table a.btn {
    margin-right: 5px;
    padding: 5px 10px;
    font-size: 0.875rem;
    border-radius: 4px;
}

.table .btn-warning {
    background-color: #ffc107;
    color: #fff;
}

.table .btn-danger {
    background-color: #dc3545;
    color: #fff;
}

.table .btn-warning:hover {
    background-color: #e0a800;
}

.table .btn-danger:hover {
    background-color: #c82333;
}

.form-inline {
    display: none;
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 30px;
    position: fixed;
    z-index: 1050;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
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
    font-weight: bold;
}

.form-inline .form-control {
    border-radius: 0.25rem;
    border: 1px solid #ccc;
    width: 100%;
    padding: 10px;
}

.form-inline .btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.form-inline .btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
}

.form-inline .btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.form-inline .btn-secondary:hover {
    background-color: #5a6268;
    border-color: #545b62;
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

        <div class="content">
            <div class="top-navbar">
                <span class="hamburger"><i class="bi bi-list"></i></span>
                <h4>Manage Services</h4>
            </div>

            <div class="table-wrapper">
                <h5>Counselors</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Service Time ID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($counselor = mysqli_fetch_assoc($counselorsResult)) { ?>
                        <tr>
                            <td><?php echo $counselor['id']; ?></td>
                            <td><?php echo $counselor['counselor_name']; ?></td>
                            <td><?php echo $counselor['service_time_id']; ?></td>
                            <td>
                                <a href="booking_edit.php?edit_counselor=<?php echo $counselor['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="booking_edit.php?delete_counselor=<?php echo $counselor['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                
                <h5>Dates</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Service ID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($date = mysqli_fetch_assoc($datesResult)) { ?>
                        <tr>
                            <td><?php echo $date['id']; ?></td>
                            <td><?php echo $date['available_date']; ?></td>
                            <td><?php echo $date['service_id']; ?></td>
                            <td>
                                <a href="booking_edit.php?edit_date=<?php echo $date['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="booking_edit.php?delete_date=<?php echo $date['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <h5>Times</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Time</th>
                            <th>Service Date ID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($time = mysqli_fetch_assoc($timesResult)) { ?>
                        <tr>
                            <td><?php echo $time['id']; ?></td>
                            <td><?php echo $time['available_time']; ?></td>
                            <td><?php echo $time['service_date_id']; ?></td>
                            <td>
                                <a href="booking_edit.php?edit_time=<?php echo $time['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="booking_edit.php?delete_time=<?php echo $time['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Add New Forms -->
            <button onclick="toggleForm('add-counselor-form')" class="btn btn-primary">Add Counselor</button>
            <button onclick="toggleForm('add-date-form')" class="btn btn-primary">Add Date</button>
            <button onclick="toggleForm('add-time-form')" class="btn btn-primary">Add Time</button>

            <!-- Add Counselor Form -->
            <div id="add-counselor-form" class="form-inline active">
    <h4>Add New Counselor</h4>
    <form method="post" action="booking_edit.php">
        <div class="form-group mb-3">
            <label for="counselor_name" class="form-label">Counselor Name</label>
            <input type="text" class="form-control" name="counselor_name" required>
        </div>
        <div class="form-group mb-3">
            <label for="service_time_id" class="form-label">Service Time ID</label>
            <input type="text" class="form-control" name="service_time_id" required>
        </div>
        <button type="submit" name="add_counselor" class="btn btn-success">Add Counselor</button>
        <button type="button" class="btn btn-secondary" onclick="toggleForm('add-counselor-form')">Cancel</button>
    </form>
</div>


            <!-- Add Date Form -->
<div id="add-date-form" class="form-inline">
    <h4>Add New Date</h4>
    <form method="post" action="booking_edit.php">
        <div class="form-group mb-3">
            <label for="available_date" class="form-label">Available Date</label>
            <input type="date" class="form-control" name="available_date" required>
        </div>
        <div class="form-group mb-3">
            <label for="service_id" class="form-label">Service ID</label>
            <input type="text" class="form-control" name="service_id" required>
        </div>
        <button type="submit" name="add_date" class="btn btn-success">Add Date</button>
        <button type="button" class="btn btn-secondary" onclick="toggleForm('add-date-form')">Cancel</button>
    </form>
</div>


<!-- Add Time Form -->
<div id="add-time-form" class="form-inline">
    <h4>Add New Time</h4>
    <form method="post" action="booking_edit.php">
        <div class="form-group mb-3">
            <label for="available_time" class="form-label">Available Time</label>
            <input type="time" class="form-control" name="available_time" required>
        </div>
        <div class="form-group mb-3">
            <label for="service_date_id" class="form-label">Service Date ID</label>
            <input type="text" class="form-control" name="service_date_id" required>
        </div>
        <button type="submit" name="add_time" class="btn btn-success">Add Time</button>
        <button type="button" class="btn btn-secondary" onclick="toggleForm('add-time-form')">Cancel</button>
    </form>
</div>


            <!-- Edit Forms -->
            <?php if ($counselorToEdit) { ?>
        <div id="edit-counselor-form" class="form-inline active">
    <h4>Edit Counselor</h4>
    <form method="post" action="booking_edit.php">
        <input type="hidden" name="counselor_id" value="1">
        <div class="form-group mb-3">
            <label for="counselor_name" class="form-label">Counselor Name</label>
            <input type="text" class="form-control" name="counselor_name" value="hello3" required>
        </div>
        <div class="form-group mb-3">
            <label for="service_time_id" class="form-label">Service Time ID</label>
            <input type="text" class="form-control" name="service_time_id" value="1" required>
        </div>
        <button type="submit" name="edit_counselor" class="btn btn-success">Save Changes</button>
        <button type="button" class="btn btn-secondary" onclick="toggleForm('edit-counselor-form')">Cancel</button>
    </form>
</div>

            <?php } ?>

            <?php if ($dateToEdit) { ?>
                <?php if ($dateToEdit) { ?>
<div id="edit-date-form" class="form-inline active">
    <h4>Edit Date</h4>
    <form method="post" action="booking_edit.php">
        <input type="hidden" name="date_id" value="<?php echo $dateToEdit['id']; ?>">
        <div class="form-group mb-3">
            <label for="available_date" class="form-label">Available Date</label>
            <input type="date" class="form-control" name="available_date" value="<?php echo $dateToEdit['available_date']; ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="service_id" class="form-label">Service ID</label>
            <input type="text" class="form-control" name="service_id" value="<?php echo $dateToEdit['service_id']; ?>" required>
        </div>
        <button type="submit" name="edit_date" class="btn btn-success">Save Changes</button>
        <button type="button" class="btn btn-secondary" onclick="toggleForm('edit-date-form')">Cancel</button>
    </form>
</div>
<?php } ?>

            <?php } ?>

            <?php if ($timeToEdit) { ?>
                <?php if ($timeToEdit) { ?>
<div id="edit-time-form" class="form-inline active">
    <h4>Edit Time</h4>
    <form method="post" action="booking_edit.php">
        <input type="hidden" name="time_id" value="<?php echo $timeToEdit['id']; ?>">
        <div class="form-group mb-3">
            <label for="available_time" class="form-label">Available Time</label>
            <input type="time" class="form-control" name="available_time" value="<?php echo $timeToEdit['available_time']; ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="service_date_id" class="form-label">Service Date ID</label>
            <input type="text" class="form-control" name="service_date_id" value="<?php echo $timeToEdit['service_date_id']; ?>" required>
        </div>
        <button type="submit" name="edit_time" class="btn btn-success">Save Changes</button>
        <button type="button" class="btn btn-secondary" onclick="toggleForm('edit-time-form')">Cancel</button>
    </form>
</div>
<?php } ?>

            <?php } ?>
        </div>
    </div>

    <script>
        function toggleForm(formId) {
            document.querySelectorAll('.form-inline').forEach(function (form) {
                if (form.id === formId) {
                    form.classList.toggle('active');
                } else {
                    form.classList.remove('active');
                }
            });
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

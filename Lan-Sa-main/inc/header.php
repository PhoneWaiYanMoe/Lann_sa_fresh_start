<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session if not already started
}
require_once 'dbcon.php';

// Fetch logo path
$sql = "SELECT logo_path FROM logo_settings ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $logo_path = 'admin/' . $row['logo_path']; // Correct the path with 'admin/' prefix
} else {
    $logo_path = "images/Logo/Lo.jpg"; // Default logo path (adjust this as needed)
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php if (isset($page_title)) { echo "$page_title"; } ?> - Logo Name
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .logo-circle {
            width: 50px; /* Adjust the size as needed */
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white px-lg-3 py-lg-2 shadow-sm sticky-top">
        <div class="container-fluid">
        <a class="navbar-brand me-5" href="index.php">
    <div class="logo-circle">
        <img src="<?php echo htmlspecialchars($logo_path); ?>" alt="Logo" class="logo-image">
    </div>
</a>


            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active me-2" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-2" href="booking.php">Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-2" href="services_display.php">Service</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-2" href="index.php#contact">Contact us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-2" href="about.php">About</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
                        <a href="edit_profile.php" class="btn btn-outline-dark shadow-none me-lg-3 me-2">Edit Profile</a>
                        <?php
                        $profile_picture = isset($_SESSION['profile_picture']) && !empty($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'uploads/default_image.svg';
                        ?>
                        <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="rounded-circle" style="width: 40px; height: 40px;">
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-dark shadow-none me-lg-3 me-2">Login</a>
                        <a href="register.php" class="btn btn-outline-dark shadow-none">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

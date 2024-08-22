<?php
session_start();
require_once 'dbcon.php';

// Ensure user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: login.php');
    exit();
}

// Ensure user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    die('User ID not set in session.');
}

$user_id = $_SESSION['user_id'];
$message = '';

// Fetch current user information
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Error executing query: ' . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($result);

if (!$user) {
    die('User not found.');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $user['password'];
    $profile_picture = $user['profile_picture'];

    // Handle profile picture upload
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $profile_picture = $target_file;
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    }

    // Update user information in the database
    $sql = "UPDATE users SET name='$name', password='$password', profile_picture='$profile_picture' WHERE id=$user_id";
    if (mysqli_query($conn, $sql)) {
        $message = "Profile updated successfully.";
        
        // Update session profile picture
        $_SESSION['profile_picture'] = $profile_picture;

        // Redirect to index.php
        header('Location: index.php');
        exit();
    } else {
        $message = "Error updating profile: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #4caf50;
            border-color: #4caf50;
        }
        .btn-primary:hover {
            background-color: #388e3c;
            border-color: #388e3c;
        }
        .btn-outline-dark {
            border-color: #4caf50;
        }
        .btn-outline-dark:hover {
            background-color: #4caf50;
            border-color: #4caf50;
            color: #fff;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body class="bg-light">

    <?php include('inc/header.php'); ?>

    <div class="container profile-container">
        <h2 class="text-center mb-4">Edit Profile</h2>
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="edit_profile.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="password" name="password">
                <small class="form-text text-muted">Leave blank to keep current password.</small>
            </div>
            <div class="mb-3">
                <label for="profile_picture" class="form-label">Profile Picture</label>
                <input class="form-control" type="file" id="profile_picture" name="profile_picture">
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Update Profile</button>
                <a href="logout.php" class="btn btn-outline-dark shadow-none">Logout</a>
            </div>
        </form>
    </div>

    <?php include('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

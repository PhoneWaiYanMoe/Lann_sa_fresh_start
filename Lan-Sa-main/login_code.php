<?php
session_start();
include('dbcon.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    echo "Entered Email: $email<br>";
    echo "Entered Password: $password<br>";

    $query = "SELECT id, name, password, profile_picture, is_admin FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        $_SESSION['status'] = "Database error: " . mysqli_error($conn);
        header("Location: login.php");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $user_id, $username, $hashed_password, $profile_picture, $is_admin);
    
    if (mysqli_stmt_fetch($stmt)) {
        echo "User ID: $user_id<br>";
        echo "Username: $username<br>";
        echo "Is Admin: $is_admin<br>";
    } else {
        echo "No user found with that email.";
        $_SESSION['status'] = "No Account Found with this Email";
        header("Location: login.php");
        exit();
    }
    mysqli_stmt_close($stmt);

    if (password_verify($password, $hashed_password)) {
        echo "Password verified!";
        
        // Set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user_id; // Store the user ID in the session
        $_SESSION['username'] = $username; // Store the username in the session
        $_SESSION['profile_picture'] = $profile_picture; // Store the profile picture URL in the session

        // Check if the user is an admin
        if ($is_admin) {
            $_SESSION['is_admin'] = true; // Mark the user as admin
            header("Location: admin/dashboard.php"); // Redirect to admin dashboard
        } else {
            header("Location: index.php"); // Redirect to regular user dashboard
        }
        exit();
    } else {
        echo "Password verification failed.";
        $_SESSION['status'] = "Invalid login credentials.";
        header("Location: login.php");
        exit();
    }
}
?>

<?php
session_start();
include('dbcon.php');

// Debugging function
function debug($message) {
    echo "<pre>DEBUG: $message</pre>";
}

if (isset($_POST['verify_btn'])) {
    $otp = $_POST['otp'];
    debug("Received OTP: $otp");

    if (empty($otp)) {
        $_SESSION['status'] = "No OTP provided!";
        debug('No OTP provided.');
        header("Location: verify_otp.php");
        exit();
    }

    // Verify OTP using prepared statement to prevent SQL injection
    $query = "SELECT * FROM users WHERE otp = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        debug('Failed to prepare statement: ' . mysqli_error($conn));
        $_SESSION['status'] = "Database error.";
        header("Location: verify_otp.php");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $otp);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        debug('OTP matched.');

        // OTP is correct, update user's status to confirmed
        $updateQuery = "UPDATE users SET otp = '', is_confirmed = 1 WHERE otp = ?";
        $updateStmt = mysqli_prepare($conn, $updateQuery);
        if (!$updateStmt) {
            debug('Failed to prepare update statement: ' . mysqli_error($conn));
            $_SESSION['status'] = "Database error.";
            header("Location: verify_otp.php");
            exit();
        }
        mysqli_stmt_bind_param($updateStmt, "s", $otp);
        if (mysqli_stmt_execute($updateStmt)) {
            $_SESSION['status'] = "OTP verified! Your registration is complete.";
            debug('OTP verification successful.');
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['status'] = "OTP verification failed! Please try again.";
            debug('OTP verification failed during update: ' . mysqli_error($conn));
            header("Location: verify_otp.php");
            exit();
        }
    } else {
        $_SESSION['status'] = "Invalid OTP! Please try again.";
        debug('Invalid OTP.');
        header("Location: verify_otp.php");
        exit();
    }
} else {
    $_SESSION['status'] = "Invalid request method.";
    debug('Invalid request method.');
    header("Location: verify_otp.php");
    exit();
}
?>

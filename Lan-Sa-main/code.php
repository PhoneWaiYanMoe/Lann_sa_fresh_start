<?php
session_start();
include('dbcon.php');

// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';  // Ensure this path is correct

// Process registration
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['password'], $_POST['confirm_password'])) {
        // Retrieve form data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        // Validate form data
        if ($password !== $confirmPassword) {
            $_SESSION['status'] = "Passwords do not match.";
            header("Location: register.php");
            exit;
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Generate OTP
        $otp = mt_rand(100000, 999999);

        // Check if email already exists
        $checkEmail = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $checkEmail);
        if (!$stmt) {
            $_SESSION['status'] = "Database error: " . mysqli_error($conn);
            header("Location: register.php");
            exit;
        }
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $_SESSION['status'] = "Email already registered.";
            header("Location: register.php");
            exit;
        }
        mysqli_stmt_close($stmt);

        // Insert user data into the database
        $insertUser = "INSERT INTO users (name, email, phone, password, otp) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertUser);
        if (!$stmt) {
            $_SESSION['status'] = "Database error: " . mysqli_error($conn);
            header("Location: register.php");
            exit;
        }
        mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $phone, $hashedPassword, $otp);

        if (mysqli_stmt_execute($stmt)) {
            // Send OTP email
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // Set your SMTP server here
                $mail->SMTPAuth = true;
                $mail->Username = 'wyan40653@gmail.com';  // SMTP username
                $mail->Password = 'ifcm honk yhcy xskp';  // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                //Recipients
                $mail->setFrom('wyan40653@gmail.com', 'Your Name');
                $mail->addAddress($email, $name);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP Code';
                $mail->Body = "Your OTP code is <strong>$otp</strong>.";

                $mail->send();
                $_SESSION['status'] = "Registration successful! Please check your email for the OTP.";
                header("Location: verify_otp.php");
                exit;
            } catch (Exception $e) {
                $_SESSION['status'] = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
                header("Location: register.php");
                exit;
            }
        } else {
            $_SESSION['status'] = "Database error: " . mysqli_error($conn);
            header("Location: register.php");
            exit;
        
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['status'] = "All fields are required.";
        header("Location: register.php");
        exit;
    }
} else {
    $_SESSION['status'] = "Invalid request method.";
    header("Location: register.php");
    exit;
}
?>

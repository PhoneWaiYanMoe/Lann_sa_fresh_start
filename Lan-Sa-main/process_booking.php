
<?php
session_start();
require 'dbcon.php';
require 'vendor/autoload.php'; // For PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['status'] = "Invalid request method.";
    header("Location: booking.php");
    exit();
}

$userId = $_SESSION['user_id']; // Assume user ID is stored in session after login
$userQuery = $conn->prepare("SELECT email, name FROM users WHERE id = ?");
$userQuery->bind_param("i", $userId);
$userQuery->execute();
$userResult = $userQuery->get_result();
$userData = $userResult->fetch_assoc();
$userQuery->close();

$userEmail = $userData['email'];
$userName = $userData['name'];

$availableDate = $_POST['availableDate'];
$availableTime = $_POST['availableTime'];
$chooseCountry = $_POST['chooseCountry'];
$chooseService = $_POST['chooseService'];
$servicePrice = $_POST['servicePrice'];
$chooseCounselor = $_POST['chooseCounselor'];
$confirmation = $_POST['confirmation'];

if ($confirmation === 'yes') {
    // Insert into booking_history
    $insertHistoryQuery = $conn->prepare("INSERT INTO booking_history (user_id, user_email, booking_date, booking_time, counselor_name, country_name, service_name, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $insertHistoryQuery->bind_param("issssssd", $userId, $userEmail, $availableDate, $availableTime, $chooseCounselor, $chooseCountry, $chooseService, $servicePrice);
    $insertHistoryQuery->execute();
    $insertHistoryQuery->close();

    // Proceed with sending email
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'wyan40653@gmail.com'; // Your email address
    $mail->Password = 'ifcm honk yhcy xskp'; // Your email password or app-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('lannsa.org@gmail.com', 'LANSA Booking');
        $mail->addAddress($userEmail);

        $mail->isHTML(true);
        $mail->Subject = 'Booking Confirmation';
        $mail->Body = "Dear $userName,<br><br>Your booking for $chooseService on $availableDate at $availableTime has been confirmed.<br><br>Thank you,<br>LANSA Team";

        if ($mail->send()) {
            $_SESSION['status'] = "Booking successful! A confirmation email has been sent to your email address.";
        } else {
            $_SESSION['status'] = "Booking confirmed, but the confirmation email could not be sent.";
        }
    } catch (Exception $e) {
        $_SESSION['status'] = "Booking confirmed, but the confirmation email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    $_SESSION['status'] = "Booking was not confirmed.";
}

header("Location: booking_confirmation.php");
exit();
?>

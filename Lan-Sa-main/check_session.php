<?php
session_start();
include('dbcon.php'); // Include the database connection

$response = array('auth' => false);

if (isset($_SESSION['auth']) && $_SESSION['auth'] === true && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Query to check if the user exists in the database
    $query = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $query->bind_param("i", $userId);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        // If the user is found in the database, they are authenticated
        $response['auth'] = true;
    }
    $query->close();
}

$conn->close();
echo json_encode($response);
?>

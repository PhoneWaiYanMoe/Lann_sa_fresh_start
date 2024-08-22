<?php
// Start the session
session_start();
include('dbcon.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        echo "<script>alert('You must be logged in to submit a review.'); window.location.href = 'login.php';</script>";
        exit;
    }

    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $review_text = isset($_POST['review_text']) ? $_POST['review_text'] : '';

    if ($rating > 0 && !empty($review_text)) {
        $query = "INSERT INTO user_ratings (user_id, rating, review_text, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iis", $user_id, $rating, $review_text);

        if ($stmt->execute()) {
            echo "<script>document.getElementById('feedbackMessage').style.display = 'block';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = window.location.href;</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Please fill out all fields correctly.'); window.location.href = window.location.href;</script>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Your Review</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .custom-bg {
            background-color: #4caf50;
        }
        .custom-bg:hover {
            background-color: #388e3c;
        }
        .form-label {
            font-weight: 500;
        }
        .container {
            max-width: 500px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn {
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 25px;
        }
        .alert {
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body class="bg-light">

    <?php include('inc/header.php'); ?>

    <div class="container mt-4">
        <h5 class="mb-4 text-center">Submit Your Review</h5>
        <form id="reviewForm" action="review.php" method="POST">
            <div class="form-group mb-3">
                <label class="form-label">Rating</label>
                <select class="form-select shadow-none" name="rating" required>
                    <option value="">Select Rating</option>
                    <option value="5">5 - Excellent</option>
                    <option value="4">4 - Very Good</option>
                    <option value="3">3 - Good</option>
                    <option value="2">2 - Fair</option>
                    <option value="1">1 - Poor</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Review</label>
                <textarea class="form-control shadow-none" name="review_text" rows="4" required></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn text-purple custom-bg shadow-none">Submit Review</button>
            </div>
        </form>
        <div id="feedbackMessage" class="alert alert-success text-center" style="display: none;">
            Your review has been submitted successfully.
        </div>
    </div>

    <?php include('inc/footer.php'); ?>

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

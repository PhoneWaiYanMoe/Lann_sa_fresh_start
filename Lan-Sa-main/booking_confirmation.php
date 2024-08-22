<?php
session_start();
include('inc/header.php'); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LANSA - Booking Confirmation</title>
    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-6 mx-auto bg-white shadow p-4 rounded">
                <?php if (isset($_SESSION['status'])): ?>
                    <div class="alert alert-success">
                        <?php echo $_SESSION['status']; unset($_SESSION['status']); ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger">
                        Something went wrong. Please try again.
                    </div>
                <?php endif; ?>
                <a href="index.php" class="btn btn-primary">Back to Home</a>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>
</body>

</html>

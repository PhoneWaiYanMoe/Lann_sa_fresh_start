<?php
session_start();
include('dbcon.php');

// Fetch all services
$serviceQuery = $conn->prepare("SELECT service_name, image_path, price FROM services");
$serviceQuery->execute();
$serviceResult = $serviceQuery->get_result();

// Close the service query statement
$serviceQuery->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LANSA - SERVICES</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .pop:hover {
            border-top-color: var(--teal) !important;
            transform: scale(1.03);
            transition: all 0.3s;
        }
        .service-img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">All Available Services</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Choose a service you would like to book.
        </p>
    </div>

    <div class="container">
        <div class="row">
            <?php if ($serviceResult->num_rows > 0): ?>
                <?php while ($service = $serviceResult->fetch_assoc()): ?>
                    <?php
                        // Correctly format and escape the image path
                        $imagePath = htmlspecialchars(trim($service['image_path']));
                        $correctedImagePath = 'admin/' . $imagePath;
                    ?>
                    <div class="col-lg-4 col-md-6 mb-5 px-4">
                        <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                                <img src="<?php echo $correctedImagePath; ?>" class="service-img" alt="<?php echo htmlspecialchars($service['service_name']); ?>">
                                <div class="mt-3">
                                    <h5><?php echo htmlspecialchars($service['service_name']); ?></h5>
                                    <p class="mb-0">Price: $<?php echo number_format($service['price'], 2); ?></p>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No services available.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

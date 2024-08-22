<?php
session_start();
include('dbcon.php');

// Get the country from the URL or set it to 'Unknown Country' if not provided
$country = isset($_GET['country']) ? $_GET['country'] : 'Unknown Country';

// Fetch country ID based on the country name
$countryQuery = $conn->prepare("SELECT id FROM countries WHERE name = ?");
$countryQuery->bind_param("s", $country);
$countryQuery->execute();
$countryResult = $countryQuery->get_result();
$countryData = $countryResult->fetch_assoc();
$countryId = $countryData['id'] ?? 0;

// Close the country query statement
$countryQuery->close();

// Fetch services for the selected country
$serviceQuery = $conn->prepare("SELECT service_name, image_path, price FROM services WHERE country_id = ?");
$serviceQuery->bind_param("i", $countryId);
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
    <link rel="stylesheet" href="path_to_your_bootstrap_css">
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
        <h2 class="fw-bold h-font text-center">Our Services in <?php echo htmlspecialchars($country); ?></h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Choose a service you would like to book in <?php echo htmlspecialchars($country); ?>.
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
                            <a href="booking.php?country=<?php echo urlencode($country); ?>&service=<?php echo urlencode($service['service_name']); ?>">
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
                <p class="text-center">No services available for <?php echo htmlspecialchars($country); ?>.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>

    <script src="path_to_your_bootstrap_js"></script>
</body>

</html>
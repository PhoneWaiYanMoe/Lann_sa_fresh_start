<?php
session_start();
include('dbcon.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle the form submission
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        // Insert the form data into the database
        $stmt = $conn->prepare("INSERT INTO contact_us (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);

        if ($stmt->execute()) {
            echo "<script>alert('Your message has been sent successfully!');</script>";
        } else {
            echo "<script>alert('There was an error submitting your message. Please try again later.');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}
// Fetch countries and their images
$countryQuery = "SELECT id, name, image_path FROM countries ORDER BY name ASC";
$countryResult = $conn->query($countryQuery);

if ($countryResult === false) {
    die("Error fetching countries: " . $conn->error);
}

// Fetch carousel images
$carouselQuery = "SELECT image_path FROM content_management WHERE type = 'carousel_image' ORDER BY position ASC";
$carouselResult = $conn->query($carouselQuery);

if ($carouselResult === false) {
    die("Error fetching carousel images: " . $conn->error);
}

// Fetch title
$titleQuery = "SELECT content FROM content_management WHERE type = 'title' ORDER BY position ASC LIMIT 1";
$titleResult = $conn->query($titleQuery);

if ($titleResult === false) {
    die("Error fetching title: " . $conn->error);
}

$title = $titleResult->fetch_assoc()['content'] ?? '';

// Fetch text
$textQuery = "SELECT content FROM content_management WHERE type = 'text' ORDER BY position ASC";
$textResult = $conn->query($textQuery);

if ($textResult === false) {
    die("Error fetching text: " . $conn->error);
}

$text = '';
while ($row = $textResult->fetch_assoc()) {
    $text .= $row['content'] . "\n\n";
}
$result = $conn->query("SELECT * FROM webinars LIMIT 1");
$webinar = $result->fetch_assoc();

// Determine the image path
$imagePath = isset($webinar['image_path']) ? $webinar['image_path'] : 'default.jpg'; // Use a default image if none is set

// Debugging: Output the webinar array to verify the correct image is being fetched
echo "<!-- Debugging: Webinar Array: ";
var_export($webinar);
echo " -->";
// Fetch reviews for the testimonial slider
$reviewQuery = "SELECT r.rating, r.review_text, u.name, u.profile_picture 
                FROM user_ratings r 
                JOIN users u ON r.user_id = u.id";
$reviewResult = $conn->query($reviewQuery);

if ($reviewResult === false) {
    die("Error fetching reviews: " . $conn->error);
}


// Fetch contact information
$sql = "SELECT phone_number,email, facebook_url, instagram_url, linkedin_url FROM contact_info WHERE id = 1";
$result = $conn->query($sql);

// Check if data is available
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $phone_number = $row['phone_number'];
    $email = $row['email'];
    $facebook_url = $row['facebook_url'];
    $instagram_url = $row['instagram_url'];
    $linkedin_url = $row['linkedin_url'];

} else {
    $phone_number = "+000000";
    $email = "www@gmail.com";
    $facebook_url = "#";
    $instagram_url = "#";
    $linkedin_url = "#";

}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LANSA - Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merienda:wght@300..900&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/common.css">
    <style>
        .availability-form {
            margin-top: -50px;
            z-index: 2;
            position: relative;
        }

        @media screen and (max-width: 575px) {
            .availability-form {
                margin-top: -50px;
                padding: 0 35px;
            }
        }

        .justified-text {
            text-align: justify;
            text-align-last: left;
            margin: 0;
            line-height: 1.5;
        }
    </style>
</head>
<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <!-- Carousel -->
    <div class="container-fluid px-lg-4 mt-4">
        <div class="swiper swiper-container w-100 d-block">
            <div class="swiper-wrapper">
                <?php while ($row = $carouselResult->fetch_assoc()): ?>
                    <?php 
                        $imagePath = htmlspecialchars(trim($row['image_path']));
                        $correctedImagePath = 'admin/' . $imagePath;
                    ?>
                    <div class="swiper-slide">
                        <img src="<?php echo $correctedImagePath; ?>" class="w-100 d-block" alt="Carousel Image"/>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- Intro of Web -->
    <div class="container availability-form">
        <div class="row">
            <div class="col-lg-12 bg-white shadow p-4 rounded">
                <h2 class="pt-4 mb-4 text-center fw-bold h-font">
                    <?php echo htmlspecialchars($title); ?>
                </h2>
                <div class="p-4">
                    <p class="justified-text">
                        <?php echo nl2br(htmlspecialchars($text)); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
<!-- Available Services (Countries) -->
<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Available Services</h2>
<div class="container">
    <div class="row">
        <?php while ($country = $countryResult->fetch_assoc()): ?>
            <?php
                // Correctly format and escape the image path
                $imagePath = htmlspecialchars(trim($country['image_path']));
                $correctedImagePath = 'admin/' . $imagePath;
            ?>
            <div class="col-lg-4 col-md-6 my-3">
                <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
                    <a href="services.php?country=<?php echo urlencode($country['name']); ?>">
                        <img src="<?php echo $correctedImagePath; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($country['name']); ?>">
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>


 <!-- Webinar -->
<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Register Webinar</h2>
<div class="container">
    <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
        <?php
            // Correctly format and escape the image path
            $imagePath = htmlspecialchars(trim($webinar['image_path']));
            $correctedImagePath = 'admin/' . $imagePath;
        ?>
        <img src="<?php echo $correctedImagePath; ?>" width="80px" alt="Webinar Image">
    </div>
    <div class="mt-4 text-center">
        <a href="<?php echo htmlspecialchars($webinar['link']); ?>" class="btn btn-sm text-black custom-bg shadow-none">Register Webinar Here</a>
    </div>
</div>
    <!-- Testimonials -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">REVIEWS</h2>
    <div class="container mt-5">
        <div class="swiper swiper-testimonials">
            <div class="swiper-wrapper mb-5">
                <?php while ($reviewRow = $reviewResult->fetch_assoc()): ?>
                    <?php 
                        $stars = '';
                        for ($i = 0; $i < $reviewRow['rating']; $i++) {
                            $stars .= '<i class="bi bi-star-fill text-warning"></i>';
                        }
                        for ($i = $reviewRow['rating']; $i < 5; $i++) {
                            $stars .= '<i class="bi bi-star-fill text-secondary"></i>';
                        }
                    ?>
                    <div class="swiper-slide bg-white p-4">
                 

<!-- Dynamic PHP version -->
<div class="profile d-flex align-items-center mb-3">
    <img src="<?php echo htmlspecialchars($reviewRow['profile_picture']); ?>" width="30px" height="30px" class="rounded-circle" style="object-fit: cover;">
    <h6 class="m-0 ms-2"><?php echo htmlspecialchars($reviewRow['name']); ?></h6>
</div>

                        <p>
                            <?php echo htmlspecialchars($reviewRow['review_text']); ?>
                        </p>
                        <div class="rating">
                            <span class="badge rounded-pill bg-light">
                                <?php echo $stars; ?>
                            </span>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
        <div class="col-lg-12 text-center mt-5">
            <a href="review.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Give Feedback >>></a>
        </div>   
    </div>

    <!-- Reach Us Section -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font" id="contact">REACH US</h2>
    <div class="container">
        <div class="row justify-content-center">

    
<!-- HTML for displaying contact details -->
<div class="col-lg-4 col-md-6">
    <div class="bg-white p-4 rounded mb-4">
        <h5>Call us</h5>
        <a href="tel:<?php echo $phone_number; ?>" class="d-inline-block mb-2 text-decoration-none text-dark">
            <i class="bi bi-telephone-fill"></i> <?php echo $phone_number; ?>
        </a>
        <br>
        <a href="mailto:<?php echo $email; ?>" class="d-inline-block text-decoration-none text-dark">
            <i class="bi bi-envelope-fill"></i> <?php echo $email; ?>
        </a>
    </div>
    <div class="bg-white p-4 rounded mb-4">
        <h5>Follow us</h5>
        <a href="<?php echo $linkedin_url; ?>" class="d-inline-block mb-3">
            <span class="badge bg-light text-dark fs-6 p-2">
                <i class="bi bi-linkedin me-1"></i> LinkedIn
            </span>
        </a>
        <br>
        <a href="<?php echo $facebook_url; ?>" class="d-inline-block mb-3">
            <span class="badge bg-light text-dark fs-6 p-2">
                <i class="bi bi-facebook me-1"></i> Facebook
            </span>
        </a>
        <br>
        <a href="<?php echo $instagram_url; ?>" class="d-inline-block">
            <span class="badge bg-light text-dark fs-6 p-2">
                <i class="bi bi-instagram me-1"></i> Instagram
            </span>
        </a>
    </div>
</div>

            <div class="col-lg-6 col-md-6">
                <div class="bg-white rounded shadow p-4">
                    <form method="post" action="">
                        <h5>Send a message</h5>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500">Name</label>
                            <input type="text" name="name" class="form-control shadow-none" required>
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500">Email</label>
                            <input type="email" name="email" class="form-control shadow-none" required>
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500">Subject</label>
                            <input type="text" name="subject" class="form-control shadow-none" required>
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500">Message</label>
                            <textarea name="message" class="form-control" rows="5" style="resize: none;" required></textarea>
                        </div>
                        <button type="submit" class="btn text-white custom-bg mt-3">SEND</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php')?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".swiper-container", {
            spaceBetween: 30,
            effect: "fade",
            loop: true,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            }
        });

        var swiper = new Swiper('.swiper-testimonials', {
            effect: 'coverflow',
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "auto",
            loop: true,
            coverflowEffect: {
                rotate: 50,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows: false,
            },
            pagination: {
                el: '.swiper-pagination',
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                },
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            }
        });
    </script>
</body>
</html>
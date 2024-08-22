<?php
session_start();
require_once 'dbcon.php';

// Fetch general information
$generalInfoQuery = "SELECT key_name, content FROM general_info";
$generalInfoResult = $conn->query($generalInfoQuery);
$generalInfo = [];
if ($generalInfoResult->num_rows > 0) {
    while ($row = $generalInfoResult->fetch_assoc()) {
        $generalInfo[$row['key_name']] = $row['content'];
    }
}

// Fetch team members
$teamMembersQuery = "SELECT name, role, image, description, team FROM team_members";
$teamMembersResult = $conn->query($teamMembersQuery);
$teamMembers = [];
if ($teamMembersResult->num_rows > 0) {
    while ($row = $teamMembersResult->fetch_assoc()) {
        $teamMembers[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LANSA - ABOUT</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php require('inc/links.php'); ?>
    <style>
        .box {
            border-top-color: var(--teal) !important;
        }
    </style>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">ABOUT US</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            <?php echo htmlspecialchars($generalInfo['big_sentence']); ?>
        </p>
    </div>

    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
                <h3 class="mb-3"><?php echo htmlspecialchars($generalInfo['section_heading']); ?></h3>
                <p>
                    <?php echo htmlspecialchars($generalInfo['slogan']); ?>
                </p>
            </div>
            <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
                <!-- Correctly format the image path -->
                <img src="images/about/about.jpeg" class="w-100" alt="About Us">
            </div>
        </div>
    </div>

    <h3 class="my-5 fw-bold h-font text-center">FOUNDER TEAM</h3>
    <div class="container px-4">
    <div class="swiper mySwiper">
        <div class="swiper-wrapper mb-5">
            <?php
            foreach ($teamMembers as $member) {
                if ($member['team'] == 'Management') {
                    // Escape image path and other data
                    $imagePath = htmlspecialchars(trim($member['image']));
                    // Correctly format and prepend the directory path
                    $correctedImagePath = 'admin/' . $imagePath;
                    echo '
                    <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                        <img src="' . $correctedImagePath . '" class="w-100" alt="' . htmlspecialchars($member['name']) . '">
                        <h5 class="mt-2">' . htmlspecialchars($member['name']) . '</h5>
                        <p>' . htmlspecialchars($member['role']) . '</p>
                        <p>' . htmlspecialchars($member['description']) . '</p>
                    </div>
                    ';
                }
            }
            ?>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>

    <h3 class="my-5 fw-bold h-font text-center">IT TEAM</h3>
    <div class="container px-4">
    <div class="swiper mySwiper">
        <div class="swiper-wrapper mb-5">
            <?php
            foreach ($teamMembers as $member) {
                if ($member['team'] == 'IT') {
                    // Escape image path and other data
                    $imagePath = htmlspecialchars(trim($member['image']));
                    // Correctly format and prepend the directory path
                    $correctedImagePath = 'admin/' . $imagePath;
                    echo '
                    <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                        <img src="' . $correctedImagePath . '" class="w-100" alt="' . htmlspecialchars($member['name']) . '">
                        <h5 class="mt-2">' . htmlspecialchars($member['name']) . '</h5>
                        <p>' . htmlspecialchars($member['role']) . '</p>
                        <p>' . htmlspecialchars($member['description']) . '</p>
                    </div>
                    ';
                }
            }
            ?>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>

            <div class="swiper-pagination"></div>
        </div>
    </div>

    <?php require('inc/footer.php') ?>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <script>
        var swiper = new Swiper('.mySwiper', {
            slidesPerView: 4,
            spaceBetween: 40,
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
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 3,
                },
            }
        });
    </script>

</body>

</html>

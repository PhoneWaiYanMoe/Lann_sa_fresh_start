<?php
session_start();
include('dbcon.php');

// Retrieve the country and service from the URL parameters
$country = isset($_GET['country']) ? $_GET['country'] : 'Unknown Country';
$service = isset($_GET['service']) ? $_GET['service'] : 'Unknown Service';


// Fetch the country ID based on the country name
$countryQuery = $conn->prepare("SELECT id FROM countries WHERE name = ?");
$countryQuery->bind_param("s", $country);
$countryQuery->execute();
$countryResult = $countryQuery->get_result();
$countryData = $countryResult->fetch_assoc();
$countryId = $countryData['id'] ?? 0;
$countryQuery->close();

// Fetch the service ID and price based on the selected service and country
$serviceQuery = $conn->prepare("SELECT id, price FROM services WHERE country_id = ? AND service_name = ?");
$serviceQuery->bind_param("is", $countryId, $service);
$serviceQuery->execute();
$serviceResult = $serviceQuery->get_result();
$serviceData = $serviceResult->fetch_assoc();
$serviceId = $serviceData['id'] ?? 0;
$price = $serviceData['price'] ?? 0;
$serviceQuery->close();

// Fetch available dates for the selected service
$datesQuery = $conn->prepare("SELECT id, available_date FROM service_dates WHERE service_id = ?");
$datesQuery->bind_param("i", $serviceId);
$datesQuery->execute();
$datesResult = $datesQuery->get_result();
$availableDates = $datesResult->fetch_all(MYSQLI_ASSOC);
$datesQuery->close();



// Fetch available times for the selected service date (use first available date by default)
$availableTimes = [];
if (!empty($availableDates)) {
    $firstDateId = $availableDates[0]['id'];
    $timesQuery = $conn->prepare("SELECT id, available_time FROM service_times WHERE service_date_id = ?");
    $timesQuery->bind_param("i", $firstDateId);
    $timesQuery->execute();
    $timesResult = $timesQuery->get_result();
    $availableTimes = $timesResult->fetch_all(MYSQLI_ASSOC);
    $timesQuery->close();
}

// Fetch counselors for the selected service time (use first available time by default)
$counselors = [];
if (!empty($availableTimes)) {
    $firstTimeId = $availableTimes[0]['id'];
    $counselorsQuery = $conn->prepare("SELECT counselor_name FROM service_counselors WHERE service_time_id = ?");
    $counselorsQuery->bind_param("i", $firstTimeId);
    $counselorsQuery->execute();
    $counselorsResult = $counselorsQuery->get_result();
    $counselors = $counselorsResult->fetch_all(MYSQLI_ASSOC);
    $counselorsQuery->close();
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LANSA - BOOKING</title>
    <?php require('inc/links.php'); ?>
    <style>
        .custom-bg {
            background-color: #6f42c1;
        }
        .custom-bg:hover {
            background-color: #5a3390;
        }
    </style>
</head>

<body class="bg-light">
<?php require('inc/header.php'); ?>

    <div class="container availability-form mt-4">
        <div class="row">
            <div class="col-lg-6 mx-auto bg-white shadow p-4 rounded">
                <h5 class="mb-4 text-center">Check Booking Availability</h5>
                <form id="bookingForm" action="process_booking.php" method="POST">
                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight: 500;">Available Date</label>
                        <select class="form-select shadow-none" name="availableDate" id="availableDate">
                            <?php foreach ($availableDates as $date): ?>
                                <option value="<?php echo $date['available_date']; ?>"><?php echo $date['available_date']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight: 500;">Available Time</label>
                        <select class="form-select shadow-none" name="availableTime" id="availableTime">
                            <?php foreach ($availableTimes as $time): ?>
                                <option value="<?php echo $time['available_time']; ?>"><?php echo $time['available_time']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight: 500;">Chosen Country</label>
                        <input type="text" class="form-control shadow-none" name="chooseCountry" value="<?php echo htmlspecialchars($country); ?>" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight: 500;">Chosen Service</label>
                        <input type="text" class="form-control shadow-none" name="chooseService" value="<?php echo htmlspecialchars($service); ?>" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight: 500;">Price</label>
                        <input type="text" class="form-control shadow-none" name="servicePrice" id="servicePrice" value="<?php echo number_format($price, 2); ?>" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight: 500;">Choose your counselor</label>
                        <select class="form-select shadow-none" name="chooseCounselor" id="chooseCounselor">
                            <?php foreach ($counselors as $counselor): ?>
                                <option value="<?php echo htmlspecialchars($counselor['counselor_name']); ?>"><?php echo htmlspecialchars($counselor['counselor_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="text-center">
                        <button type="button" class="btn btn-sm text-white custom-bg shadow-none" onclick="checkLogin()">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Booking Confirmation Modal -->
    <div class="modal fade" id="bookingconfirm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="modalForm" action="process_booking.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                            Booking Confirmation
                        </h5>
                        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center" id="modalBodyContent">
                        Are you sure to book a session on ....?
                    </div>
                    <div class="container-fluid ms-5">
                        <div class="col-md-6 ps-0 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="confirmation" value="yes" id="yesRadio">
                                <label class="form-check-label" for="yesRadio">
                                    Yes
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 p-0 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="confirmation" value="no" id="noRadio">
                                <label class="form-check-label" for="noRadio">
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="text-center my-1">
                        <button type="submit" class="btn btn-dark shadow-none mb-3 me-2">REGISTER</button>
                        <button type="button" class="btn btn-dark shadow-none mb-3" data-bs-dismiss="modal">CANCEL</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
       function checkLogin() {
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        updateModalContent();
        $('#bookingconfirm').modal('show');
    <?php else: ?>
        window.location.href = 'login.php';
    <?php endif; ?>
}


function updateModalContent() {
    $('#modalForm input[type="hidden"]').remove(); // Clear previous hidden inputs

    const availableDate = $('#availableDate').val();
    const availableTime = $('#availableTime').val();
    const chooseCountry = $('[name="chooseCountry"]').val();
    const chooseService = $('[name="chooseService"]').val();
    const servicePrice = $('[name="servicePrice"]').val();
    const chooseCounselor = $('#chooseCounselor').val();

    // Append hidden fields to the form
    $('#modalForm').append(<input type="hidden" name="availableDate" value="${availableDate}">);
    $('#modalForm').append(<input type="hidden" name="availableTime" value="${availableTime}">);
    $('#modalForm').append(<input type="hidden" name="chooseCountry" value="${chooseCountry}">);
    $('#modalForm').append(<input type="hidden" name="chooseService" value="${chooseService}">);
    $('#modalForm').append(<input type="hidden" name="servicePrice" value="${servicePrice}">);
    $('#modalForm').append(<input type="hidden" name="chooseCounselor" value="${chooseCounselor}">);

    $('#modalBodyContent').text(Are you sure to book a session on ${availableDate} at ${availableTime} for the ${chooseService} service with ${chooseCounselor} in ${chooseCountry}?);
}


    </script>
</body>

</html>
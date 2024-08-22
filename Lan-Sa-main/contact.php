<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TJ Hotel - CONTACT</title>
    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">CONTACT US</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Lorem ipsum dolor sit amet consectetur adipisicing elit.
            Nesciunt voluptas exercitationem debitis pariatur <br> aliquid provident voluptatem eum accusantium dolores consequatur maxime ad repellat
            officiis eligendi nemo commodi magnam rem beatae!
        </p>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-5 px-4">

                <div class="bg-white rounded shadow p-4">
                    <iframe class="w-100 rounded mb-4" height="320px" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d244399.49847404295!2d96.18157255!3d16.83914245!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30c1949e223e196b%3A0x56fbd271f8080bb4!2sYangon%2C%20Myanmar%20(Burma)!5e0!3m2!1sen!2s!4v1722424803429!5m2!1sen!2s" width="600" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

                    <h5>Address</h5>
                    <a href="https://maps.app.goo.gl/sWrp3A2UpCBgnhEE8" target="_blank" class="d-inline-block text-decoration-none text-dark mb-2">
                        <i class="bi bi-geo-alt"></i>XYZ,Yangon,Myanmar 
                    </a>

                    <h5 class="mt-4">Call us</h5>
                    <a href="tel: +000000" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i>+000000
                    </a>
                    <br>
                    <a href="tel: +000000" class="d-inline-block text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i>+000000
                    </a>

                    <h5 class="mt-4">Email</h5>
                    <a href="mailto: phyotheingi850@gmal.com" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-envelope"></i>phyotheingi850@gmail.co
                    </a>

                    <h5 class="mt-4">Follow us</h5>
                    <a href="#" class="d-inline-block text-dark fs-5 me-2">
                        <i class="bi bi-twitter-x me-1"></i>
                    </a>
                    <a href="#" class="d-inline-block text-dark fs-5 me-2">
                        <i class="bi bi-facebook me-1"></i>
                    </a>
                    <a href="#" class="d-inline-block text-dark fs-5">
                        <i class="bi bi-instagram me-1 "></i>
                    </a>

                </div>
            </div>
            <div class="col-lg-6 col-md-6px-4">
                <div class="bg-white rounded shadow p-4">
                    <form>
                        <h5>Send a message</h5>
                        <div class="mt-3">
                            <label class="form-label" sytle="font-weight: 500">Name</label>
                            <input type="text" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" sytle="font-weight: 500">Email</label>
                            <input type="email" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" sytle="font-weight: 500">Subject</label>
                            <input type="text" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" sytle="font-weight: 500">Message</label>
                            <textarea class="form-control" rows="5" style="resize: none;"></textarea>
                        </div>
                        <button type="submit" class="btn text-white custom-bg mt-3">SEND</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php')?>



</body>

</html>
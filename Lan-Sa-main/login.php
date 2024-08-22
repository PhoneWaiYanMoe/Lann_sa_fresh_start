<?php
session_start(); // Start the session
$page_title = "Login";
include('inc/header.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
        }
        .login-page {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
        }
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            background: linear-gradient(to bottom, #ff7e5f, #feb47b);
            color: #fff;
            max-width: 100%;
        }
        .card-header {
            background: #ff7e5f;
            padding: 1.5rem 1rem;
            text-align: center;
            border-bottom: none;
        }
        .card-header h3 {
            margin: 0;
            color: #fff;
        }
        .card-body {
            padding: 2rem;
            background-color: #fff;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
            color: #333;
        }
        .form-group label {
            color: #333;
            margin-bottom: 0.5rem;
        }
        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .form-control:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 5px rgba(106, 17, 203, 0.5);
        }
        .btn-primary {
            background-color: #ff7e5f;
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            transition: background-color 0.3s, color 0.3s;
            border-radius: 5px;
        }
        .btn-primary:hover {
            background-color: #feb47b;
            color: #fff;
        }
        .alert {
            margin-bottom: 1rem;
        }
        .login-footer {
            text-align: center;
            margin-top: 1rem;
        }
        .login-footer a {
            color: #ff7e5f;
        }

        @media (max-width: 576px) {
            .card {
                border-radius: 10px;
            }
            .card-header {
                padding: 1rem;
            }
            .card-body {
                padding: 1.5rem;
            }
            .btn-primary {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
        }
    </style>
</head>
<body>
<div class="login-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <?php
                if (isset($_SESSION['status'])) {
                    echo "<div class='alert alert-danger text-center'>" . $_SESSION['status'] . "</div>"; 
                    unset($_SESSION['status']);
                }
                ?>
                <div class="card">
                    <div class="card-header">
                        <h3>Login</h3>
                    </div>
                    <div class="card-body">
                        <form action="login_code.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" name="login_btn" class="btn btn-primary w-100">Login here</button>
                            </div>
                        </form>
                        <div class="login-footer">
                            <p>Don't have an account? <a href="register.php">Register here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>

<?php include('inc/footer.php'); ?>

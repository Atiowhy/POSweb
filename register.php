<?php
require_once 'config/db.php';

// queryRegister
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = sha1($_POST['password']);

    // register
    $sqlRegis = mysqli_query($connection, "INSERT INTO  user (name, email, password) VALUES ('$name', '$email', '$password')");

    if ($sqlRegis) {
        header('location: login.php?success-register');
    } else {
        header('location: register.php?regis-failed');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/dist/css/bootstrap.min.css">
    <title>Document</title>
</head>

<body>

    <!-- login form -->
    <div class="wrapper d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="title">
                                <h1 class="text-center">Register</h1>
                            </div>
                            <div class="form-login">
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email address</label>
                                        <input type="email" class="form-control" name="email" placeholder="input your email">
                                    </div>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" name="name" placeholder="input your username">
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password" placeholder="****">
                                    </div>
                                    <div class="btn-cta d-flex justify-content-center">
                                        <button class="btn btn-primary" name="register" type="submit">Register</button>
                                    </div>
                                    <div class="login-akun">
                                        <p>Already have an account? <a href="index.php">Login</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/dist/js/bootstrap.min.js"></script>
</body>

</html>
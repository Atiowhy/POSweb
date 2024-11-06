<?php
session_start();
session_regenerate_id();
require_once 'config/db.php';

if (isset($_POST['login'])) {
    $password = sha1($_POST['password']);
    $email = $_POST['email'];

    $sqlLogin = mysqli_query($connection, "SELECT  * FROM user WHERE  email = '$email'");
    if (mysqli_num_rows($sqlLogin) > 0) {
        $rowLogin = mysqli_fetch_assoc($sqlLogin);

        if ($rowLogin['email'] == $email &&  $rowLogin['password'] == $password) {
            $_SESSION['username'] = $rowLogin['username'];
            $_SESSION['email'] = $rowLogin['email'];
            header('location: views/kasir.php?login-success');
            // exit();
        } else {
            header('location: views/login.php?failed-login');
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'inc/head.php' ?>
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
                                <h1 class="text-center">Login</h1>
                            </div>
                            <div class="form-login">
                                <form action="" method="post">

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email address</label>
                                        <input type="email" class="form-control" name="email" placeholder="input your email">
                                    </div>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password" placeholder="***">
                                    </div>
                                    <div class="btn-cta d-flex justify-content-center">
                                        <button class="btn btn-primary col-sm-4" name="login" type="submit">Login</button>
                                    </div>
                                    <div class="login-akun">
                                        <p>Don't have an account? <a href="register.php">Register</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'inc/js.php' ?>
</body>

</html>
<?php

require "var.php";

if(isset($_SESSION["login"])){
    header("Location: index.php");
    die;
}

if(isset($_POST["submit"])){
    login($_POST);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login Page</title>
</head>
<body>
    <div class="container">
        <div class="login_box">
            <h2>LOGIN TO YOUR ACCOUNT!</h2>
            <form action="" method="post">
                <div class="login_row">
                    <div class="login_label_div">
                        <label for="user" class="login_label">Email/Username : </label>
                    </div>
                    <input type="text" name="user" id="user" placeholder="Enter Email or Username..." class="login_input">
                </div>
                <div class="login_row">
                    <div class="login_label_div">
                        <label for="pass" class="login_label">Password : </label>
                    </div>
                    <input type="password" name="pass" id="pass" placeholder="Enter Password..." class="login_input">
                </div>
                <div class="login_remember">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember me!</label>
                </div>


                <a href="registration.php">Don't have an account yet? Click here to register!</a>

                <div class="login_submit_div">
                    <button type="submit" name="submit" id="submit" class="login_submit">Login</button>
                </div>
            </form>
        </div>
    </div>


</body>
</html>
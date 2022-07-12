<?php

require "var.php";

if(isset($_POST["submit"])){
    register($_POST);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Register here!</title>
</head>
<body>

    <div class="container">
        <div class="registration_box">
            <h2>Make your account!</h2>
            <div class="registration_form">
                <form action="" method="post">
                    <div class="registration_row">
                        <label for="email" class="registration_label">Email</label>   
                        <input type="text" class="registration_input" id="email" name="email"> 
                    </div>
                    <div class="registration_row">
                        <label for="user" class="registration_label">Username</label>   
                        <input type="text" class="registration_input" id="user" name="user"> 
                    </div>
                    <div class="registration_row">
                        <label for="pass" class="registration_label">Password</label>   
                        <input type="password" class="registration_input" id="pass" name="pass"> 
                    </div>
                    <div class="registration_row">
                        <label for="con_pass" class="registration_label">Confirm Password</label>   
                        <input type="password" class="registration_input" id="con_pass" name="con_pass"> 
                    </div>
                    <button type="submit" name="submit" class="registration_submit">Register</button>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>
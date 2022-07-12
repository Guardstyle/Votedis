<?php

// Start session
session_start();

// Connect to database using PDO method
$db = new pdo('mysql:host=localhost;dbname=votedis', 'root', '');

// Check cookie
if(isset($_COOKIE['E']) && isset($_COOKIE['M']) && isset($_COOKIE['T'])){
    $userid = $_COOKIE['E'];
    $username = $_COOKIE['M'];
    $unique = $_COOKIE['T'];

    $result = $db->prepare("SELECT * FROM cookies WHERE userid = ?");
    $result->execute([$userid]);
    $rows = $result->fetchAll();
    $row = $rows[0]; 
    // Check if the cookie match the database
    if($row && $row['userid'] == $userid && hash("sha256", $row['username']) == $username && $row['unique_key'] == $unique){
        $_SESSION['login'] = true;
        $_SESSION['activeid'] = $userid;
    } else{
        // Remove Cookie
        setcookie('E', '', time()-3600, '/', null, null, true);
        setcookie('M', '', time()-3600, '/', null, null, true);
        setcookie('T', '', time()-3600, '/', null, null, true); 
    }
}

// Shorten htmlspecialchars syntax
function e($str){
    return htmlspecialchars($str);
}

function register($data){
    global $db;

    $email = strtolower(stripslashes($data["email"]));
    $username = e($data["user"]);
    $password = $data["pass"];
    $password2 = $data["con_pass"];
        
    // Check password confirmation
    if($password !== $password2){
        echo " <script>
            alert('Password confirmation is not equal to the password.\\nPlease try again!');
        </script>";
        return;
    }

    // Check email validity
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo " <script>
            alert('Email is invalid\\nPlease try again!');
        </script>";
        return;
    }

    // Username cannot contain space
    if(str_contains($username, ' ')){
        echo " <script>
            alert('Username is not allowed to contain space character');
        </script>";
        return;
    }

    // Check if email has existed
    $result = $db->prepare("SELECT username FROM users WHERE email = ?");
    $result->execute(["$email"]);
    $rows = $result->fetchAll();
    if($rows){
        echo " <script>
            alert('Your Email has been used by another account!');
        </script>";
        return;
    }

    // Check if username has existed
    $result = $db->prepare("SELECT username FROM users WHERE username = ?");
    $result->execute(["$username"]);
    $rows = $result->fetchAll();
    if($rows){
        echo " <script>
            alert('Username has been taken by another account.\\nPlease pick another username!');
        </script>";
        return;
    }

    // Encrypt password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user to database
    $result = $db->prepare("INSERT INTO users VALUES (?, ?, ?, ?)");
    $result->execute(["", "$email", "$username", "$password"]);

    if($result->rowCount() <= 0){
        $err_msg = $result->errorInfo();
        echo "
        <script>
        alert('Registration failed\\n $err_msg');
        document.location.href = 'registration.php';
        </script>";
    } else {
        echo "
        <script>
        alert('Registration completed');
        document.location.href = 'index.php';
        </script>";
    }

    // Automaticly login after registering
    $result = $db->prepare("SELECT id FROM users WHERE username = ?");
    $result->execute(["$username"]);
    $rows = $result->fetchAll();

    $_SESSION["login"] = true;
    $_SESSION["activeid"] = $rows[0]['id'];

}   

function login($data){
    global $db;

    $email = strtolower(stripslashes($data["user"]));
    $username = strtolower(e($data["user"]));
    $password = $data["pass"];

    // Check Username
    $result = $db->prepare("SELECT * FROM users WHERE LOWER(username) = ?");
    $result->execute(["$username"]);
    $rows_user = $result->fetchAll();

    // If username don't exist
    if(!$rows_user){
        // Check Email
        $result = $db->prepare("SELECT * FROM users WHERE email = ?");
        $result->execute(["$email"]);
        $rows_user = $result->fetchAll();
    }

    // If username and email don't exist, cancel login
    if(!$rows_user){
        echo "<script>
            alert('Incorrect Email/Username or Passoword!');
        </script>";
        return;
    }

    // Check if password correct
    $row = $rows_user[0];
    if(password_verify($password, $row["password"])){
        // Set session
        $_SESSION["login"] = true;
        $_SESSION["activeid"] = $row['id'];

        // Check remember me
        if(isset($data["remember"])){
            // Set time to one month
            $interval = time()+60*60*24*30;

            // Make cookie
            // Userid
            $userid = $row['id'];
            setcookie("E", $userid, $interval, '/', null, null, true);
            // Username
            $enc_user = hash('sha256', $row["username"]);
            setcookie("M", $enc_user, $interval, '/', null, null, true);

            // Check if user cookie exist in database and insert/update cookie database
            $result = $db->prepare("SELECT * FROM cookies WHERE userid = ?");
            $result->execute(["$userid"]);
            $row2 = $result->fetchAll();
            if($row2){
                $row2 = $row2[0];
                // Take cookie unique_key from database
                setcookie("T", $row2['unique_key'], $interval, '/', null, null, true);
            } else {
                // Key (Random string for validation)
                $unique_key = rand();
                $unique_key = md5($unique_key);
                setcookie("T", $unique_key, $interval, '/', null, null, true);

                // Insert cookie to database
                $result = $db->prepare("INSERT INTO cookies VALUES (?, ?, ?, ?)");
                $result->execute(["", "$userid", $row['username'], "$unique_key"]);
            }
        } 

        echo "
        <script>
        alert('Login Success.');
        document.location.href = 'index.php';
        </script>";
    } else {
        echo "
        <script>
        alert('Incorrect Email/Username or Password!');
        document.location.href = 'login.php';
        </script>";
    }

}


?>
<?php
require 'var.php';

// Alert user if users try to add poll before login
if(!isset($_SESSION["login"])){
    header("Location: index.php?addFail=1");
    die;
}

if(isset($_POST["subBtn"])){
    // How many choice are there
    $pollNum = $_POST["pollNum"] + 2;

    // Check if any of the choice description is empty
    $flag = false;
    for($i = 1; $i <= $pollNum; $i++){
        if($_POST["insertchoice$i"] === ""){
            echo "<script>
                alert('choice description can not be empty');
            </script>";
            $flag = true;
            break;
        }
    }

    if(!$flag){
        // Set all variable of empty choice to null
        for($i = $pollNum+1; $i <= 5; $i++){
            $_POST["insertchoice$i"] = null;
        }

        // Put data on Post variable to other variable
        $desc = $_POST["poll_desc"];
        $choice = [$_POST['insertchoice1'],  $_POST['insertchoice2'], $_POST['insertchoice3'],  $_POST['insertchoice4'], $_POST['insertchoice5']];
        $userid = $_SESSION["activeid"];

        // Insert data to database
        $result = $db->prepare("INSERT INTO polls VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $result->execute(["", "$desc", "0", "0", "0", "0", "0", "$choice[0]", "$choice[1]", "$choice[2]", "$choice[3]", "$choice[4]", "$userid"]);

        // Direct to main page
        header("Location: index.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Add poll</title>
</head>
<body>
    <div class="container">
        <div class="insert_box">
            <h1>Add Your Poll</h1>
            <form action="" method="post">
                <input type="hidden" id="pollNum" name="pollNum" value="0">
                <div class="insert_row">
                    <label for="poll_desc">Poll Description: </label>
                    <textarea name="poll_desc" id="poll_desc" row="1" cols="70"></textarea>
                </div>
                <div class="insert_row" id="insertrow1">
                    <label for="insertchoice1">Choice 1: </label>
                    <input type="text" id="insertchoice1" name="insertchoice1">
                </div>
                <div class="insert_row" id="insertrow2">
                    <label for="insertchoice2">Choice 2: </label>
                    <input type="text" id="insertchoice2" name="insertchoice2">
                </div>
                <div class="insert_row" id="insertrow3">
                    <label for="insertchoice3">Choice 3: </label>
                    <input type="text" id="insertchoice3" name="insertchoice3">
                </div>
                <div class="insert_row" id="insertrow4">
                    <label for="insertchoice4">Choice 4: </label>
                    <input type="text" id="insertchoice4" name="insertchoice4">
                </div>
                <div class="insert_row" id="insertrow5">
                    <label for="insertchoice5">Choice 5: </label>
                    <input type="text" id="insertchoice5" name="sinsertchoice5">
                </div>
                <div class="insert_control">
                    <button type="button" id="addBtn" name="addBtn">Add choice!</button>
                    <button type="button" id="delBtn" name="delBtn">Delete choice!</button>
                </div>

                <div class="insert_submit" method="post">
                    <button type="submit" id="subBtn" name="subBtn">Post Poll!</button>
                </div>
            </form>
        </div> 
    </div>

    <script src="js/in_script.js"></script>
</body>
</html>
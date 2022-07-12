<?php
// Include var.php
require "var.php";

if(isset($_GET["addFail"])){
    echo "<script>
        alert('You have to login to add poll');
    </script>";
}

// db query to get the poll
$result = $db->prepare("SELECT * FROM polls");
$result->execute([]);
$rows = $result->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>VoteDis</title>
</head>
<body>
    
    <div class="container">
        <div class="nav">
            <div class="add_poll">
                <a href="insert.php">Add poll!</a>
            </div>
            <div class="logout_div">
                <?php if(isset($_SESSION['login'])): ?>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="header">
            <h1>Votedis</h1>
            <h2>Fastest way to do survey</h2>
        </div>

        <div class="search">
            <h3>Search poll here!</h3>
            <textarea name="search_bar//" id="search_bar" cols="120" rows="1" autofocus></textarea>
        </div>

        <div class="main_section" id="main_section">
            <?php
            $idx = 0;
            foreach($rows as $row):
                $question = $row["description"];
                $item = [];
                $vote = [];

                for($i = 0; $i < 5; $i++){
                    $label = "label$i";
                    $choice = "choice$i";

                    $item[] = $row[$label];
                    $vote[] = $row[$choice];
                }

                $activeChoice = -1;

                if(isset($_SESSION["login"])){
                    $userid = $_SESSION['activeid'];
                    $pollid = $row['id'];

                    $result = $db->prepare("SELECT vote FROM votes WHERE userid = ? AND pollid = ?");
                    $result->execute(["$userid", "$pollid"]);
                    $rows_choice = $result->fetchAll();
                    if($rows_choice) $activeChoice = $rows_choice[0]['vote'];
                }
            ?>
                <div id="secret<?=$idx;?>" class="secret"><?=$row["id"];?></div>
                <div class="voting" id="voting<?=$idx;?>">
                    <p><?=$question;?></p>
                    <form action="" class="polls_section">
                        <?php for($i = 0; $i < 5; $i++):
                            if($item[$i] === null || $item[$i] === ''){
                                break;
                            }
                        ?>
                            <?php if($i !== $activeChoice): ?>
                                <div class="choice">
                                    <button type="button" id="poll<?=$idx;?>choice<?=$i;?>"><?=$item[$i];?></button>
                                    <label for="choice<?=$i;?>" id="poll<?=$idx;?>count<?=$i;?>"><?=$vote[$i];?></label>
                                </div>
                            <?php else : ?>
                                <div class="choice">
                                    <button type="button" id="poll<?=$idx;?>choice<?=$i;?>" class="chosen"><?=$item[$i];?></button>
                                    <label for="choice<?=$i;?>" id="poll<?=$idx;?>count<?=$i;?>"><?=$vote[$i];?></label>
                                </div>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </form>
                </div>
            <?php 
                $idx++;
                endforeach; 
            ?>
        </div>

    </div>

    <script src="js/script.js"></script>
</body>
</html>
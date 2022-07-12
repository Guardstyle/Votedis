<?php

// include var.php
require "../var.php";

// get pollId, choice, and idx;
$pollId = $_GET["pollId"];
$choice = $_GET["choice"];
$idx = $_GET["idx"];

// Get row from database
$result = $db->prepare("SELECT * FROM polls WHERE id = ?");
$result->execute(["$pollId"]);
$row = $result->fetchAll();
$row = $row[0];

// Check if our client hasn't login
if(!isset($_SESSION["login"])):
    $question = $row["description"];
    $item = [];
    $vote = [];

    for($i = 0; $i < 5; $i++){
        $label = "label$i";
        $choice = "choice$i";

        $item[] = $row[$label];
        $vote[] = $row[$choice];
    }
?>
    <p><?=$question;?></p>
    <script> alert("You must login to vote!"); </script>
    <form action="" class="polls_section">
        <?php for($i = 0; $i < 5; $i++):
            if($item[$i] === null || $item[$i] === ''){
                break;
            }
        ?>
            <div class="choice">
                <button type="button" id="poll<?=$idx;?>choice<?=$i;?>"><?=$item[$i];?></button>
                <label for="choice<?=$i;?>" id="poll<?=$idx;?>count<?=$i;?>"><?=$vote[$i];?></label>
            </div>
        <?php endfor; ?>
    </form>
    <p class="login_warning">*You must login to vote</p>
    <?php
    die;
    endif;
    ?>

<?php

// Take userid
$userid = $_SESSION["activeid"];

// Check if our client has voted on this poll before
$result = $db->prepare("SELECT * FROM votes WHERE userid = ? AND pollid = ?");
$result->execute(["$userid", "$pollId"]);
$row_vote = $result->fetchAll();

// Prepare string for mysql
$choiceStr = "choice$choice";

// active choice (voted choice by client)
$activeChoice = -1;

if(!$row_vote){
    $result = $db->prepare("INSERT INTO votes VALUES (?, ?, ?, ?)");
    $result->execute(["", "$userid", "$pollId", "$choice"]);
    $row["$choiceStr"]++;
    $activeChoice = $choice;
} else {
    $row_vote = $row_vote[0];
    $prev_vote = $row_vote["vote"];
    
    // Check if user voted on different choice than before
    if($prev_vote != $choice && $prev_vote != -1){
        $choiceStr2 = "choice$prev_vote";
        $row["$choiceStr"]++;
        $row["$choiceStr2"]--;
        $result = $db->prepare("UPDATE votes SET vote = ? WHERE userid = ? AND pollid = ?");
        $result->execute(["$choice", "$userid", "$pollId"]);
        $activeChoice = $choice;
    } else if($prev_vote == -1){ // Check if user isn't voting on anything yet
        $row["$choiceStr"]++;
        $result = $db->prepare("UPDATE votes SET vote = ? WHERE userid = ? AND pollid = ?");
        $result->execute(["$choice", "$userid", "$pollId"]);
        $activeChoice = $choice;
    } else { // Check if user vote on the previous vote (deactivate vote)
        $row["$choiceStr"]--;
        $result = $db->prepare("UPDATE votes SET vote = ? WHERE userid = ? AND pollid = ?");
        $result->execute(["-1", "$userid", "$pollId"]);
        $activeChoice = -1;
    }
}

$c0 = $row["choice0"];
$c1 = $row["choice1"];
$c2 = $row["choice2"];
$c3 = $row["choice3"];
$c4 = $row["choice4"];

$result = $db->prepare("UPDATE polls SET 
    choice0 = ?,
    choice1 = ?,
    choice2 = ?,
    choice3 = ?,
    choice4 = ?
    WHERE id = ?
");
$result->execute(["$c0", "$c1", "$c2", "$c3", "$c4", "$pollId"]);

$question = $row["description"];
$item = [];
$vote = [];

for($i = 0; $i < 5; $i++){
    $label = "label$i";
    $choice = "choice$i";

    $item[] = $row[$label];
    $vote[] = $row[$choice];
}
?>

<p><?=$question;?></p>
<form action="" class="polls_section">
    <?php for($i = 0; $i < 5; $i++):
        if($item[$i] === null || $item[$i] === ''){
            break;
        }
    ?>
        <?php if($i != $activeChoice): ?>
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
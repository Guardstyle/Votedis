<?php
require "../var.php";

// Get keyword from ajax
$keyword = $_GET["keyword"];

// db query based on keyword
$result = $db->prepare("SELECT * FROM polls WHERE
    description LIKE ? OR
    label0 LIKE ? OR
    label1 LIKE ? OR
    label2 LIKE ? OR
    label3 LIKE ? OR
    label4 LIKE ?
");
$result->execute(["%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%"]);
$rows = $result->fetchAll();

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
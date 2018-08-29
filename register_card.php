<?php

function register_card($SN, $uid) {
    $servername = "localhost";
    include("saatlant_tally.php");
    $dbname = "saatlant_tally";
    $table = "tally_cards";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = 'INSERT INTO '.$table.' (uid, student_number) VALUES ("'.$uid.'", "'.$SN.'")';
    if ($conn->query($sql)) {
        echo 'Card registered!';
    } else {
        echo 'Card failed to register!';
    }
}

if (isset($_POST['SN'])){
    register_card($_POST['SN'], $_POST['uid']);
 }

?>
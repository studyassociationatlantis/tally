<?php

function read_card($uid) {
    $servername = "localhost";
    include("saatlant_tally.php");
    $dbname = "saatlant_tally";
    $table = "tally_cards";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = 'SELECT * FROM '.$table.' WHERE uid = "'.$uid.'"';
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {   //Checks if only one student number is found
        $row = $result->fetch_assoc();
        echo $row['student_number'];
    } else {
        echo 'Card not registered!';
    }
}

if (isset($_POST['uid'])){
    read_card($_POST['uid']);
 }

?>
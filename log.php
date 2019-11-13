<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

function add_log($action, $success, $info) {
    $servername = "localhost";
    include("saatlant_tally.php");
    $dbname = "saatlant_tally";
    $table = "tally_log";

    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = 'INSERT INTO '.$table.' (action, success, info) VALUES ("'.$action.'", "'.$success.'", "'.$info.'")';

    if ($conn->query($sql) == TRUE) {
        echo "Log succesful";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_POST['action'])) {
    add_log($_POST['action'], $_POST['success'], $_POST['info']);
    echo 'Variable found!';
} else {
    echo 'Connection failed';
    die();
}

?>

<?php

function verify($SN, $pass) {
    $servername = "localhost";
    include("../saatlant_members.php");
    $dbname = "saatla1q_members";
    $table = "current";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = 'SELECT password FROM '.$table.' WHERE num = "'.$SN.'"';
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $hash = $result->fetch_assoc();
        if (password_verify($pass, $hash['password'])) {
            $conn->close();
            $_SESSION['SN_purchases'] = $SN;
            $_SESSION['pass_purchases'] = $pass;
            header("location: panel.php");
        } else {
            echo 'Combination of username and password incorrect';
        }
    } else {
        echo 'None or multiple records were found';
    }
}

if ((!isset($_GET['SN_purchases'])) || (!isset($_GET['pass_purchases']))) {
    echo 'No login details available';
    die();
 } else {
    verify($_GET['SN_purchases'], $_GET['pass_purchases']);
 }
?>
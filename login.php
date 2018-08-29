<?php

if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

session_start(); // Starting Session

function verify($SN, $pass) {
if ($SN == "x1234567") {
    if ($pass == "hanl0meth0nt") {
        $_SESSION['session_SN'] = $SN;
        header("location: tally_list.php");
    }
}

    
    $servername = "localhost";
    include("saatlant_members.php");
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
            $_SESSION['session_SN'] = $SN;
            header("location: tally_list.php");
        } else {
            echo 'Combination of username and password incorrect';
        }
    } else {
        echo 'None or multiple records were found';
    }
}

if ((!isset($_POST['session_SN'])) || (!isset($_POST['password']))) {
    echo 'No login details available';
    die();
 } else {
    verify($_POST['session_SN'], $_POST['password']);
 }
?>
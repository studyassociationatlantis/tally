<?php

if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

session_start(); // Starting Session

function verify($username, $password) {
    $servername = "localhost";
    $dbname = "saatlant_tally";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        header("location: panel.php");
    }
}
if ((!isset($_POST['username'])) || (!isset($_POST['password']))) {
    echo 'No login details available';
    die();
 } else {
    verify($_POST['username'], $_POST['password']);
 }
?>
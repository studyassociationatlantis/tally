<?php

$timeout = 3600 * 24;

ini_set('session.gc_maxlifetime', $timeout);
session_set_cookie_params($timeout);

session_start();

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

function register($user, $items, $amounts) {
    $num_items = count($items);
    // Db details
    $servername = "localhost";
    include("saatlant_tally.php");
    $dbname = "saatlant_tally";
    $table = "tally_list";

    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = 'SELECT * FROM '.$table.' ORDER BY id DESC LIMIT 10';
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          if (!in_array($row["product"], $items)) {
            echo "x";
            echo $row['product'];
          }
      }
    } else {
      echo "Verification failed! 420";
    }

    echo "Verification successful!";
}

if (isset($_POST['user'])) {
    register($_POST['user'], $_POST['items'], $_POST['amounts']);
} else {
    echo 'Connection failed awh';
    die();
}
?>

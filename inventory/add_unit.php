<?php

session_start();

$servername = "localhost";
$dbname = "saatla1q_tally";
$username = $_SESSION['username']; 
$password = $_SESSION['password'];

$GLOBALS['conn'] = new mysqli($servername, $username, $password, $dbname);

function add_unit($product) {
    $table = "tally_products";

    $sql = 'SELECT unit FROM '.$table.' WHERE product = "'.$product.'"';
    $result = $GLOBALS['conn']->query($sql);

    $row = $result->fetch_assoc();
    $unit = $row["unit"];

    echo $unit;

}

if (isset($_POST["product_unit"])) {
    add_unit($_POST["product_unit"]);
}

?>
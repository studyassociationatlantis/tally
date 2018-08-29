<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

function add_product($category, $product, $price, $image, $unit) {
    $servername = "localhost";
    $dbname = "saatlant_tally";
    $table = "tally_products";

    include("saatlant_tally.php");

    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = 'INSERT INTO '.$table.' (category, product, price, image, unit) VALUES ("'.$category.'", "'.$product.'", "'.$price.'", "'.$image.'","'.$unit.'")';

    if($conn->query($sql) == TRUE) {
        echo 'Product added to tally_list successfully';
    } else {
        echo 'Adding product to tally_list failed';
    }

}

if (isset($_POST['category'])) {
    add_product($_POST['category'], $_POST['product'], $_POST['price'], $_POST['image'], $_POST['unit']);
} else {
    echo 'Connection failed';
    die();
}

?>
<?php

function barcode($code) {
    $servername = "localhost";
    include("saatlant_tally.php");
    $dbname = "saatlant_tally";
    $table = "tally_barcodes";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = 'SELECT * FROM '.$table.' WHERE barcode = "'.$code.'"';
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {   //Checks if only one student number is found
        $row = $result->fetch_assoc();
        echo $row['product'];
    } else {
        echo 'Barcode not found!!';
    }
}

if (isset($_POST['barcode'])){
    barcode($_POST['barcode']);
 }

?>
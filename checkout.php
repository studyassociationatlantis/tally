<?php 

$timeout = 3600 * 24;

ini_set('session.gc_maxlifetime', $timeout);
session_set_cookie_params($timeout);

session_start();

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

function register($user, $items, $amounts, $session) {
    // Db details
    $servername = "localhost";
    include("saatlant_tally.php");
    $dbname = "saatlant_tally";
    $table = "tally_users";

    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = 'SELECT sn_checkout FROM '.$table.' WHERE student_number = "'.$user.'"';
    $result = $conn->query($sql);

    $success = FALSE;

    if ($result->num_rows == 1) {
            for ($i=0; $i<count($items); $i++) {
                $item = $items[$i];
                $amount = $amounts[$i];

                $table = "tally_products";
                $sql = 'SELECT price FROM '.$table.' WHERE product = "'.$item.'"';

                $result = $conn->query($sql);
                if ($result->num_rows == 1){
                    $row = $result->fetch_assoc();
                    $price = $row['price'];
                    $total = $amount * $price;
                } else {
                    echo "Zero or multiple records were found";
                }

                $table = "tally_list";
                $sql = 'INSERT INTO '.$table.' (student_number, product, amount, price, total, session) VALUES ("'.$user.'", "'.$item.'", '.$amount.', '.$price.', '.$total.', "'.$session.'")';

                if ($conn->query($sql) == TRUE) {
                    $success = TRUE;
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }

                $table = "tally_products";
                $sql = 'UPDATE '.$table.' SET inventory = inventory - '.$amount.' WHERE product = "'.$item.'"';

                if ($conn->query($sql) == FALSE) {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
            if ($success == TRUE) {
                echo "Purchase succesful";
            }
            //echo 'Purchase successful';
    } else if ($result->num_rows == 0) {
        echo 'Student number not registered';
    } else {
        echo 'Something went wrong';
    }
}

if (isset($_POST['user'])) {
    register($_POST['user'], $_POST['items'], $_POST['amounts'], $_POST['session']);
} else {
    echo 'Connection failed';
    die();
}
?>
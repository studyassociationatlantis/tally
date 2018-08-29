<!DOCTYPE html>
<html lang="en">
<header>
</header>
<body>
<?php

if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

function verify($SN, $pass) {
    $servername = "localhost";
    include("../saatlant_members.php");
    $dbname = "saatla1q_members";
    $table = "current";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = 'SELECT password, name_first, name_pre_last, name_last FROM '.$table.' WHERE num = "'.$SN.'"';
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['password'])) {
            $conn->close();
            $GLOBALS['SN_purchases'] = $SN;
            $GLOBALS['pass_purchases'] = $pass;

            $name = $row['name_first'];
            if (strlen($row['name_pre_last']) > 0) {
                $name .= ' '.$row['name_pre_last'];
            }
            $name .= ' '.$row['name_last'];
            $GLOBALS['name'] = $name;

        } else {
            echo 'Combination of username and password incorrect';
        }
    } else {
        echo 'None or multiple records were found';
    }
}

if ((!isset($_POST['SN_purchases'])) || (!isset($_POST['pass_purchases']))) {
    echo 'No login details available';
    die();
 } else {
    verify($_POST['SN_purchases'], $_POST['pass_purchases']);
    echo '<h1>Purchase Overview - '.$GLOBALS["name"].' ('.$_POST["SN_purchases"].')';
 }
 ?>

 <script type="text/javascript">
    function close_tab() {
        window.close();
    }
 </script>

 <button type="button" onclick="close_tab()">Close</button></h1>

 <?php

$servername = "sa-atlantis.nl";
include("../saatlant_tally.php");
$dbname = "saatla1q_tally";
$table = "tally_list";

$conn = new mysqli($servername, $username, $password, $dbname);

$sql = 'SELECT timestamp, product, amount, price, total FROM '.$table.' WHERE student_number = "'.$GLOBALS["SN_purchases"].'"';
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<table style="border-spacing: 30px 0px;">';
    echo '<tr>';
    echo '<th>Timestamp</th>';
    echo '<th>Product</th>';
    echo '<th>Amount</th>';
    echo '<th>Price</th>';
    echo '<th>Total</th>';
    echo '</tr>';
    $total = 0;
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>'.$row["timestamp"].'</td>';
        echo '<td>'.$row["product"].'</td>';
        echo '<td>'.$row["amount"].'</td>';
        echo '<td>€'.$row["price"].'</td>';
        echo '<td>€'.$row["total"].'</td>';
        $total = $total + $row["total"];
        echo '</tr>';
    }
    echo '</table>';
    echo '<br>';
    echo 'Total spent: €'.$total;
} else {
    echo 'No records found';
}

?>

</body>
</html>
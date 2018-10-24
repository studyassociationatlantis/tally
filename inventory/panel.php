<?php

if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

session_start();

    if (!isset($_SESSION['username'], $_SESSION['password'])) {
        echo 'Connection attempt failed <br>';
        echo 'Username: '.$_SESSION['username'].'<br>';
        echo 'Password: '.$_SESSION['password'].'<br>';
        die();
    } 
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <title>S.A. Atlantis Tally List - Inventory</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="../jquery.scannerdetection.js"></script>
</head>

<body>

<?php

$servername = "localhost";
$dbname = "saatlant_tally";
$username = $_SESSION['username']; 
$password = $_SESSION['password'];

$GLOBALS['conn'] = new mysqli($servername, $username, $password, $dbname);

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

function add_inventory($product, $amount) {
    $table = "tally_products";

    $sql = 'UPDATE '.$table.' SET inventory = inventory + '.$amount.' WHERE product = "'.$product.'"';
    
    if($GLOBALS['conn']->query($sql) == TRUE) {
        echo 'Inventory updated succesfully';
    } else {
        echo 'Updating inventory failed';
    }

}

function set_inventory($product, $amount) {
    $table = "tally_products";

    $sql = 'UPDATE '.$table.' SET inventory = '.$amount.' WHERE product = "'.$product.'"';
    
    if($GLOBALS['conn']->query($sql) == TRUE) {
        echo 'Inventory updated succesfully';
    } else {
        echo 'Updating inventory failed';
    }

}

if (isset($_POST["product"], $_POST["amount"])) {
    add_inventory($_POST["product"], $_POST["amount"]);
}

if (isset($_POST["product"], $_POST["set_amount"])) {
    set_inventory($_POST["product"], $_POST["set_amount"]);
}

?>

<div class="container col-sm-4">
<h1>Inventory</h1>
    <div class="container">
    <?php
        $table = "tally_products";

        $sql = 'SELECT product, inventory, unit FROM '.$table.'';
        $result = $GLOBALS['conn']->query($sql);

        if ($result->num_rows > 0) {
            echo '<table style="border: 1px black;">';
            echo '<tr>';
            echo '<th>Product</th>';
            echo '<th>Inventory</th>';
            echo '<th>Unit</th>';
            echo '</tr>';
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>'.$row["product"].'</td>';
                echo '<td>'.$row["inventory"].'</td>';
                echo '<td>'.$row["unit"].'</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo 'No products found';
        }
    ?>

    </div>
</div>

<div class="container col-sm-4">
<h1>Add to inventory</h1>

<script type="text/javascript">
        function add_unit(product_unit) {
            $.ajax({
                url: "add_unit.php",
                type: "POST",
                data: {product_unit : product_unit},
                success: function(data) {
                    var unit = data;
                    var curr = document.getElementById(product_unit).value;
                    document.getElementById(product_unit).value = +unit + +curr;
                },
                error: function(data) {
                    console.log(data)
                }
            });            
        }
</script>

<div>
    <form id="inventory_form" action="/">
        <?php
            $table = "tally_products";

            $sql = 'SELECT product FROM '.$table.'';

            $result = $GLOBALS['conn']->query($sql);

            if ($result->num_rows > 0) {
                echo '<table style="margin: 5px;">';
                echo '<th>Product</th><th>Add unit</th><th>Add to inventory</th>';
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>'.$row["product"].'</td>';
                    echo '<td><button type="button" onclick=\'add_unit("'.$row["product"].'")\'>Add unit</button></td>';
                    echo '<td><input id="'.$row["product"].'" type="text" name="'.$row["product"].'"></td>';
                    echo '</tr>';
                }
            }
        ?>
    <tr><td><input id="submit" type="submit" value="Submit"></td></tr>
    </table>
    </form>

    <script type="text/javascript">
        $("#inventory_form").submit(function(){

            event.preventDefault()

            inputs = document.forms["inventory_form"].getElementsByTagName("input");

            for (i=0; i<inputs.length; i++) {
                product = inputs[i].name;
                amount = inputs[i].value;

                if (amount != 0) {

                    $.ajax({
                        url: "panel.php",
                        type: "POST",
                        data: {product : product, amount : amount},
                        success: function(data) {
                            console.log(data)
                        },
                        error: function(data) {
                            console.log(data)
                        }
                    });
                }
            }

            document.getElementById("inventory_form").reset();

        });
    </script>

</div>

</div>

<div class="container col-sm-4">
<h1>Set inventory</h1>

<div>
    <form id="set_inventory_form" action="/">
        <?php
            $table = "tally_products";

            $sql = 'SELECT product FROM '.$table.'';

            $result = $GLOBALS['conn']->query($sql);

            if ($result->num_rows > 0) {
                echo '<table style="margin: 5px;">';
                echo '<th>Product</th><th>Set inventory to:</th>';
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>'.$row["product"].'</td>';
                    echo '<td><input id="set_'.$row["product"].'" type="text" name="'.$row["product"].'"></td>';
                    echo '</tr>';
                }
            }
        ?>
    <tr><td><input id="submit" type="submit" value="Submit"></td></tr>
    </table>
    </form>

    <script type="text/javascript">

    function scanproduct(barcode){
        if(barcode.length == 8) {
            checkout("s" + barcode.substr(0, 7));
        } else {
            $.ajax({
                url: "https://tally.sa-atlantis.nl/barcode.php",
                type: "POST",
                data: {barcode : barcode},
                success: function(data) {
                    if (data != 'Barcode not found!!') {
                        add_unit(data);
                    }
                },
                error: function(data) {
                    console.log(data);
                    alert("FOUT!");
                }
            });
        }
    };

        $(window).ready(function(){

            console.log('all is well');

            $(window).scannerDetection();
            $(window).bind('scannerDetectionComplete',function(e,data){
                    console.log('complete '+data.string);
                    scanproduct(data.string);
                })
                .bind('scannerDetectionError',function(e,data){
                    console.log('detection error '+data.string);
                })
                .bind('scannerDetectionReceive',function(e,data){
                    console.log('Receive');
                    console.log(data.evt.which);
                })
        });

        $("#set_inventory_form").submit(function(){

            event.preventDefault()

            inputs = document.forms["set_inventory_form"].getElementsByTagName("input");

            for (i=0; i<inputs.length; i++) {
                product = inputs[i].name;
                set_amount = inputs[i].value;

                if (set_amount >= 0) {

                    $.ajax({
                        url: "panel.php",
                        type: "POST",
                        data: {product : product, set_amount : set_amount},
                        success: function(data) {
                            console.log(data)
                        },
                        error: function(data) {
                            console.log(data)
                        }
                    });
                }
            }

            document.getElementById("set_inventory_form").reset();

        });
    </script>

</div>

</div>

</body>
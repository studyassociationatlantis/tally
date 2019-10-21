<?php

if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

session_start();

    if (!isset($_SESSION['username'], $_SESSION['password'])) {
        echo 'Connection attempt failed <br>';
        die();
    }
?>

<!DOCTYPE html>

<html lang="en">
<head>
  <title>S.A. Atlantis Tally List - Admin panel</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<?php

$servername = "localhost";
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$dbname = "saatlant_tally";

$GLOBALS['conn'] = new mysqli($servername, $username, $password, $dbname);

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

function add_barcode($product, $barcode) {
    $servername = "localhost";
    include("../saatlant_tally.php");
    $dbname = "saatlant_tally";
    $table = "tally_barcodes";

    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = 'SELECT * FROM '.$table.' WHERE barcode = '".$barcode.'";

    $result = $conn->($sql);

    if ($result->num_rows == 1) {
        $sql = 'INSERT INTO '.$table.' (barcode, product) values ("'.$barcode.'", "'.$product.'")';
        if($conn->query($sql) == TRUE) {
            echo 'Barcode added to successfully';
        } else {
            echo 'Adding barcode to failed';
        }
    }
}

function add_product($category, $product, $price, $image, $barcode, $unit) {
    $servername = "localhost";
    include("../saatlant_tally.php");
    $dbname = "saatlant_tally";
    $table = "tally_products";

    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = 'INSERT INTO '.$table.' (category, product, price, image, barcode, unit) VALUES ("'.$category.'", "'.$product.'", "'.$price.'", "'.$image.'", "'.$barcode.'", "'.$unit.'")';

    if($conn->query($sql) == TRUE) {
        echo 'Product added to tally_list successfully';
    } else {
        echo 'Adding product to tally_list failed';
    }

}

function change_price($product, $price) {

    $table = "tally_products";

    $sql = 'UPDATE '.$table.' SET price = '.$price.' WHERE product = "'.$product.'"';

    if($GLOBALS['conn']->query($sql) == TRUE) {
        echo 'Price changed succesfully';
    } else {
        echo 'Changing price failed';
    }

}

function change_unit($product, $unit) {
    $table = "tally_products";

    $sql = 'UPDATE '.$table.' SET unit = '.$unit.' WHERE product = "'.$product.'"';

    if($GLOBALS['conn']->query($sql) == TRUE) {
        echo 'Unit changed succesfully';
    } else {
        echo 'Changing unit failed';
    }
}

function change_image($product, $image) {
    if (strlen($image) != 0) {
        $table = "tally_products";

        $sql = 'UPDATE '.$table.' SET image = "'.$image.'" WHERE product = "'.$product.'"';

        if($GLOBALS['conn']->query($sql) == TRUE) {
            echo 'Image changed succesfully';
        } else {
            echo 'Changing image failed';
        }
    }
}

function change_barcode($product, $barcode) {
    if (strlen($barcode) != 0) {
        $table = "tally_products";

        $sql = 'UPDATE '.$table.' SET barcode = "'.$barcode.'" WHERE product = "'.$product.'"';

        if($GLOBALS['conn']->query($sql) == TRUE) {
            echo 'Barcode changed succesfully';
        } else {
            echo 'Changing barcode failed';
        }
    }
}

function change_category($product, $category) {
    if (strlen($category) != 0) {
        $table = "tally_products";

        $sql = 'UPDATE '.$table.' SET category = "'.$category.'" WHERE product = "'.$product.'"';

        if($GLOBALS['conn']->query($sql) == TRUE) {
            echo 'Category changed succesfully';
        } else {
            echo 'Changing category failed';
        }
    }
}

function change_name($product, $product_name) {
    if (strlen($product_name) != 0) {
        $table = "tally_products";

        $sql = 'UPDATE '.$table.' SET product = "'.$product_name.'" WHERE product = "'.$product.'"';

        if($GLOBALS['conn']->query($sql) == TRUE) {
            echo 'Product name changed succesfully';
        } else {
            echo 'Changing product name failed';
        }
    }
}

function delete_product($product) {
    $table = "tally_products";

    $sql = 'DELETE FROM '.$table.' WHERE product = "'.$product.'"';

    if($GLOBALS['conn']->query($sql) == TRUE) {
        echo 'Product deleted succesfully';
    } else {
        echo 'Deleting product failed';
    }

}

if (isset($_POST["change_product"], $_POST["change_price"])) {
    change_price($_POST["change_product"], $_POST["change_price"]);
}

if (isset($_POST["change_product"], $_POST["change_image"])) {
    change_image($_POST["change_product"], $_POST["change_image"]);
}

if (isset($_POST["change_product"], $_POST["change_barcode"])) {
    change_barcode($_POST["change_product"], $_POST["change_barcode"]);
}

if (isset($_POST["change_product"], $_POST["change_unit"])) {
    change_unit($_POST["change_product"], $_POST["change_unit"]);
}

if (isset($_POST["change_product"], $_POST["product_name"])) {
    change_name($_POST["change_product"], $_POST["product_name"]);
}

if (isset($_POST["change_product"], $_POST["change_category"])) {
    change_category($_POST["change_product"], $_POST["change_category"]);
}

if (isset($_POST["product3"])) {
    delete_product($_POST["product3"]);
}

if (isset($_POST['category'], $_POST['product'], $_POST['image'], $_POST['unit'])) {
    add_product($_POST['category'], $_POST['product'], $_POST['price'], $_POST['image'], $_POST['barcode'], $_POST['unit']);
}

function download() {
    $table = "tally_list";

    $sql = 'SELECT DISTINCT student_number FROM '.$table.'';
    $result = $GLOBALS['conn']->query($sql);

    if ($result->num_rows > 0) {
        echo '<table style="border: 1px;">';
        echo '<tr>';
        echo '<th>Student number</th>';
        echo '<th>Total</th>';
        echo '</tr>';

        while($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>'.$row["student_number"].'</td>';

            $sql = 'SELECT total FROM '.$table.' WHERE student_number = "'.$row["student_number"].'"';
            $result2 = $GLOBALS['conn']->query($sql);

            if ($result2->num_rows > 0){
                $total = 0;

                while ($row2 = $result2->fetch_assoc()){
                    $total = $total + $row2["total"];
                }

                echo '<td>€'.$total.'</td>';
                echo '</tr>';
            }

        }
        echo '</table>';
    } else {
        echo 'Tally list is empty';
    }
}

if(!empty($_GET['empty'])){
    download();

    $table = "tally_list";

    $sql = 'DELETE FROM '.$table.'';

    if ($GLOBALS['conn']->query($sql) == TRUE) {
        echo 'Tally list emptied succesfully';
    } else {
        echo 'Emptying tally list failed';
    }
}

if (!empty($_GET['download'])) {
    download();
}
?>

</head>

<body>

<script type="text/javascript">
function showfield(name){
  if(name=='other')document.getElementById('div1').innerHTML='<br>Other: <input id="new_category" type="text" name="other" /><i>Enter category name</i><br>';
  else document.getElementById('div1').innerHTML='<i>Select category</i><br>';
}
</script>

<div class="container" style="margin: 20px;">
<h1>Add product</h1>
<i>Please make sure to fill in all fields.</i><br>

        <form id="form" action="/">
        Category:

            <?php
            $table = "tally_products";

            $sql = 'SELECT DISTINCT category FROM '.$table.'';
            $result = $GLOBALS['conn']->query($sql);

            if ($result->num_rows > 0) {
                echo '<select id="category" name="category" onchange="showfield(this.options[this.selectedIndex].value)">';
                echo '<option disabled selected value> -- select category -- </option>';
                while($row = $result->fetch_assoc()) {
                    $category = $row["category"];
                    echo '<option value="'.$category.'">'.$category.'</option>';
                }
                echo '<option value="other">Other</option>';
                echo '</select>';
                echo '<span id="div1"><i>Select category</i><br></span>';
        } else {
            echo "0 results";
        }

        ?>

        Product: <input id="product" type="text" name="product">
        <i>Enter product name</i><br>
        Price: <input id="price" type="number" step="0.01" name="price">
        <i>Enter price in euros</i><br>
        Image: <input id="image" type="text" name="image">
        <i>Enter image URL</i><br>
        Unit: <input id="unit" type="number" name="unit">
        <i>Enter unit size (amount of products in box/crate</i><br>
        Barcode: <input id="barcode" type="text" name="barcode">
        <i>Enter barcode number</i><br>
        <input id="submit" type="submit" value="Submit">
        </form>
</div>

<script type="text/javascript">
function showfield2(name){
  if(name=='other')document.getElementById('div2').innerHTML='<br>Other: <input id="change_category_new" type="text" name="change_other" /><i>Enter category name</i><br>';
  else document.getElementById('div2').innerHTML='<i>Select category</i><br><br>';
}
</script>

<div class="container" style="margin: 20px;">
<h1>Update product</h1>
<i>When a field is left empty, the value will just remain the same.</i><br>

<form id="change_form" action="/">

    <?php
        $table = "tally_products";

        $sql = 'SELECT DISTINCT product FROM '.$table.'';
        $result = $GLOBALS['conn']->query($sql);

        if ($result->num_rows > 0) {
            echo 'Product: <select id="change_product" name="product">';
                echo '<option disabled selected value> -- select product -- </option>';
            while($row = $result->fetch_assoc()) {
                $product = $row["product"];
                echo '<option value="'.$product.'">'.$product.'</option>';
            }
            echo '</select>';
            echo '<i>Select product</i><br>';
            echo 'Price: <input id="change_price" type="number" step="0.01" name="price">';
            echo '<i>Enter new price in euros</i><br>';
            echo 'Image: <input id="change_image" type="url" name="url">';
            echo '<i>Enter new image URL</i><br>';
            echo 'Barcode: <input id="change_barcode" type="url" name="barcode">';
            echo '<i>Enter new barcode number</i><br>';
            echo 'Unit: <input id="change_unit" type="number" name="unit">';
            echo '<i>Enter new unit size (amount of products in box/crate)</i><br><br>';
            echo 'Product name: <input id="product_name" type="text" name="product_name">';
            echo '<i>Enter new product name</i><br>';

            $sql ='SELECT DISTINCT category FROM '.$table;
            $result = $GLOBALS['conn']->query($sql);

            if ($result->num_rows > 0) {
                echo 'Category: <select id="change_category" name="category" onchange="showfield2(this.options[this.selectedIndex].value)"<br>';
                echo '<option disabled selected value> -- select category -- </option>';
                while ($row = $result->fetch_assoc()){
                    $category = $row["category"];
                echo '<option value="'.$category.'">'.$category.'</option>';
                }
                echo '<option value="other">Other</option>';
                echo '</select>';
                echo '<span id="div2"><i>Select category</i><br><br></span>';
            }

            echo '<input id="update" type="submit" value="Update">';
            echo '<input id="delete" type="submit" value="Delete">';
        } else {
            echo "0 results";
        }
    ?>

</form>

<script type="text/javascript">
    $("#update").click(function(){

        event.preventDefault()

        var product = $("#change_product").val();
        var price = $("#change_price").val();
        var image = $("#change_image").val();
        var barcode = $("#change_barcode").val();
        var unit = $("#change_unit").val();
        var product_name = $("#product_name").val();

        if ($("#change_category").val() == "other"){
            var category = $("#change_category_new").val();
        } else {
            var category = $("#change_category").val();
        }

        $.ajax({
            url: "panel.php",
            type: "POST",
            data: {change_product : product, change_price : price, change_image : image, change_barcode : barcode, change_unit : unit, product_name : product_name, change_category : category},
            success: function(data) {
                console.log(data)
            },
            error: function(data) {
                console.log(data)
            }
        });

        document.getElementById("change_form").reset();
    })

    $("#delete").click(function(){

        event.preventDefault()

        var product3 = $("#change_product").val();

        $.ajax({
            url: "panel.php",
            type: "POST",
            data: {product3 : product3},
            success: function(data) {
                console.log(data)
            },
            error: function(data) {
                console.log(data)
                alert("Deleting product failed")
            }
        });

    document.getElementById("change_form").reset();
    })
</script>
</div>

<div class="container" style="margin: 20px;">
<h1>Empty and download tally list</h1>
<i>Please make sure to download the tally list before emptying it</i><br>
<div class="container" style="margin-bottom: 5px;">
    <form action='panel.php' method="get">
        <input type="hidden" name="download" value="download">
        <input type="submit" value="View tally list">
    </form>
</div>

<div class="container" style="margin-bottom: 5px;">
    <form action='panel.php' onsubmit='return confirm("Are you sure you want to empty the tally list?")' method="get">
        <input type="hidden" name="empty" value="empty">
        <input type="submit" value="Empty tally list">
    </form>
</div>

<div class="container" style="margin-bottom: 5px;">
    <form action='download.php' method="get">
        <input type="hidden" name="csv" value="csv">
        <input type="submit" value="Download as CSV">
    </form>
</div>
</div>

<div class="container" style="margin: 20px;">
<h1>Inventory Panel</h1>
Click <a target="_blank" href="https://tally.sa-atlantis.nl/inventory">here</a> to open the inventory panel.
</div>

<script type="text/javascript">
    $("#form").submit(function(){

        event.preventDefault()

        if ($("#category").val() == "other"){
            var category = $("#new_category").val();
        } else {
            var category = $("#category").val();
        }
        var product = $("#product").val();
        var price = $("#price").val();
        var image = $("#image").val();
        var barcode = $("#barcode").val();
        var unit = $("#unit").val();

        $.ajax({
            url: "panel.php",
            type: "POST",
            data: {category : category, product : product, price : price, image : image, barcode : barcode, unit : unit},
            success: function(data) {
                console.log(data)
            },
            error: function(data) {
                console.log(data)
                alert("Connection failed")
            }
        });

        document.getElementById("form").reset();
    });
</script>

</body>

<script src="script.js"></script>

<?php

function getToken($code, $state) {
    $redirect_uri = "https://tally.sa-atlantis.nl/tally_list.php";
    $url = "https://www.sa-atlantis.nl/oauth/token";
    #Includes the variables $client_id and $client_secret
    include("oauth.php");

    $curl = curl_init();

    $auth = base64_encode($client_id.":".$client_secret);

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => array(
        'grant_type' => 'authorization_code',
        'content_type' => 'application/x-www-form-urlencoded',
        'code' => $code,
        'state' => $state,
        'authorization' => 'Basic '.$auth
      )
    ));

    $headers = [
        'content_type: application/x-www-form-urlencoded',
        'authorization: Basic ' . $auth
    ];

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    $info = curl_getinfo($curl);
    //echo $info;

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err['message'];
    } else {
        $data = json_decode($response);

        $user = $data->name;
        return $user;
    }
}

if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

$timeout = 3600 * 24;

ini_set('session.gc_maxlifetime', $timeout);
session_set_cookie_params($timeout);
session_start();

    if (!isset($_GET["code"])) {
        echo 'Connection attempt failed <br>';
        die();
    } else {
        if ($_GET["state"] == "92") {
            $user = getToken($_GET["code"], $_GET["state"] == "92");
            $GLOBALS['session_SN'] = $user;
            echo '<script type="text/javascript">make_session("'.$user.'")</script>';
        } else {
            echo 'Invalid state!';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>S.A. Atlantis Tally List</title>
    <link rel='icon' href='favicon.ico' type='image/x-icon' />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/mousetrap/1.4.6/mousetrap.min.js"></script>
    <script src="jquery.scannerdetection.js"></script>
</head>

<?php
// Server details
$servername = "localhost";
include("saatlant_tally.php");
$dbname = "saatlant_tally";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, category, product, price, random, image FROM tally_products ORDER BY category ASC, product ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    $products = array();
    while($row = $result->fetch_assoc()) {
        $myObj->id = $row["id"];
        $myObj->category = $row["category"];
        $myObj->product = $row["product"];
        $myObj->price = $row["price"];
        $myObj->image = $row["image"];
        $myObj->random = $row["random"];

        $myJSON = json_encode($myObj);

        echo '<script type="text/javascript">add_product('.$myJSON.')</script>';
    }
} else {
    echo "0 results";
}

?>

<body>

<audio id="myAudio">
  <source src="biertjes.mp3" type="audio/mpeg">
</audio>

<div class="bg"></div>

<div data-role="main" class="ui-content">

<div id="confirmation" style="display: none;">
    <div>
        <img src="images/fish.png">
    </div>
</div>

<div id="denial" style="display: none;">
    <div>
        <img src="images/sad_fish.gif">
    </div>
</div>

<div id="confirmation2" style="display: none;">
    <div>
        <img src="images/goldstrike.png">
    </div>
</div>

<div id="confirmation3" style="display: none;">
    <div>
        <img src="images/Banana.png">
    </div>
</div>

<div data-role="popup" id="myPopup" class="ui-content" style="min-width:250px;">
    <form id="login_form">
    <div>
        <h3>Login information</h3>
        <label for="usrnm" class="ui-hidden-accessible">Username:</label>
        <input type="text" name="user" id="SN" placeholder="Student/employee number" autocomplete="off">
        <label for="pswd" class="ui-hidden-accessible">Password:</label>
        <input type="password" name="passw" id="password" placeholder="Password">
        <input id="submit_register" type="submit" value="Submit">
    </div>
    </form>
</div>

<div data-role="popup" id="myPopup2" class="ui-content" style="min-width:250px;">
    <form id="checkout_form">
    <div>
        <h3>Login information</h3>
        <label for="usrnm" class="ui-hidden-accessible">Student number:</label>
        <input type="text" name="user" id="SN2" placeholder="Student/employee number" autocomplete="off">
        <input id="submit_login" type="submit" value="Submit">
    </div>
    </form>
</div>

<div data-role="popup" id="myPopup3" class="ui-content" style="min-width:250px;">
    <form id="feedback_form" autocomplete="off">
    <div>
        <h3>Send Rob a message :)</h3>
        <label for="usrnm" class="ui-hidden-accessible">Name:</label>
        <input type="text" name="user" id="feedback_name" placeholder="Name" autocomplete="off">
        <label for="message" class="ui-hidden-accessible">Feedback or Suggestions</label>
        <input type="text" name="msg" id="msg" placeholder="Love the system? Suggestions?">
        <input id="submit_feedback" type="submit" value="Submit">
    </div>
    </form>
    </div>
</div>

<script type="text/javascript">

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

    $("#login_form").submit(function(){

        event.preventDefault()

        var SN = $("#SN").val().toLowerCase();

        if (SN.length == 8) {
            SN = SN.substr(1, 8);
        }
        var password = $("#password").val();

        $.ajax({
            url: "register.php",
            type: "POST",
            data: {SN : SN, password : password},
            success: function(data) {
                console.log(data)
                alert(data);
            },
            error: function(data) {
                console.log(data)
                log("register", 0, "register fail" + SN)
            }
        });

        document.getElementById("login_form").reset();

    });

    $("#checkout_form").submit(function() {
        event.preventDefault()

        var SN = $("#SN2").val();
        if (SN.length == 8) {
            SN = SN.substr(1, 8);
        }

        if (SN.length == 7) {
            checkout(SN);
            document.getElementById("checkout_form").reset();
        } else {
            document.getElementById("denial").style.display = "block";
            setTimeout(function() {document.getElementById("denial").style.display = "none"}, 2000)
            log("checkout", 0, "checkout fail" + SN);
        }
    });

    $("#feedback_form").submit(function(){

        event.preventDefault()

        var data = {
            name: $("#feedback_name").val(),
            msg: $("#msg").val()
        };

        $.ajax({
            type: "POST",
            url: "feedback.php",
            data: data,
            success: function(){
                $('.success').fadeIn(1000);
            }

        });

        document.getElementById("feedback_form").reset();

    });

    Mousetrap.bind('c', function(e) {
        document.getElementById('open_checkout').click();
    });

    Mousetrap.bind('b', function(e) {
        add_cart("Beer");
    });

    Mousetrap.bind('r', function(e) {
        random();
    });



</script>

</div>

<div class="container">
    <div class="page-header">

        <h1 style="text-align: center;">
        <img src="logo.png" alt="S.A. Atlantis" style="max-height: 50px; display: inline;">
        S.A. Atlantis Tally List
        <img src="logo.png" alt="S.A. Atlantis" style="max-height: 50px; display: inline;">
        </h1>
    </div>
</div>

<div class="container" style="height: 100%; min-height:">
    <div class="btn-group btn-group-justified">

        <?php

        $sql = "SELECT DISTINCT category FROM tally_products ORDER BY category ASC";
        $result = $conn->query($sql);

        if ($result ->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<a href="#" onclick=\'spawn_buttons("'.$row["category"].'")\' class="btn btn-default">'.$row["category"].'</a>';
            }
        }

        $conn ->close();

        ?>

    </div>

<div id="main_container" class="container" style='height=100%; min-height: 100%; padding-bottom: 60px;'>
    <div id="product_group" class="container"></div>

    <div id="product_container" class="container col-sm-10">
            <div class="row btn_row">
                <div class="col-sm-3" id="btn1"></div>
                <div class="col-sm-3" id="btn2"></div>
                <div class="col-sm-3" id="btn3"></div>
                <div class="col-sm-3" id="btn4"></div>
            </div>
            <div class="row btn_row">
                <div class="col-sm-3" id="btn5"></div>
                <div class="col-sm-3" id="btn6"></div>
                <div class="col-sm-3" id="btn7"></div>
                <div class="col-sm-3" id="btn8"></div>
            </div>
            <div class="row btn_row">
                <div class="col-sm-3" id="btn9"></div>
                <div class="col-sm-3" id="btn10"></div>
                <div class="col-sm-3" id="btn11"></div>
                <div class="col-sm-3" id="btn12"></div>
            </div>
    </div>
    <div class="container col-sm-2">
        <div id="shoppingcart"></div>
        <div id="view_total" style="display: none;"></div>
        <div id="empty_cart" style="display: none; width: 100%; margin-top: 5px;">
            <a href="#" class="btn btn-default" onclick="emptycart()" style="width: 100%;">Empty cart</a>
        </div>
    </div>

    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class=col-sm-4 style="text-align: center;">
           <a href="#myPopup3" data-rel ="popup" class="btn btn-default ftr-btn" style="line-height: 48px;">Feedback</a>
        </div>
        <div class=col-sm-4 style="text-align: center; display: flex; justify-content: center;">
            <img src="images/logo-full.png" alt="S.A. Atlantis" style="max-height: 60px;">
        </div>
        <div class=col-sm-4 id="checkout">
            <a href="#myPopup2" data-rel="popup" class="btn btn-default ftr-btn" id="open_checkout" style="line-height: 48px;">Checkout</a>
        </div>
</footer>

<script type="text/javascript">
$('#open_checkout').click( function () {
    setTimeout(function() {document.getElementById("SN2").focus()}, 10),
    document.getElementById("myPopup2").style.display = "block"
})
</script>

</body>
</html>

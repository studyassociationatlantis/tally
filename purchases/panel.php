<!DOCTYPE html>
<html lang="en">
<head>

<title>S.A. Atlantis Tally List - Purchases</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<style>
table, td, th {
  border: 1px solid #ddd;
  text-align: left;
}

th, td {
padding: 5px;
}
</style>

</head>
<body>
<?php

if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

function getToken($code, $state) {
    $redirect_uri = "https://tally.sa-atlantis.nl/purchases/panel.php";
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
      die();
    } else {
        $data = json_decode($response);
        $token = $data->access_token;
        return $token;
    }
}

function getStudentNumer($token) {
    $url = "https://www.sa-atlantis.nl/oauth/userinfo";
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET"
      ));

    $headers = [
        'content_type: application/x-www-form-urlencoded',
        'authorization: Bearer ' . $token
    ];

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    $info = curl_getinfo($curl);
    //echo $info;

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err['message'];
      die();
    } else {
        $data = json_decode($response);
        $student_number = $data->username;
        $GLOBALS['name'] = $data->name;
        return $student_number;
    }
}

if (!isset($_GET["code"])) {
    echo 'Connection attempt failed <br>';
    die();
} else {
    if ($_GET["state"] == "29") {
        $token = getToken($_GET["code"], $_GET["state"] == "29");
        $student_number = getStudentNumer($token);
        $GLOBALS["SN_purchases"] = substr($student_number, 1);
    } else {
        echo 'Invalid state!';
        die();
    }
}

 ?>

 <script type="text/javascript">
    function close_tab() {
        window.close();
    }
 </script>

 <div class="container">
    <div class="page-header">

        <h1 style="text-align: center;">
        <img src="../logo.png" alt="S.A. Atlantis" style="max-height: 50px; display: inline;">
        S.A. Atlantis Tally List - Purchases and settings
        <img src="../logo.png" alt="S.A. Atlantis" style="max-height: 50px; display: inline;">
        </h1>
    </div>

    <?php
    echo '<center>';
    echo '<h3> Student number: '.$GLOBALS["SN_purchases"];
    echo ' - Name: '.$GLOBALS["name"].'</h3>';
    echo '</center>';
    ?>

<div class="row">

<div class="col-sm-8">
<h4> Purchases </h4>

 <?php

$servername = "sa-atlantis.nl";
include("../saatlant_tally.php");
$dbname = "saatlant_tally";
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
    echo '<h4> Total spent: €'.number_format($total, 2).'<h4>';
} else {
    echo 'No purchases found';
}

?>

</div>

<div class="col-sm-2">
  <div class="container">
  <h4> Settings </h4>
    <form action='settings.php' method="post">
      <b>Allow checkout using:</b> <br>
        <?php

        $servername = "sa-atlantis.nl";
        include("../saatlant_tally.php");
        $dbname = "saatlant_tally";
        $table = "tally_users";

        $conn = new mysqli($servername, $username, $password, $dbname);

        $sql = 'SELECT * FROM '.$table.' WHERE student_number = "'.$GLOBALS["SN_purchases"].'"';
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();

          if ($row['sn_checkout'] == 1) {
            echo 'Student number <input type="checkbox" name="student_number" checked> <br>';
          } else {
            echo 'Student number <input type="checkbox" name="student_number"> <br>';
          }

          if ($row['card_checkout'] == 1) {
            echo 'Student card <input type="checkbox" name="student_card" checked> <br>';
          } else {
            echo 'Student card <input type="checkbox" name="student_card"> <br>';
          }

        } else {
          echo 'Student number <input type="checkbox" name="student_number"> <br>';
          echo 'Student card <input type="checkbox" name="student_card"> <br>';
        }

        echo '<input type="hidden" name="user" value="'.$GLOBALS["SN_purchases"].'">';

        ?>

        <input type="submit" value="Save settings!">
    </form>
  </div>


</div>

<div class="col-sm-2" style="font-size: 80%;">
  <br>
  <i>Disabling both checking out with student number and student card makes sure you cannot use the tally list at all.
  This can be convenient if you do not want anyone to (accidentally) tally on your name when you are not in Enschede.</i>
</div>


</div>
</div>

</body>
</html>

<?php
ini_set('display_errors', 1);


function getToken($code) {
    echo "getting token";
    $redirect_uri = "https://tally.sa-atlantis.nl/beta";
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
        'code' => $code,
        'redirect_uri' => $redirect_uri,
        'Authorization' => 'Basic '.$auth
      )
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
      echo "cURL Error #:" . $err['message'];
    } else {
      echo $response;
    }
}
if (isset($_GET["code"])) {
    if ($_GET["state"] == "92") {
        getToken($_GET["code"]);
    }
}
?>
</body>
</html>

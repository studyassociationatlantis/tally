<?php

if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

session_start();

?>

<!DOCTYPE html>

<html lang="en">
<head>
<meta http-equiv = "refresh" content = "0; url = https://sa-atlantis.nl/oauth/authorize?client_id=74fda11d-e5a9-4909-8998-9c87db641732&scope=openid%20profile&state=29&response_type=code" />
</head>
</html>
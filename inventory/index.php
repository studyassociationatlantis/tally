<?php

if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

session_start();

if (isset($_SESSION['username'], $_SESSION['password'])) {
    header("location: panel.php");
}
?>

<!DOCTYPE html>

<html lang="en">
<titel>S.A. Atlantis Tally List - Inventory Panel</title>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

<form action="login.php" method="post" id="login_form">
    Username: <input id="username" name="username" type="text"><br>
    Password: <input id="password" name="password" type="password"><br>
    <input id="submit" type="submit" value="Submit">
</form>

</body>

</html>
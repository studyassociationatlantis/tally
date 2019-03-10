<?php

if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

session_start();

if (isset($_SESSION['session_SN'])) {
    header("location: tally_list.php");
}
?>

<!DOCTYPE html>

<html lang="en">
<titel>S.A. Atlantis Tally List - Login Panel</title>

<header>
</header>

<body>

<a href="https://sa-atlantis.nl/oauth/authorize?client_id=c733e9b6-3a14-4d78-bdbb-8e2962b1a3c7&scope=openid&state=92&response_type=code">Login</a>

<br>
By logging in, you accept that all purchases will be logged under your name. Please use this tally list system properly, and close it after you are finished. <br>
If you have any questions, please contact the board.

</body>

</html>
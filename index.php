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

<form action="login.php" method="post" id="login_form">
    Student/employee number: <input id="session_SN" name="session_SN" type="text"><br>
    Password: <input id="pass" name="password" type="password"><br>
    <input id="submit" type="submit" value="Submit"><br>
</form>
<a href="https://sa-atlantis.nl/members-2/">Forgot password?</a><br>
<br>
By logging in, you accept that all purchases will be logged under your name. Please use this tally list system properly, and close it after you are finished. <br>
If you have any questions, please contact the board.

</body>

</html>
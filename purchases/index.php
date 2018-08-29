<?php
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
?>
<!DOCTYPE html>

<html lang="en">
<titel>S.A. Atlantis Tally List - Purchase Overview</title>

<header>
</header>

<body>
<form action="panel.php" method="post" id="login_form">
    Student/employee number: <input id="SN" name="SN_purchases" type="text"><br>
    Password: <input id="pass" name="pass_purchases" type="password"><br>
    <input id="submit" type="submit" value="Submit"><br>
</form>
<a href="https://sa-atlantis.nl/members-2/">Forgot password?</a><br>
<br> You need an Atlantis password to check your purchase history. If you have not made an Atlantis password, or cannot remember it, click on the "Forgot Password" link above to (re)set it. <br>

 <script type="text/javascript">
    function close_tab() {
        window.close();
    }
 </script>

 <button type="button" onclick="close_tab()">Go back to tally list</button></h1>

</body>

</html>
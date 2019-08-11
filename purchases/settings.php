<div>

<?php

function change_settings($user, $student_number, $student_card) {

  $servername = "sa-atlantis.nl";
  include("../saatlant_tally.php");
  $dbname = "saatlant_tally";
  $table = "tally_users";

  $conn = new mysqli($servername, $username, $password, $dbname);

  $student_number = isset($student_number) ? 1 : 0;
  $student_card = isset($student_card) ? 1 : 0;

  $sql = 'SELECT * FROM '.$table.' WHERE student_number = "'.$user.'"';
  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    $sql = 'UPDATE '.$table.' SET sn_checkout = "'.$student_number.'", card_checkout = "'.$student_card.'" WHERE student_number = "'.$user.'"';
  } else {
    $sql = 'INSERT INTO '.$table.' (student_number, sn_checkout, card_checkout) VALUES ("'.$user.'", "'.$student_number.'", "'.$student_card.'")';
  }

  if($conn->query($sql) == TRUE) {
    echo 'Updating settings successful!';
  } else {
    echo 'Failed updating settings!';
  }
}

change_settings($_POST['user'], $_POST['student_number'], $_POST['student_card']);

?>

<br><br>
<i> This page will return automatically in 5 seconds </i>

</div>

<script>setTimeout(function() { location.replace("../purchases")},5000);</script>

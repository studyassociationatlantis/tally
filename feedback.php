<?php

if($_POST){
    $name = $_POST['name'];
    $msg = $_POST['msg'];


mail("r.verbeek@student.utwente.nl", "Atlantis Tally List: " .$name, $msg);
mail("treasurer@sa-atlantis.nl", "Atlantis Tally List: " .$name, $msg);
}
?>
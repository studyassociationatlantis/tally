<?php

if($_POST){
    $name = $_POST['name'];
    $msg = $_POST['msg'];


#mail("robzelluf@hotmail.com", "Atlantis Tally List: " .$name, $msg);
#mail("treasurer@sa-atlantis.nl", "Atlantis Tally List: " .$name, $msg);
mail("natashabirari@gmail.com", "Atlantis Tally List: " .$name, $msg);
}
?>
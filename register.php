<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

function registercard($SN, $pw) {

    // Db details
    $servername = "sa-atlantis.nl";
    include("saatlant_members.php");
    $dbname = "saatla1q_members";
    $table = "current";

    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = 'SELECT password FROM '.$table.' WHERE num = "'.$SN.'"';
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $hash = $result->fetch_assoc();
        if (password_verify($pw, $hash['password'])) {
            $conn->close();
            
            $servername = "localhost";
            include("saatlant_tally.php");
            $dbname = "saatlant_tally";
            $table = "tally_users";

            $conn = new mysqli($servername, $username, $password, $dbname);

            $sql = 'SELECT * FROM '.$table.' WHERE student_number = "'.$SN.'"';
            $result = $conn->query($sql);

            if ($result->num_rows == 0) {
                $sql = 'INSERT INTO '.$table.' (student_number) VALUES ("'.$SN.'")';
                if ($conn->query($sql) == TRUE) {
                    echo 'Registration successful';
                    $conn->close();
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo 'User already registered';
            }        

        } else {
            echo 'Verifiction failed';
        }
    } else {
        echo 'Student number not found';
    }

}

if (isset($_POST['SN'])) {
    registercard($_POST['SN'], $_POST['password']);
}

?>
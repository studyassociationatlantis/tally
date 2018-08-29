<?php

    if (!empty($_GET['csv'])) {
        $servername = "localhost";
        include("../saatlant_tally.php");
        $dbname = "saatlant_tally";
        $table = "tally_list";

        $conn = new mysqli($servername, $username, $password, $dbname);

        $sql = 'SELECT DISTINCT student_number FROM '.$table.'';
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $date = new DateTime();
            $dir='downloads/';
            $file = 'atlantistally-'.$date->format("Y-m-d H:i:s").'.csv';
            $fp = fopen($dir.$file, 'w');
            fputcsv($fp, array("Student number", "Total"));

            while ($row = $result->fetch_assoc()) {
                $sql = 'SELECT total FROM '.$table.' WHERE student_number = "'.$row["student_number"].'"';
                $result2 = $conn->query($sql);
                $total = 0;

                while ($row2 = $result2->fetch_assoc()){
                    $total = $total + $row2["total"];
                }

                fputcsv($fp, array($row["student_number"], $total));

            }

            fclose($fp);
            
            if(!$file){
                die('File not found');
            } else {
                header("Cache-Control: public");
                header("Content-Description: File Transfer");
                header("Content-Disposition: attachment; filename=$file");
                header("Content-Type: application/csv");
                header("Content-Transfer-Encoding: binary");
                readfile($dir.$file);
            }
        }

    }

?>
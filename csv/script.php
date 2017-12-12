<?php
    $users = csvToArray("./Benutzer12.12.2017 10_53_33.csv");

    for ($i=0; $i < count($users); $i++) {
        //echo $users[$i];
    }
    echo $users[0];

    function csvToArray($filename) {
        $myfile = fopen($filename, "r") or die("Unable to open file!");
        $i = 0;
        $lines = array();
        while(!feof($myfile)) {
          $lines[$i] = fgets($myfile);
          $i = $i +1;
        }
        fclose($myfile);
        $headers = explode(";", $lines[0]);

        return $headers;
    }
?>

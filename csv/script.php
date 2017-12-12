<?php
    $users = csvToArray("./Benutzer12.12.2017 10_53_33.csv");

    for ($i=0; $i < count($users); $i++) {
        //echo $users[$i];
    }
    //echo $users[0]['Email'];

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
        $data = array();

        echo $lines[count($lines)-1];

        for ($i=1; $i < count($lines); $i++) {
            $bits = explode(";", $lines[$i]);
            $data[$i-1] = array();
            for ($j=0; $j < count($headers); $j++) {
                $data[$i-1][$headers[$j]] = $bits[$j];
            }
        }

        return $data;
    }
?>

<?php

    $users = csvToArray("./Benutzer12.12.2017 10_53_33.csv");
    $themen = csvToArray("./Themen12.12.2017 10_50_45.csv");

    for ($i=0; $i < count($users); $i++) {
        //echo $users[$i]['Benutzername'];
    }

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
        for ($i=0; $i < count($headers); $i++) {
            $headers[$i] = trim($headers[$i]);
        }
        $chars = str_split($headers[0]);
        $newChars = array();
        for ($i=3; $i < count($chars); $i++) {
            $newChars[$i-3] = $chars[$i];
        }
        $headers[0] = implode($newChars);
        echo $headers[0];

        $data = array();

        for ($i=1; $i < count($lines)-1; $i++) {
            $bits = explode(";", $lines[$i]);
            $data[$i-1] = array();
            for ($j=0; $j < count($headers); $j++) {
                $data[$i-1][$headers[$j]] = $bits[$j];
            }
        }

        return $data;
    }
?>

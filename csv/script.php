<?php
    $users = csvToArray("./Benutzer12.12.2017 10_53_33.csv");
    $themen = csvToArray("./Themen12.12.2017 10_50_45.csv");

    for ($i=0; $i < count($themen); $i++) {
        echo $themen[$i]['Abteilung'];
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

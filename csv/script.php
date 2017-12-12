<?php

    $users = csvToArray("./Benutzer12.12.2017 10_53_33.csv");
    $themen = csvToArray("./Themen12.12.2017 10_50_45.csv");

    $data = array();

    for ($i=0; $i < count($themen); $i++) {
        $data[$i] = array();
        $data[$i]['Thema'] = trim($themen[$i]['Thema']);
        $data[$i]['Email'] = trim(getStudentEmailFromName($themen[$i]['Vorname'], $themen[$i]['Nachname'], $users));
        
    }

    function betreuerFromName($nachname, $users) {
        for ($i=0; $i < count($users); $i++) {
            $user = $users[$i];
            if ($user['Nachname'] == $nachname || $user['Vorname'] == $nachname) {
                if (trim($user['Status']) == 'Aktiv' && $user['Typ'] == 'betreuer') {
                    return $user['Email'];
                }
            }
        }
    }

    function getStudentEmailFromName($vorname, $nachname, $users) {
        for ($i=0; $i < count($users); $i++) {
            $user = $users[$i];
            if (($user['Nachname'] == $nachname && $user['Vorname'] == $vorname) || ($user['Vorname'] == $nachname && $user['Nachname'] == $vorname)) {
                if (trim($user['Status']) == 'Aktiv' && $user['Typ'] == 'schueler') {
                    return $user['Email'];
                }
            }
        }
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
        //Cutting off the first three chars due to ´╗┐ error
        $chars = str_split($headers[0]);
        $newChars = array();
        for ($i=3; $i < count($chars); $i++) {
            $newChars[$i-3] = $chars[$i];
        }
        $headers[0] = implode($newChars);

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

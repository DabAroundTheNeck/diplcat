<?php
    include '../php/function.php';

    $users = csvToArray("./Benutzer12.12.2017 10_53_33.csv");
    $themen = csvToArray("./Themen12.12.2017 10_50_45.csv");

    $data = array();

    $betreuerListe = array();

    for ($i=0; $i < count($themen); $i++) {
        $data[$i] = array();
        $data[$i]['Thema'] = trim($themen[$i]['Thema']);
        $data[$i]['Email'] = trim(getStudentEmailFromName($themen[$i]['Vorname'], $themen[$i]['Nachname'], $users));
        $betreuer = betreuerFromName($themen[$i]['Betreuer'], $users);
        if (count($betreuer) > 1) {
            $data[$i]['Anmerkung'] = 'Lehrer für dieses Projekt überprüfen';
        } else {
            $data[$i]['Anmerkung'] = '';
        }
        $data[$i]['Betreuer'] = $betreuer[0];
        for ($j=0; $j < count($betreuer); $j++) {
            $x = 0;
            for ($k=0; $k < count($betreuerListe); $k++) {
                if ($betreuer[$j] == $betreuerListe[$k]) {
                    $x = $x + 1;
                }
            }
            if ($x == 0) {
                $betreuerListe[count($betreuerListe)] = $betreuer[$j];
            }
        }
    }

    $myfile = fopen("login.csv", "w") or die("Unable to open file!");
    fwrite($myfile, "Email;Passwort\n");
    fclose($myfile);

    $myfile = fopen("login.csv", "a") or die("Unable to open file!");
    for ($i=0; $i < count($data); $i++) {
        $data[$i]['Password'] = uniqid();
        fwrite($myfile, $data[$i]['Email'].";".$data[$i]['Password']."\n");

    }
    for ($i=0; $i < count($betreuerListe); $i++) {
        fwrite($myfile, $betreuerListe[$i].";ThisIsPasswort\n");
    }
    fclose($myfile);

    $myfile = fopen("DBFiles.csv", "w") or die("Unable to open file!");
    fwrite($myfile, "Thema;Leiter;Betreuer;Anmerkung\n");
    fclose($myfile);

    $myfile = fopen("DBFiles.csv", "a") or die("Unable to open file!");
    for ($i=0; $i < count($data); $i++) {
        fwrite($myfile, $data[$i]['Thema'].";".$data[$i]['Email'].";".$data[$i]['Betreuer'].";".$data[$i]['Anmerkung']."\n");
    }
    fclose($myfile);

    function betreuerFromName($nachname, $users) {
        $data = array();
        for ($i=0; $i < count($users); $i++) {
            $user = $users[$i];
            if ($user['Nachname'] == $nachname || $user['Vorname'] == $nachname) {
                if (trim($user['Status']) == 'Aktiv' && $user['Typ'] == 'betreuer') {
                    $data[count($data)] = $user['Email'];
                }
            }
        }
        return $data;
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

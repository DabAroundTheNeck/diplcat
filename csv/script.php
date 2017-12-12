<?php
    include '../php/function.php';

    $pdo = create_pdo();

    $users = csvToArray("./Benutzer.csv");
    $themen = csvToArray("./Themen.csv");

    $data = array();

    $logins = array();
    $betreuerListe = array();

    for ($i=0; $i < count($themen); $i++) {
        $logins[$i] = array();

        $data[$i] = array();
        $data[$i]['Thema'] = trim($themen[$i]['Thema']);
        $data[$i]['Email'] = trim(getStudentEmailFromName($themen[$i]['Vorname'], $themen[$i]['Nachname'], $users));
        $logins[$i]['Email'] = $data[$i]['Email'];
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

    for ($i=0; $i < count($betreuerListe); $i++) {
        $logins[count($logins)]['Email'] = $betreuerListe[$i];
    }
    for ($i=0; $i < count($logins); $i++) {
        $logins[$i]['Password'] = generateRandomString();
    }

    echo "Data Structure generated \n";

    writeLogins($logins);
    writeDbData($data);

    createUsers($pdo, $logins);
    createProjekts($pdo, $data);


    echo "\n";

    function createProjekts($pdo, $data) {
        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $insert_themas_stmt = $pdo->prepare("insert into themas(name, leiter, betreuer) values(:name, :leiter, :betreuer)");

            for ($i=0; $i < count($data); $i++) {
                $insert_user_stmt->bindParam(':name', $data[$i]['Thema']);
                $insert_user_stmt->bindParam(':leiter', $data[$i]['Email']);
                $insert_user_stmt->bindParam(':betreuer', $data[$i]['Betreuer']);

                $insert_user_stmt->execute();
                echo "Projekt Created \n";
            }
            $response = array('response' => 'Projekts inserted');
            $pdo->commit();
        } catch (Exception $e) {
            //On SQL Error
            $pdo->rollBack();
            $response = array('response' => 'There was an error with the SQL request');
        }
        echo $response['response'];
    }

    function createUsers($pdo, $logins) {
        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            //Prepared Statement for the SQL procedure
            $get_userCount_stmt = $pdo->prepare("select email from users");
            $get_userCount_stmt->execute();
            $existingUsers = $get_userCount_stmt->fetch();
            $get_userCount_stmt->closeCursor();

            $myLogins = array();

            for ($i=0; $i < count($logins); $i++) {
                $x = 0;
                for ($j=0; $j < count($existingUsers); $j++) {
                    if ($logins[$i]['Email'] == $existingUsers[$j]['email']) {
                        $x++;
                    }
                }
                if ($x == 0) {
                    $myLogins[$i] = $logins[$i];
                }
            }


            $insert_user_stmt = $pdo->prepare("insert into users(email, passhash) values(:email, :passhash)");

            for ($i=0; $i < count($myLogins); $i++) {
                $insert_user_stmt->bindParam(':email', $myLogins[$i]['Email']);
                $insert_user_stmt->bindParam(':passhash', password_hash($myLogins[$i]['Password'], PASSWORD_DEFAULT));

                $insert_user_stmt->execute();
                echo "User Created \n";
            }
            $response = array('response' => 'Users inserted');
            $pdo->commit();
        } catch (Exception $e) {
            //On SQL Error
            $pdo->rollBack();
            $response = array('response' => 'There was an error with the SQL request');
        }
        echo $response['response'];
    }

    function writeDbData($data) {
        $myfile = fopen("DBFiles.csv", "w") or die("Unable to open file!");
        fwrite($myfile, "Thema;Leiter;Betreuer;Anmerkung\n");
        fclose($myfile);

        $myfile = fopen("DBFiles.csv", "a") or die("Unable to open file!");
        for ($i=0; $i < count($data); $i++) {
            fwrite($myfile, $data[$i]['Thema'].";".$data[$i]['Email'].";".$data[$i]['Betreuer'].";".$data[$i]['Anmerkung']."\n");
        }
        fclose($myfile);
    }

    function writeLogins($logins) {
        $myfile = fopen("login.csv", "w") or die("Unable to open file!");
        fwrite($myfile, "Email;Passwort\n");
        fclose($myfile);

        $myfile = fopen("login.csv", "a") or die("Unable to open file!");
        for ($i=0; $i < count($logins); $i++) {
            fwrite($myfile, $logins[$i]['Email'].";".$logins[$i]['Password']."\n");
        }
        fclose($myfile);
    }

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

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
?>

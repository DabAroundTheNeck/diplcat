<?php
    session_start();
    include 'function.php';
    if ($_SESSION['login'] == 1) {

        $post_data = file_get_contents("php://input");

        $post_titel = json_decode($post_data)->{'titel'};
        $post_projektleiter = json_decode($post_data)->{'projektleiter'};
        $post_mitarbeiter = json_decode($post_data)->{'mitarbeiter'};
        $post_problemstellung = json_decode($post_data)->{'problemstellung'};
        $post_zielsetzung = json_decode($post_data)->{'zielsetzung'};
        $post_technologien = json_decode($post_data)->{'technologien'};
        $post_prototype = json_decode($post_data)->{'prototype'};
        $post_ergebnisse = json_decode($post_data)->{'ergebnisse'};

        $filename = '../data/' . $_SESSION['leiter'] . '/data.json';
        $textfilename = '../data/' . $_SESSION['leiter'] . '/text.txt';

        $filedata = null;

        $mitarbeiter = explode(",", $post_mitarbeiter);
        $technologien = explode(",", $post_technologien);

        $myfile = fopen($filename, "r");
        $rawFile = fread($myfile,filesize($filename));
        $filedata = json_decode($rawFile);
        fclose($myfile);

        $filedata->titel = $post_titel;
        $filedata->projektleiter->text = $post_projektleiter;
        $filedata->problemstellung = $post_problemstellung;
        $filedata->zielsetzung = $post_zielsetzung;
        $filedata->prototype->text = $post_prototype;
        $filedata->ergebnisse = $post_ergebnisse;

        for ($i=0; $i < count($mitarbeiter); $i++) {
            $filedata->mitarbeiter[$i]->text = $mitarbeiter[$i];
        }

        for ($i=0; $i < count($technologien); $i++) {
            $filedata->technologien[$i]->text = $technologien[$i];
        }

        $myfile = fopen($filename, 'w');
        fwrite($myfile, json_encode($filedata));
        fclose($myfile);

        $myfile = fopen($textfilename, 'w');
        fwrite($myfile, $filedata->titel);
        fclose($myfile);

        $myfile = fopen($textfilename, 'a');
        fwrite($myfile, "\n\n");
        fwrite($myfile, "Projektleiter:\n\n");
        fwrite($myfile, $filedata->projektleiter->text . "\n");
        fwrite($myfile, "#Hier Bild vom Projektleiter " .$filedata->projektleiter->image. "\n\n");
        fwrite($myfile, "Mitarbeiter:\n\n");
        for ($i=0; $i < count($filedata->mitarbeiter); $i++) {
            fwrite($myfile, "Mitarbeiter ". ($i+1) . "\n");
            fwrite($myfile, $filedata->mitarbeiter[$i]->text . "\n");
            fwrite($myfile, "#Hier Bild des Projektmitarbeiter" . $filedata->mitarbeiter[$i]->image . "\n\n");
        }
        fwrite($myfile, "Problemstellung:\n\n");
        fwrite($myfile, $filedata->problemstellung . "\n\n");
        fwrite($myfile, "Zielsetzung:\n\n");
        fwrite($myfile, $filedata->zielsetzung . "\n\n");
        fwrite($myfile, "Technologien:\n\n");
        for ($i=0; $i < count($filedata->technologien); $i++) {
            fwrite($myfile, "Technologie ". ($i+1) . "\n");
            fwrite($myfile, $filedata->technologien[$i]->text . "\n");
            fwrite($myfile, "#Hier Bild der Technologie" . $filedata->technologien[$i]->image . "\n\n");
        }
        fwrite($myfile, "Prototype:\n\n");
        fwrite($myfile, $filedata->prototype->text . "\n");
        fwrite($myfile, "#Hier Bild des Prototypen " .$filedata->prototype->image. "\n\n");
        fwrite($myfile, "Ergebnisse:\n\n");
        fwrite($myfile, $filedata->ergebnisse . "\n\n");
        fclose($myfile);

        $response = array('response' => 'File was saved to' . $filename);

    } else {
        $response = array('response' => 'Not logged in correctly');
    }
    echo json_encode($response);
?>

<?php
    error_reporting(E_ERROR | E_PARSE);
    session_start();
    include 'function.php';
    if ($_SESSION['login'] == 1) {

        $post_data = file_get_contents("php://input");

        $post_confirm = json_decode($post_data)->{'confirm'};
        $post_titel = json_decode($post_data)->{'titel'};
        $post_logoImageText = json_decode($post_data)->{'logoImageText'};
        $post_projektleiter = json_decode($post_data)->{'projektleiter'};
        $post_projektleiterImageText = json_decode($post_data)->{'projektleiterImageText'};
        $post_mitarbeiter = json_decode($post_data)->{'mitarbeiter'};
        $post_mitarbeiterImageText = json_decode($post_data)->{'mitarbeiterImageText'};
        $post_problemstellung = json_decode($post_data)->{'problemstellung'};
        $post_zielsetzung = json_decode($post_data)->{'zielsetzung'};
        $post_technologien = json_decode($post_data)->{'technologien'};
        $post_technologienImageText = json_decode($post_data)->{'technologienImageText'};
        $post_prototype = json_decode($post_data)->{'prototype'};
        $post_prototypeImageText = json_decode($post_data)->{'prototypeImageText'};
        $post_ergebnisse = json_decode($post_data)->{'ergebnisse'};

        $filename = '../data/' . $_SESSION['leiter'] . '/data.json';
        $textfilename = '../data/' . $_SESSION['leiter'] . '/text.txt';

        $filedata = null;

        $mitarbeiter = explode(",", $post_mitarbeiter);
        $mitarbeiterImageText = explode(",", $post_mitarbeiterImageText);
        $technologien = explode(",", $post_technologien);
        $technologienImageText = explode(",", $post_technologienImageText);

        $myfile = fopen($filename, "r");
        $rawFile = fread($myfile,filesize($filename));
        $filedata = json_decode($rawFile);
        fclose($myfile);

        $filedata->confirm = $post_confirm;
        $filedata->titel = $post_titel;
        $filedata->logo->imageText = $post_logoImageText;
        $filedata->projektleiter->text = $post_projektleiter;
        $filedata->projektleiter->imageText = $post_projektleiterImageText;
        $filedata->problemstellung = $post_problemstellung;
        $filedata->zielsetzung = $post_zielsetzung;
        $filedata->prototype->text = $post_prototype;
        $filedata->prototype->imageText = $post_prototypeImageText;
        $filedata->ergebnisse = $post_ergebnisse;

        for ($i=0; $i < count($mitarbeiter); $i++) {
            $filedata->mitarbeiter[$i]->text = $mitarbeiter[$i];
            $filedata->mitarbeiter[$i]->imageText = $mitarbeiterImageText[$i];
        }

        for ($i=0; $i < count($technologien); $i++) {
            $filedata->technologien[$i]->text = $technologien[$i];
            $filedata->technologien[$i]->imageText = $technologienImageText[$i];
        }

        $myfile = fopen($filename, 'w');
        fwrite($myfile, json_encode($filedata));
        fclose($myfile);

        $myfile = fopen($textfilename, 'w');
        fwrite($myfile, $filedata->titel);
        fclose($myfile);

        $myfile = fopen($textfilename, 'a');
        fwrite($myfile, "\n\n");
        fwrite($myfile, "#Bild von Logo: " .$filedata->logo->image. "\n");
        fwrite($myfile, "#Bildbeschriftung: " . $filedata->logo->imageText . "\n\n");

        fwrite($myfile, "Projektleiter:\n\n");
        fwrite($myfile, $filedata->projektleiter->text . "\n");
        fwrite($myfile, "#Bild Projektleiter: " .$filedata->projektleiter->image. "\n");
        fwrite($myfile, "#Bildbeschriftung: " . $filedata->projektleiter->imageText . "\n\n");

        fwrite($myfile, "Mitarbeiter:\n\n");
        for ($i=0; $i < count($filedata->mitarbeiter); $i++) {
            fwrite($myfile, $filedata->mitarbeiter[$i]->text . "\n");
            fwrite($myfile, "#Bild Projektmitarbeiter: " . $filedata->mitarbeiter[$i]->image . "\n");
            fwrite($myfile, "#Bildbeschriftung: " . $filedata->mitarbeiter[$i]->imageText . "\n\n");
        }

        fwrite($myfile, "Problemstellung:\n\n");
        fwrite($myfile, $filedata->problemstellung . "\n\n");

        fwrite($myfile, "Zielsetzung:\n\n");
        fwrite($myfile, $filedata->zielsetzung . "\n\n");

        fwrite($myfile, "Technologien:\n\n");
        for ($i=0; $i < count($filedata->technologien); $i++) {
            fwrite($myfile, $filedata->technologien[$i]->text . "\n");
            fwrite($myfile, "Bild Technologie: " . $filedata->technologien[$i]->image . "\n");
            fwrite($myfile, "#Bildbeschriftung: " . $filedata->technologien[$i]->imageText . "\n\n");
        }

        fwrite($myfile, "Prototype:\n\n");
        fwrite($myfile, $filedata->prototype->text . "\n");
        fwrite($myfile, "#Bild Prototypen: " .$filedata->prototype->image. "\n");
        fwrite($myfile, "#Bildbeschriftung: " . $filedata->prototype->imageText . "\n\n");

        fwrite($myfile, "Ergebnisse:\n\n");
        fwrite($myfile, $filedata->ergebnisse . "\n\n");
        fclose($myfile);

        $response = array('response' => 'File was saved to' . $filename);

    } else {
        $response = array('response' => 'Not logged in correctly');
    }
    echo json_encode($response);
?>

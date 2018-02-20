<?php
    session_start();
    include 'function.php';
    if ($_SESSION['login'] == 1) {

        $post_data = file_get_contents("php://input");
        $post_projektleiter = json_decode($post_data)->{'projektleiter'};
        $post_mitarbeiter = json_decode($post_data)->{'mitarbeiter'};
        $post_problemstellung = json_decode($post_data)->{'problemstellung'};
        $post_zielsetzung = json_decode($post_data)->{'zielsetzung'};
        $post_technologien = json_decode($post_data)->{'technologien'};
        $post_prototype = json_decode($post_data)->{'prototype'};
        $post_ergebnisse = json_decode($post_data)->{'ergebnisse'};

        $filename = '../data/' . $_SESSION['leiter'] . '/data.json';

        $filedata = null;

        $mitarbeiter = explode(",", $post_mitarbeiter);
        $technologien = explode(",", $post_technologien);

        $myfile = fopen($filename, "r");
        $rawFile = fread($myfile,filesize($filename));
        $filedata = json_decode($rawFile);
        fclose($myfile);

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

        $response = array('response' => 'File was saved to' . $filename);

    } else {
        $response = array('response' => 'Not logged in correctly');
    }
    echo json_encode($response);
?>

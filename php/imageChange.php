<?php
    error_reporting(E_ERROR | E_PARSE);
    session_start();
    include 'function.php';
    if ($_SESSION['login'] == 1) {

        $post_data = file_get_contents("php://input");
        $post_name = json_decode($post_data)->{'name'};
        $post_image = json_decode($post_data)->{'image'};

        $image_parts = explode(";base64,", $post_image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        $filename = '../data/' . $_SESSION['leiter'] . '/data.json';

        $filedata = null;

        if (file_exists($filename)) {
            $myfile = fopen($filename, "r");
            $rawFile = fread($myfile,filesize($filename));
            $filedata = json_decode($rawFile);
            fclose($myfile);
        }

        $file = '../data/' . $_SESSION['leiter'] . './images/' . $post_name . '.png';

        file_put_contents($file, $image_base64);

        switch (explode('_', $post_name)[0]) {
            case 'logo':
                $filedata->logo->image = './images/' . $post_name . '.png';
                break;
            case 'projektleiter':
                $filedata->projektleiter->image = './images/' . $post_name . '.png';
                break;
            case 'worker':
                $filedata->mitarbeiter[explode('_', $post_name)[1]]->image = './images/' . $post_name . '.png';
                break;
            case 'tech':
                $filedata->technologien[explode('_', $post_name)[1]]->image = './images/' . $post_name . '.png';
                break;
            case 'prototype':
                $filedata->prototype->image = './images/' . $post_name . '.png';
                break;
            default:
                break;
        }

        $myfile = fopen($filename, 'w');
        fwrite($myfile, json_encode($filedata));
        fclose($myfile);

        $response = array('response' => 'File was saved as: ' . $file);

    } else {
        $response = array('response' => 'Not logged in correctly');
    }
    echo json_encode($response);
?>

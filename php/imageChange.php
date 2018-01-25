<?php
    session_start();
    include 'function.php';
    if ($_SESSION['login'] == 1) {

        $post_data = file_get_contents("php://input");
        $post_name = json_decode($post_data)->{'name'};
        $post_image = json_decode($post_data)->{'image'};

        define('UPLOAD_DIR', 'data/');
        $image_parts = explode(";base64,", $post_image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = UPLOAD_DIR . $post_name . '.png';
        file_put_contents($file, $image_base64);

        $response = array('response' => 'File was saved as: ' . $file . ' the content was: ' . $image_base64);

    } else {
        $response = array('response' => 'Not logged in correctly');
    }
    echo json_encode($response);
?>

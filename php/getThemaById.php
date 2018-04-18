<?php
  session_start();
  include 'function.php';
  if ($_SESSION['login'] == 1) {
      //Creating a connection to the Database
      $pdo = create_pdo();

      $emptyFile = '{"logo":"","projektleiter":{"text":"","image":""},"mitarbeiter":[],"problemstellung":"","zielsetzung":"","technologien":[],"prototype":{"text":"","image":""},"ergebnisse":""}';

      try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->beginTransaction();

        //Getting the login-data form the post
        $post_data = file_get_contents("php://input");
        $post_id = json_decode($post_data)->{'id'};
        $post_emailS = json_decode($post_data)->{'emailS'};

        //Prepared Statement for the SQL procedure
        if ($post_emailS == 0) {
            $get_thema_stmt = $pdo->prepare('SELECT * FROM themas where leiter = :leiter');
            $get_thema_stmt->bindParam(':leiter', $_SESSION['email']);
        } else {
            $get_thema_stmt = $pdo->prepare('SELECT * FROM themas where idthema = :id and betreuer = :betreuer');
            $get_thema_stmt->bindParam(':id', $post_id);
            $get_thema_stmt->bindParam(':betreuer', $_SESSION['email']);
        }

        $get_thema_stmt->execute();
        $thema = $get_thema_stmt->fetch(PDO::FETCH_ASSOC);
        $get_thema_stmt->closeCursor();

        $filename = '../data/' . $thema['leiter'] . '/data.json';

        $filedata = null;

        if (!file_exists('../data/')) {
            mkdir('../data/');
        }

        if (!file_exists('../data/' . $thema['leiter'])) {
            mkdir('../data/' . $thema['leiter']);
            mkdir('../data/' . $thema['leiter'] . '/images');
        }

        if (!file_exists($filename)) {
            $myfile = fopen($filename, "w");
            fwrite($myfile, $emptyFile);
            fclose($myfile);
        }
        $myfile = fopen($filename, "r");
        if (filesize($filename) > 0) {
            $rawFile = fread($myfile, filesize($filename));
            $filedata = json_encode($rawFile);
            fclose($myfile);
        }


        $themaData = array('name' => $thema['name'],
                        'leiterEmail' => $thema['leiter'],
                        'betreuer' => $thema['betreuer']
                    );

        $thema = $thema['idthema'];
        $response = array('response' => 'No Errors', 'themaRe' => $themaData, 'data' => $filedata);


        $_SESSION['leiter'] = $themaData['leiterEmail'];

        $pdo->commit();
      } catch (Exception $e) {
        //On SQL Error
        $pdo->rollBack();
        $response = array('response' => 'There was an error with the SQL request');
      }
  } else {
      $response = array('response' => 'Not logged in correctly');
  }
  echo json_encode($response);
?>

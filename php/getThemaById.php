<?php
  session_start();
  include 'function.php';
  if ($_SESSION['login'] == 1) {
      //Creating a connection to the Database
      $pdo = create_pdo();

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

        $themaRe = array('name' => $thema['name'],
                        'leiter' => $thema['leiter'],
                        'betreuer' => $thema['betreuer'],
                        'mitarbeiter' => array(),
                        'technologies' => array());

        $thema = $thema['idthema'];
        $response = array('response' => 'No Errors', 'themaRe' => $themaRe);


        $_SESSION['leiter'] = $themaRe['leiter'];

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

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
            $get_thema_stmt = $pdo->prepare('SELECT * FROM themas where leiter = :leiter;');
            $get_thema_stmt->bindParam(':leiter', $_SESSION['email']);
        } else {
            $get_thema_stmt = $pdo->prepare('SELECT * FROM themas where idthema = :id and betreuer = :betreuer;');
            $get_thema_stmt->bindParam(':id', $post_id);
            $get_thema_stmt->bindParam(':betreuer', $_SESSION['email']);
        }

        $get_thema_stmt->execute();
        $thema = $get_thema_stmt->fetch(PDO::FETCH_ASSOC);
        $get_thema_stmt->closeCursor();

        $filename = $thema['projektleiterText'];

        if(file_exists($filename)){
            $fh = fopen($filename, "rb");
            $thema['projektleiterText'] = fread($fh, filesize($filename));
            fclose($fh);
        }else{
            $thema['projektleiterText'] = null;
        }

        $themaRe = array('name' => $thema['name'],
                        'leiter' => $thema['leiter'],
                        'betreuer' => $thema['betreuer'],
                        'projektleiterText' => $thema['projektleiterText'],
                        'projektleiterImage' => $thema['projektleiterImage'],
                        'mitarbeiter' => array(),
                        'technologies' => array());

        $thema = $thema['idthema'];

        $get_mitarbeiter_stmt = $pdo->prepare('SELECT * FROM mitarbeiter where themas_idthema = :id;');
        $get_mitarbeiter_stmt->bindParam(':id', $thema);

        $get_mitarbeiter_stmt->execute();
        $mitarbeiter = $get_mitarbeiter_stmt->fetchAll(PDO::FETCH_ASSOC);
        $get_mitarbeiter_stmt->closeCursor();

        $get_technologies_stmt = $pdo->prepare('SELECT * FROM technologies where themas_idthema = :id;');
        $get_technologies_stmt->bindParam(':id', $thema);

        $get_technologies_stmt->execute();
        $technologies = $get_technologies_stmt->fetchAll(PDO::FETCH_ASSOC);
        $get_technologies_stmt->closeCursor();

        for ($i=0; $i < count($mitarbeiter); $i++) {
            $filename = $mitarbeiter[$i]['text'];
            if(file_exists($filename)){
                $fh = fopen($filename, "rb");
                $text = fread($fh, filesize($filename));
                fclose($fh);
            }else{
                $text = null;
            }
            $themaRe['mitarbeiter'][$i]['text'] = $text;
            $themaRe['mitarbeiter'][$i]['image'] = $mitarbeiter[$i]['image'];
        }

        for ($i=0; $i < count($technologies); $i++) {
            $filename = $technologies[$i]['text'];
            if(file_exists($filename)){
                $fh = fopen($filename, "rb");
                $text = fread($fh, filesize($filename));
                fclose($fh);
            }else{
                $text = null;
            }
            $themaRe['technologies'][$i]['text'] = $text;
            $themaRe['technologies'][$i]['image'] = $technologies[$i]['image'];
        }



        /*



        for ($i=0; $i < count($thema); $i++) {



            $themaRe['mitarbeiter'][$i] = array('text' => $mText, 'img' => $thema[$i]['mImg']);
        }
        */
        $response = array('response' => 'No Errors', 'themaRe' => $themaRe);

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

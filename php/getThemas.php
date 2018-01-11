<?php
  session_start();
  include 'function.php';
  if ($_SESSION['login'] == 1) {
      //Creating a connection to the Database
      $pdo = create_pdo();

      try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->beginTransaction();

        //Prepared Statement for the SQL procedure
        $get_themas_stmt = $pdo->prepare("select idthema, name from themas where betreuer = :betreuer");
        $get_themas_stmt->bindParam(':betreuer', $_SESSION['email']);

        $get_themas_stmt->execute();
        $themas = $get_themas_stmt->fetchAll(PDO::FETCH_ASSOC);
        $get_themas_stmt->closeCursor();

        if (sizeof($themas) > 0) {
            $response = array('response' => 'No Errors', 'themas' => $themas);
        } else {
            $response = array('response' => 'No Themas found');
        }

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

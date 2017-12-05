<?php
  session_start();
  include 'function.php';

  //Creating a connection to the Database
  $pdo = create_pdo();

  try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    //Getting the login-data form the post
    $post_data = file_get_contents("php://input");
    $post_email = json_decode($post_data)->{'e'};
    $post_password = json_decode($post_data)->{'pw'};

    //Prepared Statement for the SQL procedure
    $get_user_stmt = $pdo->prepare("select * from users where email = :email");
    $get_user_stmt->bindParam(':email', $post_email);

    $get_user_stmt->execute();
    $userdata = $get_user_stmt->fetch();

    $get_user_stmt->closeCursor();

    if ($userdata['email'] != "" && password_verify($post_password, $userdata['passhash'])) {
      $session_id = session_id();
      //Setting Session varibles
      $_SESSION['email'] = $userdata['email'];
      $_SESSION['login'] = 1;
      setcookie("cookiezi", $_SESSION['login'], 0, "/diplcat");

      //Return value on Success
      $response = array('response' => 'There was no Error');
    } else {
      //Return value on false password verification
      $response = array('response' => 'Password verification failed');
    }
    $pdo->commit();
  } catch (Exception $e) {
    //On SQL Error
    $pdo->rollBack();
    $response = array('response' => 'There was an error with the SQL request');
  }
  //Sending the return value to the js
  echo json_encode($response);
?>

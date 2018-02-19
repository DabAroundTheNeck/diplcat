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
    $userdata = $get_user_stmt->fetch(PDO::FETCH_ASSOC);

    $get_user_stmt->closeCursor();

    if ($userdata['email'] != "" && password_verify($post_password, $userdata['passhash'])) {
      $session_id = session_id();
      //Setting Session varibles
      $_SESSION['email'] = $userdata['email'];
      $_SESSION['login'] = 1;
      setcookie("cookiezi", $_SESSION['login'], 0, "/user/diplom");
      setcookie("thema", -1, 0, "/user/diplom");

      //Return value on Success
      $response = array('response' => 1);
    } else {
      //Return value on false password verification
      $response = array('response' => 0);
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

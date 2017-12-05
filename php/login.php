<?php
  session_start();
  include 'function.php';

  //Creating a connection to the Database
  $pdo = create_pdo();

<<<<<<< HEAD
  echo "He";

=======
>>>>>>> 9853594bc8f632a4fdf9c2b22a6284b413a88d96
  try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    //Getting the login-data form the post
    $post_data = file_get_contents("php://input");
<<<<<<< HEAD
    $post_username = json_decode($post_data)->{'u'};
    $post_password = json_decode($post_data)->{'pw'};

    //Prepared Statement for the SQL procedure
    $get_user_stmt = $pdo->prepare("call get_user_username(:username)");
    $get_user_stmt->bindParam(':username', $post_username);
=======
    $post_email = json_decode($post_data)->{'e'};
    $post_password = json_decode($post_data)->{'pw'};

    //Prepared Statement for the SQL procedure
    $get_user_stmt = $pdo->prepare("select * from users where email = :email");
    $get_user_stmt->bindParam(':email', $post_email);
>>>>>>> 9853594bc8f632a4fdf9c2b22a6284b413a88d96

    $get_user_stmt->execute();
    $userdata = $get_user_stmt->fetch();

    $get_user_stmt->closeCursor();

    //Password verification
<<<<<<< HEAD
    if ($userdata['iduser'] != "" && password_verify($post_password, $userdata['password'])) {
      $session_id = session_id();
      //Checking of user is already logged in
      if (isset($userdata['session_id']) && $userdata['session_id'] != $session_id) {
        session_write_close();
        session_id($userdata['session_id']);
        session_start();
        session_unset();
        session_destroy();
        session_id($session_id);
        session_start();
      }
      //Setting Session varibles
      $_SESSION['userid'] = $userdata['iduser'];
      $_SESSION['login'] = 1;
      setcookie("cookiezi", $_SESSION['login'], 0, "/HWS");
      //Update the stored Session id
      $update_user_stmt = $pdo->prepare("call update_user_session_id(:id_in, :session_id_in)");
      $update_user_stmt->bindParam(':id_in', $userdata['iduser']);
      $update_user_stmt->bindParam(':session_id_in', $session_id);

      $update_user_stmt->execute();
      $update_user_stmt->closeCursor();
      //Return value on Success
      $response = array('response' => SUCCESS);
    } else {
      //Return value on false password verification
      $response = array('response' => FAIL);
=======
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
>>>>>>> 9853594bc8f632a4fdf9c2b22a6284b413a88d96
    }
    $pdo->commit();
  } catch (Exception $e) {
    //On SQL Error
    $pdo->rollBack();
<<<<<<< HEAD
    $response = array('response' => SQL_FAIL);
=======
    $response = array('response' => 'There was an error with the SQL request');
>>>>>>> 9853594bc8f632a4fdf9c2b22a6284b413a88d96
  }
  //Sending the return value to the js
  echo json_encode($response);
?>

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
    $get_userCount_stmt = $pdo->prepare("select count(*) as Count from users where email = :email");
    $get_userCount_stmt->bindParam(':email', $post_email);

    $get_userCount_stmt->execute();
    $userCount = $get_userCount_stmt->fetch(PDO::FETCH_ASSOC);

    $get_userCount_stmt->closeCursor();

    if ($userCount['Count'] <= 0) {
        $insert_user_stmt = $pdo->prepare("insert into users(email, passhash) values(:email, :passhash)");
        $insert_user_stmt->bindParam(':email', $post_email);
        $insert_user_stmt->bindParam(':passhash', password_hash($post_password, PASSWORD_DEFAULT));

        $insert_user_stmt->execute();

        $session_id = session_id();
        $_SESSION['email'] = $post_password;
        $_SESSION['login'] = 1;
        setcookie("cookiezi", $_SESSION['login'], 0, "/diplcat");

        $response = array('response' => 'There was no Error');
    } else {
        $response = array('response' => 'This email is already in use');
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

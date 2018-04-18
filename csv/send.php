<?php
include '../php/function.php';

$pdo = create_pdo();

function updateUser($pdo,$email,$pwd) {
    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->beginTransaction();
        $update_user_stmt = $pdo->prepare("update users set passhash=:passhash where email=:email");
        $passhash = password_hash($pwd, PASSWORD_DEFAULT);
        $update_user_stmt->bindParam(':passhash',$passhash);
        $update_user_stmt->bindParam(':email', $email);
        $update_user_stmt->execute();

        $response = array('response' => 'Projekts inserted');
        $pdo->commit();
    } catch (Exception $e) {
        //On SQL Error
        $pdo->rollBack();
        $response = array('response' => 'InsertThema: There was an error with the SQL request Error: '.$e);
    }
    //echo $response['response'];
}


if (count($argv) ==3 && $argv[2] == "87654321")  {

  $handle = fopen($argv[1], "r");
  if ($handle) {
    while (($line = fgets($handle)) !== false) {
        // process the line read.
        $line = str_replace("\n","",$line);
        $lineArray= explode(";", $line);
        echo $lineArray[0] . ";" . $lineArray[1] . ";". password_hash($lineArray[1], PASSWORD_DEFAULT) . ";\n";
        //updateUser($pdo,$lineArray[0],$lineArray[1]);
    }

    fclose($handle);
  } else {
    // error opening the file.
  }
}
?>

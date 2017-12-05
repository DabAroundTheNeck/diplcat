<?php
    //Creating a PDO Connection
    function create_pdo() {
        return new PDO('mysql:host=localhost;dbname=diplcat;charset=utf8', 'root', '');
    }
?>

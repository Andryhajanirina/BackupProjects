<?php
    /*
        Configuration de connexion personnel
    */ 
    $dbHost = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "hd_video_app";

    $results["error"] = false;
    $results["message"] = [];

    try{
        $pdo = new PDO("mysql:host=".$dbHost.";dbname=".$dbName, $dbUsername, $dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db_conn = $pdo;
    }catch(PDOException $e){
        die("Filed to connect with mysql :".$e.getMessage());
    }

?>
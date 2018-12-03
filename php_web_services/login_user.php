<?php //include("includes/connection.php");
	// require_once("includes/personal_connection.php");
	require_once("includes/connection.php");

 	// $_POST["user_pseudo"] = "ravo";
  //   $_POST["user_password"] = "ravo";

	// S'il existe un variable poste, on enverra le résultat format json
	if(isset($_POST)){

	    if(!empty($_POST["user_pseudo"]) && !empty($_POST["user_password"])){

	        $pseudo = $_POST["user_pseudo"];
	        $password = $_POST["user_password"];

	        $sql = $pdo->prepare("SELECT * FROM tbl_users WHERE user_pseudo = :pseudo");
	        $sql->execute([":pseudo" => $pseudo]);
	        $row = $sql->fetch(PDO::FETCH_OBJ); //Récupère le résultat de la requette sous forme d'objet

	        if($row){
	            if(password_verify($password, $row->encrypted_password)){
	                $results["error"] = false;
	                $results["id"] = $row->id;
	                $results["user_pseudo"] = $row->user_pseudo;
	            }else{
	                $results["error"] = true;
	                $results["message"] = "Pseudo ou mot de passe incorrect";
	            }
	        }else{ // Si on ne trouve pas de résultat
	            $results["error"] = true;
	            $results["message"] = "Pseudo ou mot de passe incorrect";
	        }
	        
	    }else{
	        $results["error"] = true;
	        $results["message"] = "Veuillez remplir tous les champs";
	    }

	    echo json_encode($results);
	}	
	


?> 
<?php
    require 'vendor/autoload.php';
    require_once("includes/personal_connection.php");
    // require_once("includes/connection.php");
    include_once("includes/email_authentification.php");
    use Mailgun\Mailgun;

    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();

/*TEST*/
/*$_POST["user_last_name"] = "Rado";
$_POST["user_first_name"] = "rakoto";
$_POST["user_pseudo"] = "rakoto";
$_POST["user_email"] = "rakotora@gmail.com";
$_POST["user_password"] = "123456";
$_POST["password_confirm"] = "123456";*/
/*TEST*/




    // S'il existe un variable poste, on enverra le résultat format json
    if(isset($_POST)){
        //echo json_encode($_POST);
        if(!empty($_POST["user_last_name"]) &&
            !empty($_POST["user_first_name"]) &&
            !empty($_POST["user_pseudo"]) &&
            !empty($_POST["user_email"]) &&
            !empty($_POST["user_password"]) &&
            !empty($_POST["password_confirm"]))
        {
            $nom = $_POST["user_last_name"];
            $prenom = $_POST["user_first_name"];
            $pseudo = $_POST["user_pseudo"];
            $email = $_POST["user_email"];
            $password = $_POST["user_password"];
            $password_confirm = $_POST["password_confirm"];

            //Vérification du nom
            //Le nom doit être alphabétique et plus de 2 caractère
            if(strlen($nom) < 2 ||
                !preg_match("/^[a-zA-Z -]+$/", $nom))
            {
                    $results["error"] = true;
                    $results["message"]["user_last_name"] = "Nom invalide";
            }

            //Vérification du prénom
            //Le prénom doit être alphabétique et plus de 2 caractère
            if(strlen($prenom) < 2 ||
                !preg_match("/^[a-zA-Z -]+$/", $prenom))
            {
                    $results["error"] = true;
                    $results["message"]["user_first_name"] = "Prénom invalide";
            }

            //Vérification du pseudo
            if(strlen($pseudo) < 2 ||
                !preg_match("/^[a-zA-Z0-9 _-]+$/", $pseudo) ||
                strlen($pseudo) > 60){
                    $results["error"] = true;
                    $results["message"]["user_pseudo"] = "Pseudo invalide";
            }else{
                //Vérifier que le pseudo n'existe pas
                $requette = $pdo->prepare("SELECT id FROM tbl_users WHERE user_pseudo = :pseudo");
                $requette->execute([":pseudo" => $pseudo]);

                $row = $requette->fetch();

                if($row){
                    $results["error"] = true;
                    $results["message"]["user_pseudo"] = "Le pseudo est déjà pris";
                }
            }

            //Vérification de l'email
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $results["error"] = true;
                $results["message"]["user_email"] = "Email invalide";
            }else{
                //Vérifier que l'email n'existe pas
                $requette = $pdo->prepare("SELECT id FROM tbl_users WHERE user_email = :email");
                $requette->execute([":email" => $email]);

                $row = $requette->fetch();
                if($row){
                    $results["error"] = true;
                    $results["message"]["user_email"] = "L'email existe déjà";
                }
            }

            //Vérification du password
            if($password !== $password_confirm){
                $results["error"] = true;
                $results["message"]["user_password"] = "Les mots de passe doivent être identique";
            }

            if($results["error"] === false)
            {
                # Instantiate the client. with api-key for parametter
                $mgClient = new Mailgun($_ENV['MAILGUN_API_KEY']);

                // A remplacer par le nom de domaine réelle
                $domain = "sandbox8696153101784b64bdc9443716fd6514.mailgun.org";

                $password = password_hash($password, PASSWORD_BCRYPT);

                // Générer un token qu'on utilisera pour pour la validation mail
                $token = bin2hex(openssl_random_pseudo_bytes(32));
                

                //Insertion
                $sql = $pdo->prepare(
                    "INSERT INTO tbl_users(
                        user_last_name,
                        user_first_name,
                        user_pseudo,
                        user_email,
                        encrypted_password,
                        token,
                        status)
                    VALUES(
                        :nom,
                        :prenom,
                        :pseudo,
                        :email,
                        :password,
                        :token,
                        :status)
                    ");

                $sql->execute([
                    ":nom" => $nom,
                    ":prenom" => $prenom,
                    ":pseudo" => $pseudo,
                    ":email" => $email,
                    ":password" => $password,
                    ":token" => $token,
                    ":status" => 0
                ]);

                $userId = $pdo->lastInsertId();

                //Construct the URL.
                $url = "http://192.168.88.166/hdvideo2424/united_malagasy/verify.php?token=$token&user=$userId";

                //Build the HTML for the link.
                $link = "<a href='" . $url . "'>Confirmer votre inscription</a>";

                $titre = "Bonjour $nom $prenom alias <strong>$pseudo</strong>";

                $contenu = "Vous venez de vous inscrire via notre application mobile <strong>UNITED MALAGASY</strong>. A fin de confirmer votre inscription, veillez cliquer sur le lien suivant";

                $message = templateAuthentification($titre, $contenu, $link);

                // Envoi de mail
/*                
                $resultat = $mgClient->sendMessage($domain,
                [
                  'from'    => 'andryhaj@gmail.com', //Sender
                  'to'      => "$email", //Destinataire = user
                  'subject' => 'Authentification',
                  'html'    => "$message"
                ]);*/
                
                // var_dump($sql);
                // echo $userId;

                if(!$sql){
                    $results["error"] = true;
                    $results["message"] = "Erreur lors de l'inscription";
                }

                // Envoi de mail
                
                $resultat = $mgClient->sendMessage($domain,
                [
                  'from'    => 'andryhaj@gmail.com', //Sender
                  'to'      => "$email", //Destinataire = user
                  'subject' => 'Authentification',
                  'html'    => "$message"
                ]);
            }
        }else{
            $results["error"] = true;
            $results["message"] = "Veuillez remplir tous les champs";
        }

        echo json_encode($results);
    }

?>
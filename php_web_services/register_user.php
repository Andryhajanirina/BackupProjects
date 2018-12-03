<?php
    require 'vendor/autoload.php';
    // require_once("includes/personal_connection.php");
    require_once("includes/connection.php");
    require_once("includes/mail_templating.php");
    use Mailgun\Mailgun;

    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();

    // S'il existe un variable poste, on enverra le résultat format json
    if(isset($_POST)){
        //echo json_encode($_POST);
        if(!empty($_POST["user_name"]) &&
            !empty($_POST["user_pseudo"]) &&
            !empty($_POST["user_email"]) &&
            !empty($_POST["user_password"]) &&
            !empty($_POST["password_confirm"]))
        {
            $nom = $_POST["user_name"];
            $pseudo = $_POST["user_pseudo"];
            $email = $_POST["user_email"];
            $password = $_POST["user_password"];
            $password_confirm = $_POST["password_confirm"];

            //Vérification du nom
            if(strlen($nom) < 2 ||
                !preg_match("/^[a-zA-Z0-9 -]+$/", $nom) ||
                strlen($nom) > 60)
            {
                    $results["error"] = true;
                    $results["message"]["user_name"] = "Nom invalide";
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

            if($results["error"] === false){
                # Instantiate the client. with api-key for parametter
                $mgClient = new Mailgun($_ENV['MAILGUN_API_KEY']);

                // A remplacer par le nom de domaine réelle
                $domain = "sandbox8696153101784b64bdc9443716fd6514.mailgun.org";

                $password = password_hash($password, PASSWORD_BCRYPT);

                // Générer un token qu'on utilisera pour pour la validation mail
                $token = bin2hex(openssl_random_pseudo_bytes(32));
                

                //Insertion
                $sql = $pdo->prepare("INSERT INTO tbl_users(user_name, user_pseudo, user_email, encrypted_password, token, status) VALUES(:nom, :pseudo, :email, :password, :token, :status)");
                $sql->execute([
                    ":nom" => $nom,
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


                // Envoi de mail
                $resultat = $mgClient->sendMessage($domain,
                [
                  'from'    => 'andryhaj@gmail.com', //Sender
                  'to'      => "$email", //Destinataire = user
                  'subject' => 'Authentification',
                  'text'    => "Bonjour $nom alias $pseudo, vous venez de vous inscrire via notre application mobile UNITED MALAGASY. A fin de confirmer votre inscription, veillez cliquer sur le lien suivant $link"
                ]);

                if(!$sql){
                    $results["error"] = true;
                    $results["message"] = "Erreur lors de l'inscription";
                }
            }
        }else{
            $results["error"] = true;
            $results["message"] = "Veuillez remplir tous les champs";
        }

        echo json_encode($results);
    }

?>
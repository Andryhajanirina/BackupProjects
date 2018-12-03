<?php
    require 'vendor/autoload.php';
    // require_once("includes/personal_connection.php");
    require_once("includes/connection.php");
    use Mailgun\Mailgun;

    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();

	//Make sure that our query string parameters exist.
	if(isset($_GET['token']) && isset($_GET['user'])){
	    $token = trim($_GET['token']);
	    $userId = trim($_GET['user']);
	    
	    $sql = "SELECT user_email, user_pseudo, COUNT(*) AS num FROM tbl_users WHERE id = :user_id AND token = :token";
	    $stmt = $pdo->prepare($sql);
	    $stmt->bindParam(':user_id', $userId);
	    $stmt->bindParam(':token', $token);
	    $stmt->execute();
	    
	    $result = $stmt->fetch(PDO::FETCH_ASSOC);
	    if($result['num'] == 1){
	        //Token is valid. Verify the email address
	        $sql = "UPDATE tbl_users SET status = :status WHERE id = :user_id";
	        $stmt= $pdo->prepare($sql);
	        $stmt->execute([":status" => 1, ":user_id" => $userId]);

	        // Envoi mail de confirmation
		    $mgClient = new Mailgun($_ENV['MAILGUN_API_KEY']);
		    $domain = "sandbox8696153101784b64bdc9443716fd6514.mailgun.org";

		    $user_pseudo = $result['user_pseudo'];
            $titre = "Félicitation $user_pseudo";

            $link = "http://192.168.88.166/hdvideo2424/united_malagasy/index.php"
            
            $contenu = "Votre inscription vien d'être validé.";
            $message = templateConfirmation($titre, $contenu, $link);

		    # Make the call to the client.

		    $resultat = $mgClient->sendMessage($domain,
		            [
					  'from'    => 'andryhaj@gmail.com',
					  'to'      => $result['user_email'],
					  'subject' => 'Mail de confirmation',
					  'text'    => $message
					]);
	    } else{
	        //Token is not valid.
	        echo "token non valide";
	    }
	    
	}
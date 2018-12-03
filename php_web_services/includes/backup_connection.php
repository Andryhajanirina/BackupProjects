<?php
    error_reporting(0);
 		 ob_start();
    session_start();
 
 	header("Content-Type: text/html;charset=UTF-8");
	
		// Pour le démo original
		// if($_SERVER['HTTP_HOST']=="localhost" or $_SERVER['HTTP_HOST']=="192.168.1.125")

 		// Pour le démo SMTP
		// if($_SERVER['HTTP_HOST']=="localhost" or $_SERVER['HTTP_HOST']=="192.168.88.166")

 		// Pour le démo SAYNA
		if($_SERVER['HTTP_HOST']=="localhost" or 
			$_SERVER['HTTP_HOST']=="192.168.1.26" or 
			$_SERVER['HTTP_HOST']=="192.168.1.70" or
			$_SERVER['HTTP_HOST']=="192.168.88.166")
		{	
			//local  

				 DEFINE ('DB_USER', 'root');
				 DEFINE ('DB_PASSWORD', '');
				 DEFINE ('DB_HOST', 'localhost'); //host name depends on server
				 DEFINE ('DB_NAME', 'hd_video_app');

				 $results["error"] = false;
				 $results["message"] = [];
		}
		else
		{
			//local live 

		 	 DEFINE ('DB_USER', '');
			 DEFINE ('DB_PASSWORD', '');
			 DEFINE ('DB_HOST', 'localhost'); //host name depends on server
			 DEFINE ('DB_NAME', '');

			 $results["error"] = false;
			 $results["message"] = [];
		}

	
	// $mysqli =mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME); //Orientée Objet

	if ($mysqli->connect_errno) 
	{
    	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}

	// mysqli_query($mysqli,"SET NAMES 'utf8'");
	$mysqli->query("SET NAMES utf8"); //Orientée objet	 	 



	//Settings
	$sql = "SELECT * FROM tbl_settings where id='1'";
	$setting_result = $mysqli->query($sql);
	$settings_details = $setting_result->fetch_assoc();

    define("APP_NAME",$settings_details['app_name']);
    define("APP_LOGO",$settings_details['app_logo']);

    define("ONESIGNAL_APP_ID",$settings_details['onesignal_app_id']);
    define("ONESIGNAL_REST_KEY",$settings_details['onesignal_rest_key']);

    define("API_LATEST_LIMIT",$settings_details['api_latest_limit']);
    define("API_CAT_ORDER_BY",$settings_details['api_cat_order_by']);
    define("API_CAT_POST_ORDER_BY",$settings_details['api_cat_post_order_by']);
    define("API_ALL_VIDEO_ORDER_BY",$settings_details['api_all_order_by']);
    

    //Profile
    if(isset($_SESSION['id']))
    {
    	$profile_qry="SELECT * FROM tbl_admin where id='".$_SESSION['id']."'";
	    $profile_result=mysqli_query($mysqli,$profile_qry);
	    $profile_details=mysqli_fetch_assoc($profile_result);

	    define("PROFILE_IMG",$profile_details['image']);
    }
    
	
 
?> 
	 
 
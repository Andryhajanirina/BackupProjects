<?php include("includes/connection.php");
	 

	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
	$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

	  
	if($username=="")
	{
		 $_SESSION['msg']="1"; 
		 header( "Location:index.php");
		 exit;
		 
	}
	else if($password=="")
	{
		$_SESSION['msg']="2"; 
		header( "Location:index.php");
		exit;		 
	}	 
	else
	{
		$result = $pdo->query("select * from tbl_admin where username='".$username."' and password='".md5($password)."'");//nanao modif teo za
		 
		// $result=mysqli_query($mysqli,$qry);		
		
		// $num_rows = $result->rowCount();
		if($result->rowCount() > 0)
		{ 
			$row = $result->fetch(PDO::FETCH_ASSOC);

			$_SESSION['id']=$row['id'];
		    $_SESSION['admin_name']=$row['username'];
			  
			header( "Location:home.php");
			exit;
				
		}
		else
		{
			$_SESSION['msg']="4"; 
			header( "Location:index.php");
			exit;
			 
		}
	}
	
	
	


?> 
<?php include("includes/connection.php");
 	  include("includes/function.php"); 	
	
	$file_path = 'http://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/';
 	 
	if(isset($_GET['cat_list']))
 	{
 		$jsonObj= array();
		
		$cat_order=API_CAT_ORDER_BY;


		/*$query="SELECT cid,category_name,category_image FROM tbl_category WHERE status=1 ORDER BY tbl_category.".$cat_order."";
		$sql = mysqli_query($mysqli,$query)or die(mysql_error());*/

		try {
			$sql = $pdo->query("SELECT cid,category_name,category_image FROM tbl_category WHERE status=1 ORDER BY tbl_category.".$cat_order."");
		} catch (Exception $e) {
			die($e->getMessage());
		}

		while($data = $sql->fetch(PDO::FETCH_ASSOC))
		{
			 

			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];
			$row['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];
 
			array_push($jsonObj,$row);
		
		}

		$set['HD_VIDEO'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 	}
	else if(isset($_GET['cat_id']))
	{
		$post_order_by=API_CAT_POST_ORDER_BY;

		$cat_id=$_GET['cat_id'];	

		$query_rec = $pdo->query("SELECT COUNT(*) as num FROM tbl_video
		LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid 
		where tbl_video.cat_id='".$cat_id."' AND tbl_video.status='1'");

		$total_pages = $query_rec->fetch(PDO::FETCH_ASSOC);
		
			
		$limit=($_GET['page']-1) * 10;


		$jsonObj= array();	
	
	    $query = "SELECT * FROM tbl_video
		LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid 
		where tbl_video.cat_id='".$cat_id."' AND tbl_video.status='1' ORDER BY tbl_video.id ".$post_order_by." LIMIT $limit, 10";

		try {
			$sql = $pdo->query($query);
			// $res = mysqli_query($mysqli,$query)or die(mysqli_error());
		} catch (Exception $e) {
			die($e->getMessage());
		}

		

		$row['num'] = $total_pages['num'];
		
		while($data = $sql->fetch(PDO::FETCH_ASSOC))
		{
			
			$row['id'] = $data['id'];
			$row['cat_id'] = $data['cat_id'];
			$row['video_type'] = $data['video_type'];
			$row['video_title'] = $data['video_title'];
			$row['video_url'] = $data['video_url'];
			$row['video_id'] = $data['video_id'];
			
			if($data['video_type']=='server_url' or $data['video_type']=='local')
			{
				$row['video_thumbnail_b'] = $file_path.'images/'.$data['video_thumbnail'];
				$row['video_thumbnail_s'] = $file_path.'images/thumbs/'.$data['video_thumbnail'];
			}
			else
			{
				$row['video_thumbnail_b'] = $data['video_thumbnail'];
				$row['video_thumbnail_s'] = $data['video_thumbnail'];
			}
 
			$row['video_duration'] = $data['video_duration'];
			$row['video_description'] = $data['video_description'];
			$row['total_views'] = $data['total_views'];

			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];
			$row['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];
			 

			array_push($jsonObj,$row);
		
		}

		$set['HD_VIDEO'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

		
	}
	else if(isset($_GET['all_videos']))
	{
		$post_order_by=API_CAT_POST_ORDER_BY;

 
		$query_rec = $pdo->query("SELECT COUNT(*) as num FROM tbl_video
		LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid 
		WHERE tbl_video.status='1'");

		$total_pages = $query_rec->fetch(PDO::FETCH_ASSOC);
		
			
		$limit=($_GET['page']-1) * 10;


		$jsonObj= array();	
	
	    $query="SELECT * FROM tbl_video
		LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid 
		WHERE tbl_video.status='1' ORDER BY tbl_video.id ".$post_order_by." LIMIT $limit, 10";

		try {
			$sql = $pdo->query($query);
		} catch (Exception $e) {
			die($e->getMessage());
		}

		$row['num'] = $total_pages['num'];
		
		while($data = $sql->fetch(PDO::FETCH_ASSOC))
		{
			
			$row['id'] = $data['id'];
			$row['cat_id'] = $data['cat_id'];
			$row['video_type'] = $data['video_type'];
			$row['video_title'] = $data['video_title'];
			$row['video_url'] = $data['video_url'];
			$row['video_id'] = $data['video_id'];
			
			if($data['video_type']=='server_url' or $data['video_type']=='local')
			{
				$row['video_thumbnail_b'] = $file_path.'images/'.$data['video_thumbnail'];
				$row['video_thumbnail_s'] = $file_path.'images/thumbs/'.$data['video_thumbnail'];
			}
			else
			{
				$row['video_thumbnail_b'] = $data['video_thumbnail'];
				$row['video_thumbnail_s'] = $data['video_thumbnail'];
			}
 
			$row['video_duration'] = $data['video_duration'];
			$row['video_description'] = $data['video_description'];
			$row['total_views'] = $data['total_views'];

			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];
			$row['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];
			 

			array_push($jsonObj,$row);
		
		}

		$set['HD_VIDEO'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

		
	}	 
	else if(isset($_GET['latest']))
	{
		//$limit=$_GET['latest'];	 

		$limit=API_LATEST_LIMIT;

		$jsonObj= array();	
 
		$query="SELECT * FROM tbl_video
		LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid 
		WHERE tbl_video.status='1' ORDER BY tbl_video.id DESC LIMIT $limit";

		try {
			$sql = $pdo->query($query);
		} catch (Exception $e) {
			die($e->getMessage());
		}

		while($data = $sql->fetch(PDO::FETCH_ASSOC))
		{
			$row['id'] = $data['id'];
			$row['cat_id'] = $data['cat_id'];
			$row['video_type'] = $data['video_type'];
			$row['video_title'] = $data['video_title'];
			$row['video_url'] = $data['video_url'];
			$row['video_id'] = $data['video_id'];

			if($data['video_type']=='server_url' or $data['video_type']=='local')
			{
				$row['video_thumbnail_b'] = $file_path.'images/'.$data['video_thumbnail'];
				$row['video_thumbnail_s'] = $file_path.'images/thumbs/'.$data['video_thumbnail'];
			}
			else
			{
				$row['video_thumbnail_b'] = $data['video_thumbnail'];
				$row['video_thumbnail_s'] = $data['video_thumbnail'];
			}

			$row['video_duration'] = $data['video_duration'];
			$row['video_description'] = $data['video_description'];
			$row['total_views'] = $data['total_views'];

 			 

			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];
			$row['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];
			 

			array_push($jsonObj,$row);
		
		}

		$set['HD_VIDEO'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
	else if(isset($_GET['search_text']))
	{ 
 
		$jsonObj= array();	
 
		$query="SELECT * FROM tbl_video
		LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid 
		WHERE tbl_video.status='1' AND tbl_video.video_title like '%".$_GET['search_text']."%' ORDER BY tbl_video.video_title DESC";

		try {
			$sql = $pdo->query($query);
		} catch (Exception $e) {
			die($e->getMessage());
		}

		while($data = $sql->fetch(PDO::FETCH_ASSOC))
		{
			$row['id'] = $data['id'];
			$row['cat_id'] = $data['cat_id'];
			$row['video_type'] = $data['video_type'];
			$row['video_title'] = $data['video_title'];
			$row['video_url'] = $data['video_url'];
			$row['video_id'] = $data['video_id'];

			if($data['video_type']=='server_url' or $data['video_type']=='local')
			{
				$row['video_thumbnail_b'] = $file_path.'images/'.$data['video_thumbnail'];
				$row['video_thumbnail_s'] = $file_path.'images/thumbs/'.$data['video_thumbnail'];
			}
			else
			{
				$row['video_thumbnail_b'] = $data['video_thumbnail'];
				$row['video_thumbnail_s'] = $data['video_thumbnail'];
			}

			$row['video_duration'] = $data['video_duration'];
			$row['video_description'] = $data['video_description'];
			$row['total_views'] = $data['total_views'];

 			 

			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];
			$row['category_image'] = $file_path.'images/'.$data['category_image'];
			$row['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];
			 

			array_push($jsonObj,$row);
		
		}

		$set['HD_VIDEO'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}	 
	else if(isset($_GET['home']))
	{
		$jsonObj_0= array();	
 
		$query_featured="SELECT * FROM tbl_video
		LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid 
		WHERE tbl_video.featured_video='1' ORDER BY tbl_video.total_views DESC";

		try {
			$sql_featured= $pdo->query($query_featured);
		} catch (Exception $e) {
			die($e->getMessage());
		}

		while($data_featured = $sql_featured->fetch(PDO::FETCH_ASSOC))
		{
			$row_0['id'] = $data_featured['id'];
			$row_0['cat_id'] = $data_featured['cat_id'];
			$row_0['video_type'] = $data_featured['video_type'];
			$row_0['video_title'] = $data_featured['video_title'];
			$row_0['video_url'] = $data_featured['video_url'];
			$row_0['video_id'] = $data_featured['video_id'];
			 
			if($data_featured['video_type']=='server_url' or $data_featured['video_type']=='local')
			{
				$row_0['video_thumbnail_b'] = $file_path.'images/'.$data_featured['video_thumbnail'];
				$row_0['video_thumbnail_s'] = $file_path.'images/thumbs/'.$data_featured['video_thumbnail'];
			}
			else
			{
				$row_0['video_thumbnail_b'] = $data_featured['video_thumbnail'];
				$row_0['video_thumbnail_s'] = $data_featured['video_thumbnail'];
			}

			$row_0['video_duration'] = $data_featured['video_duration'];
			$row_0['video_description'] = $data_featured['video_description'];
			$row_0['total_views'] = $data_featured['total_views'];
 			 

			$row_0['cid'] = $data_featured['cid'];
			$row_0['category_name'] = $data_featured['category_name'];
			$row_0['category_image'] = $file_path.'images/'.$data_featured['category_image'];
			$row_0['category_image_thumb'] = $file_path.'images/thumbs/'.$data_featured['category_image'];
			 
			 
			array_push($jsonObj_0,$row_0);
		
		}

		$row['featured_videos']=$jsonObj_0;
		
		$jsonObj_1= array();	
 
		$query_views="SELECT * FROM tbl_video
		LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid 
		WHERE tbl_video.status='1' ORDER BY tbl_video.total_views DESC LIMIT 4";

		try {
			$sql_views = $pdo->query($query_views);
		} catch (Exception $e) {
			die($e->getMessage());
		}

		while($data_view = $sql_views->fetch(PDO::FETCH_ASSOC))
		{
			$row0['id'] = $data_view['id'];
			$row0['cat_id'] = $data_view['cat_id'];
			$row0['video_type'] = $data_view['video_type'];
			$row0['video_title'] = $data_view['video_title'];
			$row0['video_url'] = $data_view['video_url'];
			$row0['video_id'] = $data_view['video_id'];
			 
			if($data_view['video_type']=='server_url' or $data_view['video_type']=='local')
			{
				$row0['video_thumbnail_b'] = $file_path.'images/'.$data_view['video_thumbnail'];
				$row0['video_thumbnail_s'] = $file_path.'images/thumbs/'.$data_view['video_thumbnail'];
			}
			else
			{
				$row0['video_thumbnail_b'] = $data_view['video_thumbnail'];
				$row0['video_thumbnail_s'] = $data_view['video_thumbnail'];
			}

			$row0['video_duration'] = $data_view['video_duration'];
			$row0['video_description'] = $data_view['video_description'];
			$row0['total_views'] = $data_view['total_views'];
 			 

			$row0['cid'] = $data_view['cid'];
			$row0['category_name'] = $data_view['category_name'];
			$row0['category_image'] = $file_path.'images/'.$data_view['category_image'];
			$row0['category_image_thumb'] = $file_path.'images/thumbs/'.$data_view['category_image'];
			 
			 
			array_push($jsonObj_1,$row0);
		
		}

		$row['most_viewed']=$jsonObj_1;


		$jsonObj_2= array();	
 
		$query="SELECT * FROM tbl_video
		LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid 
		WHERE tbl_video.status='1' ORDER BY tbl_video.id DESC LIMIT 4";

		try {
			$sql = $pdo->query($query);
		} catch (Exception $e) {
			die($e->getMessage());
		}

		while($data = $sql->fetch(PDO::FETCH_ASSOC))
		{
			$row1['id'] = $data['id'];
			$row1['cat_id'] = $data['cat_id'];
			$row1['video_type'] = $data['video_type'];
			$row1['video_title'] = $data['video_title'];
			$row1['video_url'] = $data['video_url'];
			$row1['video_id'] = $data['video_id'];
			 
			if($data['video_type']=='server_url' or $data['video_type']=='local')
			{
				$row1['video_thumbnail_b'] = $file_path.'images/'.$data['video_thumbnail'];
				$row1['video_thumbnail_s'] = $file_path.'images/thumbs/'.$data['video_thumbnail'];
			}
			else
			{
				$row1['video_thumbnail_b'] = $data['video_thumbnail'];
				$row1['video_thumbnail_s'] = $data['video_thumbnail'];
			}

			$row1['video_duration'] = $data['video_duration'];
			$row1['video_description'] = $data['video_description'];
			$row1['total_views'] = $data['total_views'];
 			 

			$row1['cid'] = $data['cid'];
			$row1['category_name'] = $data['category_name'];
			$row1['category_image'] = $file_path.'images/'.$data['category_image'];
			$row1['category_image_thumb'] = $file_path.'images/thumbs/'.$data['category_image'];
			 
			 
			array_push($jsonObj_2,$row1);
		
		}

		$row['latest_video']=$jsonObj_2;


		$jsonObj_3= array();	

	    $cat_order=API_CAT_ORDER_BY;


		$cat_query="SELECT cid,category_name,category_image FROM tbl_category ORDER BY tbl_category.".$cat_order." LIMIT 4";

		try {
			$cat_sql = $pdo->query($cat_query);
		} catch (Exception $e) {
			die($e->getMessage());
		}

		while($data_3 = $cat_sql->fetch(PDO::FETCH_ASSOC))
		{
			 

			$row3['cid'] = $data_3['cid'];
			$row3['category_name'] = $data_3['category_name'];
			$row3['category_image'] = $file_path.'images/'.$data_3['category_image'];
			$row3['category_image_thumb'] = $file_path.'images/thumbs/'.$data_3['category_image'];
 
			array_push($jsonObj_3,$row3);		
		}
	 

		$row['all_video_cat']=$jsonObj_3; 

		$set['HD_VIDEO'] = $row;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
	else if(isset($_GET['most_viewed']))
	{

		$jsonObj_1= array();	
 
		$query_views="SELECT * FROM tbl_video
		LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid 
		WHERE tbl_video.status='1' ORDER BY tbl_video.total_views DESC";

		try {
			$sql_views = $pdo->query($query_views);
		} catch (Exception $e) {
			die($e->getMessage());
		}

		while($data_view = $sql_views->fetch(PDO::FETCH_ASSOC))
		{
			$row0['id'] = $data_view['id'];
			$row0['cat_id'] = $data_view['cat_id'];
			$row0['video_type'] = $data_view['video_type'];
			$row0['video_title'] = $data_view['video_title'];
			$row0['video_url'] = $data_view['video_url'];
			$row0['video_id'] = $data_view['video_id'];
			 
			if($data_view['video_type']=='server_url' or $data_view['video_type']=='local')
			{
				$row0['video_thumbnail_b'] = $file_path.'images/'.$data_view['video_thumbnail'];
				$row0['video_thumbnail_s'] = $file_path.'images/thumbs/'.$data_view['video_thumbnail'];
			}
			else
			{
				$row0['video_thumbnail_b'] = $data_view['video_thumbnail'];
				$row0['video_thumbnail_s'] = $data_view['video_thumbnail'];
			}

			$row0['video_duration'] = $data_view['video_duration'];
			$row0['video_description'] = $data_view['video_description'];
			$row0['total_views'] = $data_view['total_views'];
 			 

			$row0['cid'] = $data_view['cid'];
			$row0['category_name'] = $data_view['category_name'];
			$row0['category_image'] = $file_path.'images/'.$data_view['category_image'];
			$row0['category_image_thumb'] = $file_path.'images/thumbs/'.$data_view['category_image'];
			 
			 
			array_push($jsonObj_1,$row0);
		
		}
 
		$set['HD_VIDEO'] = $jsonObj_1;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}	 
	else if(isset($_GET['video_id']))
	{
		  
				 
		$jsonObj= array();	

		$query="SELECT * FROM tbl_video
		LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
		WHERE tbl_video.id='".$_GET['video_id']."'";

		try {
			$sql = $pdo->query($query);
		} catch (Exception $e) {
			die($e->getMessage());
		}

		while($data = $sql->fetch(PDO::FETCH_ASSOC))
		{
			 
			
			$row['cat_id'] = $data['cat_id'];
			$row['category_name'] = $data['category_name'];

			$row['id'] = $data['id'];
			$row['video_type'] = $data['video_type'];
			$row['video_title'] = $data['video_title'];
			$row['video_url'] = $data['video_url'];
			$row['video_id'] = $data['video_id'];
			
			if($data['video_type']=='server_url' or $data['video_type']=='local')
			{
				$row['video_thumbnail_b'] = $file_path.'images/'.$data['video_thumbnail'];
				$row['video_thumbnail_s'] = $file_path.'images/thumbs/'.$data['video_thumbnail'];
			}
			else
			{
				$row['video_thumbnail_b'] = $data['video_thumbnail'];
				$row['video_thumbnail_s'] = $data['video_thumbnail'];
			}
			
			$row['video_duration'] = $data['video_duration'];
			$row['video_description'] = $data['video_description'];
			$row['total_views'] = $data['total_views'];
 			
 			$SQL2 = "SELECT * FROM tbl_video 
 			LEFT JOIN tbl_category ON tbl_video.cat_id= tbl_category.cid
 			WHERE tbl_video.status=1 AND tbl_video.cat_id = '".$data['cat_id']."'";

	       	$result2 = $pdo->query($SQL2);
					
			$subvidArr=array();
			while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) 
			{	
				if($data['id'] != $row2['id'])
				{		
					if($row2['video_type']=='server_url' or $row2['video_type']=='local')
					{
						$video_thumbnail_b = $file_path.'images/'.$row2['video_thumbnail'];
						$video_thumbnail_s = $file_path.'images/thumbs/'.$row2['video_thumbnail'];
					}
					else
					{
						$video_thumbnail_b = $row2['video_thumbnail'];
						$video_thumbnail_s = $row2['video_thumbnail'];
					}	

					$temp = array('rel_vid' => $row2['id'],'video_type' =>$row2['video_type'], 'video_title' => $row2['video_title'] ,'video_url' => $row2['video_url'] ,'video_id' => $row2['video_id'] ,'video_duration' => $row2['video_duration'] ,'total_views' => $row2['total_views'] ,'video_thumbnail_b' => $video_thumbnail_b,
					 'video_thumbnail_s' => $video_thumbnail_s,'category_name' => $row2['category_name']);
					$subvidArr[]=$temp;
				}
			}
			$row['related']=$subvidArr;	

			array_push($jsonObj,$row);
		
		}

		$view_qry=$pdo->query("UPDATE tbl_video SET total_views = total_views + 1 WHERE id = '".$_GET['video_id']."'");
 

		$set['HD_VIDEO'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();	
 

	}	  	 
	else 
	{
		$jsonObj= array();	

		$query="SELECT * FROM tbl_settings WHERE id='1'";
		try {
			$sql = $pdo->query($query);
		} catch (Exception $e) {
			die($e->getMessage());
		}

		while($data = $sql->fetch(PDO::FETCH_ASSOC))
		{
			 
			$row['app_name'] = $data['app_name'];
			$row['app_logo'] = $data['app_logo'];
			$row['app_version'] = $data['app_version'];
			$row['app_author'] = $data['app_author'];
			$row['app_contact'] = $data['app_contact'];
			$row['app_email'] = $data['app_email'];
			$row['app_website'] = $data['app_website'];
			$row['app_description'] = stripslashes($data['app_description']);
 			$row['app_developed_by'] = $data['app_developed_by'];

			$row['app_privacy_policy'] = stripslashes($data['app_privacy_policy']);
			
			$row['publisher_id'] = $data['publisher_id'];
			$row['interstital_ad'] = $data['interstital_ad'];
			$row['interstital_ad_id'] = $data['interstital_ad_id'];
			$row['interstital_ad_click'] = $data['interstital_ad_click'];
 			$row['banner_ad'] = $data['banner_ad'];
 			$row['banner_ad_id'] = $data['banner_ad_id'];
	

			array_push($jsonObj,$row);
		
		}

		$set['HD_VIDEO'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();	
	}		
	 
	 
?>
<?php include("includes/header.php");

$file_path = 'http://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/';
?>
<div class="row">
      <div class="col-sm-12 col-xs-12">
     	 	<div class="card">
		        <div class="card-header">
		          Example API urls
		        </div>
       			    <div class="card-body no-padding">
         		
         			 <pre><code class="html"><b>Home Videos</b><br><?php echo $file_path."api.php?home"?><br><br><b>Most View Videos</b><br><?php echo $file_path."api.php?most_viewed"?><br><br><b>Latest Videos</b><br><?php echo $file_path."api.php?latest"?><br><br><b>Category List</b><br><?php echo $file_path."api.php?cat_list"?><br><br><b>Videos list by Cat ID</b><br><?php echo $file_path."api.php?cat_id=1&page=1"?><br><br><b>Single Video</b><br><?php echo $file_path."api.php?video_id=8"?><br><br><b>Search Videos</b><br><?php echo $file_path."api.php?search_text=fashion"?><br><br><b>All Videos</b><br><?php echo $file_path."api.php?all_videos&page=1"?><br><br><b>App Details</b><br><?php echo $file_path."api.php"?></code></pre>
       		
       				</div>
          	</div>
        </div>
</div>
    <br/>
    <div class="clearfix"></div>
        
<?php include("includes/footer.php");?>       

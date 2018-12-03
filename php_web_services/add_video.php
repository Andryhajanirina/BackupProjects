<?php include("includes/header.php");

  require("includes/function.php");
  require("language/language.php");

 
  $cat_qry = "SELECT * FROM tbl_category ORDER BY category_name";
  $cat_result = $pdo->query($cat_qry); 
  
  if(isset($_POST['submit']))
  {

        if ($_POST['video_type']=='youtube')
        {

              $video_url=$_POST['video_url'];

              $youtube_video_url = addslashes($_POST['video_url']);
              parse_str( parse_url( $youtube_video_url, PHP_URL_QUERY ), $array_of_vars );
              $video_id=  $array_of_vars['v'];

              $video_thumbnail='';     

        }         
        
        if ($_POST['video_type']=='server_url')
        {
              $video_url=$_POST['video_url'];

              $video_thumbnail=rand(0,99999)."_".$_FILES['video_thumbnail']['name'];
       
              //Main Image
              $tpath1='images/'.$video_thumbnail;        
              $pic1=compress_image($_FILES["video_thumbnail"]["tmp_name"], $tpath1, 80);
         
              //Thumb Image 
              $thumbpath='images/thumbs/'.$video_thumbnail;   
              $thumb_pic1=create_thumb_image($tpath1,$thumbpath,'200','200');   

              $video_id='';

        } 

        if ($_POST['video_type']=='local')
        {

              $file_path = 'http://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/uploads/';
              
              $video_url=$file_path.$_POST['video_file_name'];

              $video_thumbnail=rand(0,99999)."_".$_FILES['video_thumbnail']['name'];
       
              //Main Image
              $tpath1='images/'.$video_thumbnail;        
              $pic1=compress_image($_FILES["video_thumbnail"]["tmp_name"], $tpath1, 80);
         
              //Thumb Image 
              $thumbpath='images/thumbs/'.$video_thumbnail;   
              $thumb_pic1=create_thumb_image($tpath1,$thumbpath,'200','200');   

              $video_id='';
        } 


          
        $data = array( 
          'cat_id'  =>  $_POST['cat_id'],
          'video_type'  =>  $_POST['video_type'],
          'video_title'  =>  $_POST['video_title'],
          'video_url'  =>  $video_url,
          'video_id'  =>  $video_id,
          'video_thumbnail'  =>  $video_thumbnail,
          'video_duration'  =>  $_POST['video_duration'],
          'video_description'  =>  $_POST['video_description'],
          );    


        /*Commenté pour test utilisé une requette directe*/
        // $qry = Insert('tbl_video',$data);

        /*TEST*/ 
        $sql = "INSERT INTO tbl_video(
                  cat_id,
                  video_type,
                  video_title,
                  video_url,
                  video_id,
                  video_thumbnail,
                  video_duration,
                  video_description
                )
                VALUES (
                  :cat_id,
                  :video_type,
                  :video_title,
                  :video_url,
                  :video_id,
                  :video_thumbnail,
                  :video_duration,
                  :video_description
                )";


          $query = $pdo->prepare($sql);
          $query->execute([
                  ":cat_id" => $data['cat_id'],
                  ":video_type" => $data['video_type'],
                  ":video_title" => $data['video_title'],
                  ":video_url" => $data['video_url'],
                  ":video_id" => $data['video_id'],
                  ":video_thumbnail" => $data['video_thumbnail'],
                  ":video_duration" => $data['video_duration'],
                  ":video_description" => $data['video_description']
          ]);
        /*TEST*/ 

      
    $_SESSION['msg']="10";
 
    header( "Location:add_video.php");
    exit; 

     
  }
  
    
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script>
            $(function () {
                $('#btn').click(function () {
                    $('.myprogress').css('width', '0');
                    $('.msg').text('');
                    var video_local = $('#video_local').val();
                    if (video_local == '') {
                        alert('Please enter file name and select file');
                        return;
                    }
                    var formData = new FormData();
                    formData.append('video_local', $('#video_local')[0].files[0]);
                    $('#btn').attr('disabled', 'disabled');
                     $('.msg').text('Uploading in progress...');
                    $.ajax({
                        url: 'uploadscript.php',
                        data: formData,
                        processData: false,
                        contentType: false,
                        type: 'POST',
                        // this part is progress bar
                        xhr: function () {
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function (evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = evt.loaded / evt.total;
                                    percentComplete = parseInt(percentComplete * 100);
                                    $('.myprogress').text(percentComplete + '%');
                                    $('.myprogress').css('width', percentComplete + '%');
                                }
                            }, false);
                            return xhr;
                        },
                        success: function (data) {
                         
                            $('#video_file_name').val(data);
                            $('.msg').text("File uploaded successfully!!");
                            $('#btn').removeAttr('disabled');
                        }
                    });
                });
            });
        </script>
<script type="text/javascript">
$(document).ready(function(e) {
           $("#video_type").change(function(){
          
           var type=$("#video_type").val();
              
              if(type=="youtube")
              { 
                //alert(type);
                $("#video_url_display").show();
                $("#video_local_display").hide();
                $("#thumbnail").hide();
              } 
              else if(type=="server_url")
              {
                 
                 $("#video_url_display").show();
                 $("#thumbnail").show();
                 $("#video_local_display").hide();
              }
              else
              {   
                     
                $("#video_url_display").hide();               
                $("#video_local_display").show();
                $("#thumbnail").show();

              }    
              
         });
        });
</script>
<div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">Add Video</div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row mrg-top">
            <div class="col-md-12">
               
              <div class="col-md-12 col-sm-12">
                <?php if(isset($_SESSION['msg'])){?> 
                 <div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                  <?php echo $client_lang[$_SESSION['msg']] ; ?></a> </div>
                <?php unset($_SESSION['msg']);}?> 
              </div>
            </div>
          </div>
          <div class="card-body mrg_bottom"> 
            <form action="" name="add_form" method="post" class="form form-horizontal" enctype="multipart/form-data">
 
              <div class="section">
                <div class="section-body">
                   <div class="form-group">
                    <label class="col-md-3 control-label">Category :-</label>
                    <div class="col-md-6">
                      <select name="cat_id" id="cat_id" class="select2" required>
                        <option value="">--Select Category--</option>
                        <?php
                            while($cat_row = $cat_result->fetch(PDO::FETCH_ASSOC))
                            {
                        ?>                       
                        <option value="<?php echo $cat_row['cid'];?>"><?php echo $cat_row['category_name'];?></option>                           
                        <?php
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Video Title :-</label>
                    <div class="col-md-6">
                      <input type="text" name="video_title" id="video_title" value="" class="form-control" required>
                    </div>
                  </div>
                   <div class="form-group">
                    <label class="col-md-3 control-label">Video duration :-</label>
                    <div class="col-md-6">
                      <input type="text" name="video_duration" id="video_duration" value="" class="form-control" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Video Type :-</label>
                    <div class="col-md-6">                       
                      <select name="video_type" id="video_type" style="width:280px; height:25px;" class="select2" required>
                            <option value="">--Select Type--</option>
                            <option value="youtube">Youtube</option>                            
                            <option value="server_url">From Server</option>
                            <option value="local">From Local</option>
                      </select>
                    </div>
                  </div>
                  <div id="video_url_display" class="form-group">
                    <label class="col-md-3 control-label">Video URL :-</label>
                    <div class="col-md-6">
                      <input type="text" name="video_url" id="video_url" value="" class="form-control">
                    </div>
                  </div>
                  <div id="video_local_display" class="form-group" style="display:none;">
                    <label class="col-md-3 control-label">Video Upload :-</label>
                    <div class="col-md-6">
                    
                    <input type="hidden" name="video_file_name" id="video_file_name" value="" class="form-control">
                      <input type="file" name="video_local" id="video_local" value="" class="form-control">

                      <div class="progress">
                            <div class="progress-bar progress-bar-success myprogress" role="progressbar" style="width:0%">0%</div>
                        </div>

                        <div class="msg"></div>
                        <input type="button" id="btn" class="btn-success" value="Upload" />
                    </div>
                  </div><br>
                  <div id="thumbnail" class="form-group" style="display:none;">
                    <label class="col-md-3 control-label">Thumbnail Image:-
                      <p class="control-label-help">(Recommended resolution: 800x400)</p>
                    </label>
                    <div class="col-md-6">
                      <div class="fileupload_block">
                        <input type="file" name="video_thumbnail" value="" id="fileupload">
                       <div class="fileupload_img"><img type="image" src="assets/images/add-image.png" alt="category image" /></div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Video Description :-</label>
                    <div class="col-md-6">                    
                      <textarea name="video_description" id="video_description" class="form-control"></textarea>

                      <script>CKEDITOR.replace( 'video_description' );</script>
                    </div>
                  </div><br>
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="submit" class="btn btn-primary">Save</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
        
<?php include("includes/footer.php");?>       

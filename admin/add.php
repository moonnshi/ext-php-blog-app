<?php

  session_start();
  require '../config/config.php';
  require '../config/common.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){ // if no login sesion, back to login
    header('Location:login.php');
  }

  if($_SESSION['role']!=1){ // if not admin role, back to login
    header('Location:login.php');
  }

  if($_POST){
     if( empty($_POST['title']) || empty($_POST['content']) || empty($_FILES['image']['name']) ){
       $titleError=empty($_POST['title'])? 'Title is required.':'';
       $contentError=empty($_POST['content'])? 'Content is required.':'';
       $imageError=empty($_FILES['image']['name'])? 'Image is required.':'';
     }
     else{
       $file='../images/'.($_FILES['image']['name']);
       $imageType=pathinfo($file,PATHINFO_EXTENSION);
       if($imageType!='png' && $imageType!='jpeg'&& $imageType!='jpg' ){
         echo("<script>alert('Image type must be png oor jpeg or jpg.')</script>");
       }
       else{
         $title=$_POST['title'];
         $content=$_POST['content'];
         $image=$_FILES['image']['name'];
         move_uploaded_file($_FILES['image']['tmp_name'],$file);
         $statement = $pdo->prepare("INSERT INTO posts (title,content,author_id,image) VALUES (:title,:content,:authorId,:image)");
         $result = $statement->execute(
           array(
             ':title'=>$title,
             ':content'=>$content,
             ':authorId'=>$_SESSION['user_id'],
             'image'=>$image
             )
           );
         if($result){
           echo("<script>alert('Successfully added.');window.location.href='index.php'</script>");
         }
       }
     }
  }
?>

<?php include('header.php')?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <form action="add.php" method="post" enctype="multipart/form-data">
                <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                <div class="form-group">
                  <label for="title">Title</label>
                  <p style="color:red;"><?= !empty($titleError)?'*'.$titleError:''; ?><p>
                  <input type="text" class="form-control" name="title" placeholder="Enter blog title"/>
                </div>
                <div class="form-group">
                  <label for="content">Content</label>
                  <p style="color:red;"><?= !empty($contentError)?'*'.$contentError:''; ?><p>
                  <textarea class="form-control" name="content" rows="8" cols="80" placeholder="Enter blog content" ></textarea>
                </div>
                <div class="form-group">
                  <label for="image">Image</label>
                  <p style="color:red;"><?= !empty($imageError)?'*'.$imageError:''; ?><p>
                  <input type="file" name="image" value="" />
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-success">Submit</button>
                  <a href="index.php" type="button" class="btn btn-default">Back</a>
                </div>
              </form>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
<?php include('footer.html')?>

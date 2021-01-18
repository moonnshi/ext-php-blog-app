<?php
require '../config/config.php';
  session_start();
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
  }

  if($_POST){
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
?>

<?php include('header.html')?>
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
                <div class="form-group">
                  <label for="title">Title</label>
                  <input type="text" class="form-control" name="title" placeholder="Enter blog title"/>
                </div>
                <div class="form-group">
                  <label for="content">Content</label>
                  <textarea class="form-control" name="content" rows="8" cols="80" placeholder="Enter blog content" required></textarea>
                </div>
                <div class="form-group">
                  <label for="image">Image</label>
                  <input type="file" name="image" value="" required/>
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

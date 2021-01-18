<?php
require '../config/config.php';
  session_start();
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
  }

  // get post by id
  $statement=$pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
  $statement->execute();
  $result=$statement->fetchAll();

  // update post
  if($_POST){
    $id=$_POST['id'];
    $title=$_POST['title'];
    $content=$_POST['content'];
    if($_FILES['image']['name']!=null){
      $file='../images/'.($_FILES['image']['name']);
      $imageType=pathinfo($file,PATHINFO_EXTENSION);
      if($imageType!='png' && $imageType!='jpeg'&& $imageType!='jpg' ){
        echo("<script>alert('Image type must be png oor jpeg or jpg.')</script>");
      }
      else{
        $image=$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'],$file);
        $statement = $pdo->prepare("UPDATE posts SET title='$title',content='$content',image='$image' WHERE id='$id'");
        $result = $statement->execute();
        if($result){
          echo("<script>alert('Successfully updated.');window.location.href='index.php'</script>");
        }
      }
    }
    else{
       $statement = $pdo->prepare("UPDATE posts SET title='$title',content='$content' WHERE id='$id'");
       $result = $statement->execute();
       if($result){
         echo("<script>alert('Successfully updated.');window.location.href='index.php'</script>");
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
              <form action="" method="post" enctype="multipart/form-data">
                <!-- <input type="hidden" value="<?= $result[0]['id'] ?>" -->
                <div class="form-group">
                  <input type="hidden" name="id" value="<?= $result[0]['id'] ?>" />
                  <label for="title">Title</label>
                  <input type="text" class="form-control" name="title" value="<?php echo $result[0]['text']?>" placeholder="Enter blog title"/>
                </div>
                <div class="form-group">
                  <label for="content">Content</label>
                  <textarea class="form-control" name="content"  rows="8" cols="80" placeholder="Enter blog content" required>
                    <?php echo $result[0]['content']?>
                  </textarea>
                </div>
                <div class="form-group">
                  <label for="image">Image</label>
                  <br>
                  <img src="../images/<?php echo $result[0]['image']?>"  width="100" height="100" alt="<?= $result[0]['image'] ?>"/>
                  <br>
                  <input type="file" name="image" value="" />
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-warning">Update</button>
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
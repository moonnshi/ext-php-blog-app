<?php
  require 'config/config.php';
  session_start();
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
}

  // get post by id
  $statement=$pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
  $statement->execute();
  $result=$statement->fetchAll();

  //get comments by id
  $statement=$pdo->prepare("SELECT * FROM comments WHERE post_id=".$_GET['id']);
  $statement->execute();
  $comments=$statement->fetchAll();

  //$auth_id=$comments[0]['author_id'];
  // $auth_id=1;
  // $statement=$pdo->prepare("SELECT * FROM users WHERE id=".$auth_id);
  // $statement->execute();
  // $auResult=$statement->fetchAll();

  // post comment
  $post_id=$_GET['id'];
  if($_POST){
    $comment=$_POST['comment'];
    $author_id=$_SESSION['user_id'];
    $statement = $pdo->prepare("INSERT INTO comments (content,author_id,post_id) VALUES (:content,:author_id,:post_id)");
    $result = $statement->execute(
      array(
        ':content'=>$comment,
        ':author_id'=>$author_id,
        ':post_id'=>$post_id
      )
    );
    if($result){
      header('Location:blogDetail.php?id='.$post_id);
    }
  }

  // get comments
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Widgets</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="margin-left:0px !important">
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header" >
                <div style="text-align:center;float:none;" class="card-title">
                  <h4 ><?= $result[0]['title'] ?></h4>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <img class="img-fluid pad" src="images/<?php echo $result[0]['image']?>" style="height:200px !important" alt="Photo">
                <p><?= $result[0]['content'] ?></p>
                <h3>Comments</h3>
                <a href="/blog" type="button" class="btn btn-default">Go Back</a>
              </div>
              <!-- /.card-body -->
              <div class="card-footer card-comments">
                <div class="card-comment">
                  <?php  if($comments): ?>
                    <?php foreach ($comments as $comment): ?>
                      <div class="comment-text" style="margin-left:0px !important">
                        <?php
                          $auth_id=$comment['author_id'];
                          $statement=$pdo->prepare("SELECT * FROM users WHERE id=".$auth_id);
                          $statement->execute();
                          $user=$statement->fetchAll();
                        ?>
                        <span class="username">
                          <?= $user[0]['name'] ?>
                          <span class="text-muted float-right">
                            <?= $comment['created_at'] ?>
                          </span>
                        </span><!-- /.username -->
                        <p><?= $comment['content'] ?></p>
                      </div>
                      <!-- /.comment-text -->
                    <?php endforeach; ?>
                  <?php endIf; ?>
                </div>
                <!-- /.card-comment -->
              </div>
              <!-- /.card-footer -->
              <div class="card-footer">
                <form action="#" method="post">
                  <!-- .img-push is used to add margin to elements next to floating images -->
                  <div class="img-push">
                    <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
                  </div>
                </form>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer" style="margin-left:0px !important">
    <div class="float-right d-none d-sm-inline">
      <a href="logout.php" type="button" class="btn btn-default">Logout</a>
    </div>
    <strong>Copyright &copy; 2021 <a href="#">A Programmer</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>

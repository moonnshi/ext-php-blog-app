<?php
  require 'config/config.php';
  session_start();
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
}
?>
<?php

  $pageno=(!empty($_GET['pageno']))?$_GET['pageno']:'1';
  $numOfRec=6;
  $offset=($pageno-1)*$numOfRec;

  if(empty($_POST['search'])){
    $statement=$pdo->prepare('SELECT * from posts ORDER BY id DESC ');
    $statement->execute();
    $rawResults=$statement->fetchAll();
    $total_pages=ceil(count($rawResults)/$numOfRec);

    $statement=$pdo->prepare("SELECT * from posts ORDER BY id DESC LIMIT $offset,$numOfRec");
    $statement->execute();
    $results=$statement->fetchAll();

  }
  else{
    $search=$_POST['search'];
    $statement=$pdo->prepare("SELECT * from posts WHERE title LIKE '%$search%' ORDER BY id DESC ");
    $statement->execute();
    $rawResults=$statement->fetchAll();
    $total_pages=ceil(count($rawResults)/$numOfRec);

    $statement=$pdo->prepare("SELECT * from posts WHERE title LIKE '%$search%' ORDER BY id DESC LIMIT $offset,$numOfRec");
    $statement->execute();
    $results=$statement->fetchAll();

  }

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
  <!-- Navbar -->
  <!-- <nav class="main-header navbar navbar-expand navbar-white navbar-light">
  </nav> -->
  <!-- /.navbar -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="margin-left:0px !important">
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <?php if($results):?>
            <?php $i=1; foreach ($results as $post):?>
              <div class="col-md-4">
                <!-- Box Comment -->
                <div class="card card-widget">
                  <div class="card-header" >
                    <div style="text-align:center;float:none;" class="card-title">
                      <h4 ><?= $post['title'] ?></h4>
                    </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <a href="blogDetail.php?id=<?= $post['id'] ?>"> <img class="img-fluid pad" src="../images/<?php echo $post['image']?>" style="height:200px !important" alt="Photo"></a>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
              <!-- /.col -->
          <?php $i++; endforeach; ?>
          <?php endIf;?>

        </div>
        <!-- /.row -->
        <!-- paginator -->
        <div class="row" style="float:right;margin-right:0px">
          <nav aria-label="Page navigation example" >
            <ul class="pagination">
              <li class="page-item"><a class="page-link" href="index.php?pageno=1">First</a></li>
              <li class="page-item" <?php if($pageno <= 1){ echo 'disabled';}?> >
                <a class="page-link" href="<?php echo ($pageno <= 1)? '#' : 'index.php?pageno='.$pageno-1 ?>">Previous</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#"><?= $pageno ?></a>
              </li>
              <li class="page-item" <?php if($pageno >= $total_pages){ echo 'enabled';} ?> >
                  <a class="page-link" href="<?php echo ($pageno >= $total_pages)? '#' :'index.php?pageno='.$pageno+1; ?>">Next</a>
              </li>
              <li class="page-item"><a class="page-link" href="index.php?pageno=<?= $total_pages ;?>">Last</a></li>
            </ul>
          </nav>
        </div>
        <!-- /.paginator -->
      </div><!-- /.container-fluid -->
    </section><br>
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

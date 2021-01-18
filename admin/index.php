<?php
require '../config/config.php';
  session_start();
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
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
            <div class="card-header">
              <h3 class="card-title">Blogs Listing</h3>
            </div>
            <!-- /.card-header -->
            <?php

              $pageno=(!empty($_GET['pageno']))?$_GET['pageno']:'1';
              $numOfRec=10;
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
            <div class="card-body">
              <div>
                <a href="add.php" class="btn btn-success">New Blog Post</a>
              </div><br>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th style="width: 10px">#</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th style="width: 40px">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if($results):?>
                    <?php $i=1; foreach ($results as $post):?>
                      <tr>
                        <td><?= $offset+1 ?></td>
                        <td><?= $post['title']?></td>
                        <td><?= substr($post['content'],0,100)?></td>
                        <td>
                          <div class="btn-group">
                            <div class="container">
                              <a href="edit.php?id=<?php echo $post['id']?>" class="btn btn-warning">Edit</a>
                            </div>
                            <div class="container">
                              <a href="delete.php?id=<?php echo $post['id']?>" onclick="return confirm('Are you want to delete this blog?')" class="btn btn-danger">Delete</a>
                            </div>
                          </div>
                        </td>
                      </tr>
                  <?php $i++; endforeach; ?>
                  <?php endIf;?>

                </tbody>
              </table>
              <br>
              <nav aria-label="Page navigation example" style="float:right;">
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

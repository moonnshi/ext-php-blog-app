<?php

  session_start();
  require '../config/config.php';
  require '../config/common.php';


  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
  }
  if(!empty($_SESSION['user_id']) && $_SESSION['role']!=1){
    header('Location:login.php');
  }

  // set search value to setcookie
  if(!empty($_POST['search'])){
    setcookie("search", $_POST['search'], time() + (86400 * 30), "/");
  }
  else{
    if(empty($_GET['pageno'])){
      unset($_COOKIE['search']);
      setcookie('search', null, -1, '/');
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
            <div class="card-header">
              <h3 class="card-title">Users Listing</h3>
            </div>
            <!-- /.card-header -->
            <?php

              $pageno=(!empty($_GET['pageno']))?$_GET['pageno']:'1';
              $numOfRec=5;
              $offset=($pageno-1)*$numOfRec;

              if(empty($_POST['search']) && (empty($_COOKIE['search']))){
                $statement=$pdo->prepare('SELECT * from users ORDER BY id DESC ');
                $statement->execute();
                $rawResults=$statement->fetchAll();
                $total_pages=ceil(count($rawResults)/$numOfRec);

                $statement=$pdo->prepare("SELECT * from users ORDER BY id DESC LIMIT $offset,$numOfRec");
                $statement->execute();
                $results=$statement->fetchAll();

              }
              else{
                $search=(!empty($_POST['search']))? $_POST['search'] : $_COOKIE['search'];
                $statement=$pdo->prepare("SELECT * from users WHERE name LIKE '%$search%' ORDER BY id DESC ");
                $statement->execute();
                $rawResults=$statement->fetchAll();
                $total_pages=ceil(count($rawResults)/$numOfRec);

                $statement=$pdo->prepare("SELECT * from users WHERE name LIKE '%$search%' ORDER BY id DESC LIMIT $offset,$numOfRec");
                $statement->execute();
                $results=$statement->fetchAll();

              }

            ?>
            <div class="card-body">
              <div>
                <a href="add_user.php" class="btn btn-success">New User</a>
              </div><br>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th style="width: 10px">#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th style="width: 40px">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if($results):?>
                    <?php $i=1; foreach ($results as $user):?>
                      <tr>
                        <td><?= $offset+$i ?></td>
                        <td><?= escape($user['name']); ?></td>
                        <td><?= substr(escape($user['email']),0,50) ?></td>
                        <td><?= ($user['role'])?'Admin':'User'; ?></td>
                        <td>
                          <div class="btn-group">
                            <div class="container">
                              <a href="edit_user.php?id=<?php echo $user['id']?>" class="btn btn-warning">Edit</a>
                            </div>
                            <div class="container">
                              <a href="delete_user.php?id=<?php echo $user['id']?>" onclick="return confirm('Are you sure to delete this user?')" class="btn btn-danger">Delete</a>
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
                  <li class="page-item"><a class="page-link" href="user_list.php?pageno=1">First</a></li>
                  <li class="page-item" <?php if($pageno <= 1){ echo 'disabled';}?> >
                    <a class="page-link" href="<?php echo ($pageno <= 1)? '#' : 'user_list.php?pageno='.$pageno-1 ?>">Previous</a>
                  </li>
                  <li class="page-item">
                    <a class="page-link" href="#"><?= $pageno ?></a>
                  </li>
                  <li class="page-item" <?php if($pageno >= $total_pages){ echo 'enabled';} ?> >
                      <a class="page-link" href="<?php echo ($pageno >= $total_pages)? '#' :'user_list.php?pageno='.$pageno+1; ?>">Next</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="user_list.php?pageno=<?= $total_pages ;?>">Last</a></li>
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

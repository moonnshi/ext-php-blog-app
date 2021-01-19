<?php
require '../config/config.php';
  session_start();
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
  }
  if(!empty($_SESSION['user_id']) && $_SESSION['role']!=1){
    header('Location:login.php');
  }

  // get user by id
  $statement=$pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
  $statement->execute();
  $user=$statement->fetchAll();

  // update post
  if($_POST){
    $id=$_POST['id'];
    $name=$_POST['name'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $role=(!empty($_POST['role']))?1:0; // check admin or user role

    $statement=$pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
    $statement->execute(array(':email'=>$email,':id'=>$id));
    $user=$statement->fetch(PDO::FETCH_ASSOC);
    $statement = $pdo->prepare("UPDATE users SET name='$name',email='$email',password='$password',role='$role' WHERE id='$id'");
    $result = $statement->execute();
    if($result){
     echo("<script>alert('Successfully updated.');window.location.href='user_list.php'</script>");
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
              <form action="#" method="post">
                <div class="form-group">
                  <label for="name">Name</label>
                  <input type="hidden" name="id" value="<?= $user[0]['id']?>" />
                  <input type="text" class="form-control" name="name" value="<?= $user[0]['name']?>" placeholder="Enter name."/>
                </div>
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control"  name="email" value="<?= $user[0]['email']?>" placeholder="Enter email." required/>
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" name="password" value="<?= $user[0]['password']?>" placeholder="Enter password." required>
                </div>
                <div class="form-group">
                  <label for="role">Is admin?</label>
                  <input type="checkbox" name="role" value="1" <?php if($user[0]['role']==1){ echo 'checked';}?> >
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-warning">Update</button>
                  <a href="user_list.php" type="button" class="btn btn-default">Back</a>
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

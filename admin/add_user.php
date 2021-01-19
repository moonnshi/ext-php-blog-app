<?php
require '../config/config.php';
  session_start();
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
  }
  
  if(!empty($_SESSION['user_id']) && $_SESSION['role']!=1){
    header('Location:login.php');
  }

  if($_POST){
    $name=$_POST['name'];
    $email=$_POST['email'];
    $password=$_POST['password'];

    // check admin or user role
    $role=(!empty($_POST['role']))?1:0;

    $statement=$pdo->prepare("SELECT * FROM users WHERE email=:email");
    $statement->bindValue(':email',$email);
    $statement->execute();
    $user=$statement->fetch(PDO::FETCH_ASSOC);

    if($user){
      echo("<script>alert('Email already existed.');</script>");
    }
    else{
      $statement = $pdo->prepare("INSERT INTO users (name,email,password,role) VALUES (:name,:email,:password,:role)");
      $result = $statement->execute(
        array(
          ':name'=>$name,
          ':email'=>$email,
          ':password'=>$password,
          ':role'=>$role
          )
        );
      if($result){
          echo("<script>alert('Successfully added.');window.location.href='user_list.php'</script>");
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
              <form action="add_user.php" method="post" >
                <div class="form-group">
                  <label for="title">Name</label>
                  <input type="text" class="form-control" name="name" placeholder="Enter name."/>
                </div>
                <div class="form-group">
                  <label for="content">Email</label>
                  <input type="email" class="form-control"  name="email" placeholder="Enter email." required/>
                </div>
                <div class="form-group">
                  <label for="content">Password</label>
                  <input type="password" class="form-control" name="password" placeholder="Enter password." required>
                </div>
                <div class="form-group">
                  <label for="role">Is admin?</label>
                  <input type="checkbox" name="role" value="1" />
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
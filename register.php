<?php

  session_start();
  require 'config/config.php';
  require 'config/common.php';

  if($_POST){
    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || (strlen($_POST['password'])<4)){
      $nameError=empty($_POST['name'])? 'Name is required.':'';
      $emailError=empty($_POST['email'])? 'Email is required.':'';
      if(empty($_POST['password'])){
        $passwordError='Password is required.';
      }
      elseif (strlen($_POST['password'])<4) {
        $passwordError='Password should be at least 4 characters.';
      }
      else{
        $passwordError='';
      }
    }else{
      $name=$_POST['name'];
      $email=$_POST['email'];
      $password=password_hash($_POST['password'],PASSWORD_DEFAULT);

      $statement=$pdo->prepare("SELECT * FROM users WHERE email=:email");
      $statement->bindValue(':email',$email);
      $statement->execute();
      $user=$statement->fetch(PDO::FETCH_ASSOC);

      if($user){
        echo("<script>alert('Email already existed.');</script>");
      }
      else{
        $statement = $pdo->prepare("INSERT INTO users (name,email,password) VALUES (:name,:email,:password)");
        $result = $statement->execute(
          array(
            ':name'=>$name,
            ':email'=>$email,
            ':password'=>$password,
            )
          );
        if($result){
            echo("<script>alert('Successfully registerd. You can login now.');window.location.href='login.php'</script>");
        }
      }
    }
  }
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blog | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Blog</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Register New Account</p>
      <form action="register.php" method="post">
        <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
        <p style="color:red;"><?= !empty($nameError)?'*'.$nameError:''; ?><p>
        <div class="input-group mb-3">
          <input type="text" name="name" class="form-control" placeholder="Name">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <p style="color:red;"><?= !empty($emailError)?'*'.$emailError:''; ?><p>
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <p style="color:red;"><?= !empty($passwordError)?'*'.$passwordError:''; ?><p>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="container">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
            <a href="login.php" class="btn btn-default btn-block">Login</a>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <!-- <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p> -->
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="../pluginsjquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../pluginsbootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>

</body>
</html>

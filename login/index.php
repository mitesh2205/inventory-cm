<!-- jQuery -->
<script src="../plugins/jquery/sweet_alert.js"></script>
<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<?php
include_once('../database/connectdb.php'); 
session_start();

if(isset($_POST['btn_login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $select = $inventory->prepare("select * from users where email='$email' and password='$password'");

    $select->execute();

    $row = $select->fetch(PDO::FETCH_ASSOC);

    if($row){
        if($email == $row['email'] AND $password == $row['password']){

            

            if($row['role'] === 'admin'){
                $_SESSION['userid'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['role'] = $row['role'];
                echo '<script> 
                jQuery(function validation(){
                    swal({
                        title: "Good Job!'.$_SESSION['username'].'",
                        text: "Login Matched!",
                        icon: "success",
                        button: "Loading....."
                      });
                     }); </script>';
                header('refresh:2;../dashboard.php');
            }
            elseif($row['role'] === 'user'){
                $_SESSION['userid'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['role'] = $row['role'];
                echo '<script> 
                jQuery(function validation(){
                swal({
                    title: "Good Job!'.$_SESSION['username'].'",
                    text: "Login Matched!",
                    icon: "success",
                    button: "Loading....."
                  });
                 }); </script>';
                header('refresh:2;../user.php');
            }
            //  $success = 'Login Successfull!';
        }
        else{
            echo 'Login Failed';
        }
    }
    else{
        echo '<script> 
        jQuery(function validation(){
        swal({
            title: "Login Credential Does Not Match!",
            text: "You clicked the button!",
            icon: "error",
          });
         }); </script>';
        // echo 'User with this credentials does not exist!!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>INVENTORY | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="index.php"><b>INVENTORY</b>POS</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to your account</p>

      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="email" class="form-control" name="email" placeholder="Email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-8">
              <a href="#" onclick="swal('To Get Password', 'Please Contact to Admin OR Service Provider', 'error')">I forgot my password</a>
          </div>
          <div class="col-4">
            <button type="submit" name="btn_login" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mb-1">
       
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->


</body>
</html>

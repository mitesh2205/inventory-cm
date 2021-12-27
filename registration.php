<!-- jQuery -->
<script src="plugins/jquery/sweet_alert.js"></script>
<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<script src="plugins/jquery/jquery.min.js"></script>

<?php 
include_once('database/connectdb.php');
$page = 'registration';
session_start();

if($_SESSION['email'] == "" || $_SESSION['role'] == "user"){
  header('location:index.php');
}

include_once('header.php'); 
error_reporting(0);
if(isset($_POST['userIdd'])){
$id = $_POST['userIdd'];

$delete = $inventory->prepare("delete from users where id = $id");
if($delete->execute()){
 
}
}


if(isset($_POST['btn_register'])){
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $role = $_POST['role'];
  
  $filename = $_FILES['userImage']['name'];
  $tempname = $_FILES['userImage']['tmp_name'];
  $filesize = $_FILES['userImage']['size'];

  $file_extension = explode('.',$filename);
  $file_extension = strtolower(end($file_extension));
  
  $file_newfile = uniqid().'.'.$file_extension;
  $store = "storage/users/".$file_newfile;

  if($file_extension == 'jpg' || $file_extension == 'png' || $file_extension == 'jpeg' || $file_extension == 'gif'){
    if($filesize >= 1000000){
        echo '<script> 
        jQuery(function validation(){
            swal({
                title: "Image size exceed!",
                text: "Upload Image size less then 1 MB!",
                icon: "warning",
                button: "Ok"
              });
            }); </script>';
    }else{
        if(move_uploaded_file($tempname,$store)){
              $select = $inventory->prepare("select * from users where email = '$email' OR username='$name'");
              $select->execute();
            
              $row = $select->fetch(PDO::FETCH_ASSOC);
              if($name != "" || $password != "" || $email != "" || $role != ""){
              if($row ==""){
                  $insert = $inventory->prepare("insert into users (username, email, image, password, role) values(:name, :email, :usersImage, :pass, :role)");
            
                  $insert->bindParam(':name', $name);
                  $insert->bindParam(':email', $email);
                  $insert->bindParam(':pass', $password);
                  $insert->bindParam(':role', $role);
                  $insert->bindParam(':usersImage', $store);
            
                  if($insert->execute()){
                    echo '<script> 
                    jQuery(function validation(){
                        swal({
                            title: "Good Job!",
                            text: "Registered User!",
                            icon: "success",
                            button: "Ok"
                          });
                        }); </script>';
                  }else{
                    echo '<script> 
                    jQuery(function validation(){
                        swal({
                            title: "Oopss something went wrong!",
                            text: "Please try again later!",
                            icon: "error",
                            button: "Ok"
                          });
                        }); </script>';
                  }
              }
              else{
                echo '<script> 
                jQuery(function validation(){
                    swal({
                        title: "Email or Username already exists!",
                        text: "Please use another email!",
                        icon: "warning",
                        button: "Ok"
                      });
                    }); </script>';
              }
            }
            else{
              echo '<script> 
                jQuery(function validation(){
                    swal({
                        title: "Details required!",
                        text: "Please fill all the details!",
                        icon: "warning",
                        button: "Ok"
                      });
                    }); </script>';
            }
          
        }
    }
}else{
    echo '<script> 
    jQuery(function validation(){
        swal({
            title: "Image extension warning!",
            text: "please upload image in Png, JPG, jpeg or GIF extension!",
            icon: "warning",
            button: "Ok"
          });
        }); </script>';
}



  
}

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Registration</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active"><a href="#">Register User</a></li>

          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="row">
    <div class="col-6">
    </div>
    <div class="col-6">
    <button class="btn btn-success pull-right mb-2 mr-4" data-toggle="modal" data-target="#exampleModaladd"><i class="fa fa-plus pr-2"></i> Add Account</button>
    </div>
  </div>
  <div class="content">
    <div class="container-fluid">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Registration Form</h3>
        </div>
        <!-- /.card-header -->
        <div class="row">
            <div class="col-md-12 pt-2">
        <div class="card-body">
            <table id="example1" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Password</th>
                    <th scope="col">Role</th>
                    <th scope="col">Delete</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
              $select = $inventory->prepare("select * from users order by id desc");
              $select->execute();
              
              while($row = $select->fetch(PDO::FETCH_OBJ)){
                echo'
                <tr>
                <td>'.$row->id.'</td>
                <td>'.$row->username.'</td>
                <td>'.$row->email.'</td>
                <td>'.$row->password.'</td>
                <td>'.$row->role.'</td>
                <td>
                <button id="'.$row->id.'" class="btn btn-danger accountd"> <i class="fa fa-trash text-white"> Delete</i> </button>
                </td>';
              }
            ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modal Add Account-->
<div class="modal fade" id="exampleModaladd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Register User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="" role="form" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="exampleInputEmail1">Name</label>
                  <input type="text" name="name" class="form-control" id="name" placeholder="Name">
                </div>
                <div class="form-group">
                  <label>Email address</label>
                  <input type="email" name="email" class="form-control" id="exampleInputEmail1"
                    placeholder="Enter email">
                </div>
                <div class="form-group">
                                <label for="exampleInputFile">Profile Pic</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="userImage" class="custom-file-input"
                                            id="userImage">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                </div>
                            </div>
                <div class="form-group">
                  <label>Password</label>
                  <input type="password" name="password" class="form-control" id="exampleInputPassword1"
                    placeholder="Password">
                </div>
                <div class="form-group">
                  <label>Select</label>
                  <select name="role" id="" class="form-control">
                    <option value="" disabled selected>Select</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                  </select>
                </div>
                <button type="submit" name="btn_register" class="btn btn-primary float-right">Save</button>
          </form>
      </div>
    </div>
  </div>
</div>
<!-- jQuery -->
<script src="plugins/jquery/sweet_alert.js"></script>
<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE App -->
<!-- <script src="dist/js/adminlte.min.js"></script> -->
<!-- bs-custom-file-input -->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<?php include_once('footer.php'); ?>
<script>
    $(function () {
        bsCustomFileInput.init();
    });
</script>
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "ordering": false , "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
</script>
<script>
     $(document).ready(function () {
        $('.accountd').click(function () {
            var tdh = $(this);
            var userId = $(this).attr('id');
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this account!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: 'registration.php',
                        type: 'post',
                        data: {
                            userIdd: userId,
                        },
                        success: function (getdata) {
                            tdh.parents('tr').hide();
                        },
                        error: function () {
                            alert("fail");
                        }
                    });
                    swal("Poof! Account has been deleted!", {
                    icon: "success",
                    });
                } else {
                    swal("Your Account is safe!");
                }
                });
        });
    });
</script>

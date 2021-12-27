<!-- jQuery -->
<script src="plugins/jquery/sweet_alert.js"></script>
<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<script src="plugins/jquery/jquery.min.js"></script>
<?php 
include_once('database/connectdb.php');
session_start();
if($_SESSION['email'] == ""){
    header("location:index.php");
}
include_once('header.php');

// Update password Logic

if(isset($_POST['password_update'])){

  $oldPassword = $_POST['oldPassword'];
  $newPassword = $_POST['newPassword'];
  $confirmPassword = $_POST['confirmPassword'];
  $email = $_SESSION['email'];

  $select = $inventory->prepare("select * from users where email = '$email'");
  $select->execute();

  $row=$select->fetch(PDO::FETCH_ASSOC);
  if($row){
    $usernameDb = $row['email'];
    $passwordDb = $row['password'];

    if($oldPassword == $passwordDb){
      if($newPassword == $confirmPassword){
        if($oldPassword == $newPassword || $passwordDb == $confirmPassword){
          echo '<script> 
                      jQuery(function validation(){
                          swal({
                              title: "Same Password '.$_SESSION['username'].'",
                              text: "Old Password and new password cannot be same!",
                              icon: "warning",
                              button: "Ok"
                            });
                          }); </script>';
        }
        else{
          $update = $inventory->prepare("update users set password = $newPassword where email = '$email'");
          $update->bindParam(':pass' ,$confirmPassword);
          $update->bindParam(':email' ,$email);

          if($update->execute()){
              echo '<script> 
                        jQuery(function validation(){
                            swal({
                                title: "Password Changed '.$_SESSION['username'].'",
                                text: "Your password is updated!",
                                icon: "success",
                                button: "Ok"
                              });
                            }); </script>';
          }
          else{
              echo '<script> 
                        jQuery(function validation(){
                            swal({
                                title: "Oopss!!",
                                text: "Something went Wrong, Please try again!",
                                icon: "error",
                                button: "Ok"
                              });
                            }); </script>';
          } 
        }
      }
    }  
  }
}
?>
<div class="content-wrapper">
<section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">
          <?php
                $email = $_SESSION['email'];
                  $select_info = $inventory->prepare("select * from users where email = '$email'");
                  $select_info->execute();
                
                  $row_info = $select_info->fetch(PDO::FETCH_ASSOC);
                ?>
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                       src="<?php echo $row_info['image'] != null ? $row_info['image'] : 'storage/users/avatar.svg';  ?>"
                       alt="User profile picture">
                </div>

                <h3 class="profile-username text-center"><?php echo $_SESSION['username']; ?></h3>
                <p class="text-muted text-center">Software Engineer</p>
                
                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Bank Name</b> <a class="float-right"><?php echo $row_info['bank_name']; ?></a>
                  </li>
                  <li class="list-group-item">
                    <b>IFSC CODE</b> <a class="float-right"><?php echo $row_info['ifsc_code']; ?></a>
                  </li>
                  <li class="list-group-item">
                    <b>A/C NO.</b> <a class="float-right"><?php echo $row_info['account_no']; ?></a>
                  </li>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                  <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Update profile</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane" id="timeline">
                    <!-- The timeline -->
                    <div class="timeline timeline-inverse">
                      <!-- timeline time label -->
                      <div class="time-label">
                        <span class="bg-danger">
                          10 Feb. 2014
                        </span>
                      </div>
                      <!-- /.timeline-label -->
                      <!-- timeline item -->
                      <div>
                        <i class="fas fa-envelope bg-primary"></i>

                        <div class="timeline-item">
                          <span class="time"><i class="far fa-clock"></i> 12:05</span>

                          <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                          <div class="timeline-body">
                            Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                            weebly ning heekya handango imeem plugg dopplr jibjab, movity
                            jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                            quora plaxo ideeli hulu weebly balihoo...
                          </div>
                          <div class="timeline-footer">
                            <a href="#" class="btn btn-primary btn-sm">Read more</a>
                            <a href="#" class="btn btn-danger btn-sm">Delete</a>
                          </div>
                        </div>
                      </div>
                      <!-- END timeline item -->
                      <!-- timeline item -->
                      <div>
                        <i class="fas fa-user bg-info"></i>

                        <div class="timeline-item">
                          <span class="time"><i class="far fa-clock"></i> 5 mins ago</span>

                          <h3 class="timeline-header border-0"><a href="#">Sarah Young</a> accepted your friend request
                          </h3>
                        </div>
                      </div>
                      <!-- END timeline item -->
                      <!-- timeline item -->
                      <div>
                        <i class="fas fa-comments bg-warning"></i>

                        <div class="timeline-item">
                          <span class="time"><i class="far fa-clock"></i> 27 mins ago</span>

                          <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

                          <div class="timeline-body">
                            Take me to your leader!
                            Switzerland is small and neutral!
                            We are more like Germany, ambitious and misunderstood!
                          </div>
                          <div class="timeline-footer">
                            <a href="#" class="btn btn-warning btn-flat btn-sm">View comment</a>
                          </div>
                        </div>
                      </div>
                      <!-- END timeline item -->
                      <!-- timeline time label -->
                      <div class="time-label">
                        <span class="bg-success">
                          3 Jan. 2014
                        </span>
                      </div>
                      <!-- /.timeline-label -->
                      <!-- timeline item -->
                      <div>
                        <i class="fas fa-camera bg-purple"></i>

                        <div class="timeline-item">
                          <span class="time"><i class="far fa-clock"></i> 2 days ago</span>

                          <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                          <div class="timeline-body">
                            <img src="https://placehold.it/150x100" alt="...">
                            <img src="https://placehold.it/150x100" alt="...">
                            <img src="https://placehold.it/150x100" alt="...">
                            <img src="https://placehold.it/150x100" alt="...">
                          </div>
                        </div>
                      </div>
                      <!-- END timeline item -->
                      <div>
                        <i class="far fa-clock bg-gray"></i>
                      </div>
                    </div>
                  </div>
                  <!-- /.tab-pane -->

                  <div class="active tab-pane" id="settings">
                    <form class="form-horizontal" action="" method="post" role="form">
                      <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Old Pasword</label>
                        <div class="col-sm-10">
                          <input type="password" name="oldPassword" class="form-control" id="oldPassword" placeholder="Password">
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="password"  class="col-sm-2 col-form-label">New Pasword</label>
                        <div class="col-sm-10">
                          <input type="password" name="newPassword" class="form-control" id="newPassword" placeholder="Password">
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Confirm Pasword</label>
                        <div class="col-sm-10">
                          <input type="password"name="confirmPassword" class="form-control" id="confirmPassword" placeholder="Password">
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" name="password_update" class="btn btn-primary">Update</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
</div>
<!-- jQuery -->
<script src="plugins/jquery/sweet_alert.js"></script>
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<?php include_once('footer.php') ?>
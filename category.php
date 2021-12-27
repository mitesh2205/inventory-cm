<!-- jQuery -->
<script src="plugins/jquery/sweet_alert.js"></script>
<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<script src="plugins/jquery/jquery.min.js"></script>
<?php 
include_once('database/connectdb.php');
$page = 'category';
session_start();
if($_SESSION['email'] == ""){
    header("location:index.php");
}
error_reporting(0);
if(isset($_POST['categoryIdd'])){
$id = $_POST['categoryIdd'];

$delete = $inventory->prepare("delete from category where id = $id");
if($delete->execute()){

}
}

if(isset($_POST['btn_category'])){
    $category = $_POST['category'];
  
 
    if($category != ""){
        $insert = $inventory->prepare("insert into category (category) values(:category)");
  
        $insert->bindParam(':category', $category);
  
        if($insert->execute()){
          echo '<script> 
          jQuery(function validation(){
              swal({
                  title: "Good Job!",
                  text: "Category Added!",
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
              title: "Details required!",
              text: "Please fill all the details!",
              icon: "warning",
              button: "Ok"
            });
          }); </script>';
  }
  }
include_once('header.php'); ?>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" >
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Category</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active"><a href="#">Category</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    
    <div class="content">
      <div class="container-fluid">
      <div class="row">
      <div class="col-6">
      </div>
      <div class="col-6">
       <button class="btn btn-success pull-right mb-2 mr-4" data-toggle="modal" data-target="#exampleModaladd"><i class="fa fa-plus pr-2"></i> Add Category</button>
      </div>
    </div>
        <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Category Details</h3>
        </div>
        <!-- /.card-header -->
          <div class="row">
            <div class="col-md-12 pt-2">
              <div class="card-body">
              <table id="example1" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Category</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
              $select = $inventory->prepare("select * from category order by id desc");
              $select->execute();
              
              while($row = $select->fetch(PDO::FETCH_OBJ)){
                echo'
                <tr id="'.$row->id.'">
                <td>'.$row->id.'</td>
                <td data-target = "category_name">'.$row->category.'</td>
                <td>'.date('F d, Y', strtotime($row->created_at)).'</td>
                <td>
                <a href="#" data-role="update" data-id="'.$row->id.'" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" name="category_update"> <i class="fa fa-edit text-white"> Edit</i> </a>
                <button id="'.$row->id.'"  class="btn btn-danger categoryd"> <i class="fa fa-trash text-white"> Delete</i> </button>
                </td>
                ';
                
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
  <!-- Modal category update-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Category Update</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="category">Category</label>
          <input type="text" name="updateCategoryName" id="updateCategoryName" class="form-control" id="category" placeholder="Enter Category">
        </div>   
        <input type="hidden" id="categoryId">  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Close</button>
        <button type="button" id="save" class="btn btn-primary" onclick="document.location.href='category.php';">Update</button>
      </div>
    </div>
  </div>
</div>

  <!-- Modal category Add-->
  <div class="modal fade" id="exampleModaladd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Category Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" role="form" method="post">
          <div class="form-group">
            <label for="category">Category</label>
              <input type="text" name="category" class="form-control" id="category" placeholder="Enter Category">
          </div>
          <button type="submit" name="btn_category" class="btn btn-primary float-right">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- jQuery -->
<script src="plugins/jquery/sweet_alert.js"></script>
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<!-- <script src="dist/js/adminlte.min.js"></script> -->

<script>
    $(document).ready(function(){
        $(document).on('click', 'a[data-role=update]', function(){
            var id = $(this).data('id');
            var categoryName = $('#'+id).children('td[data-target="category_name"]').text();

            $('#updateCategoryName').val(categoryName);
            $('#categoryId').val(id);
        })
    });

    $('#save').click(function(){
        var id = $('#categoryId').val();
        var categoryName = $('#updateCategoryName').val();
        $.ajax({
            url: 'model.php',
            method: 'POST',
            data: {
                categoryName:categoryName,
                id:id,
            },
            success:function(response){
                $('#'+id).children('td[data-target="category_name"]').text();
            }
        })
    });
</script>
<script>
$(document).ready(function(){
$(".nav-item a").click(function() {
  $(".nav-item a").removeClass("active");
  $(this).addClass("active");
});
});
</script>
 <?php include_once('footer.php'); ?>
 <script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "ordering": false , "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
<script>
     $(document).ready(function () {
        $('.categoryd').click(function () {
            var tdh = $(this);
            var categoryId = $(this).attr('id');
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this category!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: 'category.php',
                        type: 'post',
                        data: {
                            categoryIdd: categoryId,
                        },
                        success: function (getdata) {
                            tdh.parents('tr').hide();
                        },
                        error: function () {
                            alert("fail");
                        }
                    });
                    swal("Poof! Category has been deleted!", {
                    icon: "success",
                    });
                } else {
                    swal("Your category is safe!");
                }
                });
        });
    });
</script>
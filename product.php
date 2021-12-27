<!-- jQuery -->
<script src="plugins/jquery/sweet_alert.js"></script>
<script src="plugins/jquery/jquery.min.js"></script>
<?php 
include_once('database/connectdb.php');
session_start();
if($_SESSION['email'] == ""){
    header("location:index.php");
}
$page = 'product';
include_once('header.php'); 

error_reporting(0);
if(isset($_POST['productIdd'])){
$id = $_POST['productIdd'];

$delete = $inventory->prepare("delete from products where id = $id");
if($delete->execute()){
 
}
else{
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

if(isset($_POST['productUpdate'])){
    $productId = $_POST['id'];
    $productName = $_POST['productName'];
    $category = $_POST['category'];
    $purchasePrice = $_POST['purchasePrice'];
    $salePrice = $_POST['salePrice'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];

    $filename = $_FILES['productImage']['name'];
    $tempname = $_FILES['productImage']['tmp_name'];
    $filesize = $_FILES['productImage']['size'];
    if($tempname != ""){
        $filename = $_POST['image'];
        if (file_exists($filename)) {
          unlink($filename);
        }
        $file_extension = explode('.',$filename);
        $file_extension = strtolower(end($file_extension));
        
        $file_newfile = uniqid().'.'.$file_extension;
        $store = "storage/products/".$file_newfile;

        if($file_extension == 'jpg' || $file_extension == 'png' || $file_extension == 'jpeg' || $file_extension == 'gif'){
            if($filesize >= 10000000){
                echo '<script> 
                jQuery(function validation(){
                    swal({
                        title: "Image size exceed!",
                        text: "Upload Image size less then 10 MB!",
                        icon: "warning",
                        button: "Ok"
                    });
                    }); </script>';
            }else{
                if(move_uploaded_file($tempname,$store)){
                    $update = $inventory->prepare("update products set productName = '$productName', categoryId = $category, purchasePrice= $purchasePrice, salePrice= $salePrice, stock = $stock, description = '$description', productImage = '$store' where id=$productId");
  
  
        if($update->execute()){
          echo '<script> 
          jQuery(function validation(){
              swal({
                  title: "Updated!",
                  text: "Product Updated successfully!",
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
    else{
        $store = $_POST['image'];
        $update = $inventory->prepare("update products set productName = '$productName', categoryId = $category, purchasePrice= $purchasePrice, salePrice= $salePrice, stock = $stock, description = '$description', productImage = '$store' where id=$productId");
  
  
        if($update->execute()){
          echo '<script> 
          jQuery(function validation(){
              swal({
                  title: "Updated!",
                  text: "Product Updated successfully!",
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

        
  }



if(isset($_POST['btnProductAdd'])){
    $productName = $_POST['productName'];
    $category = $_POST['category'];
    $purchasePrice = $_POST['purchasePrice'];
    $salePrice = $_POST['salePrice'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];

    $filename = $_FILES['productImage']['name'];
    $tempname = $_FILES['productImage']['tmp_name'];
    $filesize = $_FILES['productImage']['size'];

    $file_extension = explode('.',$filename);
    $file_extension = strtolower(end($file_extension));
    
    $file_newfile = uniqid().'.'.$file_extension;
    $store = "storage/products/".$file_newfile;

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
                if($productName != "" || $category != "" || $pruchasePrice != "" || $salePrice != ""){
                    $insert = $inventory->prepare("insert into products (productName, categoryId, purchasePrice, salePrice, stock, description, productImage) values(:productName, :categoryId, :purchasePrice, :salePrice, :stock, :description, :productImage)");
              
                    $insert->bindParam(':productName', $productName);
                    $insert->bindParam(':categoryId', $category);
                    $insert->bindParam(':purchasePrice', $purchasePrice);
                    $insert->bindParam(':salePrice', $salePrice);
                    $insert->bindParam(':stock', $stock);
                    $insert->bindParam(':description', $description);
                    $insert->bindParam(':productImage', $store);
              
                    if($insert->execute()){
                      echo '<script> 
                      jQuery(function validation(){
                          swal({
                              title: "Product Added!",
                              text: "Produc added successfully!",
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
<div class="content-wrapper" style="overflow-x:auto;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Products</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><a href="product.php">Product</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                </div>
                <div class="col-6">
                    <button class="btn btn-success pull-right mb-2 mr-4" data-toggle="modal"
                        data-target="#exampleModaladd">
                        <i class="fa fa-plus pr-2"></i> Add Product</button>
                </div>
            </div>
            <div class="row">
                <div class="col-12 pt-2">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Product Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">Purchase Price</th>
                                        <th scope="col">Sale Price</th>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Product Image</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $select = $inventory->prepare("select * from products order by id desc");
                                        $select->execute();
                                        
                                        while($row = $select->fetch(PDO::FETCH_OBJ)){
                                            error_reporting(0);
                                            $select_cat = $inventory->prepare("select * from category where id = $row->categoryId");
                                            $select_cat->execute();
                                            $row_cat = $select_cat->fetch(PDO::FETCH_OBJ);
                                            
                                            echo'
                                            <tr>
                                            <td>'.$row->id.'</td>
                                            <td>'.$row->productName.'</td>
                                            <td>'.$row_cat->category.'</td>
                                            <td>'.$row->purchasePrice.'</td>
                                            <td>'.$row->salePrice.'</td>
                                            <td>'.$row->stock.'</td>
                                            <td>'.$row->description.'</td>
                                            <td><img src="'.$row->productImage.'" class="rounded img-fluid" width="50px" alt="'.$row->productImage.'"></td>
                                            <td>
                                            <a href="modal.php?id='.$row->id.'" class="btn btn-success productv" data-productv="'.$row->id.'" data-toggle="modal" data-target="#exampleModalview" name="product_view"> <i class="fa fa-eye text-white"> View</i> </a>
                                           
                                            <a href="modal.php?id='.$row->id.'" class="btn btn-primary product" data-product="'.$row->id.'" data-toggle="modal" data-target="#exampleModal" name="product_update"> <i class="fa fa-edit text-white"> Edit</i> </a>
                                            
                                            <button id="'.$row->id.'" class="btn btn-danger btndelete" title"Product Delete" data-toggle="tooltip"> <i class="fa fa-trash text-white"> Delete</i> </button>
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
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- Modal Product View-->
<div class="modal fade" id="exampleModalview" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Product View</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body product_response">
            </div>
        </div>
    </div>
</div>
<!-- Modal Product update-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Product Update</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form role="form" method="POST" action="product.php" enctype="multipart/form-data">
                <div class="modal-body product_response">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" name="productUpdate" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Add Product-->
<div class="modal fade" id="exampleModaladd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Product Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" role="form" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Product Name</label>
                                <input type="text" name="productName" class="form-control" id="productName"
                                    placeholder="Product Name" required>
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="" disabled selected>Select Category</option>
                                    <!-- Select category from category table -->
                                    <?php
                                    $select = $inventory->prepare("select * from category");
                                    $select->execute();
                                    while($row = $select->fetch(PDO::FETCH_ASSOC)){
                                        extract($row);
                                    ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['category']; ?>
                                    </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Purchase Price</label>
                                <input type="number" min="1" step="1" name="purchasePrice" class="form-control"
                                    id="purchasePrice" placeholder="Purchase Price">
                            </div>
                            <div class="form-group">
                                <label>Sale Price</label>
                                <input type="number" min="1" step="1" name="salePrice" class="form-control"
                                    id="salePrice" placeholder="Sale Price">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Stock</label>
                                <input type="number" min="1" step="1" name="stock" class="form-control" id="stock"
                                    placeholder="Enter..">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" placeholder="Enter..." name="description"
                                    id="description" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Product Image</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="productImage" class="custom-file-input"
                                            id="productImage">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" name="btnProductAdd" class="btn btn-primary float-right">Save</button>
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
            "responsive": true,
            "ordering": false,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
<script>
     $(document).ready(function () {
        $('.btndelete').click(function () {
            var tdh = $(this);
            var productId = $(this).attr('id');
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this product!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: 'product.php',
                        type: 'post',
                        data: {
                            productIdd: productId,
                        },
                        success: function (getdata) {
                            tdh.parents('tr').hide();
                        },
                        error: function () {
                            alert("fail");
                        }
                    });
                    swal("Poof! Your Product has been deleted!", {
                    icon: "success",
                    });
                } else {
                    swal("Your Product is safe!");
                }
                });
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.product').click(function () {
            var productId = $(this).data('product');
            $.ajax({
                url: 'model.php',
                type: 'post',
                data: {
                    productId: productId,
                },
                success: function (getdata) {
                    $('.product_response').html(getdata);
                },
                error: function () {
                    alert("fail");
                }
            });
        });
    });
    $(document).ready(function () {
        $('.productv').click(function () {
            var productId = $(this).data('productv');
            $.ajax({
                url: 'model.php',
                type: 'post',
                data: {
                    productIdv: productId,
                },
                success: function (getdata) {
                    $('.product_response').html(getdata);
                },
                error: function () {
                    alert("fail");
                }
            });
        });
    });
   
</script>
<!-- jQuery -->
<script src="plugins/jquery/sweet_alert.js"></script>
<script src="plugins/jquery/jquery.min.js"></script>
<?php 
include_once('database/connectdb.php');
session_start();
if($_SESSION['email'] == ""){
    header("location:index.php");
}
$page = 'orderlist';
include_once('header.php'); 

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="overflow-x:auto;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Order List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Order / <a href="product.php">Order List</a></li>
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
                   <a href="createorder.php"><button class="btn btn-success pull-right mb-2 mr-4" data-toggle="modal"
                        data-target="#exampleModaladd">
                        <i class="fa fa-plus pr-2"></i> Create Order</button></a>
                </div>
            </div>
            <div class="row">
                <div class="col-12 pt-2">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Order Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Client Name</th>
                                        <th scope="col">Client Contact</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Paid</th>
                                        <th scope="col">Due</th>
                                        <th scope="col">Payment Status</th>
                                        <th scope="col">Payment Type</th>
                                        <th scope="col">Created At</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $select = $inventory->prepare("select * from invoice order by id desc");
                                        $select->execute();
                                        
                                        while($row = $select->fetch(PDO::FETCH_OBJ)){
                                            
                                            echo'
                                            <tr>
                                            <td>'.$row->id.'</td>
                                            <td>'.$row->client_name.'</td>
                                            <td>'.$row->client_contact.'</td>
                                            <td>'.$row->total.'</td>
                                            <td>'.$row->paid.'</td>
                                            <td>'.$row->due.'</td>
                                            <td>'.$row->payment_status.'</td>
                                            <td>'.$row->payment_type.'</td>
                                            <td>'.$row->created_at.'</td>
                                            <td>
                                            <a href="invoice_db.php?id='.$row->id.'" class="btn btn-warning orderp" data-orderp="'.$row->id.'" name="order_print" target="_blank"> <i class="fa fa-print text-white"> Print</i> </a>
                                           
                                            <a href="editorder.php?id='.$row->id.'" class="btn btn-primary " data-order="'.$row->id.'" name="order_update"> <i class="fa fa-edit text-white"> Edit</i> </a>
                                            
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
<!-- jQuery -->
<script src="plugins/jquery/sweet_alert.js"></script>
<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<!-- <script src="dist/js/adminlte.min.js"></script> -->
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

    $(document).ready(function () {
        $('.btndelete').click(function () {
            var tdh = $(this);
            var orderId = $(this).attr('id');
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this order!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: 'model.php',
                        type: 'post',
                        data: {
                            orderIdd: orderId,
                        },
                        success: function (getdata) {
                            tdh.parents('tr').hide();
                        },
                        error: function () {
                            alert("fail");
                        }
                    });
                    swal("Poof! Your order has been deleted!", {
                    icon: "success",
                    });
                } else {
                    swal("Your order is safe!");
                }
                });
        });
    });
</script>
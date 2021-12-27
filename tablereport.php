<?php 
include_once('database/connectdb.php');
error_reporting(0);
session_start();
if($_SESSION['email'] == ""){
    header("location:index.php");
}
$page = 'tablereport';
include_once('header.php'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sales Report in Table Format</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Sales Report /<a href="tablereport.php"> Table Report</a>
                        </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="card-title">
                        From: <?php if(isset($_POST['date_from'])){ echo date('d-M-Y',strtotime($_POST['date_from'])); }?> -- To: <?php if(isset($_POST['date_to'])){ echo date('d-M-Y',strtotime($_POST['date_to'])); } ?>
                    </div>
                </div>
                <div class="card-body">
                    <form action="" role="form" method="post">
                        <div class="row pb-3">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <div class="input-group date" data-date-format="yyyy-MM-DD" id="reservationdate1"
                                        data-target-input="nearest">
                                        <input type="text" name="date_from" class="form-control datetimepicker-input" data-target="#reservationdate1"
                                            placeholder="Date" data-target="#reservationdate1"
                                            data-toggle="datetimepicker" />
                                        <!-- <label>From:</label> -->
                                        <div class="input-group-append" data-target="#reservationdate1"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <!-- <label>To:</label> -->
                                    <div class="input-group date" data-date-format="yyyy-MM-DD" id="reservationdate2"
                                        data-target-input="nearest">
                                        <input type="text" name="date_to" class="form-control datetimepicker-input" data-target="#reservationdate2"
                                            placeholder="Date" data-target="#reservationdate2"
                                            data-toggle="datetimepicker" />
                                        <div class="input-group-append" data-target="#reservationdate2"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <input type="submit" name="btn_table_report_filter" value="Filter By Date"
                                    class="btn btn-success">
                            </div>
                        </div>
                    </form>

                    <?php
                    $select = $inventory->prepare("select sum(total) as total, sum(subtotal) as subtotal, count(id) as invoice from invoice where order_date between :fromdate AND :todate order by id desc");
                    $select->bindParam(':fromdate',$_POST['date_from']);
                    $select->bindParam(':todate',$_POST['date_to']);

                    $select->execute();
                    $row = $select->fetch(PDO::FETCH_OBJ);
                    $net_total = $row->total;
                    $subtotal = $row->subtotal;
                    $invoice = $row->invoice;
                    ?>

                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fa fa-file"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Total Invoice</span>
                                    <span class="info-box-number">
                                        <?php echo $invoice ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-danger elevation-1"><i
                                        class="fa fa-inr"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">SUB TOTAL</span>
                                    <span class="info-box-number">&#8377; <?php echo number_format($subtotal) ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <!-- fix for small devices only -->
                        <div class="clearfix hidden-md-up"></div>

                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><i
                                        class="fa fa-inr"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">NET TOTAL</span>
                                    <span class="info-box-number">&#8377; <?php echo number_format($net_total) ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                    </div>

                    <!-- Table -->

                    <div class="row">
                <div class="col-12 pt-2">
                    <div class="card card-primary">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Client Name</th>
                                        <th scope="col">Contact</th>
                                        <th scope="col">SubTotal</th>
                                        <th scope="col">Discount</th>
                                        <th scope="col">GST (18%)</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Paid</th>
                                        <th scope="col">Due</th>
                                        <th scope="col">Payment Status</th>
                                        <th scope="col">Payment Type</th>
                                        <th scope="col">Order Date</th>
                                        <!-- <th scope="col">Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                        $select = $inventory->prepare("select * from invoice where order_date between :fromdate AND :todate order by id desc");
                                        $select->bindParam(':fromdate',$_POST['date_from']);
                                        $select->bindParam(':todate',$_POST['date_to']);

                                        $select->execute();
                                        
                                        while($row = $select->fetch(PDO::FETCH_OBJ)){
                                            error_reporting(0);
                                            
                                            echo'
                                            <tr>
                                            <td>'.$row->id.'</td>
                                            <td>'.$row->client_name.'</td>
                                            <td>'.$row->client_contact.'</td>
                                            <td>'.number_format($row->subtotal).'</td>
                                            <td>'.number_format($row->discount).'</td>
                                            <td>'.$row->gst.'</td>
                                            <td><span class="badge badge-primary">&#8377; '.number_format($row->total).'</span></td>
                                            <td>'.number_format($row->paid).'</td>
                                            <td>'.number_format($row->due).'</td>
                                            <td><span class="badge badge-success">'.$row->payment_status.'</span></td>';
                                            ?>
                                            <?php
                                            if($row->payment_type == 'cash'){
                                                echo'<td><span class="badge badge-primary">'.$row->payment_type.'</span></td>';
                                            }
                                            elseif($row->payment_type == 'card'){
                                                echo'<td><span class="badge badge-warning">'.$row->payment_type.'</span></td>';
                                            }
                                            else{
                                                echo'<td><span class="badge badge-info">'.$row->payment_type.'</span></td>';
                                            }
                                            echo'
                                            <td>'.date('d-M-Y',strtotime($row->order_date)).'</td>';                                            
                                        }
                                        ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include_once('footer.php'); ?>
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
    //Date picker
    $('#reservationdate1').datetimepicker({
        format: 'L',
        autoclose: 'true'
    });
    $('#reservationdate2').datetimepicker({
        format: 'L',
        autoclose: 'true'
    });
</script>
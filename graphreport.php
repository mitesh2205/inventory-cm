<?php 
include_once('database/connectdb.php');
error_reporting(0);
session_start();
if($_SESSION['email'] == ""){
    header("location:index.php");
}
$page = 'graphreport';
include_once('header.php'); ?>
<script src="chart.js-3.6.2/package/dist/chart.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sales Report in Graph Format</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Sales Report /<a href="graphreport.php"> Graph Report</a>
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
                                        <div class="input-group-append" data-target="#reservationdate1"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
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
                    $select = $inventory->prepare("select order_date, sum(total) as price from invoice where order_date between :fromdate AND :todate group by order_date");

                    $select->bindParam(':fromdate',$_POST['date_from']);
                    $select->bindParam(':todate',$_POST['date_to']);
                    $select->execute();

                    $total = [];
                    $date = [];
                    while($row = $select->fetch(PDO::FETCH_ASSOC)){
                        extract($row);
                        $total[] = $price;
                        $date[] = $order_date;
                    }
                    // echo json_encode($price);
                    ?>
                    <div class="chart" >
                        <canvas id="myChart" style="max-height:250px"></canvas>
                    </div>
                    <?php
                    $select = $inventory->prepare("select product_name, sum(quantity) as quantity from invoice_details where order_date between :fromdate AND :todate group by product_id");

                    $select->bindParam(':fromdate',$_POST['date_from']);
                    $select->bindParam(':todate',$_POST['date_to']);
                    $select->execute();

                    $pname = [];
                    $qty = [];
                    while($row = $select->fetch(PDO::FETCH_ASSOC)){
                        extract($row);
                        $pname[] = $product_name;
                        $qty[] = $quantity;
                    }
                    // echo json_encode($price);
                    ?>
                    <div class="chart">
                        <canvas id="bestSellingProduct" style="max-height:250px"></canvas>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>

<?php include_once('footer.php'); ?>
<script>
  const labels = <?php echo json_encode($date); ?>;
const data = {
  labels: labels,
  datasets: [{
    label: 'Total Earning',
    backgroundColor: 'rgb(255, 99, 132)',
    borderColor: 'rgb(255, 99, 132)',
    data: <?php echo json_encode($total);?>,
  }]
};

const config = {
  type: 'bar',
  data: data,
  options: {}
};

const myChart = new Chart(
    document.getElementById('myChart'),
    config
  );

  const label_productName = <?php echo json_encode($pname); ?>;
const data_bestSellingProduct = {
  labels: label_productName,
  datasets: [{
    label: 'Quantity Sold',
    backgroundColor: 'rgb(102, 99, 132)',
    borderColor: 'rgb(102, 99, 132)',
    data: <?php echo json_encode($qty);?>,
  }]
};

const config_bestSellingProduct = {
  type: 'line',
  data: data_bestSellingProduct,
  options: {}
};

const bestSellingProduct = new Chart(
    document.getElementById('bestSellingProduct'),
    config_bestSellingProduct
  );


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
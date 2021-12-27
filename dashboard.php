<?php 
include_once('database/connectdb.php');

session_start();
if($_SESSION['email'] == ""){
    header("location:index.php");
}
$page = 'dashboard';
include_once('header.php'); 
$select = $inventory->prepare("select sum(total) as total , count(id) as invoice from invoice");
$select->execute();
$select_product = $inventory->prepare("select count(productName) as p from products");
$select_product->execute();

$select_category = $inventory->prepare("select count(id) as c from category");
$select_category->execute();

$row = $select->fetch(PDO::FETCH_OBJ);
$row_product = $select_product->fetch(PDO::FETCH_OBJ);
$row_category = $select_category->fetch(PDO::FETCH_OBJ);

$net_total = $row->total;
$total_order = $row->invoice;

$total_product = $row_product->p;

$total_category = $row_category->c;


$select = $inventory->prepare("select order_date, total from invoice group by order_date desc LIMIT 30");

$select->execute();

$ttl = [];
$date = [];
while($row = $select->fetch(PDO::FETCH_ASSOC)){
    extract($row);
    $ttl[] = $total;
    $date[] = $order_date;
}
// echo json_encode($ttl);


?>
<script src="chart.js-3.6.2/package/dist/chart.min.js"></script>
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Admin Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active"><a href="#">Dashboard</a></li>

          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3><?php echo $total_order; ?></h3>

              <p>Total Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              <h3>&#8377; <?php echo number_format($net_total); ?></h3>

              <p>Total Revenue</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              <h3><?php echo $total_product; ?></h3>

              <p>Total Products</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-danger">
            <div class="inner">
              <h3><?php echo $total_category; ?></h3>

              <p>Total Category</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>

      <div class="card card-primary card-outline">
        <div class="card-header">
          <div class="card-title">
            Earning By Date
          </div>
        </div>
        <div class="card-body">
          <div class="chart">
            <canvas id="earningByDate" style="max-height:250px"></canvas>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
        <div class="card card-danger card-outline">
        <div class="card-header">
          <div class="card-title">
            Best Selling Product
          </div>
        </div>
        <div class="card-body">
        <table id="best_sellingProductList" class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Price</th>
                    <th scope="col">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
              $select = $inventory->prepare("select product_id, product_name, price, sum(quantity) as q, sum(quantity * price) as total from invoice_details group by product_id order by sum(quantity) desc LIMIT 30");

              $select->execute();
              
              while($row = $select->fetch(PDO::FETCH_OBJ)){
                echo'
                <tr>
                <td>'.$row->product_id.'</td>
                <td>'.$row->product_name.'</td>
                <td><span class="badge badge-primary">'.$row->q.'</span></td>
                <td><span class="badge badge-success">&#8377; '.number_format($row->price).'</span></td>
                <td><span class="badge badge-warning">&#8377;'.number_format($row->total).'</span></td>
                </tr>';
                
              }
            ?>
                </tbody>
              </table>
        </div>
      </div>
        </div>
        <div class="col-md-6">
        <div class="card card-warning card-outline">
        <div class="card-header">
          <div class="card-title">
            Recent Orders
          </div>
        </div>
        <div class="card-body">
        <table id="recent_orders" class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Client Name</th>
                    <th scope="col">orderDate</th>
                    <th scope="col">Total</th>
                    <th scope="col">Payment Type</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
              $select = $inventory->prepare("select * from invoice order by id desc LIMIT 50");

              $select->execute();
              
              while($row = $select->fetch(PDO::FETCH_OBJ)){
                echo'
                <tr>
                <td><a href="editorder.php?id='.$row->id.'">'.$row->id.'</a></td>
                <td>'.$row->client_name.'</td>
                <td>'.$row->order_date.'</td>
                <td><span class="badge badge-warning">&#8377; '.number_format($row->total).'</span></td>';
                ?>
                <?php
                if($row->payment_type == 'cash'){
                    echo'<td><span class="badge badge-primary">'.$row->payment_type.'</span></td>';
                }
                elseif($row->payment_type == 'card'){
                    echo'<td><span class="badge badge-warning">'.$row->payment_type.'</span></td>';
                }
                else{
                    echo'<td><span class="badge badge-primary">'.$row->payment_type.'</span></td>';
                }
                echo'
                </tr>';
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
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">

    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
  const labels_earningByDate = <?php echo json_encode($date); ?> ;
  const data = {
    labels: labels_earningByDate,
    datasets: [{
      label: 'Total Earning',
      backgroundColor: 'rgb(255, 99, 132)',
      borderColor: 'rgb(255, 99, 132)',
      data: <?php echo json_encode($ttl); ?> ,
    }]
  };

  const config_earningByDate = {
    type: 'bar',
    data: data,
    options: {}
  };

  const earningByDate = new Chart(
    document.getElementById('earningByDate'),
    config_earningByDate
  );

</script>
<?php include_once('footer.php'); ?>
<script>
  $(function () {
    $('#best_sellingProductList').DataTable({
        "ordering":false
    });
  });

  $(function () {
    $('#recent_orders').DataTable({
        "ordering":false
    });
  });
</script>
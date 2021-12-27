<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini dark-mode">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="profile.php" class="nav-link">Profile</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link">Contact</a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">

        <li class="nav-item d-none d-sm-inline-block">
          <a href="logout.php" class="nav-link">
            <i class="fa fa-sign-out"> Logout</i>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>



      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
                 
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="index3.html" class="brand-link">
        <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
          style="opacity: .8">
        <span class="brand-text font-weight-light"><B>INVENTORY</b>-POS</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
                  <?php
                  $email = $_SESSION['email'];
                  $select_info = $inventory->prepare("select * from users where email = '$email'");
                  $select_info->execute();
                
                  $row_info = $select_info->fetch(PDO::FETCH_ASSOC);
                  ?>
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="<?php echo $row_info['image'] != null ? $row_info['image'] : 'storage/users/avatar.svg';  ?>" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block"><?php echo "Welcome - ".$_SESSION['username']; ?></a>
          </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item ">
              <a href="dashboard.php" class="nav-link <?php if($page=='dashboard'){echo 'active';}?>">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="category.php" class="nav-link <?php if($page=='category'){echo 'active';}?>">
                <i class="nav-icon fa fa-list-alt"></i>
                <p>
                  Category
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="product.php" class="nav-link <?php if($page=='product'){echo 'active';}?>">
                <i class="nav-icon fa fa-product-hunt"></i>
                <p>
                  Product
                </p>
              </a>
            </li>
            <li class="nav-item <?php if($page=='ordercreate' || $page=='orderlist'){echo 'menu-is-opening menu-open';}?>">
            <a href="#" class="nav-link ">
              <i class="nav-icon fa fa-first-order"></i>
              <p>
                Order
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="<?php if($page=='order' || $page=='orderlist' || $page=='ordercreate'){echo 'display:block';}?>">
              <li class="nav-item ">
                <a href="orderlist.php" class="nav-link <?php if($page=='orderlist'){echo 'active';}?>">
                  <i class="fa fa-list-ul nav-icon"></i>
                  <p>Order List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="createorder.php" class="nav-link <?php if($page=='ordercreate'){echo 'active';}?>">
                  <i class="fa fa-first-order nav-icon"></i>
                  <p>Create Order</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item <?php if($page=='tablereport' || $page=='graphreport'){echo 'menu-is-opening menu-open';}?>">
            <a href="#" class="nav-link ">
              <i class="nav-icon fa fa-first-order"></i>
              <p>
                Sales Report
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="<?php if($page=='sales_report' || $page=='tablereport' || $page=='graphreport'){echo 'display:block';}?>">
              <li class="nav-item ">
                <a href="tablereport.php" class="nav-link <?php if($page=='tablereport'){echo 'active';}?>">
                  <i class="fa fa-list-ul nav-icon"></i>
                  <p>Table Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="graphreport.php" class="nav-link <?php if($page=='graphreport'){echo 'active';}?>">
                  <i class="fa fa-first-order nav-icon"></i>
                  <p>Graph Report</p>
                </a>
              </li>
            </ul>
          </li>
            <li class="nav-item">
              <a href="registration.php" class="nav-link <?php if($page=='registration'){echo 'active';}?>">
                <i class="nav-icon fa fa-registered"></i>
                <p>
                  Registration
                </p>
              </a>
            </li>
            
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>
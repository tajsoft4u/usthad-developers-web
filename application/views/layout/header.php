<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="" content="">
  <meta name="author" content="">
  <title>USTHAD DEVELOPERS</title>
  <!-- Vendors Style-->
  
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/assets/css/vendors_css.css">
  <!-- Style-->
  <link rel="icon" href="<?php echo base_url()?>uploads/banner/banner.jpeg">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/assets/css/skin_color.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
</head>

<body class="hold-transition light-skin sidebar-mini theme-primary">

  <div class="wrapper">

    <header class="main-header">
      <div class="d-flex align-items-center logo-box justify-content-between">
        <a href="#" class="waves-effect waves-light nav-link rounded d-none d-md-inline-block mx-10 push-btn" data-toggle="push-menu" role="button">
          <i class="ti-menu"></i>
        </a>
        <!-- Logo -->
        <a href="<?php echo base_url() ?>" class="logo">
          <!-- logo-->
          <div class="logo-lg">
            <!-- <span class="light-logo">Usthad Developers</span> -->
          <?php $banner=$this->db->where('id',1)->get('banner')->row();?>
            <img src="<?php echo $banner->imgUrl?>"/>
          </div>
        </a>
      </div>
      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top pl-10">
        <!-- Sidebar toggle button-->
        <div class="app-menu">
          <ul class="header-megamenu nav">
            <li class="btn-group nav-item d-md-none">
              <a href="#" class="waves-effect waves-light nav-link rounded push-btn" data-toggle="push-menu" role="button">
                <i class="ti-menu"></i>
              </a>
            </li>

          </ul>
        </div>

        <div class="navbar-custom-menu r-side">
          <ul class="nav navbar-nav">

         
            <!-- User Account-->
            <li class="dropdown user user-menu ">
              <a href="#" class="waves-effect waves-light dropdown-toggle float-right" data-toggle="dropdown" title="User">
                <i class="ti-user float-right"></i>
              </a>
              <ul class="dropdown-menu animated flipInX">
                <li class="user-body">
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="<?php echo base_url('Master/adminLogout') ?>"><i class="ti-lock text-muted mr-2"></i>Logout</a>
                </li>
              </ul>
            </li>


          </ul>
        </div>
      </nav>
    </header>

    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar-->
      <section class="sidebar">
        <div class="user-profile px-10 py-15">
          <div class="d-flex align-items-center">
            <!-- <div class="info ml-10">
              <h5 class="mb-0"><?php echo $this->session->userdata('admin_username') ?></h5>
            </div> -->
          </div>
        </div>

        <!-- sidebar menu-->
        <ul class="sidebar-menu" data-widget="tree">

          <li>
            <a href="<?php echo base_url('products') ?>">
              <i class="ti-layout-grid2"></i>
              <span>Product</span>
            </a>
          </li>
          <li>
            <a href="<?php echo base_url('locations') ?>">
              <i class="ti-location-pin"></i>
              <span>Location</span>
            </a>
          </li>
          <li>
            <a href="<?php echo base_url('cities') ?>">
              <i class="ti-location-pin"></i>
              <span>Cities</span>
            </a>
          </li>
          <li>
            <a href="<?php echo base_url('area') ?>">
              <i class="ti-location-pin"></i>
              <span>Areas</span>
            </a>
          </li>

          <li>
            <a href="<?php echo base_url('categories') ?>">
              <i class="ti-shopping-cart"></i>
              <span>Category</span>
            </a>
          </li>
          <li>
            <a href="<?php echo base_url('budget') ?>">
              <i class="ti-money"></i>
              <span>Budget</span>
            </a>
          </li>
          <!-- <li> 
            <a href="<?php echo base_url('Master/adminLogout') ?>">
              <i class="ti-lock"></i>
              <span>Logout</span>
            </a>
          </li> -->
        </ul>
      </section>

    </aside>
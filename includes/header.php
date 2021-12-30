<?php
 if(isset($_GET['action']) && $_GET['action'] == 'pin'){
   
  $status['pin'] = 1;
  $obj->Update("tbl_notes",$status,"nid",array($_GET['id']));
  echo "<script> window.location.href='".base_url('home')."'</script>";

}
if(isset($_GET['action']) && $_GET['action'] == 'unpin'){
  $status['pin'] = 0;
  $obj->Update("tbl_notes",$status,"nid",array($_GET['id']));
  echo "<script> window.location.href='".base_url('home')."'</script>";

}
require_once ("config/config.php");
require_once ("config/db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contract Management system</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
 <!-- <link rel="stylesheet" href="assets/nepali-date-picker/np-date-picker.css"> -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="dist/css/style.css">
  <script src="plugins/jquery/jquery.min.js"></script>
<style>
  .media-body{
    margin-top:-32px;
  }
  th{
        position: sticky;
        top:0;
        background-color: #fff;
        color:#000;
    }
</style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    
  <!-- Preloader -->
  <!-- <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div> -->

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
     
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <!-- <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li> -->

      <!-- Note  Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
        <b>  <i class="far fa-edit"></i> Your Note </b>
         
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <!-- pin section  -->
           <?php
            $pinnote = $obj->select("tbl_notes","*","pin",array(1)," ORDER BY date DESC LIMIT 1");
            if($pinnote){ 
              foreach($pinnote as $k => $pinnote) {
              ?>
                <a href="add_note.php?action=showall&nid=<?=$pinnote['nid'];?>" class="" style="padding-left: 6px; padding-right:6px;">
            <!-- Message Start -->
            <div class="media">
              <div class="media-body" style="background:#2d830a;margin-top:0;padding-left:3px;">
                <h3 class="dropdown-item-title">
                <b style="color:#fff"><?=$pinnote['note_title'];?></b>
                <?php if($pinnote['pin'] == 1){ ?>
                <a title="Unpin Note" href="<?=base_url('header.php?action=unpin&id='.$pinnote['nid']);?>"  class="float-right text-sm text-danger mr-3" ><i class="fa fa-thumbtack fa-2x"></i></a>

               <?php  } elseif($pinnote['pin'] == 0){ ?>
                  <a title="Pin Note" href="<?=base_url('header.php?action=pin&id='.$pinnote['nid']);?>"  class="float-right text-sm text-success mr-3" ><i class="fa fa-thumbtack fa-2x"></i></a>

               <?php  } ?>
                </h3>
                <?php $para = substr($pinnote['note'], 0, 60); ?>
                <a style="color:#fff" href="add_note.php?action=showall&nid=<?=$pinnote['nid'];?>"><?=$para; if(strlen($pinnote['note'])>60) echo "...";?></a>
                <p class="text-sm mb-1"  style="color:#fff"><i class="fa fa-calendar mr-1"></i>  <?=$pinnote['date'];?></p>
              </div>
            </div>
            <!-- Message End -->
          </a>
           <?php  } }
           ?>
        
          <!-- message section  -->
          <?php 
          $notes = $obj->select("tbl_notes","*","pin",array(0)," ORDER BY date DESC LIMIT 4");
          
          foreach($notes as $key => $notes) {
            
          ?>
             <a href="add_note.php?action=showall&nid=<?=$notes['nid'];?>" class="" style="padding-left: 6px; padding-right:6px;">
            <!-- Message Start -->
            <div class="media">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                <b><?=$notes['note_title'];?></b>
                  <a title="Pin Note" href="<?=base_url('header.php?action=pin&id='.$notes['nid']);?>"  class="float-right text-sm text-black mr-3" ><i class="fa fa-thumbtack fa-2x"></i></a>
               
                </h3>
                <?php $para = substr($notes['note'], 0, 60); ?>
                <a href="add_note.php?action=showall&nid=<?=$notes['nid'];?>"><?=$para; if(strlen($notes['note'])>60) echo "...";?></a>
                <!-- <p class="text-sm"><?=$notes['note'];?></p> -->
                <p class="text-sm text-muted"><i class="fa fa-calendar mr-1"></i>  <?=$notes['date'];?></p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider mt-2"></div>

          <?php } ?>
          <!-- message section  -->

          <div class="footer-m" style="display: flex;justify-content:center;align-items:center;">
          <a title="Add Note " href="<?=base_url('add_note');?>" class="btn btn-sm btn-success ml-2" style="float: right !important;" ><i class="fa fa-plus"></i></a>

              <a href="<?=base_url('add_note');?>" class="dropdown-item dropdown-footer">See All Messages</a>
          </div>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <?php 
           $con_ids = '';
           $redListContracts = '';
          $count = 0;
          $contract_dates = $obj->select("tbl_contract");
            $today_date =strtotime( date('Y-m-d'));
            foreach($contract_dates as $value){
              if($today_date > strtotime($value['end_date']))
              $check_date = ($today_date - strtotime($value['end_date']));
              
              elseif($today_date < strtotime($value['end_date']))
              $check_date = (strtotime($value['end_date'])-$today_date);
              $sum = round(($check_date / 86400));
             
              if($sum <= 30 && $sum >=0){
               if($value['status'] == 1){
                $count++;
               }
                $redList[] = $value['conid'];
                // $redList = array_unique($redList);
                $con_ids = implode(",",$redList);
                
              }
            } 
            

           
              
          ?>
          <span class="badge badge-warning navbar-badge"><?=$count;
?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header text-danger"><b><?php if($count>1){
            echo $count." Contracts are expiring soon.";
          }else{
            echo $count." Contract is expiring soon.";
          }?> </b></span>
          <div class="dropdown-divider"></div>
          <?php 
          $today_date =strtotime( date('Y-m-d'));
        
if($con_ids != ''){
  $sql = "SELECT * FROM tbl_contract WHERE conid in(".$con_ids.") AND status = 1 LIMIT 5";
  // $sql = "SELECT * FROM tbl_contract WHERE conid in(".$con_ids.")  LIMIT 5";

  // echo $sql ;
 
  $redListContracts = $obj->ArrQuery($sql);

}
if($redListContracts){
          foreach($redListContracts as $cons){ ?>
          <a href="<?=base_url('urgent_bill')."?action=from_notify&id=".$cons['conid'];?>" class="dropdown-item">
            <i class="fas fa-home mr-2"></i>
            <b><?php 
            
            $con_title = substr($cons['con_title'], 0, 20);
            
            ?>
          <?=$con_title; if(strlen($cons['con_title'])>20) echo "...";?>  
          </b>
            <span class="float-right text-danger text-sm">
            <?php  
             if($today_date > strtotime($cons['end_date']))
             $check_date = ($today_date - strtotime($cons['end_date']));
             
             elseif($today_date < strtotime($cons['end_date']))
             $check_date = (strtotime($cons['end_date'])-$today_date);
           $check_date = ($check_date / 86400);
           if($check_date < 2){
            echo $check_date . " Day ";
           }else{
            echo $check_date . " Days ";

           }
          
            ?>

            </span>
            <?php $org_name = $obj->select("tbl_organization","*","oid",array($cons['org_id'])); ?>
           <p class="text-info ml-3">( <?= $org_name[0]['org_name'];?>)</p>
           
            
          </a>
          
          <div class="dropdown-divider"></div>
          <?php }
          }
          ?>
         
          <a href="<?=base_url('urgent_bill');?>" class="dropdown-item dropdown-footer text-primary text-bold bg-success">See All Expiring Contracts</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="btn btn-warning"  href="<?=base_url('logout');?>" >
          <i class="fas fa-power-off"></i> Log Out
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4 bg-secondary">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">CMS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
        </div>
      </div> -->

      <!-- SidebarSearch Form -->
      <!-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open mt-2">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Organization
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?=base_url('add_org');?>" class="nav-link active">
                  <i class="fas fa-plus nav-icon"></i>
                  <p>Add Organization</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?=base_url('display_org');?>" class="nav-link active">
                  <i class="fas fa-tv nav-icon"></i>
                  <p>Display Organization</p>
                </a>
              </li>
              
            </ul>
          </li>
      
          <!-- <li class="nav-item">
            <a href="pages/widgets.html" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Widgets
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li> -->
          <li class="nav-item menu-open bg-primary mt-2">
            <a href="<?=base_url('display_contract');?>" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Display All Contracts
              </p>
            </a>
          </li>
       
          <li class="nav-item menu-open bg-primary mt-2">
            <a href="<?=base_url('add_contact_person');?>" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Add Contact Persons
              </p>
            </a>
          </li>
              <!-- bill  -->
              <li class="nav-item menu-open mt-2">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-receipt"></i>
              <p>
                Bill
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <!-- <li class="nav-item">
                <a href="<?=base_url('add_bill');?>" class="nav-link active">
                  <i class="fas fa-plus nav-icon"></i>
                  <p>Add Bill</p>
                </a>
              </li> -->
              <li class="nav-item">
                <a href="<?=base_url('display_bill');?>" class="nav-link active">
                  <i class="fas fa-tv nav-icon"></i>
                  <p>Display all Bill</p>
                </a>
              </li>
              
            </ul>
          </li>

          <!-- <li class="nav-item menu-open bg-primary mt-2">
            <a href="<?=base_url('add_payment');?>" class="nav-link">
              <i class="nav-icon fas fa-money-bill-alt"></i>
              <p>
                Receive Payment
              </p>
            </a>
          </li> -->
          <li class="nav-item menu-open bg-primary mt-2">
            <a href="<?=base_url('display_payment');?>" class="nav-link">
              <i class="nav-icon fas fa-receipt"></i>
              <p>
                Display Payment
              </p>
            </a>
          </li>
          <li class="nav-item menu-open bg-primary mt-2">
            <a href="<?=base_url('add_user');?>" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Add User
              </p>
            </a>
          </li>
          <li class="nav-item menu-open bg-primary mt-2">
            <a href="<?=base_url('add_note');?>" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Your Notes
              </p>
            </a>
          </li>
          <li class="nav-item menu-open bg-primary mt-2">
            <a href="<?=base_url('view_commission_details');?>" class="nav-link">
              <i class="nav-icon fas fa-money-check"></i>
              <p>
                Commissions
              </p>
            </a>
          </li>
          <li class="nav-item menu-open bg-warning mt-2">
            <a href="<?=base_url('urgent_bill');?>" class="nav-link">
              <i class="nav-icon fas fa-bell"></i>
              <p>
                Expiring Contracts
              </p>
            </a>
          </li>
          <li class="nav-item menu-open bg-danger mt-2">
            <a href="<?=base_url('expired_contract_list');?>" class="nav-link">
              <i class="nav-icon fas fa-book-dead"></i>
              <p>
                Expired Contracts
              </p>
            </a>
          </li>
          
         
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <div class="content-wrapper">
<div class="container">
<div class="row">


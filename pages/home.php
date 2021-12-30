<?php
$orgs_only = $active_orgs = $inactive_orgs = $total_contracts = $active_contracts = $inactive_contracts
= $total_received_amt = $total_due_amt = $total_billed_amt = 0;
// org 
$orgs_only = $obj->Query("SELECT count(oid) as orgs FROM tbl_organization ");
$orgs_only = $orgs_only[0]->orgs;  
// active org  
$orgs = $obj->Query("SELECT count(oid) as orgs FROM tbl_organization WHERE status =1");
$active_orgs = $orgs[0]->orgs;
// inactive org 
$orgs1 = $obj->Query("SELECT count(oid) as orgs1 FROM tbl_organization WHERE status =1");
$inactive_orgs = $orgs1[0]->orgs1;

// total contracts 
$total_contracts = $obj->Query("SELECT count(conid) as contract FROM tbl_contract");
$total_contracts = $total_contracts[0]->contract;
// active contracts 
$active_contracts = $obj->Query("SELECT count(conid) as contract FROM tbl_contract WHERE status = 1");
$active_contracts = $active_contracts[0]->contract;

// inactive contracts 
$inactive_contracts = $obj->Query("SELECT count(conid) as contract FROM tbl_contract WHERE status = 0");
$inactive_contracts = $inactive_contracts[0]->contract;

// total billed amount 
$total_billed_amt = $obj->Query("SELECT SUM(bill_amount) as total_amt FROM tbl_bill");
$total_billed_amt = $total_billed_amt[0]->total_amt;

// total received amount 
$total_received_amt = $obj->Query("SELECT SUM(pay_amount) as total_amt FROM tbl_payment");
$total_received_amt = $total_received_amt[0]->total_amt;

$total_due_amt = $total_billed_amt - $total_received_amt;


?>

<div class="col-md-12">
    <hr>
    <h1 class="text-center" style="text-shadow: 0px 0px 35px #2196f3;">Dashboard Overview</h1>
<hr>
    <div class="row info-section">
        <div class="col-md-4">
            <table  class="table table-bordered">
                <tr >
                    <th style="color:#000">Total <i class="fa fa-circle ml-4" ></th>
                    <th style="color:green">Active <i class="fa fa-circle ml-4" ></th>
                    <th style="color:red">Inactive <i class="fa fa-circle ml-4" ></th>
                </tr>
                
            </table>
        </div>
        <div class="col-md-7 ml-5">
            <table class="table table-bordered">
                <tr>
                    <th style="color:#000">Total Receivabel <i class="fa fa-circle ml-4" ></th>
                    <th style="color:green">Received <i class="fa fa-circle ml-4" ></th>
                    <th style="color:red">Due <i class="fa fa-circle ml-4" ></th>
                </tr>
                
            </table>
        </div>
    </div>
<div class="row">
    <div class="col-md-6 d_boxes">
       <h3><b> Organization Info</b></h3>
       <hr>
            <div class="content-box">
                <div class="org">
                <h4><i class="fa fa-circle"></i> <?=$orgs_only; ?></h4>
                </div>
            <div class="active-org">
                <h4><i class="fa fa-circle"></i> <?= $active_orgs; ?></h4>
            </div>
            <div class="inactive-org">
                <h4><i class="fa fa-circle"></i> <?= $inactive_orgs; ?></h4>
            </div>
            
            </div>
     <hr>
    </div>

    
    <div class="col-md-5 d_boxes">
   <h3><b>Contract Info</b></h3>
   <hr>
            <div class="content-box">
                <div class="org">
                <h4><i class="fa fa-circle"></i> <?=$total_contracts; ?></h4>
                </div>
            <div class="active-org">
                <h4><i class="fa fa-circle"></i> <?= $active_contracts; ?></h4>
            </div>
            <div class="inactive-org">
                <h4><i class="fa fa-circle"></i> <?= $inactive_contracts; ?></h4>
            </div>
            
            </div>
     <hr>
    </div>
    
    <div class="col-md-6 d_boxes">
   <h3><b>Bill Info</b></h3>
   <hr>
            <div class="content-box">
                <div class="org">
                <h4><i class="fa fa-circle"></i> Rs.<?=$total_billed_amt; ?></h4>
                </div>
            <div class="active-org">
                <h4><i class="fa fa-circle"></i> Rs.<?= $total_received_amt; ?></h4>
            </div>
            <div class="inactive-org">
                <h4><i class="fa fa-circle"></i> Rs.<?= $total_due_amt; ?></h4>
            </div>
            
            </div>
     <hr>
    </div>
       
       
        
   
  
<style>
    .d_boxes{
        max-height: 180px;
        margin:8px;
        padding:20px;
        box-shadow:0 4px 10px 0 rgba(0,0,0,0.2);
        box-shadow: 0px 0px 10px #2196f3;
        
    }
    .content-box{
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .org,.active-org,.inactive-org{
        width: 33%;
        padding-left: 10px;
    }
    .active-org{
        color:green;

    }
    .inactive-org{
        color: red;
    }
    .info-section{
        /* padding:4px; */
        margin-bottom:20px;
              /* box-shadow: 0px 0px 25px #2196f3; */
    }
  
</style>
</div>
</div>
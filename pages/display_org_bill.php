<?php
// if coming from direct org 

if(isset($_GET['action']) && $_GET['action'] == 'delete'){
    $obj->delete("tbl_bill","bid",array($_GET['bid']));
    echo "<script>window.location.href='".base_url('display_org_bill.php?action=from_org&org_id='.$_GET['org_id'])."'</script>";
    
}

if(isset($_GET['org_id'])){
    $orgname = $obj->select('tbl_organization','*','oid',array($_GET['org_id']));

}
if(isset($_GET['action']) && $_GET['action'] == 'org_bill'){
    $bill_data = $obj->select('tbl_bill','*','org_id',array($_GET['org_id']));

}else{
    $bill_data = $obj->select('tbl_bill');

    if(isset($_GET['conid'])){
        $bill_data = $obj->select("tbl_bill","*","con_id",array($_GET['conid']));
    }

}
if(isset($_GET['action']) && $_GET['action'] == 'from_org'){
    $con_ids = $obj->select("tbl_contract","*","org_id",array($_GET['org_id']));
    foreach($con_ids as $key => $cids){
    //    if(++$key < sizeof($cids)){

    //    }
    $contract_ids[] = $cids['conid'];
    }
    $con_ids = implode(",",$contract_ids);

   $bill_data = $obj->ArrQuery("SELECT * FROM tbl_bill WHERE con_id in(".$con_ids.")");
//    print_r($bill_data);die;
    
}
if(isset($_POST['submit']) && $_POST['submit'] == 'search'){
    unset($_POST['submit']);
    $from = $_POST['from_date'];
    if($_POST['to_date'] == ''){
        $_POST['to_date'] = date('Y-m-d');
    }
    $to = $_POST['to_date'];

    $sql = "SELECT * FROM tbl_bill WHERE bill_date BETWEEN '$from' AND '$to' ";
    if(isset($_POST['con_id']) && $_POST['con_id'] !=''){
        $sql.= " AND con_id = ".$_POST['con_id'];
    }
    
    $bill_data = $obj->ArrQuery($sql);
    
}

$org = $obj->select('tbl_organization');
$contracts = $obj->select('tbl_contract');
$minDate = $obj->select('tbl_bill','MIN(bill_date) as min_date');
?>
<?php if (isset($_SESSION['success'])) { ?>
    <div class="alert alert-success">
        <?php echo $_SESSION['success'];unset($_SESSION['success']);  ?>
    </div>
    <?php }  ?>
    <?php if (isset($_SESSION['error'])) { ?>
    <div class="alert alert-danger">
        <?php echo $_SESSION['error'];unset($_SESSION['error']);  ?>
    </div>
    <?php }  ?>
   <div class="col-md-12"> <h1>Bills of <?=$orgname[0]['org_name'];?></h1> </div>
  
 <div class="col-md-12">
 <form method="post" class="form-group">
 <div class="row">
        
                <div class="col-md-4 mb-4 ">
                <?php
                $contracts= $obj->select('tbl_contract','*','org_id',array($_GET['org_id'])," AND status = 1"); ?>
                    <label for="Contract">Select Contract</label>
                    <select name="con_id"  class="form-control " id="contract">
                    <option selected disabled>Select contract</option>
                    <?php foreach($contracts as $contract): ?>
                    <option value="<?=$contract['conid'];?>" <?php if(isset($_GET['conid'])){if($contract['conid'] == $_GET['conid']) echo " selected ";} ?>><?=$contract['con_title'];?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
               
                

</div>
<div class="col-md-12">
               <label for="">Date Range</label>
               
                   <div class="row">
                   <div class="col-md-5 mb-4 ">
                        <input type="date" class="form-control" name="from_date" required min="<?=$minDate[0]['min_date'];?>"  max="<?=date('Y-m-d');?>">
                    </div>
                    <div class="col-md-5 mb-4 ">
                        <input type="date" class="form-control" name="to_date" min="<?=$minDate[0]['min_date'];?>" max="<?=date('Y-m-d');?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success" name="submit" value="search">Search</button>
                    </div>
                    </div>
               
               </div>
               </form>
 </div>

 <div class="bill_table col-md-12">
    <?php if($bill_data){ ?>

     

<table class="table table-bordered ">
    <tr>
        <th>SN</th>
        <th> Bill Title</th>
        <th>Bill No </th>
        <th>Amount(Rs)</th>
        <th>Due Amount(Rs)</th>

        <th>Billed Date </th>
        <th colspan="2">Action </th>
        <th colspan="2">Payment</th>

    </tr>
    <?php foreach($bill_data as $key=>$value) : ?>
        <tr>
            <td><?=++$key;?></td>
            <td><?=$value['bill_title'];?></td>
            <td><?=$value['bill_no'];?></td>
            <td><?=($value['taxable_amount']+$value['vat_amount']);?></td>
            <?php
           
             $total_con_amount = $obj->select("tbl_bill","*","bid",array($value['bid']));
            $total_con_amount = $total_con_amount[0]['taxable_amount'] + $total_con_amount[0]['vat_amount'];
            $total_paid = $obj->select('tbl_payment',' SUM(with_tds+vat_amount) as paid','bill_id',array($value['bid']));
            $total_paid = $total_paid[0]['paid'];
            $total_due = $total_con_amount - $total_paid; 
            ?>
            <td><?=$total_due;?></td>

            <td><?=$value['bill_date'];?></td>
           
            <td>
                <?php 
                if(isset($_GET['action']) && $_GET['action'] == 'from_org'){
                    $conid = $obj->select("tbl_bill","*","bid",array($value['bid']));
                
                    $conid = $conid[0]['con_id']; if($total_paid > 0){ ?>
                        <a class="btn btn-danger" >Locked</a>

                   <?php  }else{ ?>
                        <a href="<?=base_url('edit_org_bill'); ?>?org_id=<?=$_GET['org_id'];?>&bid=<?=$value['bid']."&conid=".$conid;?>" class="btn btn-warning ">Edit</a>
                <a href="<?=base_url("display_org_bill.php?action=delete&bid=".$value['bid']."&org_id=".$_GET['org_id']."&conid=".$conid); ?>" class="btn btn-danger " onclick="return confirm('Are you sure you want to delete this?')">Delete</a>

                   <?php  } ?>


               <?php  }else{ if($total_paid > 0 ){ ?>
                        <a class="btn btn-danger" >Locked</a>

              <?php  }else{ ?>
                <a href="<?=base_url('edit_org_bill'); ?>?org_id=<?=$_GET['org_id'];?>&bid=<?=$value['bid']."&conid=".$_GET['conid'];?>" class="btn btn-warning ">Edit</a>
                <a href="<?=base_url("display_org_bill.php?action=delete&bid=".$value['bid']."&org_id=".$_GET['org_id']."&conid=".$_GET['conid']); ?>" class="btn btn-danger " onclick="return confirm('Are you sure you want to delete this?')">Delete</a>

              <?php  }?>

              <?php   }
                ?>
            </td>
            <td>
                <?php if($total_due == 0 ){ ?>
                    <a title="Bill Cleared" class="btn btn-success "><i class="fa fa-check "></i> </a>
                    
              <?php   }else { 
                         if(isset($_GET['action']) && $_GET['action'] == 'from_org'){
                            $conid = $obj->select("tbl_bill","*","bid",array($value['bid']));
                            $conid = $conid[0]['con_id'];  ?>
                    <a href="<?=base_url('add_org_payment'); ?>?org_id=<?=$_GET['org_id'];?>&bill_id=<?=$value['bid']."&conid=".$conid;?>" class="btn btn-info "><i class="fa fa-plus "></i> </a>

                        <?php  }else{  ?>
                            
                    <a href="<?=base_url('add_org_payment'); ?>?org_id=<?=$_GET['org_id'];?>&bill_id=<?=$value['bid']."&conid=".$_GET['conid'];?>" class="btn btn-info "><i class="fa fa-plus "></i> </a>

               <?php }} ?>
            </td>
            <td>
                <a href="<?=base_url('view_individual_org_payment'); ?>?org_id=<?=$_GET['org_id'];?>&bid=<?=$value['bid'];?>" class="btn btn-info "><i class="fa fa-eye "></i> </a>
            </td>
          

        </tr>
        <?php endforeach ; ?>
</table>
<?php }else{ ?>
    <p style="color:red">No data found.</p>
<?php } ?>
</div>

<script>


$(document).on('change','#contract',function(){
    var conid = $(this).val();
   var oid = "<?=$_GET['org_id'];?>";
    var data1 = {conid:conid,oid:oid}
    $.ajax({
        type:'post',
        url:'ajax/ajax.php?action=get_sigle_org_bill',
        data:data1,
        success:function(response1){
            // alert(response1);
            $('.bill_table').html(response1);
        }
    })
});

</script>
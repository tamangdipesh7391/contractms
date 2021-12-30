<?php
if(isset($_GET['action']) && $_GET['action'] == 'individual'){
    $commission_data = $obj->select('tbl_commission','*','com_id',array($_GET['com_id']));

}else{
    $commission_data = $obj->select('tbl_commission');

}
if(isset($_POST['submit']) && $_POST['submit'] == 'search'){
    unset($_POST['submit']);
    $from = $_POST['from_date'];
    if($_POST['to_date'] == ''){
        $_POST['to_date'] = date('Y-m-d');
    }
    $to = $_POST['to_date'];

    $sql = "SELECT * FROM tbl_commission WHERE com_received_date BETWEEN '$from' AND '$to'";
   if(isset($_POST['contract'])){
       $sql.= " AND conid = ".$_POST['contract'];
   }
    $commission_data = $obj->ArrQuery($sql);
    
}
if(isset($_GET['action']) && $_GET['action'] == 'individual_con'){
    $commission_data = $obj->select("tbl_commission","*","conid",array($_GET['conid']));
}
$org = $obj->select('tbl_organization');
$contracts = $obj->select('tbl_contract');
$minDate = $obj->select('tbl_commission','MIN(com_received_date) as min_date');
if(isset($_POST['submit']) && $_POST['submit'] == 'add_commission'){
     unset($_POST['submit']);
     $obj->insert("tbl_commission_checkout",$_POST);
     echo "<script> window.location.href='".base_url('view_commission_details')."'</script>";

}
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
   <div class="col-md-12"> <h1>Display All Commissions</h1> </div>

<div class="col-md-12">
                <form method="post" class="form-group">
                    <div class="row">
                        <?php if(!isset($_GET['action']) ){ ?>
                            <div class="col-md-5 mb-4 ">
                            <label for="Contract">Select Organization</label>
                            <select name=""  class="form-control org">
                            <option selected disabled>Select Organization</option>
                            <?php foreach($org as $org): ?>
                            <option value="<?=$org['oid'];?>"><?=$org['org_name'];?></option>
                            <?php endforeach; ?>
                            </select>
                                
                            </div>
                            <div class="col-md-5 mb-4 contracts"></div>
                        <?php } ?>
                

                    </div>
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
                </form>
         </div>
 <div class="bill_table col-md-12">
    <?php if($commission_data){ ?>

     

<table class="table table-bordered ">
    <tr>
        <th>SN</th>
        <th> Contract</th>
        <th>Amount(Rs)</th>
        <th>Issued Date </th>
        <th colspan="2">Commission</th>
        <!-- <th>Action </th>
        <th colspan="2">Payment</th> -->

    </tr>
    <?php foreach($commission_data as $key=>$value) : ?>
        <tr>
            <td><?=++$key;?></td>
            <td><?php 
            $contract_resultset = $obj->select("tbl_contract","*","conid",array($value['conid']));
            $org_id = $contract_resultset[0]['org_id'];
            $org_data = $obj->select("tbl_organization","*","oid",array($org_id));
            echo $contract_resultset[0]['con_title'];
            echo "<br>"; ?>
            <span style="color:purple"><b>
            <?php echo "(".$org_data[0]['org_name'].")"; ?>
            </b></span>
            <?php ?></td>
            <td><?=$value['com_amount'];?></td>
            <td><?=$value['com_received_date'];?></td>
            <td><a class="btn btn-primary" data-toggle="modal" data-target=".exampleModalCenter<?=$key;?>"><i class="fa fa-plus"></i></a></td>
            <td><a href="<?=base_url('view_checkout_commission.php?id='.$value['com_id'].'&conid='.$value['conid']);?>" class="btn btn-primary"><i class="fa fa-eye"></i></a></td>
               <!-- Button trigger modal -->


<!-- add commission Modal -->
<div class="modal fade exampleModalCenter<?=$key;?>" id="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLongTitle"><b>Add Commission</b></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       <div class="modal-body">
     <form action="" method="post">
    
         <div class="formm-group">
              <?php 
              $total_com_amt = $total_paid = 0;
              $paid_amount_res = $obj->ArrQuery("SELECT SUM(checkout_amount) as total_checkout FROM tbl_commission_checkout WHERE com_id=".$value['com_id']);
                
                if($paid_amount_res)
                {
                    $total_paid = $paid_amount_res[0]['total_checkout'];

                }       
                       
               $total_com_amt_res = $obj->select("tbl_commission","*","com_id",array($value['com_id'])); 
             
               $total_com_amt = $total_com_amt_res[0]['com_amount'];
              ?>
          <input type="hidden" name="com_id" value="<?=$value['com_id'];?>">
         <label for=""></label><span style="color:green;"><b>( <?php 
         if($total_paid != '' && $total_paid > 0){

              if($total_paid <= $total_com_amt){
                  $rem =  $total_com_amt - $total_paid;
                  if($rem == 0)
                  echo "Cleared!";
                  else
                  echo "Rs. ".$rem." Remaining";
              }
         }elseif($total_paid == ''){
            $rem = $total_com_amt;
          echo "Rs. ".$rem." Remaining";
         }
         
         
         ?>)</b></span>
         <?php if(isset($rem)){
             if($rem == 0){ ?>

             <?php }else{ ?>
                <input type="number" name="checkout_amount" step="any" max="<?=$total_com_amt;?>" class="form-control" required><br>
                <button class="btn btn-success" type="submit" name="submit" value="add_commission">Add</button>

            <?php  }
         } ?>
         </div>
     
      
     </form>
 </div>
 <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>
           
          

        </tr>
        <?php ++$key; endforeach ; ?>
</table>
<?php }else{ ?>
    <p style="color:red">No data found.</p>
<?php } ?>
</div>

<script>
$(document).ready(function(){

// get contract 
$('.org').on('change',function(){
    var oid = $(this).val();
    var data = {oid:oid}
   
    $.ajax({
        type:'post',
        url:'ajax/ajax.php?action=get_contract_from_display_bill',
        data:data,
        success:function(response){
            // alert(response);
            $('.contracts').html(response);
        }
    })
})
})

$(document).on('change','#contract',function(){
    var conid = $(this).val();
   
    var data1 = {conid:conid}
    $.ajax({
        type:'post',
        url:'ajax/ajax.php?action=get_commission_from_display_bill',
        data:data1,
        success:function(response1){
            // alert(response1);
            $('.bill_table').html(response1);
        }
    })
});

</script>
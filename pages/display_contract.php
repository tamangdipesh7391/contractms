<?php
if(isset($_GET['action'])){
    if($_GET['action'] =='active'){
        $status['status'] = 1;
        $obj->Update("tbl_contract",$status,"conid",array($_GET['id']));
// echo "<script> window.location.href='".base_url('display_contract')."'</script>";


    }
    if($_GET['action'] =='inactive'){
        $status['status'] = 0;
         $obj->Update("tbl_contract",$status,"conid",array($_GET['id']));
// echo "<script> window.location.href='".base_url('display_contract')."'</script>";


    }

}
$org_data = $obj->select('tbl_contract');
$org = $obj->select('tbl_organization',"*","status",array(1));
$this_date = date('Y-m-d');


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
<div class="col-md-12">   
<h1>Display All Contracts</h1>

</div> 
<div class="col-md-4">
<label for="">Select Organization</label>
    <select name="org_id" id="get_org_contract" class="form-control mb-2">
        <option selected disabled>Select Organization</option>
        <?php  foreach($org as $value):?>
            <option value="<?=$value['oid'];?>"><?=$value['org_name'];?></option>
            <?php endforeach ; ?>

    </select>
</div>

<div class="col-md-12" id="contract_table">    

<?php 
if($org_data){ ?>
<table class="table table-bordered">
    <tr>
        <th>SN</th>
        <th> Contract Name</th>
        <th>Organization  </th>
        <th>Start Date </th>
        <th>End Date </th>
        <th>Amount (Rs) </th>
        <th>Commission (Rs) </th>

        <th>Method</th>
        <th>Contract</th>
        <th>Status</th>
        <th>Action </th>
        <th colspan="3">Bill </th>


    </tr>
    <?php foreach($org_data as $key=>$value) :  if($this_date < $value['end_date'] && $value['status'] == 1){ ?>
        <tr>
            <td><?=++$key;?></td>
            <td><?=$value['con_title'];?>
            <p class="text-bold text-danger">
            <?php  $today_date =strtotime( date('Y-m-d'));
        if($today_date < strtotime($value['end_date'])){
          $check_date = (strtotime($value['end_date'])-$today_date );

            $sum = round(($check_date / 86400));
            if($sum == 0 || $sum == 1 ){
              echo "(". $sum." Day left )";
            }elseif($sum >1){
              echo "(". $sum." Days left )";
            }
        }

          elseif($today_date > strtotime($value['end_date']))
         echo "Expired!";
          
         
         ?>
            </p>
        </td>
            <td>
            <?php $org = $obj->select('tbl_organization','*','oid',array($value['org_id']));
            echo $org[0]['org_name'];
            ?>
            </td>
            <td><?=$value['start_date'];?></td>
            <td><?=$value['end_date'];?></td>
            <td><?=$value['con_amount'];?></td>
            <td><?php if(!empty($value['commission_amt'])) echo $value['commission_amt']; else echo "N/A";?></td>

            <td><?=$value['payment_mode'];?></td>
            <td><?php if($value['file'] !=''){ 
                
                $img = explode(',',$value['file']);
                if(sizeof($img)<2){ ?>
                    <a href="assets/contract/<?=$img[0];?>"><i class="fa fa-file-image fa-2x"></i></a>
               <?php  }else{ 
                   
                   for($l=0;$l<sizeof($img);$l++) { ?>
                       <a href="assets/contract/<?=$img[$l];?>"><i class="fa fa-file-image fa-2x"></i> <?=$l;?></a>
                     

                <?php } }        }else{echo "N/A";} ?></td>

            <td>
            <?php 
                if($value['status'] == 1){ ?>
                    <a href="<?=base_url('display_contract'); ?>?action=inactive&id=<?=$value['conid'];?>"><i class="fa fa-check-circle fa-2x" style="color:green"></i></a>
              <?php  }elseif($value['status'] == 0){ ?>
                <a href="<?=base_url('display_contract'); ?>?action=active&id=<?=$value['conid'];?>"><i class="fa fa-times-circle fa-2x" style="color:red"></i></a>
               <?php }
            ?>
            </td>
            <td>
                <?php $orgname = $obj->select('tbl_contract','*','conid',array($value['conid']));
                $org_from_con = $orgname[0]['org_id'];
  ?>
                <a href="<?=base_url('edit_contract'); ?>?id=<?=$value['conid']."&org_id=".$org_from_con;?>" class="btn btn-warning ">Edit</a>
            </td>
            <td>
                <?php
                $total_con_amt = $obj->select("tbl_contract","*","conid",array($value['conid']));
                $total_con_amt = $total_con_amt[0]['con_amount'];
                $total_billed_amt = $obj->ArrQuery("SELECT SUM(bill_amount) as total_billed FROM tbl_bill WHERE con_id=".$value['conid']);
                $total_billed_amt = $total_billed_amt[0]['total_billed'];
                if($total_con_amt == $total_billed_amt){
                ?>
                      <a class="btn  btn-success"><i class="fa fa-check"></i></a>
                      <?php }else{ ?>
                      <a href="<?=base_url('add_contract_bill'); ?>?org_id=<?=$org_from_con;?>&conid=<?=$value['conid'];?>" class="btn btn-primary "><i class="fa fa-plus"></i></a>
<?php } ?>

                      </td>
            <td>
                <a href="<?=base_url('display_org_bill'); ?>?conid=<?=$value['conid']."&org_id=".$org_from_con;?>" class="btn btn-primary "><i class="fa fa-eye"></i></a>
            </td>
            <td>
                <a href="<?=base_url('view_commission_details.php?action=individual_con&conid='.$value['conid']);?>" class="btn btn-info "><i class="fa fa-eye"></i></a>
            </td>
            

        </tr>
        <?php } endforeach ; ?>
</table>
<?php }else{ ?>
    <p style="color:red">No contracts found.</p>
<?php } ?>
</div>


<script>
    $(document).on('change','#get_org_contract',function(){
        let oid = $(this).val();
        let orgdata = {oid:oid}
        $.ajax({
            type : 'post',
            data : orgdata,
            url : 'ajax/ajax.php?action=get_org_contract',
            success : function(response){
                $('#contract_table').html(response);
            }
        })
    })
 

</script>

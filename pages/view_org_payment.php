<?php
if(isset($_GET['org_id'])){
    $contracts = $obj->select('tbl_contract','*','org_id',array($_GET['org_id'])," AND status = 1");
}

$bill_data = $obj->select('tbl_payment');

if(isset($_GET['action']) && $_GET['action'] == 'delete'){
    $obj->delete("tbl_payment","pid",array($_GET['pid']));
    echo "<script>window.location.href='".base_url('view_org_payment.php?org_id='.$_GET['org_id'])."'</script>";
}

?>
<div class="col-md-12">
<div class="row">
    <div class="col-md-5">
        <label for="Contract">Select Contract</label>
        <select  id="contract" class="form-control">
        <option selected disabled>Select contract</option>
        <?php foreach($contracts as $contract): ?>
        <option value="<?=$contract['conid'];?>"><?=$contract['con_title'];?></option>
        <?php endforeach; ?>
        </select>

    </div>
    <div id="bills" class="col-md-5">
</div>
</div>
</div>
<hr>
<h1>Bill Table</h1>
<hr>
<div class="col-md-12 bill_table ">
<?php if($bill_data){ ?>
<table class="table table-bordered">
    <tr>
        <th>SN</th>
        <th>Receipt No </th>
        <th>Billed Amount (Rs)</th>
        <th>Billed Date </th>
        <th>VAT</th>
        <th>TDS</th>
        <th>Received Amount (Rs)</th>

        <th>Check No</th>
        <th>Receipt Image</th>
        <th colspan="2">Action</th>
      

    </tr>
    <?php foreach($bill_data as $key=>$value) : ?>
        <tr>
            <td><?=++$key;?></td>
            <td><?=$value['receipt_no'];?></td>
            <td><?=$value['pay_amount'];?></td>
            <td><?=$value['pay_date'];?></td>
            <td><?=$value['vat_amount'];?></td>
            <td><?php 
            if(isset($value['pay_amount']) || isset($value['with_tds'])){
            if($value['pay_amount'] == ''){
                $value['pay_amount'] = 0;
            }
                if($value['with_tds'] == ''){
                    $value['with_tds'] = 0;
                }
                if($value['pay_amount'] > $value['with_tds']){
                    $tds = $value['pay_amount'] - $value['with_tds'];
                    echo $tds;
                }
            
            }
            ?></td>
            <td><?php
            if(isset($_value['with_tds']) || isset($value['vat_amount'])){

                if($value['with_tds'] == ''){
                    $value['with_tds'] = 0;
                }
                    if($value['vat_amount'] == ''){
                        $value['vat_amount'] = 0;
                    }                
                    $total = $value['with_tds'] + $value['vat_amount'];
                    echo $total;
                }
            
            ?></td>

           <td><?=$value['check_no'];?></td>
            <td><?php if($value['file'] !=''){ 
                
                $img = explode(',',$value['file']);
                if(sizeof($img)<2){ ?>
                    <a href="assets/payment/<?=$img[0];?>"><i class="fa fa-file-image fa-2x"></i></a>
               <?php  }else{ 
                   
                   for($l=0;$l<sizeof($img);$l++) { ?>
                       <a href="assets/payment/<?=$img[$l];?>"><i class="fa fa-file-image fa-2x"></i> <?=$l;?></a>
                     

                <?php } }        } else{
                 echo "N/A";   
                }?>

            </td>
            <td>
                <a href="<?=base_url('edit_payment'); ?>?pid=<?=$value['pid']."&org_id=".$_GET['org_id']."&bill_id=".$value['bill_id'];?>" class="btn btn-warning ">Edit</a>
            </td>
            <td>
                <a href="<?=base_url('view_org_payment'); ?>?action=delete&pid=<?=$value['pid']."&org_id=".$_GET['org_id'];?>" onclick="return confirm('Are you sure you want to delete this?')" class="btn btn-danger ">Delete</a>
            </td>
           
          

        </tr>
        <?php endforeach ; ?>
</table>
<?php }else{ ?>

   <div class="col-md-12"> <p style="color:red">No data found.</p></div>
<?php } ?>
</div>
<script>
$(document).ready(function(){
    $('#contract').on('change',function(){
    var conid = $(this).val();
    var oid = '<?=$_GET['org_id'];?>';
   
    var data = {conid:conid,oid:oid}
    $.ajax({
        type:'post',
        url:'ajax/ajax.php?action=get_bills',
        data:data,
        success:function(response){
            // alert(response);
            $('#bills').html(response);
        }
    })
})
})
$(document).on('change','#bill_data',function(){


var bill_id = $(this).val();
    // alert(bill_id);
    var oid = '<?=$_GET['org_id'];?>';
    var data1 = {bill_id:bill_id,oid:oid}
    $.ajax({
        type:'post',
        url:'ajax/ajax.php?action=get_payment_list',
        data:data1,
        success:function(response){
            // alert(response);
            $('.bill_table').html(response);
        }
    })

   
})
</script>
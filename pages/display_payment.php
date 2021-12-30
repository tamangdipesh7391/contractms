<?php
if(isset($_GET['action']) && $_GET['action'] == 'delete'){
    $obj->delete("tbl_payment","pid",array($_GET['pid']));
    echo "<script>window.location.href='".base_url('view_org_payment.php')."'</script>";
}
$org = $obj->select('tbl_organization','*','status',array(1));
$contracts = $obj->select('tbl_contract');
$minDate = $obj->select('tbl_payment','MIN(pay_date) as min_date');
$bill_data = $obj->select('tbl_payment');

if(isset($_POST['submit']) && $_POST['submit'] == 'search'){
    unset($_POST['submit']);
    $from = $_POST['from_date'];
    if($_POST['to_date'] == ''){
        $_POST['to_date'] = date('Y-m-d');
    }
    $to = $_POST['to_date'];

    $sql = "SELECT * FROM tbl_payment WHERE pay_date BETWEEN '$from' AND '$to'";
   if(isset($_POST['bill']) && $_POST['bill'] != ''){
       $sql.= " AND bill_id = ".$_POST['bill'];
   }
    $bill_data = $obj->ArrQuery($sql);
    
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
<div class="col-md-12">
    <h1>Display Payment</h1>
</div>
<div class="col-md-12">
<form method="post" class="form-group">
                    <div class="row">
                            <div class="col-md-4 mb-4 ">
                            <label for="Contract">Select Organization</label>
                            <select name=""  class="form-control " id="org_in_payment">
                            <option selected disabled>Select Organization</option>
                            <?php foreach($org as $org): ?>
                            <option value="<?=$org['oid'];?>"><?=$org['org_name'];?></option>
                            <?php endforeach; ?>
                            </select>
                                
                            </div>
                            <div class="col-md-4 mb-4 " id="contracts_in_payment"></div>
                            <div class="col-md-4 mb-4 " id="bills_in_payment"></div>

                            
                

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
<div class="col-md-12" id="payment_list_table">
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
        <th>Receipt Image</th>
        <th colspan="2">Action </th>
      

    </tr>
    <?php foreach($bill_data as $key=>$value) : ?>
        <tr>
            <td><?=++$key;?></td>
            <td><?=$value['receipt_no'];?></td>
            <td><?=$value['pay_amount'];?></td>
            <td><?=$value['pay_date'];?></td>
            <td><?=$value['vat_amount'];?></td>
            <td><?php 
            if(isset($_value['pay_amount']) || isset($value['with_tds'])){
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
            if(isset($value['with_tds']) || isset($value['vat_amount'])){

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
           
           <?php 
           $conid = $obj->select("tbl_bill","*","bid",array($value['bill_id']));
           $conid = $conid[0]['con_id'];
           $org = $obj->select("tbl_contract","*","conid",array($conid));
           $org_id = $org[0]['org_id'];
           ?>
            <td>
                <a href="<?=base_url('edit_payment'); ?>?pid=<?=$value['pid']."&bill_id=".$value['bill_id']."&org_id=".$org_id;?>" class="btn btn-warning ">Edit</a>
            </td>
            <td>
                <a href="<?=base_url('display_payment'); ?>?action=delete&pid=<?=$value['pid'];?>" onclick="return confirm('Are you sure you want to delete this?')" class="btn btn-danger ">Delete</a>
            </td>
           
          

        </tr>
        <?php endforeach ; ?>
</table>
<?php }else{ ?>

   <div class="col-md-12"> <p style="color:red">No data found.</p></div>
<?php } ?>
</div>
 

<script>
    $(document).on('change','#org_in_payment',function(){
        let oid = $(this).val();
        let data = {oid:oid}
        $.ajax({
            type : 'post',
            data : data,
            url : 'ajax/ajax.php?action=get_contract_in_paymentlist',
            success : function(response){
                $('#contracts_in_payment').html(response);
            }
        })
    })
    $(document).on('change','#contract_list_in_payment',function(){
      
        let conid = $(this).val();
        let data = {conid:conid}
        $.ajax({
            type : 'post',
            data : data,
            url : 'ajax/ajax.php?action=get_bill_in_paymentlist',
            success : function(response){
                $('#bills_in_payment').html(response);
            }
        })
    })

    $(document).on('change','#bill_list_in_payment',function(){
        let oid = $('#org_in_payment').val();
        let bid = $(this).val();
        let data = {bid:bid,oid:oid}
        $.ajax({
            type : 'post',
            data : data,
            url : 'ajax/ajax.php?action=get_paymentlist_in_display_payment',
            success : function(response){
                $('#payment_list_table').html(response);
            }
        })
    })
    
</script>
<?php

$bill_data = $obj->select('tbl_payment','*','bill_id',array($_GET['bid']));


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
<div class="col-md-12"><h1>Display Payment</h1>
</div>
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
            <th>Action</th>
        

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
                    <a href="<?=base_url('edit_payment'); ?>?bill_id=<?=$_GET['bid'];?>&pid=<?=$value['pid'];?>" class="btn btn-warning ">Edit</a>
                </td>
            
            

            </tr>
            <?php endforeach ; ?>
    </table>
    <?php }else{ ?>

    <div class="col-md-12"> <p style="color:red">No data found.</p></div>
    <?php } ?>
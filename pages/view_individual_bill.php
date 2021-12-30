<?php

$bill_data = $obj->select('tbl_bill','*','con_id',array($_GET['conid']));
$orgname = $obj->select('tbl_contract','*','conid',array($_GET['conid']));
$orgname = $obj->select('tbl_organization','*','oid',array($orgname[0]['org_id']));


?>
<div class="col-md-12"><h1>Payments of <?=$orgname[0]['org_name'];?></h1></div>
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
    <?php if($bill_data){ ?>
<table class="table table-bordered">
    <tr>
        <th>SN</th>
        <th> Bill Title</th>
        <th>Bill No </th>
        <th>Amount(Rs)</th>
        <th>Due Amount(Rs)</th>
        <th>Billed Date </th>
        <th>Action </th>
        <th colspan="2">Payment</th>

    </tr>
    <?php foreach($bill_data as $key=>$value) : ?>
        <tr>
            <td><?=++$key;?></td>
            <td><?=$value['bill_title'];?></td>
            <td><?=$value['bill_no'];?></td>
            <td><?=$value['bill_amount'];?></td>
            <td><?=$value['due_amount'];?></td>

            <td><?=$value['bill_date'];?></td>
           
            <td>
                <a href="<?=base_url('edit_bill'); ?>?conid=<?=$_GET['conid'];?>&org_id=<?=$orgname[0]['oid'];?>&bid=<?=$value['bid'];?>" class="btn btn-warning ">Edit</a>
            </td>
            <td>
                <a class="btn btn-info" href="<?=base_url('add_individual_bill_payment');?>?org_id=<?=$orgname[0]['oid'];?>&bill_id=<?=$value['bid'];?>"><i class="fa fa-plus"></i> </a>
            </td>
            <td>
                <a class="btn btn-info" href="<?=base_url('view_individual_bill_payment');?>?org_id=<?=$orgname[0]['oid'];?>&bill_id=<?=$value['bid'];?>"><i class="fa fa-eye"></i> </a>
            </td>

          

        </tr>
        <?php endforeach ; ?>
</table>
<?php }else{ ?>
    <p style="color:red">No data found.</p>
<?php } ?>
<?php
if(isset($_GET['action'])){
    if($_GET['action'] =='active'){
        $status['status'] = 1;
        $obj->Update("tbl_organization",$status,"oid",array($_GET['id']));
// echo "<script> window.location.href='".base_url('display_contract')."'</script>";


    }
    if($_GET['action'] =='inactive'){
        $status['status'] = 0;
         $obj->Update("tbl_organization",$status,"oid",array($_GET['id']));
// echo "<script> window.location.href='".base_url('display_contract')."'</script>";


    }

}
$org_data = $obj->select('tbl_organization ORDER BY oid DESC');


?>
<div class="col-md-12">
    <h1>Our Organizations</h1>
    <input type="text" id="search_org" class="form-control m-2" placeholder="Search Organization Here">
</div>    
<div class="col-md-12">
<?php if($org_data){ ?>
<table class="table table-bordered">
    <tr>
        <th>SN</th>
        <th> Name</th>
        <!-- <th>PAN No </th>
        <th>VAT No </th>
        <th>Email </th>
        <th>Phone </th> -->
        <th>Status</th>
        <th>Action</th>
        <th colspan="2">Contract </th>
        <th colspan="2">Bill </th>
        <th colspan="2">Payment </th>


    </tr>
    <?php foreach($org_data as $key=>$value) : ?>
        <tr>
            <td><?=++$key;?></td>
            <td><?=$value['org_name'];?><br>(PAN:<?=$value['pan_no'];?>)</td>
            <!-- <td><?=$value['pan_no'];?></td>
            <td><?=$value['vat_no'];?></td>
            <td><?=$value['org_email'];?></td>
            <td><?=$value['org_phone'];?></td> -->
            <td>
            <?php 
                if($value['status'] == 1){ ?>
                    <a href="<?=base_url('display_org'); ?>?action=inactive&id=<?=$value['oid'];?>"><i class="fa fa-check-circle fa-2x" style="color:green"></i></a>
              <?php  }elseif($value['status'] == 0){ ?>
                <a href="<?=base_url('display_org'); ?>?action=active&id=<?=$value['oid'];?>"><i class="fa fa-times-circle fa-2x" style="color:red"></i></a>
               <?php }
            ?>
            </td>
            <td>
                <a href="<?=base_url('edit_org'); ?>?id=<?=$value['oid'];?>" class="btn btn-warning ">Edit</a>
            </td>
            <!-- contract  -->
            <td>
                <a href="<?=base_url('add_contract'); ?>?id=<?=$value['oid'];?>" class="btn btn-info "><i class="fa fa-plus "></i> </a>
            </td>
            <td>
                <a href="<?=base_url('view_org_contract'); ?>&org_id=<?=$value['oid'];?>" class="btn btn-info "><i class="fa fa-eye "></i> </a>
            </td>
            <!-- bill  -->
            <td>
                <a href="<?=base_url('add_org_bill'); ?>?org_id=<?=$value['oid'];?>" class="btn btn-info "><i class="fa fa-plus "></i> </a>
            </td>
            <td>
                <a href="<?=base_url('display_org_bill'); ?>?org_id=<?=$value['oid']."&action=from_org";?>" class="btn btn-info "><i class="fa fa-eye "></i> </a>
            </td>
            <!-- payment  -->
            <td>
        
                <a href="<?=base_url('add_org_payment'); ?>?org_id=<?=$value['oid']."&action=from_org";?>" class="btn btn-info "><i class="fa fa-plus "></i> </a>
            </td>
            <td>
                <a href="<?=base_url('view_org_payment'); ?>?org_id=<?=$value['oid'];?>" class="btn btn-info "><i class="fa fa-eye "></i> </a>
            </td>

        </tr>
        <?php endforeach ; ?>
</table>
<?php }else{ ?>
    <p style="color:red">No data found.</p>
<?php } ?>
</div>

<script>
    $("#search_org").keyup(function () {
    var value = this.value.toLowerCase().trim();

    $("table tr").each(function (index) {
        if (!index) return;
        $(this).find("td").each(function () {
            var id = $(this).text().toLowerCase().trim();
            var not_found = (id.indexOf(value) == -1);
            $(this).closest('tr').toggle(!not_found);
            return not_found;
        });
    });
});
</script>
<?php 
if(isset($_POST['submit']) && $_POST['submit'] == 'org_edit_data'){
  array_pop($_POST);
  $obj->Update("tbl_organization",$_POST,'oid',array($_GET['id']));
  $_SESSION['success'] = " Record updated successfully.";
  echo "<script> window.location.href='".base_url('display_org')."'</script>";



}
$org_data = $obj->select('tbl_organization','*','oid',array($_GET['id']));
?>



<div class="col-md-6">

<form method="post" class="form-group">
    <h1>Edit Organization</h1>
    <label for="">Organization Name</label>
    <input type="text" name="org_name" required class="form-control" value="<?=$org_data[0]['org_name'];?>">
    <label for="">PAN No</label>
    <input type="number" required class="form-control" name="pan_no"  value="<?=$org_data[0]['pan_no'];?>">
    <label for="">VAT No</label>
    <input type="number" required class="form-control" name="vat_no"  value="<?=$org_data[0]['vat_no'];?>">
    <label for="">Organization Email</label>
    <input type="email" required class="form-control" name="org_email"  value="<?=$org_data[0]['org_email'];?>">
    <label for="">Organization Phone</label>
    <input type="number" required class="form-control" name="org_phone"  value="<?=$org_data[0]['org_phone'];?>">
    <button class="btn btn-info mt-3" type="submit " name="submit" value="org_edit_data"> Update</button>
</form>
</div>
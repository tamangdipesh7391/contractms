<?php 
if(isset($_POST['submit']) && $_POST['submit'] == 'org_data'){
  array_pop($_POST);
  $obj->Insert("tbl_organization",$_POST);
  $_SESSION['success'] = " Organization Added successfully.";
  echo "<script> window.location.href='".base_url('add_org')."'</script>";

}




?>



<div class="col-md-6">
<?php if (isset($_SESSION['success'])) { ?>
    <div class="alert alert-success">
        <?php
         echo $_SESSION['success'];
        unset($_SESSION['success']);
          ?>
    </div>

    <?php } ; ?>

    <?php if (isset($_SESSION['error'])) { ?>
    <div class="alert alert-danger">
        <?php echo $_SESSION['error'];unset($_SESSION['error']);  ?>
    </div>
    <?php }  ?>
<form method="post" class="form-group">
    <h1>Add New Organization</h1>
    <label for="">Organization Name</label>
    <input type="text" name="org_name" required class="form-control">
    <label for="">PAN No</label>
    <input type="number" required class="form-control" name="pan_no">
    <label for="">VAT No</label>
    <input type="number" required class="form-control" name="vat_no">
    <label for="">Organization Email</label>
    <input type="email" required class="form-control" name="org_email">
    <label for="">Organization Phone</label>
    <input type="text" required class="form-control" name="org_phone">
    <button class="btn btn-info mt-3" type="submit " name="submit" value="org_data"> Add</button>
</form>
</div>
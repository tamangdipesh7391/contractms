<?php 
if(isset($_POST['submit']) && $_POST['submit'] == 'bill_data'){
  array_pop($_POST);
  $obj->Insert("tbl_bill",$_POST);
  $_SESSION['success']  ="Bill has been created successfully.";
echo "<script> window.location.href='".base_url('add_bill')."'</script>";


}
$contracts = $obj->select('tbl_contract');
?>



<div class="col-md-6">
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
<form method="post" class="form-group">
    <h1>Create Bill</h1>
    <label for="">Contract</label>
    <select name="con_id" id="" class="form-control">
        <option selected disabled> Select Contract</option>
        <?php  foreach($contracts as $value):?>
            <option value="<?=$value['conid'];?>"><?=$value['con_title'];?></option>
            <?php endforeach ; ?>
    </select>
    <label for="">Bill Title</label>
    <input type="text" name="bill_title" class="form-control">
    <label for="">Bill No</label>
    <input type="text" name="bill_no" class="form-control">
    <label for="">Bill Amount</label>
    <input type="number" class="form-control" name="bill_amount">
    
    <label for="">Billed Date</label>
    <input type="date" name="bill_date" class="form-control" value="<?=date('Y-m-d');?>">

    <button class="btn btn-info mt-3" type="submit " name="submit" value="bill_data"> Add</button>
</form>
</div>
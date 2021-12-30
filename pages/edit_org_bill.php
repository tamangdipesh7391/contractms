<?php 
$total_receivable = $paid = $due = 0;
$total_receivable = $obj->select("tbl_contract","*","conid",array($_GET['conid']));
$total_receivable = $total_receivable[0]['con_amount'];
$paid = $obj->ArrQuery(" SELECT SUM(bill_amount) as paid FROM tbl_bill WHERE con_id=".$_GET['conid']);
$paid = $paid[0]['paid'];
$due = $total_receivable - $paid;
if(isset($_POST['submit']) && $_POST['submit'] == 'edit_bill_data'){
//   array_pop($_POST);
//   $billable_amount = $obj->select('tbl_bill',' SUM(bill_amount) as billable_amount','con_id',array($_POST['con_id']));
//   $con_amount = $obj->select('tbl_contract','*','conid',array($_POST['con_id']));
//   $billable = $con_amount[0]['con_amount'] - $billable_amount[0]['billable_amount'];
//   if($billable == 0){
//       $b_amt = $obj->select('tbl_bill','*','bid',array($_GET['bid']));
//       if($_POST['bill_amount']<$b_amt[0]['bill_amount']){
//         $data['visibility'] = 0;
//         $obj->update('tbl_contract',$data,'conid',array($_POST['con_id']));
//       }
     
//   }
//   $veryify_amt = $obj->select('tbl_bill','*','bid',array($_GET['bid']));
//   $pre_total = $veryify_amt[0]['bill_amount'];
//   $pre_due = $veryify_amt[0]['due_amount'];
//   $_POST['due_amount'] = $_POST['bill_amount'];
//   if($_POST['bill_amount'] != $pre_total){
//     if($pre_total == $pre_due){
//         $_POST['due_amount'] = $_POST['bill_amount'];
//       }else{
//           $diff = $pre_total-$pre_due;
//           $_POST['due_amount'] = $_POST['bill_amount'] - $diff;
//       }
//   }
 unset($_POST['submit']);
  $obj->Update("tbl_bill",$_POST,'bid',array($_GET['bid']));
  $_SESSION['success']  ="Bill has been Updated successfully.";
echo "<script> window.location.href='".base_url('display_org_bill')."?org_id=".$_GET['org_id']."&conid=".$_GET['conid']."'</script>";


}
$edit_bill = $obj->select('tbl_bill','*','bid',array($_GET['bid']));
$contracts = $obj->select('tbl_contract','*','org_id',array($_GET['org_id']));
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
    <h1>Edit Bill</h1>
    <label for="">Contract</label>
    <select name="con_id" id="" class="form-control">
        <option selected disabled> Select Contract</option>
        <?php  foreach($contracts as $value):?>
            <option value="<?=$value['conid'];?>" <?php if($value['conid'] == $edit_bill[0]['con_id']) echo "selected"; ?> ><?=$value['con_title'];?></option>
            <?php endforeach ; ?>
    </select>
    <label for="">Bill Title</label>
    <input type="text" name="bill_title" required class="form-control" value="<?=$edit_bill[0]['bill_title'];?>">
    <label for="">Bill No</label>
    <input type="text" name="bill_no" required class="form-control"  value="<?=$edit_bill[0]['bill_no'];?>">
    <label for="">Bill Amount</label> <span style="color:green">(Max billable amount : <?=$due+$edit_bill[0]['bill_amount'];?>)</span>
    <input type="number" required class="form-control total_bill" name="bill_amount" step = "any" max="<?=$due+$edit_bill[0]['bill_amount'];?>"  value="<?=$edit_bill[0]['bill_amount'];?>">
    <label for="">Discount</label>
    <input type="number" step="any"  class="form-control discount_amount" value="<?php echo $edit_bill[0]['bill_amount'] - $edit_bill[0]['taxable_amount'] ;?>" >
    <label for="">Taxable Amount</label>
    <input type="number" step="any" name="taxable_amount" class="form-control taxable_amount" value="<?=$edit_bill[0]['taxable_amount'];?>" >
    
        <!-- <div class="col-md-12"> -->
           <div class="row">
           <div class="col-md-6">
           <label for="">VAT %</label>
                <input type="number" step="any"  class="form-control vat_percentage" value="<?php echo ($edit_bill[0]['vat_amount']/$edit_bill[0]['taxable_amount'])*100 ?>" >

            </div>
            <div class="col-md-6">
            <label for="">VAT Amount</label>
                <input type="number" step="any" name="vat_amount" class="form-control vat_amount"  value="<?=$edit_bill[0]['vat_amount'];?>" >

            </div>
           </div>
        <!-- </div> -->
        <label for="">Grand Total</label>
    <input type="number" readonly step="any"  class="form-control grand_total" value="<?php echo $edit_bill[0]['taxable_amount'] + $edit_bill[0]['vat_amount'] ;?>">    
    <label for="">Billed Date</label>
    <input type="date" name="bill_date" required class="form-control"  value="<?=$edit_bill[0]['bill_date'];?>">

    <button class="btn btn-info mt-3" type="submit " name="submit" value="edit_bill_data"> Update</button>
</form>
</div>
<script>
            $(document).ready(function(){
                $('.discount_amount').on('keyup',function(){
                    $('.taxable_amount').val(Number($('.total_bill').val()) - Number($('.discount_amount').val()));
                    let VatAmount = (Number($('.taxable_amount').val()) * 13)/100;
                    $('.vat_amount').val(VatAmount);
                    $('.grand_total').val(Number($('.taxable_amount').val())+VatAmount)
                })
                $('.vat_percentage').on('keyup',function(){
                    let VatAmount1 = (Number($('.taxable_amount').val()) * Number($('.vat_percentage').val()))/100;
                    $('.vat_amount').val(VatAmount1);
                    $('.grand_total').val(Number($('.taxable_amount').val())+VatAmount1)
                })
            })
        </script>
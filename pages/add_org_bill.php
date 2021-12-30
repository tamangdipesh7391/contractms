<?php 
if(isset($_GET['org_id'])){
    $contracts = $obj->select("tbl_contract","*","org_id",array($_GET['org_id']));
    if(!$contracts){ ?>
       <span style="color:red"><b>There is no contract for this organization yet.</b> </span>  
   <?php die; }
}
if(isset($_POST['submit']) && $_POST['submit'] == 'bill_data'){
    if(!isset($_POST['bill_amount'])){
  echo "<script> window.location.href='".base_url('display_org_bill')."?org_id=".$_GET['org_id']."'</script>";

    }
    unset($_POST['submit']);
    // $_POST['due_amount'] = $_POST['bill_amount'];
    $obj->Insert("tbl_bill",$_POST);
    $_SESSION['success']  ="Bill has been created successfully.";
  echo "<script> window.location.href='".base_url('display_org_bill')."?action=from_org&org_id=".$_GET['org_id']."'</script>";

  


}
$current_date = date('Y-m-d');
$contracts = $obj->select("tbl_contract","*","org_id",array($_GET['org_id'])," AND status = 1 AND end_date > '".$current_date."'");
?>


<div class="col-md-12">
<h1>Create Bill</h1>

</div>
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
    <label for="">Contract</label>
    <select name="con_id" id="contracts" class="form-control">
        <option selected disabled> Select Contract</option>
        <?php  foreach($contracts as $value):?>
            <option value="<?=$value['conid'];?>"><?=$value['con_title'];?></option>
            <?php endforeach ; ?>
    </select>
    <label for="">Bill Title</label>
    <input type="text" name="bill_title" required class="form-control">
    <label for="">Bill No</label>
    <input type="text" name="bill_no" required class="form-control">
    <div id="get_bill_in_create">

    </div>
    <label for="">Discount</label>
    <input type="number" step="any"  class="form-control discount_amount" >
    <label for="">Taxable Amount</label>
    <input type="number" step="any" name="taxable_amount" class="form-control taxable_amount" >
    
        <!-- <div class="col-md-12"> -->
           <div class="row">
           <div class="col-md-6">
           <label for="">VAT %</label>
                <input type="number" step="any"  class="form-control vat_percentage" >

            </div>
            <div class="col-md-6">
            <label for="">VAT Amount</label>
                <input type="number" step="any" name="vat_amount" class="form-control vat_amount" >

            </div>
           </div>
        <!-- </div> -->
        <label for="">Grand Total</label>
    <input type="number" readonly step="any"  class="form-control grand_total" >
    <!-- <label for="">Bill Amount ( <b class="billable" ></b>)</label>
    <input type="number" required class="form-control" name="bill_amount">
     -->
    <label for="">Billed Date</label>
    <input type="date" name="bill_date" required class="form-control" value="<?=date('Y-m-d');?>">

    <button class="btn btn-info mt-3" type="submit " name="submit" value="bill_data"> Add</button>
</form>
</div>
<div class="col-md-2"></div>
<div class="col-md-4">
            <div class="col-md-12">
                
            <label for="">Convert Your Date Here</label>

             <iframe src="https://www.hamropatro.com/widgets/dateconverter.php" frameborder="0" scrolling="no" marginwidth="0" marginheight="0" style="border:none; overflow:hidden; width:400px; height:200px;" allowtransparency="true"></iframe>

            </div>
            <div class="col-md-12">
                <label for="">Current Date Calendar</label>
                <iframe src="https://www.hamropatro.com/widgets/calender-medium.php" frameborder="0" scrolling="no" marginwidth="0" marginheight="0" style="border:none; overflow:hidden; width:295px; height:385px;" allowtransparency="true"></iframe>
            </div>
        </div>
<script>
$(document).on('change','#contracts',function(){
    var conid = $(this).val();
   
    var data1 = {conid:conid}
    $.ajax({
        type:'post',
        url:'ajax/ajax.php?action=get_billable_amount',
        data:data1,
        success:function(response1){
            // alert(response1);
            $('#get_bill_in_create').html(response1);
        }
    })
});

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
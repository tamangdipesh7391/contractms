<?php 

$bill = $obj->select('tbl_bill');
$payment = $obj->select('tbl_payment','*','pid',array($_GET['pid']));
// print_r($payment);
// $total_receivable = $obj->select("") ;
$total_receivable = $obj->select("tbl_bill","*","bid",array($_GET['bill_id']));
$total_receivable = $total_receivable[0]['bill_amount'];
$paid = $obj->ArrQuery("SELECT SUM(pay_amount) as paid FROM tbl_payment WHERE bill_id=".$_GET['bill_id']);
$paid = $paid[0]['paid'];
$due = $total_receivable - $paid;

// edit seciton 
if (isset($_POST['submit']) && $_POST['submit'] == 'edit_payment_data') {

  if ($_FILES['image']['name'][0]=='') {
           $_FILES['image']['name'][0] = $payment[0]['file'];
           $filename = $_FILES['image']['name'];

       // 	$data1 = explode(',', $ma[0]['docs']);
       // 		if (sizeof($data1)>1) {
       // 			for ($j=0; $j <sizeof($data1) ; $j++) { 
       // 				unlink("assets/docs/$data1[$j]");

       // 				}

       // }else{
       //   unlink("assets/docs/$ma[0]['docs']");
       // } 
       

          }
          else{
           $imgName = $_FILES['image']['name'];


           if(sizeof($imgName)<2){
               
               $ext = strtolower(pathinfo($imgName[0],PATHINFO_EXTENSION));
               $filename[] = md5(uniqid(microtime())).".".$ext;
           }else{
               for($k=0; $k < sizeof($imgName); $k++){
        
                   $ext = strtolower(pathinfo($imgName[$k],PATHINFO_EXTENSION));
                   
                   $filename []= md5(uniqid(microtime())).".".$ext;
               }
           }
                   // file part 						 
                       $data1 = explode(',', $payment[0]['file']);
                       if (sizeof($data1)>1) {
                           for ($j=0; $j <sizeof($data1) ; $j++) { 
                               unlink("assets/payment/".$data1[$j]);

                               }

               }else{
                   unlink(base_url("assets/payment/").$payment[0]['file']);
               } 
          }
          
// echo "<pre>";
// print_r($_FILES);
// die;

// imported

if (sizeof($filename)<2) {
$tmp_name = $_FILES['image']['tmp_name'][0];
$imageFileType = strtolower(pathinfo($filename[0],PATHINFO_EXTENSION));


if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" && $imageFileType!=""&&$imageFileType !="txt"&&$imageFileType !="docx"&&$imageFileType !="pdf"&& $imageFileType!="zip"&& $imageFileType!="pptx"&& $imageFileType!="mp4"&& $imageFileType!="m4p"&& $imageFileType!="m4v") {
 echo "Sorry, only JPG, JPEG, PNG , GIF,txt,docx,pdf,zip,mp4,m4p,m4v & pptx files are allowed.";

 
}else{
 
     
     $location='assets/payment'.'/'.$filename[0];
     move_uploaded_file($tmp_name, $location);//upload file
     
   if (sizeof($filename)>1) {

   $a=implode(',',$filename );
           $_POST['file'] = $a;
   }
   else{
       $_POST['file']=$filename[0];
   }
//insert filename in post variable
unset($_POST['submit']);






$last_id =  $obj->Update('tbl_payment',$_POST,'pid',array($_GET['pid']));
// $pay_bill_id = $obj->select("tbl_payment","*","pid",array($last_id));
// $pay_bill_id = $pay_bill_id[0]['bill_id'];
$obj->Delete("tbl_commission","pay_id",array($_GET['pid']));
   //inserting into table commission
   $contract_id = $obj->select("tbl_bill","*","bid",array($_GET['bill_id']));

   $get_conid = $contract_id[0]['con_id'];
   $com['bid'] = $_GET['bill_id'];
   $com['conid'] = $get_conid;
   $com['pay_id'] = $_GET['pid'];
   $total_commission1 = $obj->select("tbl_contract","*","conid",array($get_conid));
   $total_commission = $total_commission1[0]['commission_amt'];
   $total_con_amt = $total_commission1[0]['con_amount'];
   $com_amount = ($total_commission/$total_con_amt)*$_POST['pay_amount'];
   // echo " com amount= ".$com_amount; die;
   $com['com_amount'] = $com_amount;
  
   $received_com_amount = $obj->ArrQuery("SELECT SUM(com_amount) as received_com_amt FROM tbl_commission WHERE bid=".$_POST['bill_id']." AND conid=".$get_conid);
   $received_com_amount = $received_com_amount[0]['received_com_amt'];
  
   if($received_com_amount){
       if(($received_com_amount < $total_commission) && $total_commission >= ($received_com_amount + $com_amount) ){
      

           $obj->insert("tbl_commission",$com);
       }
   }else{
      
       $obj->insert("tbl_commission",$com);
   }
  $_SESSION['success'] = "Payment has been updated successfully.";
  echo "<script> window.location.href='".base_url('view_org_payment')."?org_id=".$_GET['org_id']."'</script>";
}

}


else{
 

for ($j=0; $j <sizeof($filename) ; $j++) { 
   $tmp_name = $_FILES['image']['tmp_name'][$j];
   // echo $tmp_name;
   $imageFileType = strtolower(pathinfo($filename[$j],PATHINFO_EXTENSION));



   if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" && $imageFileType!=""&&$imageFileType !="txt"&&$imageFileType !="docx"&&$imageFileType !="pdf"&& $imageFileType!="zip"&& $imageFileType!="pptx"&& $imageFileType!="mp4"&& $imageFileType!="m4p"&& $imageFileType!="m4v") {
 echo "Sorry, only JPG, JPEG, PNG , GIF,txt,docx,pdf,zip,mp4,m4p,m4v & pptx files are allowed.";

 
}else{
 

     $location='assets/payment'.'/'.$filename[$j];
     move_uploaded_file($tmp_name, $location);//upload file
     
   if (sizeof($filename)>1) {

   $a=implode(',',$filename );
           $_POST['file'] = $a;
   }
   else{
       $_POST['file']=$filename[0];
   }
//insert filename in post variable

 }


}
unset($_POST['submit']);


$obj->Update('tbl_payment',$_POST,'pid',array($_GET['pid']));
  $_SESSION['success'] = "Payment has been updated successfully.";
  echo "<script> window.location.href='".base_url('view_org_payment')."?org_id=".$_GET['org_id']."'</script>";
}



// end imported

}
// end edit section 
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
<form method="post" class="form-group" enctype="multipart/form-data">
<h1>Edit Received Payment</h1>
<input type="hidden" name="bill_id" value="<?=$payment[0]['bill_id'];?>">
<label for="">Receipt No </label>
    <input type="numbaer" name="receipt_no" class="form-control" value="<?=$payment[0]['receipt_no'];?>">

    <label for="">Billed Amount <span style="color:green">(Max billable amount : <?=$due+$payment[0]['pay_amount'];?>)</span></label>
    <input type="number" name="pay_amount" class="form-control" max="<?=$due+$payment[0]['pay_amount'];?>" value="<?=$payment[0]['pay_amount'];?>" >
    <label for="">Received Amount </label>
    <input type="number" step="any" name="with_tds" class="form-control" id="received_amount"  value="<?php
         if(isset($payment[0]['with_tds']) || isset($payment[0]['vat_amount'])){

            if($payment[0]['with_tds'] == ''){
                $payment[0]['with_tds'] = 0;
            }
                if($payment[0]['vat_amount'] == ''){
                    $payment[0]['vat_amount'] = 0;
                }                
                $total = $payment[0]['with_tds'] + $payment[0]['vat_amount'];
                echo $total;
            }
    
    ?>">
     <label for="">TDS Amount</label>
    <input type="number" id="tds_amount" step="any"  class="form-control" value="<?=$payment[0]['vat_amount'];?>">
    <label for="">VAT Amount</label>
    <input type="number" id="vat_amount" step="any" name="vat_amount" class="form-control" value="<?=$payment[0]['vat_amount'];?>">
    <label for="">Total Amount (With VAT)</label>
    <input type="number" id="total_amt_with_vat" step="any"  class="form-control" readonly>
    <label for="">Received Date</label>
    <input type="date" class="form-control" name="pay_date" value="<?=$payment[0]['pay_date'];?>">
    <label for="">Check Image (If any)</label>
    <input type="file" name="image[]" class="form-control" multiple>
    <label for="">Check No</label>
    <input type="number" name="check_no" class="form-control" value="<?=$payment[0]['check_no'];?>">
    
    <button class="btn btn-info mt-3" type="submit " name="submit" value="edit_payment_data"> Update</button>
</form>
</div>

<script>
    $(document).ready(function(){
        let with_tds_amount = "<?=$payment[0]['with_tds'];?>";
        let pay_amount = "<?=$payment[0]['pay_amount'];?>";

        $('#tds_amount').keyup(function(){
            let received_amount = Number(pay_amount) - Number($(this).val());
            $('#received_amount').val(received_amount);
        })
        $('#vat_amount').keyup(function(){
            received_amount = $('#received_amount').val();
            let vat_amount = Number(received_amount) + Number($(this).val());
            $('#total_amt_with_vat').val(vat_amount);
        })
    });
</script>
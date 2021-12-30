<?php 

$bill = $obj->select('tbl_bill');
$payment = $obj->select('tbl_payment','*','pid',array($_GET['pid']));


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






$obj->Update('tbl_payment',$_POST,'pid',array($_GET['pid']));
  $_SESSION['success'] = "Payment has been updated successfully.";
  echo "<script> window.location.href='".base_url('view_individual_org_payment')."?bid=".$_GET['bid']."&org_id=".$_GET['org_id']."'</script>";
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
  echo "<script> window.location.href='".base_url('view_individual_org_payment')."?bid=".$_GET['bid']."&org_id=".$_GET['org_id']."'</script>";
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

    <label for="">Received Amount</label>
    <input type="numbaer" name="pay_amount" class="form-control" value="<?=$payment[0]['pay_amount'];?>" >
    <label for="">Received Date</label>
    <input type="date" class="form-control" name="pay_date" value="<?=$payment[0]['pay_date'];?>">
    <label for="">Check Image (If any)</label>
    <input type="file" name="image[]" class="form-control" multiple>
    <label for="">Check No</label>
    <input type="number" name="check_no" class="form-control" value="<?=$payment[0]['check_no'];?>">
    
    <button class="btn btn-info mt-3" type="submit " name="submit" value="edit_payment_data"> Update</button>
</form>
</div>
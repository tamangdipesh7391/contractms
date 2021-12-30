<?php 
$orgname = $obj->select('tbl_organization','*','oid',array($_GET['org_id']));
$amt = $obj->select('tbl_bill','*','bid',array($_GET['bill_id']));
$total_receivable = $paid = $due = 0;
$total_receivable = $amt[0]['bill_amount'];
$paid = $obj->ArrQuery("SELECT SUM(pay_amount) as paid FROM tbl_payment WHERE bill_id = ".$_GET['bill_id']);
$paid = $paid[0]['paid'];
$due = $total_receivable - $paid;
if(!$amt){
    echo "The bill has been cleared"; die;
}
if (isset($_POST['submit'])) {
    if ($_POST['submit']=='payment_data') {
       if($_FILES['image']['name'][0] != ''){
        $imgName = $_FILES['image']['name'];

        if(sizeof($imgName)<2){
            
            $ext = strtolower(pathinfo($imgName[0],PATHINFO_EXTENSION));
            $filename []= md5(uniqid(microtime())).".".$ext;
        }else{
            for($k=0; $k < sizeof($imgName); $k++){

                $ext = strtolower(pathinfo($imgName[$k],PATHINFO_EXTENSION));
                
                $filename[]= md5(uniqid(microtime())).".".$ext;
            }
        }

    
    
                                    
        // $filename = $_FILES['image']['name'];
        if (sizeof($imgName)<2) {
            $tmp_name = $_FILES['image']['tmp_name'][0];
            $imageFileType = strtolower(pathinfo($filename[0],PATHINFO_EXTENSION));


            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
          && $imageFileType != "gif" && $imageFileType!=""&&$imageFileType !="txt"&&$imageFileType !="docx"&&$imageFileType !="pdf"&& $imageFileType!="zip"&& $imageFileType!="pptx"&& $imageFileType!="mp4"&& $imageFileType!="m4p"&& $imageFileType!="m4v") {
              echo "Sorry, only JPG, JPEG, PNG , GIF,txt,docx,pdf,zip,mp4,m4p,m4v & pptx files are allowed.";

              
          }else{
              

            // $a = " SELECT * FROM tbl_contract WHERE con_title ='".$_POST['con_title']."' AND org_id='".$_POST['org_id']."' AND start_date='".$_POST['start_date']."' AND end_date='".$_POST['end_date']."' AND con_amount='".$_POST['con_amount']."' AND payment_mode='".$_POST['payment_mode']."' AND status='".$_POST['status']."'";

            // $check = $obj->Query($a);

            // if ($check) {
            //     $_SESSION['error'] = "Data already exists";
            // }else{
                                      
              
            
            // }
            $location='assets/payment'.'/'.$filename[0];
            move_uploaded_file($tmp_name, $location);//upload file
            unset($_POST['submit']);//popping submit form post
          if (sizeof($imgName)>1) {

          $a=implode(',',$filename );
                  $_POST['file'] = $a;
          }
          else{
              $_POST['file']=$filename[0];
          }
        //   $bill_data = $obj->select('tbl_bill','*','bid',array($_GET['bill_id']));
        //   if($_POST['pay_amount'] >$bill_data[0]['deu_amount']){
        //         $_SESSION['error'] = "Receivable amount is less than you have entered!";
        //   echo "<script> window.location.href='".base_url('add_individual_bill_payment')."?bill_id=".$_GET['bill_id']."'</script>";

        //   }
        //   $receivable_amt = $bill_data[0]['bill_amount'];
        //   $minus = $_POST['pay_amount'];
        //   $update['bill_amount'] = $receivable_amt -$minus;   
        // $total_amt1 = $obj->select('tbl_bill','*','bid',array($_POST['bill_id']));
        //    $total_amt = $total_amt1[0]['bill_amount'];
        //    $due_amt = $total_amt1[0]['due_amount'];
        //    if($_POST['pay_amount']>$total_amt || $_POST['pay_amount']>$due_amt ){
        //     $_SESSION['error'] = "Entered amount is incorrect.";

        //     echo "<script> window.location.href='".base_url('add_individual_bill_payment')."?org_id=".$_GET['org_id']."&bill_id=".$_GET['bill_id']."'</script>";

        //    }
        //    else{
        //        $pay = $_POST['pay_amount'];
        //     $due_amount = $due_amt- $pay;
        //     $data['due_amount'] = $due_amount;
        //     $obj->update('tbl_bill',$data,'bid',array($_POST['bill_id']));
        //     $obj->Insert("tbl_payment",$_POST);
                 
        //       $obj->update('tbl_bill',$update,'bid',array($_GET['bill_id']));
        //       $_SESSION['success'] = "Payment has been done successfully.";
        //       echo "<script> window.location.href='".base_url('view_individual_bill_payment')."?bill_id=".$_GET['bill_id']."&org_id=".$_GET['org_id']."'</script>";
        //    } 
         
        $obj->Insert("tbl_payment",$_POST);
              echo "<script> window.location.href='".base_url('view_individual_bill_payment')."?bill_id=".$_GET['bill_id']."&org_id=".$_GET['org_id']."'</script>";
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
        //     $a = " SELECT * FROM tbl_contract WHERE con_title ='".$_POST['con_title']."' AND org_id='".$_POST['org_id']."' AND start_date='".$_POST['start_date']."' AND end_date='".$_POST['end_date']."' AND con_amount='".$_POST['con_amount']."' AND payment_mode='".$_POST['payment_mode']."' AND status='".$_POST['status']."'";

        //     $checkImg = $obj->Query($a);
        //   if($checkImg){

        //       }else{
             
        //       }
        $location='assets/payment'.'/'.$filename[$j];
        move_uploaded_file($tmp_name, $location);//upload file
        unset($_POST['submit']);//popping submit form post
      if (sizeof($_FILES['image']['name'])>1) {

      $a=implode(',',$filename );
              $_POST['file'] = $a;
      }
      else{
          $_POST['file']=$filename[0];
      }
            //insert filename in post variable



            }
             
            }
            // $a = " SELECT * FROM tbl_contract WHERE con_title ='".$_POST['con_title']."' AND org_id='".$_POST['org_id']."' AND start_date='".$_POST['start_date']."' AND end_date='".$_POST['end_date']."' AND con_amount='".$_POST['con_amount']."' AND payment_mode='".$_POST['payment_mode']."' AND status='".$_POST['status']."'";

            // $check = $obj->Query($a);

            // if ($check) {
            //     $_SESSION['error'] = "Data already exists";
            // }else{
                


               

            //   }
            // $total_amt1 = $obj->select('tbl_bill','*','bid',array($_POST['bill_id']));
            // $total_amt = $total_amt1[0]['bill_amount'];
            // $due_amt = $total_amt1[0]['due_amount'];
            // if($_POST['pay_amount']>$total_amt || $_POST['pay_amount']>$due_amt ){
            //  $_SESSION['error'] = "Entered amount is incorrect.";
 
            //  echo "<script> window.location.href='".base_url('add_individual_bill_payment')."?org_id=".$_GET['org_id']."&bill_id=".$_GET['bill_id']."'</script>";
 
            // }
            // else{
            //     $pay = $_POST['pay_amount'];
            //  $due_amount = $due_amt- $pay;
            //  $data['due_amount'] = $due_amount;
            //  $obj->update('tbl_bill',$data,'bid',array($_POST['bill_id']));
            //  $obj->Insert("tbl_payment",$_POST);
            // $_SESSION['success'] = "Payment has been done successfully.";
            // echo "<script> window.location.href='".base_url('view_individual_bill_payment')."?bill_id=".$_GET['bill_id']."&org_id=".$_GET['org_id']."'</script>";
            // } 
            $obj->Insert("tbl_payment",$_POST);
            echo "<script> window.location.href='".base_url('view_individual_bill_payment')."?bill_id=".$_GET['bill_id']."&org_id=".$_GET['org_id']."'</script>";
            
        }
        
        
        
            
       }else{
        unset($_POST['submit']);
        // $total_amt1 = $obj->select('tbl_bill','*','bid',array($_POST['bill_id']));
        // $total_amt = $total_amt1[0]['bill_amount'];
        // $due_amt = $total_amt1[0]['due_amount'];
        // if($_POST['pay_amount']>$total_amt || $_POST['pay_amount']>$due_amt ){
        //  $_SESSION['error'] = "Entered amount is incorrect.";

        //  echo "<script> window.location.href='".base_url('add_individual_bill_payment')."?org_id=".$_GET['org_id']."&bill_id=".$_GET['bill_id']."'</script>";

        // }
        // else{
        //     $pay = $_POST['pay_amount'];
        //  $due_amount = $due_amt- $pay;
        //  $data['due_amount'] = $due_amount;
        //  $obj->update('tbl_bill',$data,'bid',array($_POST['bill_id']));
        //  $obj->Insert("tbl_payment",$_POST);
        //  $_SESSION['success'] = "Payment has been done successfully.";
        //  echo "<script> window.location.href='".base_url('view_individual_bill_payment')."?bill_id=".$_GET['bill_id']."&org_id=".$_GET['org_id']."'</script>";
        // } 

        $obj->Insert("tbl_payment",$_POST);
              echo "<script> window.location.href='".base_url('view_individual_bill_payment')."?bill_id=".$_GET['bill_id']."&org_id=".$_GET['org_id']."'</script>";
       
    }
          
        
    }
}
$bill = $obj->select('tbl_bill');
?>


<div class="col-md-12">
<h1>Add Payment of <?=$orgname[0]['org_name'];?></h1>

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
<form method="post" class="form-group" enctype="multipart/form-data">
<input type="hidden" name="bill_id" value="<?=$_GET['bill_id'];?>">
<label for="">Receipt No </label>
    <input type="number" name="receipt_no" class="form-control" >

    <label for="">Received Amount  (<a style="color:blue"> Total Receivable : <?=$total_receivable;?></a> | <a style="color:green"> Paid : <?=$paid;?></a> | <a style="color:red">Due : <?=$due;?></a>)</label>
    <input type="number" name="pay_amount" class="form-control" max="<?=$due;?>" >
    <label for="">Received Date</label>
    <input type="date" class="form-control" name="pay_date" value="<?=date('Y-m-d');?>">
    <label for="">Check Image (If any)</label>
    <input type="file" name="image[]" class="form-control" multiple>
    <label for="">Check No</label>
    <input type="number" name="check_no" class="form-control">
    
    <button class="btn btn-info mt-3" type="submit " name="submit" value="payment_data"> Add</button>
</form>
</div>
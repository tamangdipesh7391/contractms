<?php 
if (isset($_POST['submit'])) {
    if ($_POST['submit']=='contract_data') {



        if(isset($_POST['start_date']) && isset($_POST['end_date'])){
           
            if($_POST['start_date'] > $_POST['end_date']){
        $old[] = $_POST;
                
                echo "<script> window.location.href='".base_url('add_contract')."?msg=error&id=".$_GET['id']."'</script>";

            }
        }
     
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
              

            $a = " SELECT * FROM tbl_contract WHERE con_title ='".$_POST['con_title']."' AND org_id='".$_POST['org_id']."' AND start_date='".$_POST['start_date']."' AND end_date='".$_POST['end_date']."' AND con_amount='".$_POST['con_amount']."' AND payment_mode='".$_POST['payment_mode']."' AND status='".$_POST['status']."'";

            $check = $obj->Query($a);

            if ($check) {
                $_SESSION['error'] = "Data already exists";
            }else{
                                      
                $location='assets/contract'.'/'.$filename[0];
                move_uploaded_file($tmp_name, $location);//upload file
                array_pop($_POST);//popping submit form post
              if (sizeof($imgName)>1) {
  
              $a=implode(',',$filename );
                      $_POST['file'] = $a;
              }
              else{
                  $_POST['file']=$filename[0];
              }
if($_POST['commission_amt'] <= $_POST['con_amount']){
                                  
                $obj->Insert("tbl_contract",$_POST);//insert query
}
                $_SESSION['success']  ="Contract has been created successfully.";
                echo "<script> window.location.href='".base_url('view_org_contract')."?org_id=".$_GET['id']."'</script>";
            }
            
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
            $a = " SELECT * FROM tbl_contract WHERE con_title ='".$_POST['con_title']."' AND org_id='".$_POST['org_id']."' AND start_date='".$_POST['start_date']."' AND end_date='".$_POST['end_date']."' AND con_amount='".$_POST['con_amount']."' AND payment_mode='".$_POST['payment_mode']."' AND status='".$_POST['status']."'";

            $checkImg = $obj->Query($a);
          if($checkImg){

              }else{
                $location='assets/contract'.'/'.$filename[$j];
                move_uploaded_file($tmp_name, $location);//upload file
                array_pop($_POST);//popping submit form post
              if (sizeof($_FILES['image']['name'])>1) {
  
              $a=implode(',',$filename );
                      $_POST['file'] = $a;
              }
              else{
                  $_POST['file']=$filename[0];
              }
              }

            //insert filename in post variable



            }
             
            }
            $a = " SELECT * FROM tbl_contract WHERE con_title ='".$_POST['con_title']."' AND org_id='".$_POST['org_id']."' AND start_date='".$_POST['start_date']."' AND end_date='".$_POST['end_date']."' AND con_amount='".$_POST['con_amount']."' AND payment_mode='".$_POST['payment_mode']."' AND status='".$_POST['status']."'";

            $check = $obj->Query($a);

            if ($check) {
                $_SESSION['error'] = "Data already exists";
            }else{
                


                if($_POST['commission_amt'] <= $_POST['con_amount']){
                                  
                    $obj->Insert("tbl_contract",$_POST);//insert query
    }                $_SESSION['success']  ="Contract has been created successfully.";
                echo "<script> window.location.href='".base_url('view_org_contract')."?org_id=".$_GET['id']."'</script>";
              }
        }
        
        
        
            
       }
       else{
           unset($_POST['submit']);
           if($_POST['commission_amt'] <= $_POST['con_amount']){
                                  
            $obj->Insert("tbl_contract",$_POST);//insert query
}           $_SESSION['success']  ="Contract has been created successfully.";
        echo "<script> window.location.href='".base_url('view_org_contract')."?org_id=".$_GET['id']."'</script>";
       }
          
        
    }
}
$org = $obj->select('tbl_organization');



?>

<div class="col-md-12">
<h1>Contract Add Form</h1>

    <div class="row">

        
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
            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'error') { ?>
            <div class="alert alert-danger">
            Contract start date must be greater than end date.
            </div>
            <?php }  ?>
        <form method="post" class="form-group" enctype="multipart/form-data" id="contract_form">
            <input type="hidden" name="org_id" value="<?php if(isset($_GET['id'])) echo $_GET['id']; ?>">
            <label for="">Contract Titlle</label>
            <input type="text" name="con_title" required class="form-control" value="<?php if(isset($old['con_title'])) echo $old['con_title']; ?>">
            <label for="">Contract Start Date</label>
            <input type="date" name="start_date" required class="form-control " id="start_date" value="<?=date('Y-m-d');?>">
            <label for="">Contract End Date</label>
            <input type="date" required class="form-control" name="end_date" id="end_date" value="<?php if(isset($old['end_date'])) echo $old['end_date']; ?>">
            <label for="">Contract Image</label>
            <input type="file"  class="form-control" name="image[]" multiple >
            <label for="">Contract Amount</label>
            <input type="number" required class="form-control" step="any" name="con_amount" value="<?php if(isset($old['con_amount'])) echo $old['con_amount']; ?>">
            <label for="">Commission Amount</label>
            <input type="number" required class="form-control" step="any" name="commission_amt" value="">
            <label for="">Payment Mode</label>
            <select name="payment_mode" id="" required class="form-control">
                <option selected disabled>Select Payment Mode</option>
                <option value="anually" <?php if(isset($old['payment_mode'])){if($old['payment_mode'] == 'anually')echo " selected "; } ?> >Anually</option>
                <option value="monthly" <?php if(isset($old['payment_mode'])){if($old['payment_mode'] == 'monthly')echo " selected "; } ?> >Monthly</option>
                <option value="quaterly" <?php if(isset($old['payment_mode'])){if($old['payment_mode'] == 'quaterly')echo " selected "; } ?> >Quaterly</option>
                <option value="semianually" <?php if(isset($old['payment_mode'])){if($old['payment_mode'] == 'semianually')echo " selected "; } ?> >Semi Anually</option>


            </select>
            <label for="">Status</label>
        <select name="status" id="" required class="form-control">
        <option value="1" <?php if(isset($old['status'])){if($old['status'] == '1')echo " selected "; } ?> >Active</option>
        <option value="0" <?php if(isset($old['status'])){if($old['status'] == '0')echo " selected "; } ?> >Inactive</option>


        </select>
            <button class="btn btn-info mt-3" type="submit " name="submit" value="contract_data">Add</button>
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
</div>
</div>
<script>
    $(document).on('submit','#contract_form',function(){
    var start = $('#start_date').val();
    var end = $('#end_date').val();
    if(start > end){
        alert("Contract Start date must be smaller than end date!");
        return false;
    }else{
        return true;
    }

    })
</script>
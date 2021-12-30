<?php 
$edit_data = $obj->Select('tbl_contract','*','conid',array($_GET['id']));
if (isset($_POST['submit']) && $_POST['submit'] == 'edit_contract_data') {

    if ($_FILES['image']['name'][0]=='') {
             $_FILES['image']['name'][0] = $edit_data[0]['file'];
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
                         $data1 = explode(',', $edit_data[0]['file']);
                         if (sizeof($data1)>1) {
                             for ($j=0; $j <sizeof($data1) ; $j++) { 
                                 unlink("assets/contract/".$data1[$j]);

                                 }

                 }else{
                     unlink("assets/contract/".$edit_data[0]['file']);
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
   
       
       $location='assets/contract'.'/'.$filename[0];
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
 $id = $_GET['id'];
 

 

if($_POST['commission_amt'] <= $_POST['con_amount']){
    $obj->Update("tbl_contract",$_POST,"conid",array($id));

}
$_SESSION['success'] = "Record Updated Successfully !";
echo "<script> window.location.href='".base_url('view_org_contract')."?org_id=".$_GET['org_id']."'</script>";
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
   

       $location='assets/contract'.'/'.$filename[$j];
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
 
  $id = $_GET['id'];
  if($_POST['commission_amt'] <= $_POST['con_amount']){
    $obj->Update("tbl_contract",$_POST,"conid",array($id));

}    $_SESSION['success'] = "Record Updated Successfully !";
    echo "<script> window.location.href='".base_url('view_org_contract')."?conid=".$_GET['id']."'</script>";
}



// end imported

}


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
<form method="post" class="form-group" enctype="multipart/form-data" id="contract_form">
    <h1>Contract Edit Form</h1>
    
    <input type="hidden" name="org_id" value="<?=$edit_data[0]['org_id'] ?>">
    <label for="">Contract Titlle</label>
    <input type="text" name="con_title" required class="form-control" value="<?=$edit_data[0]['con_title'];?>">
    <label for="">Contract Start Date</label>
    <input type="date" name="start_date" required id="start_date" class="form-control" value="<?=$edit_data[0]['start_date'];?>">
    <label for="">Contract End Date</label>
    <input type="date" required class="form-control" id="end_date" name="end_date" value="<?=$edit_data[0]['end_date'];?>">
    <label for="">Contract Image</label>
    <input type="file" <?php if($edit_data[0]['file'] == '') echo " required "; ?>  class="form-control" name="image[]" multiple >
    <label for="">Contract Amount</label>
    <input type="number" required class="form-control" name="con_amount" value="<?=$edit_data[0]['con_amount'];?>">
    <label for="">Commission Amount</label>
            <input type="number" required class="form-control" step="any" name="commission_amt" value="<?=$edit_data[0]['commission_amt'];?>">
    <label for="">Payment Mode</label>
    <select name="payment_mode" id="" required class="form-control">
        <option selected disabled>Select Payment Mode</option>
   
        <option value="anually" <?php if(isset($edit_data[0]['payment_mode'])){if($edit_data[0]['payment_mode'] == 'anually')echo " selected "; } ?> >Anually</option>
        <option value="monthly" <?php if(isset($edit_data[0]['payment_mode'])){if($edit_data[0]['payment_mode'] == 'monthly')echo " selected "; } ?> >Monthly</option>
        <option value="quaterly" <?php if(isset($edit_data[0]['payment_mode'])){if($edit_data[0]['payment_mode'] == 'quaterly')echo " selected "; } ?> >Quaterly</option>
        <option value="semianually" <?php if(isset($edit_data[0]['payment_mode'])){if($edit_data[0]['payment_mode'] == 'semianually')echo " selected "; } ?> >Semi Anually</option>

    </select>
    <label for="">Status</label>
   <select name="status" id="" required class="form-control">
       <option value="1" <?php  if($edit_data[0]['status']=='1') echo "selected";?>>Active</option>
       <option value="o" <?php  if($edit_data[0]['status']=='0') echo "selected";?>>Inactive</option>

   </select>
    <button  class="btn btn-info mt-3" type="submit " name="submit" value="edit_contract_data">Update</button>
</form>
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
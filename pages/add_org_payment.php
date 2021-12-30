<?php 
if(isset($_GET['org_id'])){
    $contracts = $obj->select("tbl_contract","*","org_id",array($_GET['org_id']));
    if(!$contracts){ ?>
       <span style="color:red"><b>There is no contract for this organization yet.</b> </span>  
   <?php die; }
}
if(isset($_GET['org_id'])){
$current_date = date('Y-m-d');

    $contracts = $obj->select('tbl_contract','*','org_id',array($_GET['org_id'])," AND status = 1 AND end_date > '".$current_date."'");
}
if (isset($_POST['submit'])) {
   
    if ($_POST['submit']=='payment_data') {
//  echo "<pre>"; print_r($_POST); die;
        
        if(!isset($_POST['conid'])){
            if(isset($_GET['action']) && $_GET['action'] == 'from_org'){
                echo "<script> window.location.href='".base_url('display_org_bill')."?org_id=".$_GET['org_id']."&conid=".$get_conid."'</script>";

           }
           else{
                   echo "<script> window.location.href='".base_url('display_org_bill')."?org_id=".$_GET['org_id']."&conid=".$_GET['conid']."'</script>";
           }
        }
        if(!isset($_POST['pay_amount'])){
            if(isset($_GET['action']) && $_GET['action'] == 'from_org'){
                echo "<script> window.location.href='".base_url('display_org_bill')."?org_id=".$_GET['org_id']."&conid=".$get_conid."'</script>";

           }
           else{
                echo " Entered in not payemnt ";die;
                   echo "<script> window.location.href='".base_url('display_org_bill')."?org_id=".$_GET['org_id']."&conid=".$_GET['conid']."'</script>";
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
                    

                            $location='assets/payment'.'/'.$filename[0];
                            move_uploaded_file($tmp_name, $location);//upload file
                            if (sizeof($imgName)>1) {

                            $a=implode(',',$filename );
                                    $_POST['file'] = $a;
                            }
                            else{
                                $_POST['file']=$filename[0];
                            }
                            // echo "<pre>"; print_r($_POST); die;
                            if(isset($_POST['conid'])){
                                $get_conid = $_POST['conid'];
                            }else{
                                $get_conid = $_GET['conid'];
                            }
                            if(isset($_GET['conid'])){
                             $get_conid_for = $_GET['conid'];
                            }else{
                                $get_conid_for = $get_conid;
                            }
                             unset($_POST['conid']);
                                unset($_POST['submit']);
                     
                               $last_id =  $obj->Insert("tbl_payment",$_POST);
                               
                             //    insert into commission table 
                             
                             //inserting into table commission
                             $com['bid'] = $_POST['bill_id'];
                             $com['conid'] = $get_conid;
                             $com['pay_id'] = $last_id;
                             $total_commission1 = $obj->select("tbl_contract","*","conid",array($get_conid));
                             $total_commission = $total_commission1[0]['commission_amt'];
                             $total_con_amt = $total_commission1[0]['con_amount'];
                             $com_amount = ($total_commission/$total_con_amt)*$_POST['pay_amount'];
                             // echo " com amount= ".$com_amount; die;
                             $com['com_amount'] = $com_amount;
                            
                             $received_com_amount = $obj->ArrQuery("SELECT SUM(com_amount) as received_com_amt FROM tbl_commission WHERE bid=".$_POST['bill_id']." AND conid=".$get_conid_for);
                             $received_com_amount = $received_com_amount[0]['received_com_amt'];
                            
                             if($received_com_amount){
                                 if(($received_com_amount < $total_commission) && $total_commission >= ($received_com_amount + $com_amount) ){
                                
                     
                                     $obj->insert("tbl_commission",$com);
                                 }
                             }else{
                             
                                 $obj->insert("tbl_commission",$com);
                             }
                             
                        $_SESSION['success'] = "Payment has been done successfully.";
                        if(isset($_GET['action']) && $_GET['action'] == 'from_org'){
                             echo "<script> window.location.href='".base_url('display_org_bill')."?org_id=".$_GET['org_id']."&conid=".$get_conid."'</script>";

                        }
                        else{
                                echo "<script> window.location.href='".base_url('display_org_bill')."?org_id=".$_GET['org_id']."&conid=".$_GET['conid']."'</script>";
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
            
                        $location='assets/payment'.'/'.$filename[$j];
                        move_uploaded_file($tmp_name, $location);//upload file
                                    if (sizeof($_FILES['image']['name'])>1) {

                                    $a=implode(',',$filename );
                                            $_POST['file'] = $a;
                                    }
                                    else{
                                        $_POST['file']=$filename[0];
                                    }

                            }
                    
                    }
                    if(isset($_POST['conid'])){
                        $get_conid = $_POST['conid'];
                    }else{
                        $get_conid = $_GET['conid'];
                    }
                    if(isset($_GET['conid'])){
                     $get_conid_for = $_GET['conid'];
                    }else{
                        $get_conid_for = $get_conid;
                    }
                     unset($_POST['conid']);
                        unset($_POST['submit']);
             
                       $last_id =  $obj->Insert("tbl_payment",$_POST);
                       
                     //    insert into commission table 
                     
                     //inserting into table commission
                     $com['bid'] = $_POST['bill_id'];
                     $com['conid'] = $get_conid;
                     $com['pay_id'] = $last_id;
                     $total_commission1 = $obj->select("tbl_contract","*","conid",array($get_conid));
                     $total_commission = $total_commission1[0]['commission_amt'];
                     $total_con_amt = $total_commission1[0]['con_amount'];
                     $com_amount = ($total_commission/$total_con_amt)*$_POST['pay_amount'];
                     // echo " com amount= ".$com_amount; die;
                     $com['com_amount'] = $com_amount;
                    
                     $received_com_amount = $obj->ArrQuery("SELECT SUM(com_amount) as received_com_amt FROM tbl_commission WHERE bid=".$_POST['bill_id']." AND conid=".$get_conid_for);
                     $received_com_amount = $received_com_amount[0]['received_com_amt'];
                    
                     if($received_com_amount){
                         if(($received_com_amount < $total_commission) && $total_commission >= ($received_com_amount + $com_amount) ){
                        
             
                             $obj->insert("tbl_commission",$com);
                         }
                     }else{
                        
                         $obj->insert("tbl_commission",$com);
                     }
                     
                        $_SESSION['success'] = "Payment has been done successfully.";
                        if(isset($_GET['action']) && $_GET['action'] == 'from_org'){
                            echo "<script> window.location.href='".base_url('display_org_bill')."?org_id=".$_GET['org_id']."&conid=".$get_conid."'</script>";

                       }
                       else{
                               echo "<script> window.location.href='".base_url('display_org_bill')."?org_id=".$_GET['org_id']."&conid=".$_GET['conid']."'</script>";
                       }        }
        
        
        
            
       }
       else{
       
        if(isset($_POST['conid'])){
            $get_conid = $_POST['conid'];
        }else{
            $get_conid = $_GET['conid'];
        }
       if(isset($_GET['conid'])){
        $get_conid_for = $_GET['conid'];
       }else{
           $get_conid_for = $get_conid;
       }
        unset($_POST['conid']);
           unset($_POST['submit']);

          $last_id =  $obj->Insert("tbl_payment",$_POST);
          
        //    insert into commission table 
        
        //inserting into table commission
        $com['bid'] = $_POST['bill_id'];
        $com['conid'] = $get_conid;
        $com['pay_id'] = $last_id;
        $total_commission1 = $obj->select("tbl_contract","*","conid",array($get_conid));
        $total_commission = $total_commission1[0]['commission_amt'];
        $total_con_amt = $total_commission1[0]['con_amount'];
        $com_amount = ($total_commission/$total_con_amt)*$_POST['pay_amount'];
        // echo " com amount= ".$com_amount; die;
        $com['com_amount'] = $com_amount;
       
        $received_com_amount = $obj->ArrQuery("SELECT SUM(com_amount) as received_com_amt FROM tbl_commission WHERE bid=".$_POST['bill_id']." AND conid=".$get_conid_for);
        $received_com_amount = $received_com_amount[0]['received_com_amt'];
       
        if($received_com_amount){
            if(($received_com_amount < $total_commission) && $total_commission >= ($received_com_amount + $com_amount) ){
           

                $obj->insert("tbl_commission",$com);
            }
        }else{
           
            $obj->insert("tbl_commission",$com);
        }

        
            $_SESSION['success'] = "Payment has been done successfully.";
            if(isset($_GET['action']) && $_GET['action'] == 'from_org'){
                echo "<script> window.location.href='".base_url('display_org_bill')."?org_id=".$_GET['org_id']."&conid=".$get_conid."'</script>";

           }
           else{
                   echo "<script> window.location.href='".base_url('display_org_bill')."?org_id=".$_GET['org_id']."&conid=".$_GET['conid']."'</script>";
           }        }
          
        
    }
}
?>


<div class="col-md-12">
<h1>Add Received Payment</h1>

</div>
<div class="col-md-7">
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
        <?php if(isset($_GET['bill_id'])) { 
            $bills = $obj->select("tbl_bill","*","bid",array($_GET['bill_id'])); ?>
            <label for="">Your Bill</label>
        <select name="bill_id" id="con_bill" class="form-control">
            
        <option value="<?=$bills[0]['bid'];?>" selected><?=$bills[0]['bill_title'];?></option>
            
        </select>


        <?php }else { ?>
            <label for="Contract">Select Contract</label>
        <select  id="contract" name="conid" class="form-control">
        <option selected disabled>Select contract</option>
        <?php foreach($contracts as $contract): ?>
        <option value="<?=$contract['conid'];?>"><?=$contract['con_title'];?></option>
        <?php endforeach; ?>
        </select>
        <div id="bills">
        </div>
        <?php } ?>

            <label for="">Receipt No </label>
            <input type="number" name="receipt_no" required class="form-control" >
            <div id="amount_info">
       
            <label for="">Received Amount <br>

                <?php if(isset($_GET['bill_id'])){
                    $due = $bill_paid = 0 ;
                    $bill_paid = $obj->ArrQuery("SELECT SUM(pay_amount) as paid FROM `tbl_payment` WHERE bill_id =".$_GET['bill_id']);
                    $bill_paid = $bill_paid[0]['paid'];
                    $paid = $obj->ArrQuery("SELECT SUM(with_tds + vat_amount) as paid FROM `tbl_payment` WHERE bill_id =".$_GET['bill_id']);
                    $paid = $paid[0]['paid'];
                    $check_tds_status1 = $obj->select("tbl_payment","*","bill_id",array($_GET['bill_id']));
                    if($check_tds_status1)
                    {
                     $check_tds_status1 = $check_tds_status1[0];
                     if(isset($check_tds_status1['tds_status'])){
                         if($check_tds_status1['tds_status'] == 1){
                             $tds1 = ($check_tds_status1['pay_amount'] * 1.5)/100;
                             $paid = $paid + $tds1;
                         }
                       
                        
                     }
                    }
                   
                    if($paid == NULL) $paid = 0;
                    $payable = $bills[0]['bill_amount'];
                    $dis = ($payable -$bills[0]['taxable_amount']);
                    if($payable > $paid){
                        $receivable = $bills[0]['taxable_amount']+$bills[0]['vat_amount'];
                      
                        $due = abs($receivable - $paid); 
                        
                        $check_tds_status = $obj->select("tbl_payment","*","bill_id",array($_GET['bill_id']));
                        if($check_tds_status)
                        {
                         $check_tds_status = $check_tds_status[0];
                         if(isset($check_tds_status['tds_status'])){
                             if($check_tds_status['tds_status'] == 1){
                                 $tds = ($check_tds_status['pay_amount'] * 1.5)/100;
                                 $due = $due + $tds;
                             }
                           
                            
                         }
                        }
                        ?>
                        (<span style="color:black">Total Billed : </span><strike><em><?=$payable;?></em></strike><span> (<?=($payable-$dis);?>)|
                        <span style="color:blue">Total Receivable : <?=($due);?>
                        </span><span style="color:green">| Total Paid :  <?=$bill_paid;?> |
                        </span> <span style="color:red"> Due amount :  <?=($payable-$bill_paid);?></span> )
                <?php 
                $max_due = $payable-$bill_paid;    
            }
                }  ?>

                    </label>
                   
                    <input type="hidden" name="pay_amount" class="unlikable-pr unlikable temp_pay_amount" id="">
                    <input type="number" id="" name="with_tds" step="any" required class="pay_amount form-control" max="<?php if(isset($max_due)){ echo $max_due;}?>" >
                   
            </div>
            <div class="form-group">
            <label for="">TDS Status</label>

        <div class="row">
        <div class="toggler ">
                <div class="circle circle-btn" >

                </div>
               

            </div>
            <div class="col-md-6"></div>
            <div class="tds-box ">
                    <input type="text"  class="show-tds form-control">
                    <input type="hidden" name="tds_status" class="tds-status">
                </div>
        </div>
            <div class="row">
                <div class="col-md-6">
                     <label for="">Enter VAT Amount</label>
                    <?php if(isset($_GET['bill_id'])){ ?>
                        <input type="text"  name="vat_amount" id="" class="form-control vat_amount  current_vat_amount" value="<?php $vat_amount_only = $obj->select("tbl_bill","*","bid",array($_GET['bill_id'])); if($vat_amount_only[0]['vat_amount'] != '') echo $vat_amount_only[0]['vat_amount']; ?>">

                   <?php  }else{ ?>
                    <input type="text"  name="vat_amount" id="" class="form-control vat_amount <?php // if(isset($_GET['action']) && $_GET['action'] == 'from_org') { echo "current_vat_amount";} ?> current_vat_amount">

                    <?php } ?>

                </div>
                <div class="col-md-6">
            <label for="">Total Received Amount (Including VAT)</label>

                    <input type="text" readonly name="" id="" class="form-control total_vat_amount">

                </div>
            </div>

         
          
            </div>
            <label for="">Received Date </label>
            <input type="date" required class="form-control" name="pay_date" value="<?=date('Y-m-d');?>">
            <label for="">Cheque Image (If any)</label>
            <input type="file"  name="image[]"  class="form-control" multiple>
            <label for="">Cheque No</label>
            <input type="number" name="check_no"  class="form-control">
            
            <button class="btn btn-info mt-3" type="submit " name="submit" value="payment_data"> Add</button>
</form>
</div>
<div class="col-md-1"></div>
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
$(document).ready(function(){
    $('#contract').on('change',function(){
    var conid = $(this).val();
    var data = {conid:conid}
    $.ajax({
        type:'post',
        url:'ajax/ajax.php?action=payment_from_display_org',
        data:data,
        success:function(response){
            // alert(response);
            $('#bills').html(response);
        }
    })
})
})

$(document).on('change','#con_bill',function(){
    var bid = $(this).val();
    var data = {bid:bid}
    $.ajax({
        type:'post',
        url:'ajax/ajax.php?action=get_amount_from_display_org',
        data:data,
        success:function(response){
            // alert(response);
            $('#amount_info').html(response);
            $('.current_vat_amount').val(Number($('.vat_amount').val()));
        }
    })
})
</script>

<!-- tds and vat calculation js  -->
<script>
    
             $(document).ready(function(){
                
                $(".pay_amount").keyup(function(){
              
                       
                        $('.unlikable').val(Number($(this).val()));
                        let is_vat_amt_tmp = Number($('.vat_amount').val());
                    if(is_vat_amt_tmp == ''){
                        let vat_amount1 = Number($(this).val());
                        let total_v_amount1 = vat_amount1 + is_vat_amt_tmp;
                        $('.total_vat_amount').val(total_v_amount1);

                    }else{
                        let vat_amount1 = Number($(this).val());
                        let total_v_amount1 = vat_amount1 + is_vat_amt_tmp;
                        $('.total_vat_amount').val(total_v_amount1);

                    }
                    })
                    $('.vat_amount').keyup(function(){
                        let is_vat_amt = Number($('.pay_amount').val());
                    if(is_vat_amt == ''){
                        alert("Amount Field cannot be empty!")
                        $('.vat_amount').val() = '';
                    }else{
                        let vat_amount = Number($(this).val());
                        let total_v_amount = vat_amount + is_vat_amt;
                        $('.total_vat_amount').val(total_v_amount);

                    }
                       
                   })
                $(".circle-btn").click(function(){
                   
                   
                   
                    let is_amount = Number($('.temp_pay_amount').val());
                    if(is_amount == ''){
                        alert("Amount Field cannot be empty!");
                    }else{
                        let check_class = $(".circle-btn").hasClass("active-circle")
                        if(check_class == true){
                            $('.unlikable-pr').addClass('unlikable')

                                    // let tds = (1.5 * is_amount)/100;
                                    // // is_amount = Number(is_amount);
                                    // // tds = Number(tds);
                                    // let new_amount = is_amount + tds
                                    $('.pay_amount').val(is_amount);
                                    if($('.total_vat_amount').val() != ''){
                                    let new_amountv = Number($('.pay_amount').val())
                                    let v_amount = Number($('.vat_amount').val())
                                    $('.total_vat_amount').val(new_amountv + v_amount );
                                    $('.show-tds').val('');
                                    $('.tds-status').val(0);
                                }
                        }else{
                          $('.unlikable-pr').removeClass('unlikable')

                            let tds = (1.5 * is_amount)/100;
                            let new_amount = (is_amount - tds)
                            $('.pay_amount').val(new_amount);
                            if($('.total_vat_amount').val() != ''){
                                let new_amountv = Number($('.pay_amount').val())
                            let v_amount = Number($('.vat_amount').val())
                      

                            $('.total_vat_amount').val(new_amountv + v_amount);
                            $('.show-tds').val(Number(tds));
                            $('.tds-status').val(1);

                        }
                        }
                        
                        
                        $(".circle-btn").toggleClass("active-circle");
                    $(".toggler").toggleClass("active-circle-bg");
                    }
                });
                });
            </script>
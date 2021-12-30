<?php
require_once ("../config/config.php");
require_once ("../config/db.php");

if(isset($_GET['action']) )
{
     //Calculating Billable amount
     if($_GET['action'] == 'get_billable_amount'){
          $total_payable = $obj->select("tbl_contract","*","conid",array($_POST['conid']));
          $total_payable = $total_payable[0]['con_amount'];
          
        $total_paid = $obj->ArrQuery("SELECT SUM(bill_amount) as paid FROM tbl_bill WHERE con_id=".$_POST['conid']);
        $total_paid = $total_paid[0]['paid'];
        //   $total_bills = $obj->Query("SELECT * FROM tbl_bill WHERE con_id = ".$_POST['conid']);
        //   $paid_amt = $due = 0;
    //       if($total_bills){
    //       foreach($total_bills as $bill){
              
    //           $amt = $obj->select("tbl_payment","*","bill_id",array($bill->bid));
    //           if($amt){
    //            $paid_amt += $amt[0]['pay_amount'];

    //           }
    //       }
    //  }
          if($total_payable > $total_paid || $total_payable == $total_paid){
               $due = $total_payable - $total_paid;
          }
     
          ?>
         <label for="">Bill Amount ( <b>
         <span style="color:black">Total Receivable : <?=$total_payable;?>
         </span><span style="color:green">| Total Billed :  <?=$total_paid;?> |
         </span> <span style="color:red"> Due amount :  <?=$due;?></span> </b>)</label>
          <?php  if($due == 0){ ?>
<b style="color:green">Thank You ! You have already cleared your all bills.</b>
         <?php  }else{ ?>
            <input type="number" required class="form-control total_bill" max="<?=$due;?>" name="bill_amount">

         <?php  } ?>
           <?php 
          
     }

     //Calculating single org bill with contract
     if($_GET['action'] == 'get_sigle_org_bill'){
          $bill_data = $obj->select('tbl_bill','*','con_id',array($_POST['conid']));
           if($bill_data){ ?>

     

               <table class="table table-bordered ">
                   <tr>
                       <th>SN</th>
                       <th> Bill Title</th>
                       <th>Bill No </th>
                       <th>Amount(Rs)</th>
                       <th>Due Amount(Rs)</th>
               
                       <th>Billed Date </th>
                       <th colspan="2">Action </th>
                       <th colspan="2">Payment</th>
               
                   </tr>
                   <?php foreach($bill_data as $key=>$value) : ?>
                       <tr>
                           <td><?=++$key;?></td>
                           <td><?=$value['bill_title'];?></td>
                           <td><?=$value['bill_no'];?></td>
                           <td><?=$value['bill_amount'];?></td>
                           
            <?php
             $total_con_amount = $obj->select("tbl_bill","*","bid",array($value['bid']));
            $total_con_amount = $total_con_amount[0]['bill_amount'];
            $total_paidr = $obj->select('tbl_payment',' SUM(pay_amount) as paid','bill_id',array($value['bid']));
            $total_paid = $total_paidr[0]['paid'];
            $total_due = $total_con_amount - $total_paid; 
            ?>
            <td><?=$total_due;?></td>
               
                           <td><?=$value['bill_date'];?></td>
                          
                           <td>
                               <?php if($total_paid > 0){ ?>
 <a class="btn btn-danger" >Locked</a>
                              <?php  }else{ ?>
                                <a href="<?=base_url('edit_org_bill'); ?>?org_id=<?=$_POST['oid'];?>&bid=<?=$value['bid']."&conid=".$_POST['conid'];?>" class="btn btn-warning ">Edit</a>
                                <a href="<?=base_url("display_org_bill.php?action=delete&bid=".$value['bid']."&org_id=".$_POST['oid']."&conid=".$_POST['conid']); ?>" class="btn btn-danger " onclick="return confirm('Are you sure you want to delete this?')">Delete</a>

                             <?php   } ?>
                           </td>
                           <td>
                           <?php if($total_due == 0 ){ ?>
                           <a title="Bill Cleared" class="btn btn-success "><i class="fa fa-check "></i> </a>
                    
                     <?php   }else { ?>
                               <a href="<?=base_url('add_individual_bill_payment'); ?>?org_id=<?=$_POST['oid'];?>&bill_id=<?=$value['bid'],"&conid=".$_POST['conid'];?>" class="btn btn-info "><i class="fa fa-plus "></i> </a>
                               <?php } ?>
                           </td>
                           <td>
                               <a href="<?=base_url('view_individual_org_payment'); ?>?org_id=<?=$_POST['oid'];?>&bid=<?=$value['bid'];?>" class="btn btn-info "><i class="fa fa-eye "></i> </a>
                           </td>
                         
               
                       </tr>
                       <?php endforeach ; ?>
               </table>
               <?php }else{ ?>
                   <p style="color:red">No data found.</p>
               <?php } 
     }



if($_GET['action'] == 'payment_from_display_org')
{
     $bills = $obj->select("tbl_bill","*","con_id",array($_POST['conid'])); ?>
     <label for="">Select Bill</label>
<select name="bill_id" id="con_bill" class="form-control">
     <option selected disabed>Select Bill</option>
     <?php  foreach($bills as $bill ){ ?>
<option value="<?=$bill['bid'];?>"><?=$bill['bill_title'];?></option>
     <?php } ?>
</select>
     <?php 

}

//From display payments get bill
if($_GET['action'] == 'get_bills'){
     $bills = $obj->select("tbl_bill","*","con_id",array($_POST['conid'])); ?>
     <label for="">Select Bill</label>
<select name="bill_id" id="bill_data" class="form-control">
     <option selected disabed>Select Bill</option>
     <?php  foreach($bills as $bill ){ ?>
<option value="<?=$bill['bid'];?>"><?=$bill['bill_title'];?></option>
     <?php } ?>
</select>
     <?php 
}


//display payment list from bill
if($_GET['action'] == 'get_payment_list'){
     $bill_data = $obj->select('tbl_payment','*','bill_id',array($_POST['bill_id']));

    if($bill_data){ ?>
          <table class="table table-bordered">
              <tr>
                  <th>SN</th>
                  <th>Receipt No </th>
                  <th>Billed Amount (Rs)</th>
                    <th>Billed Date </th>
                    <th>VAT</th>
                    <th>TDS</th>
                    <th>Received Amount (Rs)</th>

                  <th>Check No</th>
                  <th>Receipt Image</th>
                  <th colspan="2">Action</th>
                
          
              </tr>
              <?php foreach($bill_data as $key=>$value) : ?>
                  <tr>
                      <td><?=++$key;?></td>
                      <td><?=$value['receipt_no'];?></td>
                      <td><?=$value['pay_amount'];?></td>
                      <td><?=$value['pay_date'];?></td>
                      <td><?=$value['vat_amount'];?></td>
            <td><?php 
            if(isset($_value['pay_amount']) || isset($value['with_tds'])){
            if($value['pay_amount'] == ''){
                $value['pay_amount'] = 0;
            }
                if($value['with_tds'] == ''){
                    $value['with_tds'] = 0;
                }
                if($value['pay_amount'] > $value['with_tds']){
                    $tds = $value['pay_amount'] - $value['with_tds'];
                    echo $tds;
                }
            
            }
            ?></td>
            <td><?php
            if(isset($_value['with_tds']) || isset($value['vat_amount'])){

                if($value['with_tds'] == ''){
                    $value['with_tds'] = 0;
                }
                    if($value['vat_amount'] == ''){
                        $value['vat_amount'] = 0;
                    }                
                    $tds = $value['with_tds'] + $value['vat_amount'];
                    echo $tds;
                }
            
            ?></td>

                     <td><?=$value['check_no'];?></td>
                      <td><?php if($value['file'] !=''){ 
                          
                          $img = explode(',',$value['file']);
                          if(sizeof($img)<2){ ?>
                              <a href="assets/payment/<?=$img[0];?>"><i class="fa fa-file-image fa-2x"></i></a>
                         <?php  }else{ 
                             
                             for($l=0;$l<sizeof($img);$l++) { ?>
                                 <a href="assets/payment/<?=$img[$l];?>"><i class="fa fa-file-image fa-2x"></i> <?=$l;?></a>
                               
          
                          <?php } }        } else{
                           echo "N/A";   
                          }?>
          
                      </td>
                      <td>
                          <a href="<?=base_url('edit_payment'); ?>?org_id=<?=$_POST['oid'];?>&pid=<?=$value['pid']."&bill_id=".$_POST['bill_id'];?>" class="btn btn-warning ">Edit</a>
                      </td>
                      <td>
                <a href="<?=base_url('view_org_payment'); ?>?action=delete&pid=<?=$value['pid']."&org_id=".$_POST['oid'];?>" onclick="return confirm('Are you sure you want to delete this?')" class="btn btn-danger ">Delete</a>
            </td>
                     
                    
          
                  </tr>
                  <?php endforeach ; ?>
          </table>
          <?php }else{ ?>
          
             <div class="col-md-12"> <p style="color:red">No data found.</p></div>
          <?php } 
}

//display org - get org_wise contract list
if($_GET['action'] == 'get_org_contract'){
     $org_data = $obj->select('tbl_contract','*','org_id',array($_POST['oid']));
     $this_date = date('Y-m-d');

     if($org_data){ ?>
          <table class="table table-bordered">
              <tr>
                  <th>SN</th>
                  <th> Contract Name</th>
                  <th>Organization  </th>
                  <th>Start Date </th>
                  <th>End Date </th>
                  <th>Amount (Rs) </th>
                  <th>Commission (Rs)</th>
                  <th>Method</th>
                  <th>Contract</th>
                  <th>Status</th>
                  <th>Action </th>
                  <th colspan="2">Bill </th>
          
          
              </tr>
              <?php foreach($org_data as $key=>$value) :  if($this_date < $value['end_date'] && $value['status'] == 1){  ?>
                  <tr>
                      <td><?=++$key;?></td>
                      <td><?=$value['con_title'];?>
                      <p class="text-bold text-danger">
                      <?php  $today_date =strtotime( date('Y-m-d'));
        if($today_date < strtotime($value['end_date'])){
          $check_date = (strtotime($value['end_date'])-$today_date );

            $sum = round(($check_date / 86400));
            if($sum == 0 || $sum == 1 ){
              echo "(". $sum." Day left )";
            }elseif($sum >1){
              echo "(". $sum." Days left )";
            }
        }

          elseif($today_date > strtotime($value['end_date']))
         echo "Expired!";
          
         
         ?>
            </p>
                    </td>
                      <td>
                      <?php $org = $obj->select('tbl_organization','*','oid',array($value['org_id']));
                      echo $org[0]['org_name'];
                      ?>
                      </td>
                      <td><?=$value['start_date'];?></td>
                      <td><?=$value['end_date'];?></td>
                      <td><?=$value['con_amount'];?></td>
            <td><?php if(!empty($value['commission_amt'])) echo $value['commission_amt']; else echo "N/A";?></td>

                      <td><?=$value['payment_mode'];?></td>
                      <td><?php if($value['file'] !=''){ 
                          
                          $img = explode(',',$value['file']);
                          if(sizeof($img)<2){ ?>
                              <a href="assets/contract/<?=$img[0];?>"><i class="fa fa-file-image fa-2x"></i></a>
                         <?php  }else{ 
                             
                             for($l=0;$l<sizeof($img);$l++) { ?>
                                 <a href="assets/contract/<?=$img[$l];?>"><i class="fa fa-file-image fa-2x"></i> <?=$l;?></a>
                               
          
                          <?php } }        } else{echo "N/A";}?></td>
          
                      <td>
                      <?php 
                          if($value['status'] == 1){ ?>
                              <a href="<?=base_url('display_contract'); ?>?action=inactive&id=<?=$value['conid'];?>"><i class="fa fa-check-circle fa-2x" style="color:green"></i></a>
                        <?php  }elseif($value['status'] == 0){ ?>
                          <a href="<?=base_url('display_contract'); ?>?action=active&id=<?=$value['conid'];?>"><i class="fa fa-times-circle fa-2x" style="color:red"></i></a>
                         <?php }
                      ?>
                      </td>
                      <td>
                          <?php $orgname = $obj->select('tbl_contract','*','conid',array($value['conid']));
                          $org_from_con = $orgname[0]['org_id'];
            ?>
                          <a href="<?=base_url('edit_contract'); ?>?id=<?=$value['conid']."&org_id=".$org_from_con;?>" class="btn btn-warning ">Edit</a>
                      </td>
                      <td>
                      <a href="<?=base_url('add_contract_bill'); ?>?org_id=<?=$org_from_con;?>&conid=<?=$value['conid'];?>" class="btn btn-primary "><i class="fa fa-plus"></i></a>

                      </td>
                      <td>
                          <a href="<?=base_url('display_org_bill'); ?>?conid=<?=$value['conid']."&org_id=".$org_from_con;?>" class="btn btn-primary "><i class="fa fa-eye"></i></a>
                      </td>
                      
          
                  </tr>
                  <?php } endforeach ; ?>
          </table>
          <?php }else{ ?>
              <p style="color:red">No contracts found.</p>
          <?php }
}

// get expired org contracts 
if($_GET['action'] == 'get_expired_contract'){
    // print_r($_POST); die;
    $this_date = date('Y-m-d');
    $org_data = $obj->ArrQuery("SELECT * FROM tbl_contract WHERE  org_id=".$_POST['oid'] ." AND end_date > '". $this_date."'");

    if(isset($_POST['clause'])){
        if($_POST['clause'] == 'inactive_cons'){
            
            
            $org_data = $obj->ArrQuery("SELECT * FROM tbl_contract WHERE  org_id=".$_POST['oid'] ." AND status = 0 ");

        }
        elseif($_POST['clause'] == 'expired_inactive_cons'){
            $org_data = $obj->ArrQuery("SELECT * FROM tbl_contract WHERE  org_id=".$_POST['oid'] ." AND status = 0  AND end_date > '". $this_date."' ");

        }
    }
    if($org_data){ ?>
         <table class="table table-bordered">
             <tr>
                 <th>SN</th>
                 <th> Contract Name</th>
                 <th>Organization  </th>
                 <th>Start Date </th>
                 <th>End Date </th>
                 <th>Amount(Rs) </th>
                 <th>Method</th>
                 <th>Contract</th>
                 <th>Status</th>
                 <th>Action </th>
                 <th colspan="2">Bill </th>
         
         
             </tr>
             <?php foreach($org_data as $key=>$value) : ?>
                 <tr>
                     <td><?=++$key;?></td>
                     <td><?=$value['con_title'];?>
                     <p class="text-bold text-danger">(
       <?php  $today_date =strtotime( date('Y-m-d'));
       if($today_date > strtotime($value['end_date'])){
         $check_date = ($today_date - strtotime($value['end_date']));
         $sum = round(($check_date / 86400));
         if($check_date >= 0){
           $years = floor($sum / 365);
           $months = floor(($sum - ($years * 365))/30.5);
           $Days = ($sum - ($years * 365) - ($months * 30.5));
           $Days = round($Days);
          if($years > 0){
               if($years == 1){
                   echo $years."Y-";
               }else{
                   echo $years."Y-";

               }
           }
           if($months > 0){
               if($months == 1){
                   echo $months."M-";
               }else{
                   echo $months."M-";

               }
           }
           if($Days > 0){
               if($Days == 1){
                   echo $Days."Day left";
               }else{
                   echo $Days."Days left";

               }
           }
          
          
         }
        }else{
            echo "Expired!";
        }
         ?>
         )</p>
                   </td>
                     <td>
                     <?php $org = $obj->select('tbl_organization','*','oid',array($value['org_id']));
                     echo $org[0]['org_name'];
                     ?>
                     </td>
                     <td><?=$value['start_date'];?></td>
                     <td><?=$value['end_date'];?></td>
                     <td><?=$value['con_amount'];?></td>
                     <td><?=$value['payment_mode'];?></td>
                     <td><?php if($value['file'] !=''){ 
                         
                         $img = explode(',',$value['file']);
                         if(sizeof($img)<2){ ?>
                             <a href="assets/contract/<?=$img[0];?>"><i class="fa fa-file-image fa-2x"></i></a>
                        <?php  }else{ 
                            
                            for($l=0;$l<sizeof($img);$l++) { ?>
                                <a href="assets/contract/<?=$img[$l];?>"><i class="fa fa-file-image fa-2x"></i> <?=$l;?></a>
                              
         
                         <?php } }        } else{echo "N/A";}?></td>
         
                     <td>
                     <?php 
                         if($value['status'] == 1){ ?>
                             <a href="<?=base_url('display_contract'); ?>?action=inactive&id=<?=$value['conid'];?>"><i class="fa fa-check-circle fa-2x" style="color:green"></i></a>
                       <?php  }elseif($value['status'] == 0){ ?>
                         <a href="<?=base_url('display_contract'); ?>?action=active&id=<?=$value['conid'];?>"><i class="fa fa-times-circle fa-2x" style="color:red"></i></a>
                        <?php }
                     ?>
                     </td>
                     <td>
                         <?php $orgname = $obj->select('tbl_contract','*','conid',array($value['conid']));
                         $org_from_con = $orgname[0]['org_id'];
           ?>
                         <a href="<?=base_url('edit_contract'); ?>?id=<?=$value['conid']."&org_id=".$org_from_con;?>" class="btn btn-warning ">Edit</a>
                     </td>
                     <td>
                     <a href="<?=base_url('add_contract_bill'); ?>?org_id=<?=$org_from_con;?>&conid=<?=$value['conid'];?>" class="btn btn-primary "><i class="fa fa-plus"></i></a>

                     </td>
                     <td>
                         <a href="<?=base_url('display_org_bill'); ?>?conid=<?=$value['conid']."&org_id=".$org_from_con;?>" class="btn btn-primary "><i class="fa fa-eye"></i></a>
                     </td>
                     
         
                 </tr>
                 <?php endforeach ; ?>
         </table>
         <?php }else{ ?>
             <p style="color:red">No contracts found.</p>
         <?php }
}


// get org contract for urgent bill 
if($_GET['action'] == 'get_org_contract_in_urgent_bill'){
    $con_ids = '';
    $today_date =strtotime( date('Y-m-d'));
    $dates = $obj->ArrQuery("SELECT * FROM tbl_contract WHERE status = 1");
    foreach($dates as $dates){
        if($today_date < strtotime($dates['end_date'])){
         $check_date = (strtotime($dates['end_date']) - $today_date);
         $check_date = ($check_date / 86400);
       

         if($check_date == 0 || $check_date <= 30){
              $redList[] = $dates['conid'];
         }
    }
}
if(!empty($redList)){
    $con_ids = implode(",",$redList);

}
$org_data = '';
if($con_ids != ''){
$org_data = $obj->ArrQuery("SELECT * FROM tbl_contract WHERE conid in(".$con_ids.") AND status = 1 AND org_id =".$_POST['oid']);
}
    if($org_data){ ?>
         <table class="table table-bordered ">
             <tr>
                 <th>SN</th>
                 <th> Contract Name</th>
                 <th>Organization  </th>
                 <th>Start Date </th>
                 <th>End Date </th>
                 <th>Amount(Rs) </th>
                 <th>Method</th>
                 <th>Contract</th>
                 <th>Status</th>
                 <th>Action </th>
                 <th colspan="2">Bill </th>
         
         
             </tr>
             <?php foreach($org_data as $key=>$value) : ?>
                 <tr>
                     <td><?=++$key;?></td>
                     <td><?=$value['con_title'];?>
                     <p class="text-bold text-danger">
                     <?php  $today_date =strtotime( date('Y-m-d'));
        if($today_date < strtotime($value['end_date'])){
          $check_date = (strtotime($value['end_date'])-$today_date );

            $sum = round(($check_date / 86400));
            if($sum == 0 || $sum == 1 ){
              echo "(". $sum." Day left )";
            }elseif($sum >1){
              echo "(". $sum." Days left )";
            }
        }

          elseif($today_date > strtotime($value['end_date']))
         echo "Expired!";
          ?>
                     </p>
                    </td>
                     <td>
                     <?php $org = $obj->select('tbl_organization','*','oid',array($value['org_id']));
                     echo $org[0]['org_name'];
                     ?>
                     </td>
                     <td><?=$value['start_date'];?></td>
                     <td><?=$value['end_date'];?></td>
                     <td><?=$value['con_amount'];?></td>
                     <td><?=$value['payment_mode'];?></td>
                     <td><?php if($value['file'] !=''){ 
                         
                         $img = explode(',',$value['file']);
                         if(sizeof($img)<2){ ?>
                             <a href="assets/contract/<?=$img[0];?>"><i class="fa fa-file-image fa-2x"></i></a>
                        <?php  }else{ 
                            
                            for($l=0;$l<sizeof($img);$l++) { ?>
                                <a href="assets/contract/<?=$img[$l];?>"><i class="fa fa-file-image fa-2x"></i> <?=$l;?></a>
                              
         
                         <?php } }        } else{echo "N/A";}?></td>
         
                     <td>
                     <?php 
                         if($value['status'] == 1){ ?>
                             <a href="<?=base_url('display_contract'); ?>?action=inactive&id=<?=$value['conid'];?>"><i class="fa fa-check-circle fa-2x" style="color:green"></i></a>
                       <?php  }elseif($value['status'] == 0){ ?>
                         <a href="<?=base_url('display_contract'); ?>?action=active&id=<?=$value['conid'];?>"><i class="fa fa-times-circle fa-2x" style="color:red"></i></a>
                        <?php }
                     ?>
                     </td>
                     <td>
                         <?php $orgname = $obj->select('tbl_contract','*','conid',array($value['conid']));
                         $org_from_con = $orgname[0]['org_id'];
           ?>
                         <a href="<?=base_url('edit_contract'); ?>?id=<?=$value['conid']."&org_id=".$org_from_con;?>" class="btn btn-warning ">Edit</a>
                     </td>
                     <td>
                     <a href="<?=base_url('add_contract_bill'); ?>?org_id=<?=$org_from_con;?>&conid=<?=$value['conid'];?>" class="btn btn-primary "><i class="fa fa-plus"></i></a>

                     </td>
                     <td>
                         <a href="<?=base_url('display_org_bill'); ?>?conid=<?=$value['conid']."&org_id=".$org_from_con;?>" class="btn btn-primary "><i class="fa fa-eye"></i></a>
                     </td>
                     
         
                 </tr>
                 <?php endforeach ; ?>
         </table>
         <?php }else{ ?>
             <p style="color:red">No contracts found.</p>
         <?php }
}
// get_contract_from_display_bill

if($_GET['action'] == 'get_contract_from_display_bill'){
     $contracts = $obj->select('tbl_contract','*','org_id',array($_POST['oid']));
     if(!$contracts){ ?>
     <br>
       <span style="color:red"><b>There is no contract for this organization yet.</b> </span>  

     <?php die; }
     ?>
     <label for="">Select Contract</label>
     <select name="contract" id="contract" class="form-control">
          <option selected disabled>Select Contract</option>
          <?php foreach($contracts as $contract){ ?>
     <option value="<?=$contract['conid'];?>"><?=$contract['con_title'];?></option>
         <?php  } ?>
     </select>
<?php }

//get_bill_from_display_bill
if($_GET['action'] == 'get_bill_from_display_bill'){
     $bill_data = $obj->select('tbl_bill','*','con_id',array($_POST['conid']));
   if($bill_data){ ?>

     

          <table class="table table-bordered ">
              <tr>
                  <th>SN</th>
                  <th> Bill Title</th>
                  <th>Bill No </th>
                  <th>Total Billed(Rs)</th>
                  <th>Due Amount(Rs)</th>
          
                  <th>Billed Date </th>
                  <th colspan="2">Action </th>
                  <th colspan="2">Payment</th>
          
              </tr>
              <?php foreach($bill_data as $key=>$value) : ?>
                  <tr>
                      <td><?=++$key;?></td>
                      <td><?=$value['bill_title'];?></td>
                      <td><?=$value['bill_no'];?></td>
                      <td><?=($value['taxable_amount']+$value['vat_amount']);?></td>
                        <?php
             $total_con_amount = $obj->select("tbl_bill","*","bid",array($value['bid']));
             $total_con_amount = $total_con_amount[0]['taxable_amount'] + $total_con_amount[0]['vat_amount'];
             $total_paid = $obj->select('tbl_payment',' SUM(with_tds+vat_amount) as paid','bill_id',array($value['bid']));
             $total_paid = $total_paid[0]['paid'];
            $total_due = $total_con_amount - $total_paid; 
            ?>
            <td><?=$total_due;?></td>          
                      <td><?=$value['bill_date'];?></td>
                     
                      <td>
                      <?php if($total_paid > 0){ ?>
                    <a class="btn btn-danger" >Locked</a>

               <?php  }else{ ?>
                <a href="<?=base_url('edit_bill'); ?>?bid=<?=$value['bid'];?>" class="btn btn-warning ">Edit</a>
                <a href="<?=base_url('display_bill.php?action=delete&bid='.$value['bid']); ?>" class="btn btn-danger " onclick="return confirm('Are you sure you want to delete this?')">Delete</a>

               <?php  } ?>
                      </td>
                      <td>
                           <?php if($total_due == 0 ){ ?>
                           <a title="Bill Cleared" class="btn btn-success "><i class="fa fa-check "></i> </a>
                    
                     <?php   }else { ?>
                               <a href="<?=base_url('add_org_payment'); ?>?org_id=<?=$_POST['oid'];?>&bill_id=<?=$value['bid'],"&conid=".$_POST['conid'];?>" class="btn btn-info "><i class="fa fa-plus "></i> </a>
                               <?php } ?>
                           </td>
                      <td>
                          <a href="<?=base_url('view_payment'); ?>?bid=<?=$value['bid'];?>" class="btn btn-info "><i class="fa fa-eye "></i> </a>
                      </td>
                    
          
                  </tr>
                  <?php endforeach ; ?>
          </table>
          <?php }else{ ?>
              <p style="color:red">No data found.</p>
          <?php } 
}

//get_commission_from_display_bill
if($_GET['action'] == 'get_commission_from_display_bill'){
    $commission_data = $obj->select('tbl_commission','*','conid',array($_POST['conid']));
    if(isset($_POST['submit']) && $_POST['submit'] == 'add_commission'){
        unset($_POST['submit']);
        $obj->insert("tbl_commission_checkout",$_POST);
        echo "<script> window.location.href='".base_url('view_commission_details')."'</script>";
   
   }
  if($commission_data){ ?>

    

<table class="table table-bordered ">
    <tr>
        <th>SN</th>
        <th> Contract</th>
        <th>Amount(Rs)</th>
        <th>Issued Date </th>
        <th colspan="2">Commission</th>
        <!-- <th>Action </th>
        <th colspan="2">Payment</th> -->

    </tr>
    <?php foreach($commission_data as $key=>$value) : ?>
        <tr>
            <td><?=++$key;?></td>
            <td><?php 
            $contract_resultset = $obj->select("tbl_contract","*","conid",array($value['conid']));
            $org_id = $contract_resultset[0]['org_id'];
            $org_data = $obj->select("tbl_organization","*","oid",array($org_id));
            echo $contract_resultset[0]['con_title'];
            echo "<br>"; ?>
            <span style="color:purple"><b>
            <?php echo "(".$org_data[0]['org_name'].")"; ?>
            </b></span>
            <?php ?></td>
            <td><?=$value['com_amount'];?></td>
            <td><?=$value['com_received_date'];?></td>
            <td><a class="btn btn-primary" data-toggle="modal" data-target=".ModalCenter<?=$key;?>"><i class="fa fa-plus"></i></a></td>
            <td><a href="<?=base_url('view_checkout_commission.php?id='.$value['com_id'].'&conid='.$value['conid']);?>" class="btn btn-primary"><i class="fa fa-eye"></i></a></td>
               <!-- Button trigger modal -->


<!-- add commission Modal -->
<div class="modal fade ModalCenter<?=$key;?>" id="" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="ModalLongTitle"><b>Add Commission</b></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       <div class="modal-body">
     <form action="" method="post">
    
         <div class="formm-group">
              <?php 
              $total_com_amt = $total_paid = 0;
              $paid_amount_res = $obj->ArrQuery("SELECT SUM(checkout_amount) as total_checkout FROM tbl_commission_checkout WHERE com_id=".$value['com_id']);
                
                if($paid_amount_res)
                {
                    $total_paid = $paid_amount_res[0]['total_checkout'];

                }       
                       
               $total_com_amt_res = $obj->select("tbl_commission","*","com_id",array($value['com_id'])); 
             
               $total_com_amt = $total_com_amt_res[0]['com_amount'];
              ?>
          <input type="hidden" name="com_id" value="<?=$value['com_id'];?>">
         <label for=""></label><span style="color:green;"><b>( <?php 
         if($total_paid != '' && $total_paid > 0){

              if($total_paid <= $total_com_amt){
                  $rem =  $total_com_amt - $total_paid;
                  if($rem == 0)
                  echo "Cleared!";
                  else
                  echo "Rs. ".$rem." Remaining";
              }
         }elseif($total_paid == ''){
            $rem = $total_com_amt;
          echo "Rs. ".$rem." Remaining";
         }
         
         
         ?>)</b></span>
         <?php if(isset($rem)){
             if($rem == 0){ ?>

             <?php }else{ ?>
                <input type="number" name="checkout_amount" step="any" max="<?=$total_com_amt;?>" class="form-control" required><br>
                <button  class="btn btn-success" type="submit" name="submit" value="add_commission">Add</button>

            <?php  }
         } ?>
         </div>
     
      
     </form>
 </div>
 <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>
           
          

        </tr>
        <?php ++$key; endforeach ; ?>
</table>
       
         <?php }else{ ?>
             <p style="color:red">No data found.</p>
         <?php } 
}

//get contract list in display payment
if($_GET['action'] == 'get_contract_in_paymentlist'){
     $contracts = $obj->select('tbl_contract','*','org_id',array($_POST['oid']),' AND status = 1'); 
     if(!$contracts){ ?>
     <br>
       <span style="color:red"><b>There is no contract for this organization yet.</b> </span>  

    <?php die; }

     ?>
     <label for="">Select Contract</label>
     <select name="contract" id="contract_list_in_payment" class="form-control">
          <option selected disabled>Select Contract</option>
          <?php foreach($contracts as $contract){ ?>
     <option value="<?=$contract['conid'];?>"><?=$contract['con_title'];?></option>
         <?php  } ?>
     </select>
     <?php 
}

// get bill list in payment display 
if($_GET['action'] == 'get_bill_in_paymentlist'){
     $bills = $obj->select('tbl_bill','*','con_id',array($_POST['conid'])); ?>
     <label for="">Select Bill</label>
     <select name="bill" id="bill_list_in_payment" class="form-control">
          <option selected disabled>Select Contract</option>
          <?php foreach($bills as $bill){ ?>
     <option value="<?=$bill['bid'];?>"><?=$bill['bill_title'];?></option>
         <?php  } ?>
     </select>
     <?php 
}

//get_paymentlist_in_display_payment

if($_GET['action'] == 'get_paymentlist_in_display_payment'){
   
     $bill_data = $obj->select('tbl_payment','*','bill_id',array($_POST['bid']));

      if($bill_data){ ?>
          <table class="table table-bordered">
              <tr>
              <th>SN</th>
        <th>Receipt No </th>
        <th>Billed Amount (Rs)</th>
        <th>Billed Date </th>
        <th>VAT</th>
        <th>TDS</th>
        <th>Received Amount (Rs)</th>

        <th>Check No</th>
        <th>Receipt Image</th>
        <th colspan="2">Action</th>
          
              </tr>
              <?php foreach($bill_data as $key=>$value) : ?>
                  <tr>
                  <td><?=++$key;?></td>
            <td><?=$value['receipt_no'];?></td>
            <td><?=$value['pay_amount'];?></td>
            <td><?=$value['pay_date'];?></td>
            <td><?=$value['vat_amount'];?></td>
            <td><?php 
            if(isset($_value['pay_amount']) || isset($value['with_tds'])){
            if($value['pay_amount'] == ''){
                $value['pay_amount'] = 0;
            }
                if($value['with_tds'] == ''){
                    $value['with_tds'] = 0;
                }
                if($value['pay_amount'] > $value['with_tds']){
                    $tds = $value['pay_amount'] - $value['with_tds'];
                    echo $tds;
                }
            
            }
            ?></td>
            <td><?php
            if(isset($value['with_tds']) || isset($value['vat_amount'])){

                if($value['with_tds'] == ''){
                    $value['with_tds'] = 0;
                }
                    if($value['vat_amount'] == ''){
                        $value['vat_amount'] = 0;
                    }                
                    $total = $value['with_tds'] + $value['vat_amount'];
                    echo $total;
                }
            
            ?></td>

           <td><?=$value['check_no'];?></td>
            <td><?php if($value['file'] !=''){ 
                
                $img = explode(',',$value['file']);
                if(sizeof($img)<2){ ?>
                    <a href="assets/payment/<?=$img[0];?>"><i class="fa fa-file-image fa-2x"></i></a>
               <?php  }else{ 
                   
                   for($l=0;$l<sizeof($img);$l++) { ?>
                       <a href="assets/payment/<?=$img[$l];?>"><i class="fa fa-file-image fa-2x"></i> <?=$l;?></a>
                     

                <?php } }        } else{
                 echo "N/A";   
                }?>

            </td>
            <td>
                <a href="<?=base_url('edit_payment'); ?>?pid=<?=$value['pid']."&org_id=".$_GET['org_id']."&bill_id=".$value['bill_id'];?>" class="btn btn-warning ">Edit</a>
            </td>
            <td>
                <a href="<?=base_url('display_payment'); ?>?action=delete&pid=<?=$value['pid'];?>" onclick="return confirm('Are you sure you want to delete this?')" class="btn btn-danger ">Delete</a>
            </td>
           
          
          
                  </tr>
                  <?php endforeach ; ?>
          </table>
          <?php }else{ ?>
          
             <div class="col-md-12"> <p style="color:red">No data found.</p></div>
          <?php } 
}

//display org- get due amount of bill
if($_GET['action'] == 'get_amount_from_display_org'){
   
  $paid=0;
    $bills = $obj->select("tbl_bill","*","bid",array($_POST['bid']));
    
    $paid = $obj->ArrQuery("SELECT SUM(with_tds  + vat_amount) as paid FROM `tbl_payment` WHERE bill_id =".$_POST['bid']);
         if($paid)
           {
            $paid = $paid[0]['paid'];
            $check_tds_status1 = $obj->select("tbl_payment","*","bill_id",array($_POST['bid']));
            if($check_tds_status1)
            {
             $check_tds_status1 = $check_tds_status1[0];
             if(isset($check_tds_status1['tds_status'])){
                 if($check_tds_status1['tds_status'] == 1){
                     $tds1 = ($check_tds_status1['pay_amount'] * 1.5)/100;
                     $paid = $paid - $tds1;
                 }
               
                
             }
            }
           }
            $payable = $bills[0]['bill_amount'];
           
            $payable_c = ($bills[0]['taxable_amount']+$bills[0]['vat_amount']);
           
            if($paid == NULL || $paid == '') $paid = 0;
            if($payable_c > $paid){
                $receivable = $bills[0]['taxable_amount']+$bills[0]['vat_amount'];
                $due = abs($receivable - $paid);
                $check_tds_status = $obj->select("tbl_payment","*","bill_id",array($_POST['bid']));
                if($check_tds_status)
                {
                 $check_tds_status = $check_tds_status[0];
                 if(isset($check_tds_status['tds_status'])){
                     if($check_tds_status['tds_status'] == 1){
                         $tds = ($check_tds_status['pay_amount'] * 1.5)/100;
                         $due = $due - $tds;
                     }
                   
                    
                 }
                }

                
                ?>
                 <label for="">Received Amount <br>
                (<span style="color:black">Total Billed : <?=$payable;?>|
                <span style="color:blue">Total Receivable : <?=($due);?>

                </span><span style="color:green">| Total Paid :  <?=$paid;?> |
                </span> <span style="color:red"> Due amount :  <?=($due-$bills[0]['vat_amount']);?></span> )
                 </label>
                 <input type="hidden" name="pay_amount" class="unlikable-pr unlikable temp_pay_amount" id="">
                <input type="hidden" class="vat_amount" value="<?=$bills[0]['vat_amount'];?>">
                 <input type="number" id="" step="any" name="with_tds" required class="form-control pay_amount" max="<?php if(isset($due)) echo ($due - $bills[0]['vat_amount']);?>" >
                <script>
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
                </script>
        <?php  
        }elseif($paid == $payable_c) { echo "Bill has been cleared.";die; } ?>
            
            <!-- <span style="color:green;" >  </span>  -->
       <?php  
}




}




?>

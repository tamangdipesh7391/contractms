<?php
if(isset($_GET['action'])){
    if($_GET['action'] =='active'){
        $status['status'] = 1;
        $obj->Update("tbl_contract",$status,"conid",array($_GET['id']));
echo "<script> window.location.href='".base_url('view_org_contract')."&org_id=".$_GET['org_id']."'</script>";


    }
    if($_GET['action'] =='inactive'){
        $status['status'] = 0;
         $obj->Update("tbl_contract",$status,"conid",array($_GET['id']));
echo "<script> window.location.href='".base_url('view_org_contract')."&org_id=".$_GET['org_id']."'</script>";


    }

}
$this_date = date('Y-m-d');

$org_data = $obj->select('tbl_contract','*','org_id',array($_GET['org_id']));


?>
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
    <?php 
if($org_data){ ?>
<table class="table table-bordered">
    <tr>
        <th>SN</th>
        <th> Contract Name</th>
        <th>Organization  </th>
        <th>Start Date </th>
        <th>End Date </th>
        <th>Amount (Rs) </th>
        <th>Commission (Rs) </th>

        <th>Method</th>
        <th>Contract Img</th>
        <th>Status</th>
        <th>Action </th>
        <th colspan="2">Bill </th>
        <th>Commission</th>

    </tr>
    <?php foreach($org_data as $key=>$value) { if($this_date < $value['end_date'] && $value['status'] == 1){ ?>
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
          
         
         
        //   if($sum >= 0){
        //       if($sum>365){
        //     $years = ($sum / 365) ; // days / 365 days
		// $years = floor($years); // Remove all decimals
        //       }
        //       if($sum>30){
		// $month = ($sum % 365) / 30; // I choose 30 for Month (30,31) ;)
		// $month = floor($month); // Remove all decimals
        //       }

		// $days = ($sum % 365) % 30; // the rest of days
        //    if($years > 0){
        //         if($years == 1){
        //             echo $years."Y-";
        //         }else{
        //             echo $years."Y-";

        //         }
        //     }
        //     if($month > 0){
        //         if($month == 1){
        //             echo $month."M-";
        //         }else{
        //             echo $month."M-";

        //         }
        //     }
        //     if($days > 0){
        //         if($days == 1){
        //             echo $days."Day left";
        //         }else{
        //             echo $days."Days left";

        //         }
        //     }
           
           
        //   }
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
                     

                <?php } }        }else{
                    echo"N/A";
                } ?></td>

            <td>
            <?php 
                if($value['status'] == 1){ ?>
                    <a href="<?=base_url('view_org_contract'); ?>?action=inactive&id=<?=$value['conid']."&org_id=".$_GET['org_id'];?>"><i class="fa fa-check-circle fa-2x" style="color:green"></i></a>
              <?php  }elseif($value['status'] == 0){ ?>
                <a href="<?=base_url('view_org_contract'); ?>?action=active&id=<?=$value['conid']."&org_id=".$_GET['org_id'];?>"><i class="fa fa-times-circle fa-2x" style="color:red"></i></a>
               <?php }
            ?>
            </td>
            <td>
                <a href="<?=base_url('edit_contract'); ?>?id=<?=$value['conid']."&org_id=".$_GET['org_id'];?>" class="btn btn-warning ">Edit</a>
            </td>
            <td>
                <a href="<?=base_url('add_contract_bill'); ?>?org_id=<?=$_GET['org_id'];?>&conid=<?=$value['conid'];?>" class="btn btn-primary "><i class="fa fa-plus"></i></a>
            </td>
            <td>
                <a href="<?=base_url('display_org_bill'); ?>?org_id=<?=$_GET['org_id'];?>&conid=<?=$value['conid'];?>" class="btn btn-primary "><i class="fa fa-eye"></i></a>
            </td>
            <td>
                <a href="<?=base_url('view_commission_details.php?action=individual_con&conid='.$value['conid']);?>" class="btn btn-info "><i class="fa fa-eye"></i></a>
            </td>
            

        </tr>
        <?php }}  ?>
</table>
<?php }else{ ?>
    <p style="color:red">No contracts found.</p>
<?php }

?>
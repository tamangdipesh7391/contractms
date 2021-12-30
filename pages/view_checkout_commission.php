<?php
$checkout_commissions_res = $obj->select("tbl_commission_checkout","*","com_id",array($_GET['id']));
if(isset($_GET['action']) && $_GET['action'] == 'delete')
{
     $obj->delete("tbl_commission_checkout","check_id",array($_GET['did']));
echo "<script> window.location.href='".base_url('view_checkout_commission&conid='.$_GET['conid'].'&id='.$_GET['id'])."'</script>";

}
if($checkout_commissions_res){
?>

<div class="col-md-12">
     <h1>Commission Payment History</h1>
     <table class="table table-bordered">
          <tr>
               <th>SN</th>
               <th>Contractor's Name</th>
               <th>Checkout Amount</th>
               <th>Date</th>
               <th>Action</th>
          </tr>
          <?php 
          foreach($checkout_commissions_res as $key=>$value) { ?>
                    <tr>
                         <td><?=++$key;?></td>
                         <td><?php
                         
                              $contractor_name = $obj->select("tbl_contract","*","conid",array($_GET['conid']));
                              $contractor_name = $contractor_name[0]['contractor'];
                              if($contractor_name != '' && $contractor_name != 0){
                                   $contractor = $obj->select("tbl_user","*","uid",array($contractor_name));
                                   echo ucfirst( $contractor[0]['username']);
                              }
                             
                              else
                              echo "N/A";
                         ?></td>
                         <td><?=$value['checkout_amount'];?></td>
                         <td><?=$value['checkout_date'];?></td>
                         <td>
                              <a href="" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                              <a href="<?=base_url('view_checkout_commission.php?action=delete&conid='.$_GET['conid'].'&id='.$_GET['id'].'&did='.$value['check_id']);?>" class="btn btn-danger"><i class="fa fa-trash" onclick="return confirm('Are you sure you want to delete this?');"></i></a>

                         </td>
                         
                    </tr>
         <?php  }
          ?>
     </table>
</div>
<?php }else{ ?>
<p class="text-center text-bold text-danger">No result found.</p>
<?php } ?>
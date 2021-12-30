<?php 
if(isset($_POST['submit']) && $_POST['submit'] == 'person_data'){
 unset($_POST['submit']);
  $obj->Insert("tbl_contact_person",$_POST);
  $_SESSION['success']  ="Contact person has been added successfully.";
echo "<script> window.location.href='".base_url('add_contact_person')."'</script>";


}
if(isset($_POST['submit']) && $_POST['submit'] == 'update'){
   unset($_POST['submit']);
    $obj->update("tbl_contact_person",$_POST,"cid",array($_GET['cid']));
    $_SESSION['success']  ="Contact person has been udpated successfully.";
  echo "<script> window.location.href='".base_url('add_contact_person')."'</script>";
  
  
  }
$org = $obj->select('tbl_organization','*','status',array(1));
$contacts = $obj->select("tbl_contact_person");
if(isset($_GET['action'])){
    if($_GET['action'] == 'delete'){
        $obj->delete("tbl_contact_person","cid",array($_GET['cid']));
echo "<script> window.location.href='".base_url('add_contact_person')."'</script>";

    }elseif($_GET['action'] == 'edit'){
$contacts = $obj->select("tbl_contact_person","*","cid",array($_GET['cid']));
$edit = $contacts[0];
    }
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
<form method="post" class="form-group">
    <h1>Add New Contact Person</h1>
    <label for="">Select Organization</label>
    <select name="org_id" id="" class="form-control">
        <option selected disabled>Select Organization</option>
        <?php  foreach($org as $value):?>
            <option value="<?=$value['oid'];?>" <?php if(isset($edit['org_id']) && $edit['org_id'] != ''){ if($edit['org_id'] == $value['oid']) { echo " selected ";} } ?>><?=$value['org_name'];?></option>
            <?php endforeach ; ?>

    </select>
    <label for="">Person's Name</label>
    <input type="text" name="c_name" class="form-control" value="<?php if(isset($edit['c_name']) && $edit['c_name'] != ''){ echo $edit['c_name']; }?>">
    <label for="">Person's Phone</label>
    <input type="text" class="form-control" name="c_phone" value="<?php if(isset($edit['c_phone']) && $edit['c_phone'] != ''){ echo $edit['c_phone']; }?>">
    <label for="">Person's Email</label>
    <input type="email" name="c_email" class="form-control" value="<?php if(isset($edit['c_email']) && $edit['c_email'] != ''){ echo $edit['c_email']; }?>">

   
  
    <button class="btn btn-info mt-3" type="submit " name="submit" <?php if(isset($_GET['action']) && $_GET['action'] == 'edit') { ?> value="update"<?php }else{ ?>value="person_data" <?php } ?>> <?php if(isset($_GET['action']) && $_GET['action'] == 'edit') { ?> Update<?php }else{ ?>Add <?php } ?></button>
</form>
</div>

<div class="col-md-12">
    <h1>Contact Person Details</h1>
    <table class="table table-bordered">
        <tr>
            <th>SN</th>
            <th>Organization</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th colspan="2">Action</th>
        </tr>
        <?php foreach($contacts as $key => $value){ ?>
            <tr>
                <td><?=++$key;?></td>
                <td><?php
                $orgname = $obj->select("tbl_organization","*","oid",array($value['org_id']));
                echo $orgname[0]['org_name'];
                ?></td>
                <td><?=$value['c_name']?></td>
                <td><?=$value['c_phone']?></td>
                <td><?=$value['c_email']?></td>
                <td>
                <a href="<?=base_url('add_contact_person'); ?>?action=edit&cid=<?=$value['cid'];?>" class="btn btn-warning "><i class="fa fa-edit"></i></a>

                </td>
                <td>
                <a href="<?=base_url('add_contact_person'); ?>?action=delete&cid=<?=$value['cid'];?>" class="btn btn-danger " onclick="return confirm('Are you sure you want to delete?')"><i class="fa fa-trash"></i></a>

                </td>
            </tr>
       <?php  } ?>
    </table>
</div>
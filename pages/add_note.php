<?php
if(isset($_GET['action'])){
     if($_GET['action'] == 'edit'){
     $noteData = $obj->select("tbl_notes","*","nid",array($_GET['nid']));
     $noteData = $noteData[0];
     }elseif($_GET['action'] == 'delete'){
          $obj->delete("tbl_notes","nid",array($_GET['nid']));
     $_SESSION['success'] = "Note deleted successfully";

    echo "<script> window.location.href='".base_url('add_note')."'</script>";

     }
}
if(isset($_POST['submit']) && $_POST['submit'] == 'add'){
     unset($_POST['submit']);
     $obj->insert("tbl_notes",$_POST);
     $_SESSION['success'] = "Note added successfully";
    echo "<script> window.location.href='".base_url('add_note')."'</script>";

}
if(isset($_POST['submit']) && $_POST['submit'] == 'update'){
     unset($_POST['submit']);
     $obj->update("tbl_notes",$_POST,"nid",array($_GET['nid']));
     $_SESSION['success'] = "Note updated successfully";
    echo "<script> window.location.href='".base_url('add_note')."'</script>";

}
$notes = $obj->select("tbl_notes");
?>



<div class="col-md-12">
<h1>Add Your Note Here</h1>

</div>
<div class="col-md-5">
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
     <form action="" method="post">
     <div class="form-group">
               <label for="">Title</label>
               <input type="text" name="note_title" class="form-control" value="<?php if(isset($noteData['note_title']) && $noteData['note_title'] != '') echo $noteData['note_title']; ?>">
          </div>
          <div class="form-group">
               <label for="">Your Message</label>
               <textarea name="note" id="" cols="30" rows="10" class="form-control"><?php if(isset($noteData['note_title']) && $noteData['note_title'] != '') echo $noteData['note_title']; ?></textarea>
          </div>
          <div class="form-group">
               <label for="">Date</label>
          <input class="form-control" type="date" name="date"  value="<?php if(isset($noteData['date']) && $noteData['date'] != '') echo $noteData['date']; else echo date('Y-m-d'); ?>">

          </div>
          <div class="form-group">
               <button class="btn btn-info" name="submit" <?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){?> value="update" <?php }else{?>value="add"<?php } ?>  type="submit"> <?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){?> Update <?php }else{?>Add<?php } ?></button>
          </div>
     </form>
</div>

<div class="col-md-12">
     <h1>All Notes</h1>
     <table class="table table-bordered">
          <tr>
               <th>SN</th>
               <th>Note Title</th>
               <th>Message</th>
               <th>Date</th>
               <th colspan="2">Action</th>
          </tr>
          <?php foreach($notes as $key => $notes){ ?>
               <tr <?php if(isset($_GET['action']) && $_GET['action'] == 'showall' && $_GET['nid'] == $notes['nid']) {?>style="box-shadow: 0px 0px 20px #2196f3;background:lightgreen
"<?php } ?>>
                    <td><?=++$key;?></td>
                    <td><?=$notes['note_title'];?></td>
                    <td class="text-justify"><?=$notes['note'];?></td>
                    <td><?=$notes['date'];?></td>
                    <td><a href="add_note.php?action=edit&nid=<?=$notes['nid'];?>" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a></td>
                    <td><a href="add_note.php?action=delete&nid=<?=$notes['nid'];?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this?')"><i class="fa fa-trash"></i></a></td>


               </tr>
          <?php } ?>
     </table>
</div>
<div style="height:50vh"></div>
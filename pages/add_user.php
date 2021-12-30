<?php 
if(isset($_POST['submit']) && $_POST['submit'] == 'user_data'){
  array_pop($_POST);
  $_POST['password'] = sha1($_POST['password']);
  $obj->Insert("tbl_user",$_POST);
  $_SESSION['success']  ="User has been created successfully.";
echo "<script> window.location.href='".base_url('add_user')."'</script>";


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
    <h1>Create User</h1>
   
    <label for="">Username</label>
    <input type="text" name="username" class="form-control">
    <label for="">Password</label>
    <input type="password" name="password" class="form-control">
    

    <button class="btn btn-info mt-3" type="submit " name="submit" value="user_data"> Add</button>
</form>
</div>
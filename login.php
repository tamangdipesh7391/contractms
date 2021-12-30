<?php 

require_once ("config/config.php");
require_once ("config/db.php");
if(isset($_POST['submit']) && $_POST['submit'] == 'login'){
     $user = $_POST['username'];
     $pass = md5($_POST['password']);
     
     $result =  $obj->ArrQuery("SELECT * FROM tbl_user WHERE username = '$user' AND password = '$pass' AND status = 1");
     if($result){
          $userData = $result[0];
          session_start();
          $_SESSION['is_auth'] = "valid";
          $_SESSION['user_type'] = $userData['user_type'];
          
  echo "<script> window.location.href='".base_url('index')."'</script>";

     }else{
          echo "<script> window.location.href='".base_url('login')."?error=true"."'</script>";

     }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Login Page</title>
     <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
<style>
     .login-container{
          display: flex;
          justify-content: center;
          align-items: center;
          height: 100vh;
     }
     .login-box{
          border: 1px solid #000;
padding:20px;
box-shadow: 0px 0px 26px #2196f3;

     }
     body{
          background: rgb(238,174,202);
background: radial-gradient(circle, rgba(238,174,202,1) 0%, rgba(148,187,233,1) 100%);
  height: 100vh;
  margin:0;
  padding:0;
     }
</style>
</head>
<body>
     

 


<div class="container">
     <div class="row">
          <div class="col-md-8 offset-md-2 login-container">
               <div class="row login-box">
               <?php if (isset($_GET['error']) && $_GET['error'] == true) { ?>
                    <div class="alert alert-danger" style="background:red;color:#fff;">
                        Invalid username or password !
                    </div>
                    <?php }  ?>
                    <div class="col-md-6">
                         <img class="img-fluid img-responsive" src="assets/img/login-page.jpg" alt="">
                    </div>
                    <div class="col-md-6">
                         <h2><i class="fa fa-lock"></i> Login Here</h2>
                         <form action="" method="post">
                              <label for="">Username</label>
                              <input type="text" name="username" class="form-control">
                              <label for="">Username</label>
                              <input type="password" name="password" class="form-control">
                              <br>
                              <button class="btn btn-info" type="submit" name="submit" value="login"> Login</button>
                         </form>
                    </div>

               </div>
          </div>
     </div>
     
</div>
</body>
</html>
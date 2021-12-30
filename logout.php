<?php 
require_once ("config/config.php");
require_once ("config/db.php");
session_start();
session_destroy();
echo "<script> window.location.href='".base_url('login')."'</script>";
?>

<?php
require_once ("config/config.php");
require_once ("config/db.php");
if(!isset($_SESSION['is_auth']) || $_SESSION['is_auth'] != 'valid')
// {
// 	echo "<script> window.location.href='".base_url('login')."'</script>";

// }




$url=isset($_GET['url']) ? $_GET['url'] :'home';

$url=str_replace('.php', '', $url);

$url.='.php';

$pagePath=root('pages/'.$url);

require_once root('includes/header.php');
 

if(file_exists($pagePath) && is_file($pagePath)){ 
	// print_r($_SESSION);
	if(isset($_SESSION['semester'])){
		
			
	
}
	require_once $pagePath;

	
}else {
	
	echo "<h1>Page not found 404</h1>";
}
require_once root('includes/footer.php');
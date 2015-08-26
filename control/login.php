<?php
$page = 'login';
require_once 'global.php';

if(isset($_GET['logout'])) {
	unset($_SESSION['cp_login']);
	$users->logout();
}

if(isset($_POST['uname'])) {
	$authenticate = $users->authenticate($_POST['uname'],$_POST['passw']);
	if($authenticate) {
		$fetch = $db->fetch('users',array('username','password'),array($_POST['uname'],$users->encrypt($_POST['passw'])));
		$_SESSION['cp_login'] = $fetch[0]['id'].'-'.$fetch[0]['password'];
		echo '<script>window.location="index.php";</script>';
		die();
	} else {
		$auth_failed = true;
		$smarty->assign('auth_failed',$auth_failed);
	}
}

$smarty->display('style/login.htm');
?>



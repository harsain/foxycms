<?php

$page = 'login';
require_once 'global.php';

if(isset($_GET['logout'])) {
	unset($_SESSION['cp_login']);
	$users->logout();
}

if(isset($_POST['uname'])) {
	$username = $_POST['uname'];
	$password = $_POST['passw'];
	$authenticate = $users->authenticate($username, $password);
	if($authenticate) {
		$fetch = $db->fetch('users',array('username','password'),array($username,$users->encrypt($password)));
		$_SESSION['cp_login'] = $fetch[0]['id'].'-'.$fetch[0]['password'];
		header('Location: index.php');
		die();
	} else {
		$auth_failed = true;
		$smarty->assign('auth_failed',$auth_failed);
	}
}

$smarty->display('style/login.htm');

?>

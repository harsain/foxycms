<?php
session_start();
/* ## Start security codes ## */
function secure($i) {
	return addslashes($i);
}
if(isset($_POST)) {
	foreach($_POST as $key=>$value) {
		if(!is_array($value)) $_POST[$key] = secure($value);
	}
}
if(isset($_GET)) {
	foreach($_GET as $key=>$value) {
		$_GET[$key] = secure($value);
	}
}
/* ## End security codes ## */

function read_file($path) {
	$filename = $path;
	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);
	return $contents;
}


// Includes.
require_once('../classes/databases.php');
require_once('../classes/languages.php');
require_once('../classes/modules.php');
require_once('../classes/users.php');
require_once('../classes/libs/Smarty.class.php');
require_once('../classes/templates.php');
require_once('../includes/db_info.php');

// Defines.
define('FOXY_VERSION', '0.11 alpha');
$time = time();

// Establishing database connection.
$db = new Databases;
switch($db_type) {
	case 'mysql':
		$db->connect('mysql',$db_host,$db_user,$db_pass,$db_name,$db_prefix);
		break;
	case 'sqlite':
		$db->connect('sqlite',$db_path);
		break;
}

// Setting global variables
$settings = $db->fetch('settings');
$settings = $settings[0];

// Establishing languages.
$lang = new Languages;

// Establishing modules.
$module_dev = new Modules;

// Establishing users.
$users = new Users;

// Establishing Smarty and templates.
$smarty = new Smarty;
$smarty->template_dir = 'style';
$smarty->compile_dir = '../cache';
$tmpl = new Templates;
$tmpl->check();

$smarty->assign('settings', $settings);
$smarty->assign('lang', $lang);
$smarty->assign('module_dev', $module_dev);
$smarty->assign('foxy_version', FOXY_VERSION);

//menu icons function
$smarty->registerPlugin('function', 'menu_icon', 'set_menu_icon');
function set_menu_icon($params, $smarty) {
	if (!empty($params['is_module']))
		$menu_icon = '';
	else 
		$menu_icon = "style=\"background-image: url('style/images/menu_icons/$params[icon]');background-repeat: no-repeat;background-position: 10px center;background-size:22px;\"";
	return $menu_icon;
}

$current_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$smarty->assign('current_url', $current_url);

$smarty->assign('date', date("d/m/Y",time()));

// Modules in Main menu
$modules = $db->fetch('modules');
$final_modules = array();
foreach($modules as $module) {
	if($users->check_priv($users->getlogin('id'),"module:$module[id]") AND $module['status'] == 1) {
		$final_modules[] = $module;
	}
}
$is_admin = $users->check_priv($users->getlogin('id'),'ADMIN');
$smarty->assign('is_admin',$is_admin);
$smarty->assign('cpmodules',$final_modules);


// Authentication
$user = array();
if(isset($_SESSION['cp_login'])) {
	$cp_login = $_SESSION['cp_login'];
	$cp_login = explode('-',$cp_login);
	$user = $cp_login[0];
	$cp_pass = $cp_login[1];
	$check = $db->num_rows('users',array('id','password'),array($user,$cp_pass));
	if($check > 0){
		$login = true;
		$user = $db->fetch('users',array('id','password'),array($user,$cp_pass));
		$user = $user[0];
	} else $login = false;
}

if(isset($login) AND $login !== true AND $page != 'login') {
	echo '<script>window.location="login.php";</script>';
	die();
}
$username = isset($user['username']) ? $user['username'] : '';
$smarty->assign('username', $username);

$current_url_minuspage = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$current_url_minuspage = preg_replace('/\&page=(.+)/','',$current_url_minuspage);
$current_url_minuspage = preg_replace('/\&page=/','',$current_url_minuspage);
$smarty->assign('current_url_minuspage', $current_url_minuspage);

?>

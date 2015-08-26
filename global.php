<?php

session_start();

/* ## Start security codes ## */
function secure($i) {
	return addslashes($i);
}
if(isset($_POST)) {
	foreach($_POST as $key=>$value) {
		$_POST[$key] = secure($value);
	}
}
if(isset($_GET)) {
	foreach($_GET as $key=>$value) {
		$_GET[$key] = secure($value);
	}
}
/* ## End security codes ## */

// Global functions
function read_file($path) {
	$filename = $path;
	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);
	return $contents;
}

// Includes.
require_once('classes/databases.php');
require_once('classes/languages.php');
require_once('classes/libs/Smarty.class.php');
require_once('classes/templates.php');
require_once('classes/modules.php');
require_once('classes/blocks.php');
require_once('includes/db_info.php');

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

// Establishing Smarty and templates.
$smarty = new Smarty;
$smarty->compile_dir = 'cache';
$smarty->assign('settings', $settings);
$tmpl = new Templates;
$smarty->assign('lang', $lang);

// Establishing modules
$module_dev = new Modules;
$smarty->assign('module_dev', $module_dev);

// Establishing blocks
$blocks = new Blocks;
$smarty->assign('blocks', $blocks);

// Defines
$time = time();

?>

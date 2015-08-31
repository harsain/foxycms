<?php

require 'global.php';

if (!$users->getlogin())
	header('Location: login.php');

$smarty->assign('root_path', str_replace('\control', '', dirname(__FILE__)));

$show = isset($_GET['show']) ? $_GET['show'] : '';
if($show == 'settings' OR $show == 'languages' OR $show == 'styles' OR $show == 'modules' OR $show == 'blocks' OR $show == 'users' OR $show == 'media_library') {
	if($users->check_priv($users->getlogin('id'),'ADMIN')) {
		define("RUN_PAGE", true);
	} else define("RUN_PAGE", false);
} elseif(empty($show) OR $show == 'home') {
	define("RUN_PAGE", true);
} else {
	$show_ex = explode('_', $show);
	if($show_ex[0] == 'module') {
		$module = $db->fetch('modules','var',$show_ex[1]);
		$module_id = $module[0]['id'];
		if($users->check_priv($users->getlogin('id'),"module:$module_id")) {
			define("RUN_PAGE", true);
		} else define("RUN_PAGE", false);
	} else define("RUN_PAGE", false);
}

if (empty($_GET['show']) OR $_GET['show'] == 'home') {
	include('pages/main.php');
} elseif (stripos($_GET['show'], 'module_') !== false) {
	$module_var = str_replace("/", '', $_GET['show']);
	$module_var = str_replace('module_', '', $module_var);
	if ($module_dev->get_admin($module_var)) {
		include($module_dev->get_admin($module_var));
	} else $tmpl->msg('this module may doesnt have an admin or it does not exists', '', 0);
} else {
	if (!include('pages/'.str_replace("/",'',$_GET['show']).'.php')) die('Error: 300, The page you have requested does not exist.');
}
ob_start();
$DOCUMENT_ROOT = isset($DOCUMENT_ROOT) ? $DOCUMENT_ROOT : '';
include($DOCUMENT_ROOT."menu_session.php");
$smarty->assign("menu_session", ob_get_contents());
ob_end_clean();
if(!isset($_GET['ajax'])) {
	$smarty->display('style/index.htm');
}

?>

<?php

if (RUN_PAGE !== true) die();

$action = isset($_GET['action']) ? $_GET['action'] : '';
switch ($action) {
	default:
		$languages = $db->fetch('languages');
		$smarty->assign('languages', $languages);
		$styles = $db->fetch('styles');
		$smarty->assign('styles', $styles);
		$smarty->assign('tmp_page', 'settings_edit.htm');
		break;
	case 'start_edit':
		$update = $db->update('settings', array('site_title','site_url', 'language', 'style', 'control_ajax', 'control_max'), array($_POST['site_title'],$_POST['site_url'], $_POST['language'], $_POST['style'], $_POST['control_ajax'], $_POST['control_max']));
		if ($update) $tmpl->msg('done', '?show=settings',1);
		else $tmpl->msg('error');
		break;
	
}

?>

<?php

require_once('global.php');

$tmpl->get_style_headers();

if (!empty($_GET['mod'])) {
	$modules = $db->fetch('modules', array('var', 'status'), array($_GET['mod'], 1));
	if ($module_dev->get($modules[0]['var'])) {
		define("RUN_PAGE", true);
		$smarty->assign('module_link', '?mod='.$modules[0]['var']);
		include($module_dev->get($modules[0]['var']));
	} else $tmpl->show_msg($lang->get_phrase('the_requested_module_doesnt_have_index'));
} else {
	echo 'asd';
}

$tmpl->get_style_footers();

?>

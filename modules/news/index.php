<?php

if (RUN_PAGE !== true) die();

switch ($_GET['show']) {
	default:
		$cats = $db->fetch('news_cats', 'parent_id', 0, 'order');
		$module_dev->assign('cats', $cats);
		$module_dev->display('news_show_categories');
		break;
	
	
}

?>

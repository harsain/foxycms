<?php
if (!RUN_PAGE) die();

$server_os = PHP_OS;

$php_version = phpversion();

$notes = $db->fetch('cp_notes');
$smarty->assign('notes',$notes);
$smarty->assign('database',$db_type);
$smarty->assign('server_os',$server_os);
$smarty->assign('php_version',$php_version);
$smarty->assign('tmp_page','main.htm');

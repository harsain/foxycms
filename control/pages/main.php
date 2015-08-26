<?php
if (!RUN_PAGE) die();
if(mb_ereg('Unix',$_SERVER['SERVER_SOFTWARE'])) $server_os = 'Linux';
if(mb_ereg('Win32',$_SERVER['SERVER_SOFTWARE'])) $server_os = 'Windows';

$php_version = phpversion();

$database = $db_type.' '.@mysql_get_server_info(); 

$notes = $db->fetch('cp_notes');
$smarty->assign('notes',$notes);
$smarty->assign('database',$database);
$smarty->assign('server_os',$server_os);
$smarty->assign('php_version',$php_version);
$smarty->assign('tmp_page','main.htm');

?>

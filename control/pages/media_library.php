<?php
if (RUN_PAGE !== true) die();

$action = isset($_GET['action']) ? $_GET['action'] : '';
switch($action) {
	default:
		$dirs_array = $db->fetch('uploads_dir',false,false,'id','ASC');
		
		$smarty->assign('dirs_array',$dirs_array);
		$smarty->display('style/media_library.htm');
		break;
	case 'delete_files_multi':
		$ids = $_GET['ids'];
		$final_ids = array();
		$explode = explode('|', $ids);
		foreach($explode as $id) {
			$db->delete('uploads','id',$id);
		}
		break;
	case 'move_files_multi':
		$new_dir = $_GET['new_dir'];
		$ids = $_GET['ids'];
		$final_ids = array();
		$explode = explode('|', $ids);
		foreach($explode as $id) {
			$db->update('uploads', 'dir', $new_dir, 'id', $id);
		}
		break;
}



?>

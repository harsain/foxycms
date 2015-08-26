<?php

if (RUN_PAGE !== true) die();

$action = isset($_GET['action']) ? $_GET['action'] : '';
switch ($action ) {
	default:
		$blocks = $db->fetch('blocks',false,false,'order','ASC');
		$smarty->assign('blocks', $blocks);
		$smarty->assign('tmp_page', 'blocks_show.htm');
		break;
	case 'add_block':
		$blocks_dir = opendir('../blocks');
		while(($file = readdir($blocks_dir)) !== false) {
			if ($file != '.' && $file != '..' && substr($file, -4) == '.php') $files[] = $file;
		}
		closedir($blocks_dir);
		$smarty->assign('files', $files);
		$smarty->assign('tmp_page', 'blocks_add.htm');
		break;
	case 'start_add_block':
		$title = $_POST['title'];
		$type = $_POST['type'];
		$html_content = $_POST['html_content'];
		$php_file = $_POST['php_file'];
		$place = $_POST['place'];
		$show_title = $_POST['show_title'];
		$status = $_POST['status'];
		
		if($type == 3) {
			$final_file = time().'_'.basename($_FILES['file']['name']);
			$target = '../blocks/'.$final_file; 
			move_uploaded_file($_FILES['file']['tmp_name'], $target);
			$php_file = $final_file;
			$type = 2;
		}
		
		$insert = $db->insert('blocks', array('title', 'type', 'html_content', 'php_file', 'place', 'show_title', 'status'), array($title, $type, $html_content, $php_file, $place, $show_title, $status));
		if ($insert) $tmpl->msg('done', '?show=blocks',1);
		else $tmpl->msg('error');
		break;
	case 'edit_block':
		$id = (int) $_GET['id'];
		$block = $db->fetch('blocks', 'id', $id);
		$smarty->assign('block', $block[0]);
		$blocks_dir = opendir('../blocks');
		while(($file = readdir($blocks_dir)) !== false) {
			if ($file != '.' && $file != '..' && substr($file, -4) == '.php') $files[] = $file;
		}
		closedir($blocks_dir);
		$smarty->assign('files', $files);
		$smarty->assign('tmp_page', 'blocks_edit.htm');
		break;
	case 'start_edit_block':
		$id = (int) $_POST['id'];
		$title = $_POST['title'];
		$type = $_POST['type'];
		$html_content = $_POST['html_content'];
		$php_file = $_POST['php_file'];
		$place = $_POST['place'];
		$show_title = $_POST['show_title'];
		$status = $_POST['status'];
		
		if($type == 3) {
			$final_file = time().'_'.basename($_FILES['file']['name']);
			$target = '../blocks/'.$final_file; 
			move_uploaded_file($_FILES['file']['tmp_name'], $target);
			$php_file = $final_file;
			$type = 2;
		}
		
		$update = $db->update('blocks', array('title', 'type', 'html_content', 'php_file', 'place', 'show_title', 'status'), array($title, $type, $html_content, $php_file, $place, $show_title, $status), 'id', $id);
		if ($update) $tmpl->msg('done', '?show=blocks',1);
		else $tmpl->msg('error');
		break;
	case 'delete_block':
		$id = (int) $_GET['id'];
		$delete = $db->delete('blocks', 'id', $id);
		if ($delete) $tmpl->msg('done', '?show=blocks',1);
		else $tmpl->msg('error');
		break;
	case 'delete_blocks_multi':
		$ids = $_GET['ids'];
		$final_ids = array();
		$explode = explode('|', $ids);
		foreach($explode as $id) {
			$final_ids[] = $id;
		}
		$db->multi_delete('blocks', 'id', $final_ids);
		break;
	case 'arrange_blocks':
		$blocks_p1 = $db->fetch('blocks','place','1','order','ASC');
		$blocks_p2 = $db->fetch('blocks','place','2','order','ASC');
		$blocks_p3 = $db->fetch('blocks','place','3','order','ASC');
		$blocks_p4 = $db->fetch('blocks','place','4','order','ASC');
		$blocks_p5 = $db->fetch('blocks','place','5','order','ASC');
		$blocks_p6 = $db->fetch('blocks','place','6','order','ASC');
		$blocks_p7 = $db->fetch('blocks','place','7','order','ASC');
		$smarty->assign('blocks_p1', $blocks_p1);
		$smarty->assign('blocks_p2', $blocks_p2);
		$smarty->assign('blocks_p3', $blocks_p3);
		$smarty->assign('blocks_p4', $blocks_p4);
		$smarty->assign('blocks_p5', $blocks_p5);
		$smarty->assign('blocks_p6', $blocks_p6);
		$smarty->assign('blocks_p7', $blocks_p7);
		$smarty->assign('tmp_page', 'blocks_arrange.htm');
		break;
}

?>

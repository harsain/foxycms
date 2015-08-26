<?php

if (RUN_PAGE !== true) die();

switch ($_GET['action']) {
	default:
		$links = $db->fetch('links', false, false, 'order', 'ASC');
		$module_dev->assign('links', $links);
		$module_dev->display_tmp('show.htm');
		break;
	case 'add_link':
		$menus = $db->fetch('blocks', 'php_file', 'links_menu.php');
		$module_dev->assign('menus', $menus);
		$module_dev->display_tmp('add.htm');
		break;
	case 'start_add_link':
		$title = $_POST['title'];
		$url = $_POST['url'];
		$menu_id = $_POST['menu_id'];
		$target = $_POST['target'];
		$order = $_POST['order'];
		$status = $_POST['status'];
		$insert = $db->insert('links', array('title', 'url', 'menu_id', 'target', 'order', 'status'), array($title, $url, $menu_id, $target, $order, $status));
		if ($insert) $module_dev->success_msg('done', '?show=module_links');
		else $module_dev->error_msg('error');
		break;
	case 'edit_link':
		$id = (int) $_GET['id'];
		$link = $db->fetch('links', 'id', $id);
		$module_dev->assign('link', $link[0]);
		$menus = $db->fetch('blocks', 'php_file', 'links_menu.php');
		$module_dev->assign('menus', $menus);
		$module_dev->display_tmp('edit.htm');
		break;
	case 'start_edit_link':
		$id = (int) $_POST['id'];
		$title = $_POST['title'];
		$url = $_POST['url'];
		$menu_id = $_POST['menu_id'];
		$target = $_POST['target'];
		$order = $_POST['order'];
		$status = $_POST['status'];
		$update = $db->update('links', array('title', 'url', 'menu_id', 'target', 'order', 'status'), array($title, $url, $menu_id, $target, $order, $status), 'id', $id);
		if ($update) $module_dev->success_msg('done', '?show=module_links');
		else $module_dev->error_msg('error');
		break;
	case 'delete_link':
		$id = (int) $_GET['id'];
		$delete = $db->delete('links', 'id', $id);
		if ($delete) $module_dev->success_msg('done', '?show=module_links');
		else $module_dev->error_msg('error');
		break;
	case 'move_up_link':
		$id = (int) $_GET['id'];
        $link = $db->fetch('links', 'id', $id);
        $prev_link = $db->fetch('links', '[<]order', $link[0]['order'], 'order', 'DESC', false, false, 1);
        if ($prev_link[0] != '') {
            $db->update('links', 'order', $prev_link[0]['order'], 'id', $id);
			$db->update('links', 'order', $link[0]['order'], 'id', $prev_link[0]['id']);
			$module_dev->success_msg('done', '?show=module_links');
        } else $module_dev->error_msg('error');
        break;
	case 'move_down_link':
		$id = (int) $_GET['id'];
        $link = $db->fetch('links', 'id', $id);
        $prev_link = $db->fetch('links', '[>]order', $link[0]['order'], 'order', 'DESC', false, false, 1);
        if ($prev_link[0] != '') {
            $db->update('links', 'order', $prev_link[0]['order'], 'id', $id);
			$db->update('links', 'order', $link[0]['order'], 'id', $prev_link[0]['id']);
			$module_dev->success_msg('done', '?show=module_links');
        } else $module_dev->error_msg('error');
        break;
}

?>

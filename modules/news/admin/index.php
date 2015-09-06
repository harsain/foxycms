<?php

if (RUN_PAGE !== true) die();

function cats_tree($id = 0, $parent_id = 0, $pre = '', $list = array()) {
	global $db;
	if ($id != 0) {
		$cats = $db->fetch('news_cats', array('[!]id', 'parent_id'), array($id, $parent_id), array('parent_id', 'order'));
		$num = $db->num_rows('news_cats', array('[!]id', 'parent_id'), array($id, $parent_id));
	} else {
		$cats = $db->fetch('news_cats', 'parent_id', $parent_id, array('parent_id', 'order'));
		$num = $db->num_rows('news_cats', 'parent_id', $parent_id);
	}
	$pre .= '- ';
	if (is_array($cats)) foreach($cats as $cat) {
		if ($num == 0 || $cat['parent_id'] == 0) $pre = '';
		$list[] = array('id' => $cat['id'], 'title' => $pre.$cat['title']);
		$list = cats_tree($id, $cat['id'], $pre, $list);
	}
	return $list;
}

switch ($_GET['action']) {
	default:
		$cat_id = (int) isset($_POST['cat_id']) ? $_POST['cat_id'] : 0;
		$module_dev->assign('cats', cats_tree());
		if (!empty($cat_id)) {
			$news = $db->fetch('news', 'cat_id', $cat_id, 'time', 'DESC');
			$module_dev->assign('news', $news);
		}
		$module_dev->display_tmp('news_show.htm');
		break;
	case 'add_news':
		$module_dev->assign('cats', cats_tree());
		$module_dev->display_tmp('news_add.htm');
		break;
	case 'start_add_news':
		$title = $_POST['title'];
		$sub_title = $_POST['sub_title'];
		$image = $_POST['image'];
		$author_id = $_POST['author_id'];
		$cat_id = $_POST['cat_id'];
		$summary = $_POST['summary'];
		$text = $_POST['text'];
		$time = $_POST['time'];
		$source = $_POST['source'];
		$comments = $_POST['comments'];
		$status = $_POST['status'];
		$insert = $db->insert('news', array('title', 'sub_title', 'image', 'author_id', 'cat_id', 'summary', 'text', 'time', 'source', 'comments', 'status'), array($title, $sub_title, $image, $author_id, $cat_id, $summary, $text, $time, $source, $comments, $status));
		if ($insert) $module_dev->success_msg('done', '?show=module_news');
		else $module_dev->error_msg('error');
		break;
	case 'edit_news':
		$id = (int) $_GET['id'];
		$news = $db->fetch('news', 'id', $id);
		$module_dev->assign('news', $news[0]);
		$module_dev->assign('cats', cats_tree());
		$module_dev->display_tmp('news_edit.htm');
		break;
	case 'start_edit_news':
		$id = (int) $_POST['id'];
		$title = $_POST['title'];
		$sub_title = $_POST['sub_title'];
		$image = $_POST['image'];
		$author_id = $_POST['author_id'];
		$cat_id = $_POST['cat_id'];
		$summary = $_POST['summary'];
		$text = $_POST['text'];
		$time = $_POST['time'];
		$source = $_POST['source'];
		$comments = $_POST['comments'];
		$status = $_POST['status'];
		$update = $db->update('news', array('title', 'sub_title', 'image', 'author_id', 'cat_id', 'summary', 'text', 'time', 'source', 'comments', 'status'), array($title, $sub_title, $image, $author_id, $cat_id, $summary, $text, $time, $source, $comments, $status), 'id', $id);
		if ($update) $module_dev->success_msg('done', '?show=module_news');
		else $module_dev->error_msg('error');
		break;
	case 'delete_news':
		$id = (int) $_GET['id'];
		$delete = $db->delete('news', 'id', $id);
		if ($delete) $module_dev->success_msg('done', '?show=module_news');
		else $module_dev->error_msg('error');
		break;
	case 'show_categories':
		$parent_id = (int) isset($_POST['parent_id']) ? $_POST['parent_id'] : 0;;
		$cats = $db->fetch('news_cats', 'parent_id', 0, 'order');
		$module_dev->assign('cats', $cats);
		$module_dev->assign('pcats', cats_tree());
		if (!empty($parent_id)) {
			$sub_cats = $db->fetch('news_cats', 'parent_id', $parent_id, 'order');
			$module_dev->assign('sub_cats', $sub_cats);
		}
		$module_dev->display_tmp('cats_show.htm');
		break;
	case 'add_category':
		$module_dev->assign('cats', cats_tree());
		$module_dev->display_tmp('cats_add.htm');
		break;
	case 'start_add_category':
		$title = $_POST['title'];
		$description = $_POST['description'];
		$parent_id = $_POST['parent_id'];
		$order = $_POST['order'];
		$status = $_POST['status'];
		$insert = $db->insert('news_cats', array('title', 'description', 'parent_id', 'order', 'status'), array($title, $description, $parent_id, $order, $status));
		if ($insert) $module_dev->success_msg('done', '?show=module_news&action=show_categories');
		else $module_dev->error_msg('error');
		break;
	case 'edit_category':
		$id = (int) $_GET['id'];
		$cat = $db->fetch('news_cats', 'id', $id);
		$module_dev->assign('cat', $cat[0]);
		$module_dev->assign('cats', cats_tree($cat[0]['id']));
		$module_dev->display_tmp('cats_edit.htm');
		break;
	case 'start_edit_category':
		$id = (int) $_POST['id'];
		$title = $_POST['title'];
		$description = $_POST['description'];
		$parent_id = $_POST['parent_id'];
		$order = $_POST['order'];
		$status = $_POST['status'];
		$update = $db->update('news_cats', array('title', 'description', 'parent_id', 'order', 'status'), array($title, $description, $parent_id, $order, $status));
		if ($update) $module_dev->success_msg('done', '?show=module_news&action=show_categories');
		else $module_dev->error_msg('error');
		break;
	case 'delete_category':
		$id = (int) $_GET['id'];
		$delete = $db->delete('news', 'id', $id);
		if ($delete) $module_dev->success_msg('done', '?show=module_news&action=show_categories');
		else $module_dev->error_msg('error');
		break;
	case 'move_up_category':
		$id = (int) $_GET['id'];
        $cat = $db->fetch('news_cats', 'id', $id);
        $prev_cat = $db->fetch('news_cats', array('[<]order', 'parent_id'), array($cat[0]['order'], $cat[0]['parent_id']), 'order', 'DESC', false, false, 1);
        if ($prev_cat[0] != '') {
            $db->update('news_cats', 'order', $prev_cat[0]['order'], 'id', $id);
			$db->update('news_cats', 'order', $cat[0]['order'], 'id', $prev_cat[0]['id']);
			$module_dev->success_msg('done', '?show=module_news&action=show_categories');
        } else $module_dev->error_msg('error');
        break;
	case 'move_down_category':
		$id = (int) $_GET['id'];
        $cat = $db->fetch('news_cats', 'id', $id);
        $next_cat = $db->fetch('news_cats', array('[>]order', 'parent_id'), array($cat[0]['order'], $cat[0]['parent_id']), 'order', 'DESC', false, false, 1);
        if ($next_cat[0] != '') {
            $db->update('news_cats', 'order', $next_cat[0]['order'], 'id', $id);
			$db->update('news_cats', 'order', $cat[0]['order'], 'id', $next_cat[0]['id']);
			$module_dev->success_msg('done', '?show=module_news&action=show_categories');
        } else $module_dev->error_msg('error');
        break;
}

?>

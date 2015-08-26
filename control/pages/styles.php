<?php

if (RUN_PAGE !== true) die();

$action = isset($_GET['action']) ? $_GET['action'] : '';
switch ($action) {
	default:
		$page = isset($_GET['page']) ? $_GET['page'] : '';
		$styles = $db->fetch('styles',false,false,'id','DESC',$page,$settings['control_max']);
		$pages_array = $db->pages_array('styles',false,false,$page,$settings['control_max']);
		$smarty->assign('styles', $styles);
		$smarty->assign('pages_array', $pages_array);
		$smarty->assign('current_page', $page);
		$smarty->assign('tmp_page', 'styles_show.htm');
		break;
	case 'add_style':
		$smarty->assign('tmp_page', 'styles_add.htm');
		break;
	case 'start_add_style':
		$code = $_POST['code'];
		$name = $_POST['name'];
		$status = $_POST['status'];
		$insert = $db->insert('styles', array('style_code', 'style_name', 'status', 'version', 'designer_name', 'designer_email'), array($code, $name, $status, $_POST['version'], $_POST['designer_name'], $_POST['designer_email']));
		if ($insert) $tmpl->msg('done', '?show=styles',1);
		else $tmpl->msg('error');
		break;
	case 'edit_style':
		$id = (int) $_GET['id'];
		$style = $db->fetch('styles', 'id', $id);
		$smarty->assign('style', $style[0]);
		$smarty->assign('tmp_page', 'styles_edit.htm');
		break;
	case 'start_edit_style':
		$id = (int) $_POST['id'];
		$code = $_POST['code'];
		$name = $_POST['name'];
		$status = $_POST['status'];
		$update = $db->update('styles', array('style_code', 'style_name', 'status', 'version', 'designer_name', 'designer_email'), array($code, $name, $status, $_POST['version'], $_POST['designer_name'], $_POST['designer_email']), 'id', $id);
		if ($update) $tmpl->msg('done', '?show=styles',1);
		else $tmpl->msg('error');
		break;
	case 'delete_style':
		$id = (int) $_GET['id'];
		$delete = $db->delete('styles', 'id', $id);
		if ($delete) $tmpl->msg('done', '?show=styles',1);
		else $tmpl->msg('error');
		break;
	case 'show_templates':
		$style_id = (int) $_GET['style_id'];
		$page = isset($_GET['page']) ? $_GET['page'] : '';
		if (!empty($style_id)) {
			$templates = $db->fetch('templates', 'style_id', $style_id,'id','DESC',$page,$settings['control_max']);
			$pages_array = $db->pages_array('templates', 'style_id', $style_id,$page,$settings['control_max']);
		} else $templates = $db->fetch('templates');
		$smarty->assign('style_id', $style_id);
		$smarty->assign('templates', $templates);
		$smarty->assign('current_page', $page);
		$smarty->assign('pages_array', $pages_array);
		$smarty->assign('tmp_page', 'templates_show.htm');
		break;
	case 'search_templates':
		$style_id = (int) $_GET['style_id'];
		$q = $_GET['q'];
		$page = isset($_GET['page']) ? $_GET['page'] : '';
		if (!empty($style_id)) {
			$templates = $db->fetch('templates', array('style_id','[%]template'), array($style_id,$q),'id','DESC',$page,$settings['control_max']);
			$pages_array = $db->pages_array('templates', array('style_id','[%]template'), array($style_id,$q),$page,$settings['control_max']);
		} else $templates = $db->fetch('templates');
		$smarty->assign('q', $q);
		$smarty->assign('style_id', $style_id);
		$smarty->assign('templates', $templates);
		$smarty->assign('current_page', $page);
		$smarty->assign('pages_array', $pages_array);
		$smarty->assign('tmp_page', 'templates_show.htm');
		break;
	case 'add_template':
		$styles = $db->fetch('styles');
		$smarty->assign('styles', $styles);
		$modules = $db->fetch('modules');
		$smarty->assign('modules', $modules);
		$smarty->assign('tmp_page', 'templates_add.htm');
		break;
	case 'start_add_template':
		$template = $_POST['template'];
		$style_id = $_POST['style_id'];
		$source = $_POST['source'];
		$owner = $_POST['owner'];
		$add = $tmpl->add_template($template, $source, $style_id, $owner);
		if ($add) $tmpl->msg('done', '?show=styles&action=show_templates&style_id='.$style_id);
		else $tmpl->msg('error');
		break;
	case 'edit_template':
		$id = $_GET['id'];
		$styles = $db->fetch('styles');
		$smarty->assign('styles', $styles);
		$modules = $db->fetch('modules');
		$smarty->assign('modules', $modules);
		$template = $db->fetch('templates', 'id', $id);
		$smarty->assign('template', $template[0]);
		$smarty->assign('tmp_page', 'templates_edit.htm');
		break;
	case 'start_edit_template':
		$id = (int) $_POST['id'];
		$style_id = $_POST['style_id'];
		$source = $_POST['source'];
		$owner = $_POST['owner'];
		$update = $tmpl->update_template($id, $source, $style_id, $owner);
		if ($update) $tmpl->msg('done', '?show=styles&action=show_templates&style_id='.$style_id,1);
		else $tmpl->msg('error');
		break;
	case 'delete_template':
		$id = (int) $_GET['id'];
		$delete = $tmpl->delete_template($id);
		if ($delete) $tmpl->msg('done', '?show=styles&action=show_templates',1);
		else $tmpl->msg('error');
		break;
	case 'edit_css':
		$id = (int) $_GET['id'];
		$style = $db->fetch('styles', 'id', $id);
		$smarty->assign('style', $style[0]);
		$smarty->assign('tmp_page', 'styles_css_edit.htm');
		break;
	case 'start_edit_css':
		$id = (int) $_POST['id'];
		$css = $_POST['css'];
		$update = $db->update('styles', 'css', $css, 'id', $id);
		if ($update) $tmpl->msg('done', '?show=styles',1);
		else $tmpl->msg('error');
		break;
	case 'export_style':
		$id = (int) $_GET['id'];
		$download_link = $tmpl->style_export($id);
		$style = $db->fetch('styles','id',$id);
		$style = $style[0];
		$smarty->assign('style', $style);
		$smarty->assign('download_link', $download_link);
		$smarty->assign('tmp_page', 'style_export.htm');
		break;
	case 'import_style':
		$smarty->assign('tmp_page', 'styles_import.htm');
		break;
	case 'start_import_style':
		$tmpName = $_FILES['file']['tmp_name'];
		$import = $tmpl->style_import($tmpName);
		if ($import) $tmpl->msg('done', '?show=styles',1);
		else $tmpl->msg('error');
		break;
	case 'delete_styles_multi':
		$ids = $_GET['ids'];
		$final_ids = array();
		$explode = explode('|', $ids);
		foreach($explode as $id) {
			$final_ids[] = $id;
		}
		$db->multi_delete('styles', 'id', $final_ids);
		break;
	case 'delete_templates_multi':
		$ids = $_GET['ids'];
		$final_ids = array();
		$explode = explode('|', $ids);
		foreach($explode as $id) {
			$final_ids[] = $id;
		}
		$db->multi_delete('templates', 'id', $final_ids);
		break;
}

?>

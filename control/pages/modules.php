<?php

if (RUN_PAGE !== true) die();

$action = isset($_GET['action']) ? $_GET['action'] : '';
switch($action) {
	default:
		$page = isset($_GET['page']) ? $_GET['page'] : '';
		$modules = $db->fetch('modules',false,false,'id','DESC',$page,$settings['control_max']);
		$pages_array = $db->pages_array('modules',false,false,$page,$settings['control_max']);
		$smarty->assign('modules', $modules);
		$smarty->assign('pages_array', $pages_array);
		$smarty->assign('current_page', $page);
		$smarty->assign('tmp_page', 'modules_show.htm');
		break;
	case 'show_details':
		$module_id = (int) $_GET['module_id'];
		$module = $db->fetch('modules','id',$module_id);
		$module = $module[0];
		if($module['status'] == 1) $module_status = $lang->get_phrase('enabled'); else $module_status = $lang->get_phrase('disabled');
		$smarty->assign('module', $module);
		$smarty->assign('module_status', $module_status);
		$smarty->assign('tmp_page', 'module_details.htm');
		break;
	case 'delete_module':
		$module_id = (int) $_GET['id'];
		$delete = $module_dev->delete($module_id);
		if ($delete) $tmpl->msg('done', '?show=modules',1);
		else $tmpl->msg('error');
		break;
	case 'edit_module':
		$module_id = (int) $_GET['id'];
		$module = $db->fetch('modules','id',$module_id);
		$module = $module[0];
		$smarty->assign('module', $module);
		$smarty->assign('tmp_page', 'module_edit.htm');
		break;
	case 'start_edit_module':
		$module_id = (int) $_POST['id'];
		$edit = $db->update("modules",array('name','version','var','programmer_name','programmer_email'),
		array($_POST['name'],$_POST['version'],$_POST['var'],$_POST['programmer_name'],$_POST['programmer_email']),
		'id',$module_id);
		if ($edit) $tmpl->msg('done', '?show=modules',1);
		else $tmpl->msg('error');
		break;
	case 'export_module':
		$module_id = (int) $_GET['id'];
		$module = $db->fetch('modules','id',$module_id);
		$module = $module[0];
		
		$download_link = $module_dev->export($module_id);
		$smarty->assign('download_link', $download_link);
		$smarty->assign('module', $module);
		$smarty->assign('tmp_page', 'module_export.htm');
		break;
	case 'import_module':
		$smarty->assign('tmp_page', 'modules_import.htm');
		break;
	case 'start_import_module':

		$tmpName = $_FILES['file']['tmp_name'];
		$import = $module_dev->import($tmpName);
		if ($import) $tmpl->msg('done', '?show=modules',1);
		else $tmpl->msg('error');
		break;
	case 'create_module':
		$directories = array();
		foreach(glob('../modules/*', GLOB_ONLYDIR) as $dir) {
			$dir = str_replace('../modules/','',$dir);
			$directories[] = $dir;
		}
		$smarty->assign('directories', $directories);
		$smarty->assign('tmp_page', 'module_create.htm');
		break;
	case 'start_create_module':
		$create = $db->insert('modules',array('var','directory','status','name','version','programmer_name','programmer_email'),
		array($_POST['var'],$_POST['directory'],'1',$_POST['name'],$_POST['version'],$_POST['programmer_name'],$_POST['programmer_email']));
		if ($create) $tmpl->msg('done', '?show=modules',1);
		else $tmpl->msg('error');
		break;
	case 'delete_modules_multi':
		$ids = $_GET['ids'];
		$final_ids = array();
		$explode = explode('|', $ids);
		
		$module_class = new Modules;
		foreach($explode as $id) {
			$module_class->delete($id);
		}
		break;
	case 'toggle_status':
		$id = (int) $_GET['id'];
		$this_mod = $db->fetch('modules','id',$id); $this_mod = $this_mod[0];
		if($this_mod['status'] == 1) $set_status = 0;
		else $set_status = 1;
		
		$update = $db->update('modules', 'status', $set_status, 'id', $id);
		if($update) $tmpl->msg('done', '?show=modules',1);
		else $tmpl->msg('error');
		break;
}

?>

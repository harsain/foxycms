<?php
if (RUN_PAGE !== true) die();

$action = isset($_GET['action']) ? $_GET['action'] : '';
switch($action) {
	case 'manage_fields':
		$page = isset($_GET['page']) ? $_GET['page'] : '';
		$fields = $db->fetch('users_fields',false,false,'id','DESC',$page,$settings['control_max']);
		$pages_array = $db->pages_array('users_fields',false,false,$page,$settings['control_max']);
		$smarty->assign('fields', $fields);
		$smarty->assign('pages_array', $pages_array);
		$smarty->assign('current_page', $page);
		$smarty->assign('tmp_page', 'fields_show.htm');
		break;
	case 'add_field':
		$smarty->assign('tmp_page', 'fields_add.htm');
		break;
	case 'start_add_field':
		$add = $users->add_field($_POST['name'],$_POST['phrase'],$_POST['type'],$_POST['value']);
		if ($add) $tmpl->msg('done', '?show=users&action=manage_fields',1);
		else $tmpl->msg('error');
		break;
	case 'edit_field':
		$field = $db->fetch('users_fields','id',intval($_GET['id']));
		$field = $field[0];
		$smarty->assign('field', $field);
		$smarty->assign('tmp_page', 'fields_edit.htm');
		break;
	case 'start_edit_field':
		$update = $db->update('users_fields',array('field_name','field_type','field_value','phrase'),
			array($_POST['name'],$_POST['type'],$_POST['value'],$_POST['phrase']),'id',$_POST['id']);
		if ($update) $tmpl->msg('done', '?show=users&action=manage_fields',1);
		else $tmpl->msg('error');
		break;
	case 'delete_field':
		$delete = $users->delete_field($_GET['name']);
		if ($delete) $tmpl->msg('done', '?show=users&action=manage_fields',1);
		else $tmpl->msg('error');
	case 'add_user':
		$fields = $db->fetch('users_fields',false,false,'id','ASC');
		$users_groups = $db->fetch('users_groups',false,false,'id','ASC');
		$smarty->assign('fields', $fields);
		$smarty->assign('users_groups', $users_groups);
		$smarty->assign('tmp_page', 'users_add.htm');
		break;
	case 'start_add_user':
		$fields = array(); $values = array();
		foreach($_POST as $field=>$value) {
			$fields[] = $field; $values[] = $value;
		}
		$add = $users->register($fields,$values);
		if ($add) $tmpl->msg('done', '?show=users&action=manage_fields',1);
		else $tmpl->msg('error');
		break;
	case 'manage_users':
		$current_page = isset($_GET['page']) ? $_GET['page'] : '';
		$latest_users = $db->fetch('users',false,false,'id','DESC',$current_page,$settings['control_max']);
		$pages_array = $db->pages_array('users',false,false,$current_page,$settings['control_max']);
		$smarty->assign('latest_users', $latest_users);
		$smarty->assign('pages_array', $pages_array);
		$smarty->assign('current_page', $current_page);
		$smarty->assign('tmp_page', 'users_manage.htm');
		break;
	case 'search_users':
		$q = $_GET['q'];
		$current_page = isset($_GET['page']) ? $_GET['page'] : '';
		$latest_users = $db->fetch('users','[%]username',$q,'id','DESC',$current_page,$settings['control_max']);
		$pages_array = $db->pages_array('users','[%]username',$q,$current_page,$settings['control_max']);
		$smarty->assign('latest_users', $latest_users);
		$smarty->assign('pages_array', $pages_array);
		$smarty->assign('q', $q);
		$smarty->assign('current_page', $current_page);
		$smarty->assign('tmp_page', 'users_manage.htm');
		break;
	case 'edit_user':
		$id = (int) $_GET['id'];
		$fields = $db->fetch('users_fields',false,false,'id','ASC');
		$user = $db->fetch('users','id',$id);
		$user = $user[0];
		$users_groups = $db->fetch('users_groups',false,false,'id','ASC');
		$smarty->assign('user', $user);
		$smarty->assign('users_groups', $users_groups);
		$smarty->assign('fields', $fields);
		$smarty->assign('tmp_page', 'users_edit.htm');
		break;
	case 'start_edit_user':
		$id = (int) $_GET['id'];
		$fields = array(); $values = array();
		foreach($_POST as $field=>$value) {
			$fields[] = $field; $values[] = $value;
		}
		$update = $users->update($id,$fields,$values);
		if ($update) $tmpl->msg('done', '?show=users&action=manage_users',1);
		else $tmpl->msg('error');
		break;
		break;
	case 'users_groups':
		$users_groups = $db->fetch('users_groups',false,false,'id','DESC');
		$smarty->assign('users_groups', $users_groups);
		$smarty->assign('tmp_page', 'users_groups.htm');
		break;
	case 'add_group':
		$modules = $db->fetch('modules');
		$smarty->assign('modules',$modules);
		$smarty->assign('tmp_page','users_groups_add.htm');
		break;
	case 'start_add_group':
		$priv_part1 = "[$_POST[level]]";
		if($priv_part1 == '[MOD]') {
			$priv_part2 = '';
			foreach($_POST['module'] as $module) {
				$priv_part2 .= "(module:$module)";
			}
		}
		$final_priv = "$priv_part1|$priv_part2";
		$insert = $db->insert('users_groups',array('name','privileges'),array($_POST['name'],$final_priv));
		if ($insert) $tmpl->msg('done', '?show=users&action=users_groups',1);
		else $tmpl->msg('error');
		break;
	case 'delete_group':
		$delete = $users->delete_group($_GET['id']);
		if ($delete) $tmpl->msg('done', '?show=users&action=users_groups',1);
		else $tmpl->msg('error');
		break;
	case 'edit_group':
		$id = (int) $_GET['id'];
		$group = $db->fetch('users_groups','id',$id);
		$group = $group[0];
		
		$priv_fetch = explode('|', $group['privileges']);
		$priv_part1 = $priv_fetch[0];
		
		$modules = $db->fetch('modules');
		
		$priv_part2 = array();
		
		foreach($modules as $module) {
			if(mb_ereg('(module:'.$module['id'].')', $priv_fetch[1])) {
				$priv_part2[$module['id']] = true;
			}
		}
				
		$smarty->assign('group',$group);
		$smarty->assign('priv_part1',$priv_part1);
		$smarty->assign('priv_part2',$priv_part2);
		$smarty->assign('modules',$modules);
		$smarty->assign('tmp_page','users_groups_edit.htm');
		break;
	case 'start_edit_group':
		$id = (int) $_GET['id'];
		$priv_part1 = "[$_POST[level]]";
		if($priv_part1 == '[MOD]') {
			$priv_part2 = '';
			foreach($_POST['module'] as $module) {
				$priv_part2 .= "(module:$module)";
			}
		}
		$final_priv = "$priv_part1|$priv_part2";
		$update = $db->update('users_groups',array('name','privileges'),array($_POST['name'],$final_priv),'id',$id);
		if ($update) $tmpl->msg('done', '?show=users&action=users_groups');
		else $tmpl->msg('error');
		break;
	case 'toggle_status':
		$id = (int) $_GET['id'];
		$this_user = $db->fetch('users','id',$id); $this_user = $this_user[0];
		if($this_user['status'] == 1) $set_status = 0;
		else $set_status = 1;
		
		$update = $db->update('users', 'status', $set_status, 'id', $id);
		if($update) $tmpl->msg('done', '?show=users&action=manage_users',1);
		else $tmpl->msg('error');
		break;
}

?>

<?php

if (RUN_PAGE !== true) die();

$action = isset($_GET['action']) ? $_GET['action'] : '';
switch ($action) {
	default:
		$page = isset($_GET['page']) ? $_GET['page'] : '';
		$languages = $db->fetch('languages',false,false,'id','DESC',$page,$settings['control_max']);
		$pages_array = $db->pages_array('languages',false,false,$page,$settings['control_max']);
		
		$smarty->assign('pages_array', $pages_array);
		$smarty->assign('current_page', $page);
		
		$smarty->assign('languages', $languages);
		$smarty->assign('tmp_page', 'languages_show.htm');
		break;
	case 'add_language':
		$xml = simplexml_load_file('data/charsets.xml');
		$smarty->assign("charsets", $xml);
		$smarty->assign('tmp_page', 'languages_add.htm');
		break;
	case 'start_add_language':
		$code = $_POST['code'];
		$name = $_POST['name'];
		$dir = $_POST['dir'];
		$charset = $_POST['charset'];
		$insert = $db->insert('languages', array('lang_code', 'lang_name','dir','charset'), array($code, $name,$dir,$charset));
		if ($insert) $tmpl->msg('done', '?show=languages',1);
		else $tmpl->msg('error');
		break;
	case 'edit_language':
		$id = (int) $_GET['id'];
		$language = $db->fetch('languages', 'id', $id);
		$xml = simplexml_load_file('data/charsets.xml');
		$smarty->assign("charsets", $xml);
		$smarty->assign('language', $language[0]);
		$smarty->assign('tmp_page', 'languages_edit.htm');
		break;
	case 'start_edit_language':
		$id = (int) $_POST['id'];
		$code = $_POST['code'];
		$name = $_POST['name'];
		$dir = $_POST['dir'];
		$charset = $_POST['charset'];
		$update = $db->update('languages', array('lang_code', 'lang_name','dir', 'charset'), array($code, $name, $dir, $charset), 'id', $id);
		if ($update) $tmpl->msg('done', '?show=languages',1);
		else $tmpl->msg('error');
		break;
	case 'delete_language':
		$id = (int) $_GET['id'];
		$delete = $db->delete('languages', 'id', $id);
		if ($delete) $tmpl->msg('done', '?show=languages',1);
		else $tmpl->msg('error');
		break;
	case 'show_phrases':
		$lang_id = (int) isset($_GET['lang_id']) ? $_GET['lang_id'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : '';
		if (!empty($lang_id)) {
			$phrases = $db->fetch('phrases', 'lang_id', $lang_id, 'id', 'DESC', $page, $settings['control_max']);
			$pages_array = $db->pages_array('phrases', 'lang_id', $lang_id, $page, $settings['control_max']);
		} else {
			$phrases = $db->fetch('phrases',false,false,'id','DESC',$page, $settings['control_max']);
			$pages_array = $db->pages_array('phrases',false,false,$page, $settings['control_max']);
		}
		$smarty->assign('phrases', $phrases);
		$smarty->assign('pages_array', $pages_array);
		$smarty->assign('current_page', $page);
		$smarty->assign('tmp_page', 'phrases_show.htm');
		break;
	case 'search_phrases':
		$lang_id = (int) $_GET['lang_id'];
		$page = isset($_GET['page']) ? $_GET['page'] : '';
		if(isset($_POST['q'])) $q = $_POST['q']; elseif(isset($_GET['q'])) $q = $_GET['q'];
		if (!empty($lang_id)) {
			$phrases = $db->fetch('phrases', array('lang_id','[%]var'), array($lang_id,$q), 'id', 'DESC', $page, $settings['control_max']);
			$pages_array = $db->pages_array('phrases', '[%]var', $q, $page, $settings['control_max']);
		} else {
			$phrases = $db->fetch('phrases','[%]var',$q,'id','DESC',$page, $settings['control_max']);
			$pages_array = $db->pages_array('phrases','[%]var',$q,$page, $settings['control_max']);
		}
		$smarty->assign('phrases', $phrases);
		$smarty->assign('pages_array', $pages_array);
		$smarty->assign('q', $q);
		$smarty->assign('current_page', $page);
		$smarty->assign('tmp_page', 'phrases_show.htm');
		break;
	case 'add_phrase':
		$languages = $db->fetch('languages');
		$smarty->assign('languages', $languages);
		$modules = $db->fetch('modules');
		$smarty->assign('modules', $modules);
		$smarty->assign('tmp_page', 'phrases_add.htm');
		break;
	case 'start_add_phrase':
		foreach($_POST['var'] as $key=>$var) {
			$var = $var;
			$lang_id = $_POST['lang_id'][$key];
			$text = $_POST['text'][$key];
			$owner = $_POST['owner'][$key];
			$add = $lang->add_phrase($var, $text, $lang_id, $owner);
		}		
		if ($add) $tmpl->msg('done', '?show=languages&action=show_phrases&lang_id='.$lang_id,1);
		else $tmpl->msg('error');
		break;
	case 'start_edit_phrases':
		foreach($_POST['id'] as $key=>$id) {
			$var = $_POST['var'][$key];
			$lang_id = $_POST['lang_id'][$key];
			$text = $_POST['text'][$key];
			$owner = $_POST['owner'][$key];
			$update = $lang->update_phrase($id, $text, $lang_id, $owner);
		}
		if ($update) $tmpl->msg('done', '?show=languages&action=show_phrases&lang_id='.$lang_id,1);
		else $tmpl->msg('error');
		break;
	case 'edit_phrase':
		$explode = explode('|', $_GET['ids']);
		$phrases_list = array();
		foreach($explode as $id) {
			if(!empty($id)) {
				$its_fetch = $db->fetch('phrases','id',$id);
				$phrases_list[] = $its_fetch[0];
			}
		}
		$modules = $db->fetch('modules');
		$languages = $db->fetch('languages');
		$smarty->assign('languages', $languages);
		$smarty->assign('modules', $modules);
		$smarty->assign('phrases_list', $phrases_list);
		$smarty->assign('tmp_page', 'phrases_edit.htm');
		break;
	case 'delete_phrase':
		$id = (int) $_GET['id'];
		$delete = $lang->delete_phrase($id);
		if ($delete) $tmpl->msg('done', '?show=languages&action=show_phrases');
		else $tmpl->msg('error');
		break;
	case 'export_language':
		$id = (int) $_GET['id'];
		$language = $db->fetch('languages', 'id', $id);
		$smarty->assign('language', $language[0]);
		$smarty->assign('tmp_page', 'languages_export.htm');
		break;
	case 'start_export_language':
		$id = (int) $_POST['id'];
		$language = $db->fetch('languages', 'id', $id);
		$export = $lang->export($id,$_POST['name'],$_POST['code']);
		$smarty->assign('language', $language[0]);
		$smarty->assign('export', htmlspecialchars($export));
		$smarty->assign('tmp_page', 'start_language_export.htm');
		break;
	case 'import_language':
		$xml = simplexml_load_file('data/charsets.xml');
		$modules = $db->fetch('modules');
		$smarty->assign("charsets", $xml);
		$smarty->assign("modules", $modules);
		$smarty->assign('tmp_page', 'languages_import.htm');
		break;
	case 'start_import_language':
		$tmpName = $_FILES['file']['tmp_name'];
		$content = fread(fopen($tmpName, "r"), filesize($tmpName));		
		$insert = $db->insert('languages',array('lang_code','lang_name','dir','charset'),array($_POST['code'],$_POST['name'],$_POST['dir'],$_POST['charset']));
		if($insert) $get = $db->fetch('languages',false,false,'id','DESC'); $lang_id = $get[0]['id'];
		
		$owner = $_POST['owner'];
		
		if($lang->import($content,$lang_id,$owner)) $tmpl->msg('done', '?show=languages&action=show_languages',1);
		else $tmpl->msg('error');
		break;
	case 'delete_phrases_multi':
		$ids = $_GET['ids'];
		$final_ids = array();
		$explode = explode('|', $ids);
		foreach($explode as $id) {
			$final_ids[] = $id;
		}
		$db->multi_delete('phrases', 'id', $final_ids);
		break;
	case 'delete_languages_multi':
		$ids = $_GET['ids'];
		$final_ids = array();
		$explode = explode('|', $ids);
		foreach($explode as $id) {
			$final_ids[] = $id;
		}
		$db->multi_delete('languages', 'id', $final_ids);
		break;
		
}
?>

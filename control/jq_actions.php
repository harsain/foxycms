<?php
require 'global.php';

switch($_GET['action']) {
	case 'blocks_reorder':
		$explode = explode('-',$_GET['ids']);
		$count=0;
		foreach($explode as $block) {
			if(!empty($block)) {
				$count++;
				if(isset($_GET['place'])) $db->update('blocks','order',$count,array('id','place'),array($block,$_GET['place']));
				else $db->update('blocks','order',$count,'id',$block);
			}
		}
		break;
	case 'update_block_place':
		$id = (int) $_GET['id'];
		$new_place = (int) $_GET['new_place'];
		$db->update('blocks','place',$new_place,'id',$id);
		break;
	case 'notes_new':
		$db->insert('cp_notes',array('pos_x','pos_y','width','height','jq_id','time'),
		array($_GET['pos_x'],$_GET['post_y'],$_GET['width'],$_GET['height'],$_GET['jq_id'],time()));
		break;
	case 'notes_edit':
		function utf8_urldecode($str) {
			$str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
			return html_entity_decode($str,null,'UTF-8');
		}
		$text = utf8_urldecode($_GET['text']);
		$db->update('cp_notes',array('text','pos_x','pos_y','width','height','time'),
		array($text,$_GET['pos_x'],$_GET['pos_y'],$_GET['width'],$_GET['height'],time()),
		'jq_id',$_GET['jq_id']);
		break;
	case 'notes_delete':
		$db->delete('cp_notes','jq_id',$_GET['jq_id']);
		break;
	case 'autocomplete':
		$table = $_GET['table']; $field = $_GET['field']; $q = $_GET['term'];
		$results = $db->fetch($table,"[%]$field",$q);
		$return_array = array();
		foreach($results as $result) {
			$add['value'] = $result[$field];
			$add['id'] = $result['id'];
			array_push($return_array,$add);
		}
		echo json_encode($return_array);
		break;
	case 'medialib_create_dir':
		$insert = $db->insert('uploads_dir', array('name'), array($_POST['name']));
		if($insert) {
			$r = $db->fetch('uploads_dir',false,false,'id','DESC');
			echo $r[0]['id'];
		}
		break;
	case 'medialib_do_upload':
		include '../classes/uploads.php';
		$uploads = new Uploads();
		
		$tmp_name = $_FILES['file_to_upload']['tmp_name'];
		$name = $_FILES['file_to_upload']['name'];
		$type = 'image';
		$dir = $_POST['dir'];
		
		$upload = $uploads->upload($tmp_name, $name, $type, $dir, 'control');
		
		if($upload) {
			echo '<img src="style/images/tick.png" /><br />'.$name.' '.$lang->get_phrase('uploaded_successfully').'
			
			<script>
			$("#files_list").load("jq_actions.php?action=medialib_showfiles&dir='.$dir.'");
			</script>
			';
		} else {
			echo 'an error has occured';
		}
		break;
	case 'medialib_showfiles':
		$dir = $_GET['dir'];
		$page = $_GET['page'];
		if(empty($page)) $page = 1;
		$files = $db->fetch('uploads','dir',$dir,'id','DESC',$page,45);
		$pages_array = $db->pages_array('uploads','dir',$dir,$page,45);
		
		include '../classes/uploads.php';
		$uploads = new Uploads();
		
		if(empty($files)) {
			echo $lang->get_phrase('directory_contains_no_files');
		} else {
			foreach($files as $file) {
				$this_thumb = $uploads->get_thumb($file['id'],'control');
				echo '
				<span>
				<div id="file_'.$file['id'].'" class="file" title="'.$file['file_name'].'" style="background:url('.$this_thumb.') no-repeat;background-size:110px 90px;" onmouseover="file_mouseover('.$file['id'].');" onmouseout="file_mouseout('.$file['id'].');">
				<input type="checkbox" onclick="checkbox_clicked('.$file['id'].')" value="'.$file['id'].'" id="tobeselected" />
				<br /><br />
				<button id="select" style="display:none" onclick="choose_photo('.$file['id'].',\''.$this_thumb.'\');">'.$lang->get_phrase('select').'</button>
				
				<div id="title">'.mb_substr($file['file_name'],0,13).'...</div>
				</div>
				</span>
				';
			}
		}
		$smarty->assign('current_page',$page);
		$smarty->assign('pages_array',$pages_array);
		$smarty->assign('ajax',true);
		$smarty->assign('ajax_div','files_list');
		$smarty->display('style/pages.htm');
		break;
}

?>

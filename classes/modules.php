<?php

Class Modules {
	
	function install($directory,$status = 1) {
		global $db,$lang,$tmpl;
		
		if(file_exists("../modules/$directory")) {
			$details_file = @explode('|',read_file("../modules/$directory/details.text"));
			$name = $details_file[0];
			$var = $details_file[1];
			$version = $details_file[2];
			$programmer_name = $details_file[3];
			$programmer_email = $details_file[4];
		}
		
		$num_rows = $db->num_rows('modules', 'var', $var);
		if ($num_rows < 1) {
			$register_db = $db->insert('modules',array('var','directory','status','name','version','programmer_name','programmer_email'),array($var,$directory,$status,$name,$version,$programmer_name,$programmer_email));
			if($register_db) {
				if(file_exists("../modules/$directory/install.php")) {
					eval(read_file("../modules/$directory/install.php"));
				}
				if(file_exists("../modules/$directory/phrases.xml")) {
					$lang->import(read_file("../modules/$directory/phrases.xml"),'all',$var);
				}
				if(file_exists("../modules/$directory/templates.xml")) {
					$tmpl->import(read_file("../modules/$directory/templates.xml"),'all',$var);
				}
				@unlink("../modules/$directory/phrases.xml");
				@unlink("../modules/$directory/templates.xml");
				@unlink("../modules/$directory/install.php");
				return true;
			}
		}
	}
	
	function get($var) {
		global $db;
		if($db->num_rows('modules','var',$var) < 1) exit;
		$module = $db->fetch('modules','var',$var);
		$dir = $module[0]['directory'];
		if (file_exists("modules/$dir/index.php")) return "modules/$dir/index.php";
		else return false;
	}
	
	function delete($id, $phrases = true, $templates = true) {
		global $db;
		if($templates or $phrases) {
			$module = $db->fetch('modules','id',$id);
			$var = $module[0]['var'];
		}
		$db->delete('modules','id',$id);
		if($templates) $templates = $db->delete('templates','owner',$var);
		if($phrases) $phrases = $db->delete('phrases','owner',$var);
		$dir = $module[0]['directory'];
		require '../includes/functions/super_rmdir.php';
		@super_rmdir("../modules/$dir");
		return true;
	}
	
	function enable($id) {
		global $db;
		if($db->update('modules','status','1','id',$id)) return true; else return false;
	}
	
	function disable($id) {
		global $db;
		if($db->update('modules','status','0','id',$id)) return true; else return false;
	}
	
	function export($module_id) {
		global $db,$lang,$tmpl,$settings;
		$module = $db->fetch('modules','id',$module_id);
		$module = $module[0];
		if(is_dir("../modules/$module[directory]")) {
			$zip = new ZipArchive;
			if(file_exists("../cache/$module[directory].zip")) @unlink("../cache/$module[directory].zip");

			$res = $zip->open("../cache/$module[directory].zip", ZipArchive::CREATE);
			if ($res) {
				// Adding the module directory to the .zip file
				require '../includes/functions/list_files.php';
				foreach(list_files("../modules/$module[directory]") as $key=>$file) {
					$zip->addFile($file,str_replace("../",'',$file));
				}
				

				// Adding phrases.xml and templates.xml
				$zip->addFromString("modules/$module[directory]/phrases.xml", $lang->export($lang->id(),false,false,$module['var']));
				$zip->addFromString("modules/$module[directory]/templates.xml", $tmpl->export($tmpl->id(),false,false,$module['var']));
				# details.text = module_name|var|version|programmer_name|programmer_email
				$zip->addFromString("modules/$module[directory]/details.text", "$module[name]|$module[var]|$module[version]|$module[programmer_name]|$module[programmer_email]");
				$zip->close();
				return "$settings[site_url]/cache/$module[directory].zip";
			}
			
		} else return false;
	}	
	function import($file) {
		$zip = new ZipArchive;

		if ($zip->open($file) === TRUE) {
			$zip->extractTo('../');
			$zip->close();
		}
		require '../includes/functions/latest_dir.php';
		$dir = latest_dir('../modules/');
		if($this->install($dir)) return true; else return false;
	}
	
	function get_cp_links($id) {
		global $db;
		$module = $db->fetch('modules', 'id', $id);
		$module = $module[0];
		if (file_exists("../modules/$module[directory]/admin/menu.xml")) {
			$xml = simplexml_load_file("../modules/$module[directory]/admin/menu.xml");
			return $xml;
		} else return false;
	}
	
	function get_admin($var) {
		global $db;
		if($db->num_rows('modules','var',$var) < 1) return false;
		$module = $db->fetch('modules','var',$var);
		$dir = $module[0]['directory'];
		if (file_exists("../modules/$dir/admin/index.php")) return "../modules/$dir/admin/index.php";
		else return false;
	}
	
	function display_tmp($template) {
		global $_GET, $db, $smarty;
		$var = str_replace('module_', '', str_replace("/", '', $_GET['show']));
		$module = $db->fetch('modules', 'var', $var);
		$dir = $module[0]['directory'];
		$smarty->assign('tmp_page', str_replace('classes', '', dirname(__FILE__))."modules/$dir/admin/style/$template");
	}
	
	function admin_img($image) {
		global $_GET, $db, $smarty;
		$var = str_replace('module_', '', str_replace("/", '', $_GET['show']));
		$module = $db->fetch('modules', 'var', $var);
		$dir = $module[0]['directory'];
		return "../modules/$dir/admin/style/images/$image";
	}
	
	function assign($var, $content) {
		global $smarty;
		$smarty->assign($var, $content);
	}
	
	function success_msg($msg, $redirect) {
		global $tmpl;
		$tmpl->msg($msg, $redirect, 2, 1);
	}
	
	function error_msg($msg, $redirect = '') {
		global $tmpl;
		$tmpl->msg($msg, $redirect, 2, 0);
	}
	
	function display($template) {
		global $tmpl;
		echo $tmpl->display_template($template);
	}
	
	function display_img($image) {
		global $_GET, $db, $tmpl, $smarty;
		$style = $db->fetch('styles', 'id', $tmpl->id());
		$style = $style[0]['style_code'];
		$var = str_replace("/", '', $_GET['mod']);
		$module = $db->fetch('modules', 'var', $var);
		$var = $module[0]['var'];
		return "styles/$style/$var/$image";
	}

}

?>

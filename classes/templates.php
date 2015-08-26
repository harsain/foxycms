<?php

class Templates {
	
	public $styles_table = 'styles';
	public $templates_table = 'templates';
	public $default_style = '1';
	
	# get current style id
	function id() {
		global $db,$settings;
		if(isset($_COOKIE['style'])) {
			$num_rows = $db->num_rows($this->styles_table, array('id'), array($_COOKIE['style']));
			if($num_rows < 1) {
				$styles = $db->fetch('styles');
				return $styles[0]['id'];
			} else return intval($_COOKIE['lang']);
		} else {
			$num_rows = $db->num_rows($this->styles_table, array('id'), array($settings['style']));
			if ($num_rows < 1) {
				$styles = $db->fetch('styles');
				return $styles[0]['id'];
			} else return intval($settings['style']);
		}
	}
	
	# creating style
	function create($style_id, $style_name, $status = 1) {
		global $db;
		$insert = $db->insert($this->styles_table, array('style_id', 'style_name', 'status'), array($style_id, $style_name, $status));
		if ($insert) return true; else return false;
	}
	
	# check if cookies exists
	function check() {
		if (isset($_COOKIES['style']) && !empty($_COOKIES['style'])) {
			# add here auto select style
		} else {
			$this->set($this->default_style);
		}
	}
	
	# setting style in cookies
	function set($style_id) {
		global $db;
		$num_rows = $db->num_rows($this->styles_table, array('id'), array($style_id));
		if ($num_rows < 1) die("ERROR: 200");
		else {
			setcookie("style", $style_id, time()+60*60*24*365);
			return true;
		}
	}
	
	# getting a template from a style
	function get_template($template,$style_id = false) {
		if(!$style_id) $style_id = $this->id();
		global $db;
		$get = $db->fetch($this->templates_table,array('style_id','template'),array($style_id,$template));
		if ($get) return $get[0]['source']; else return false;
	}
	
	# display a template from a style
	function display_template($template,$style_id = false) {
		if(!$style_id) $style_id = $this->id();
		global $smarty;
		return $smarty->display('string:'.$this->get_template($template, $style_id));
	}
	
	# display a string
	function display_string($text) {
		global $smarty;
		return $smarty->display('string:'.$text);
	}
	
	# adding a template into a style
	function add_template($template, $source, $style_id = false, $owner = false) {
		global $db;
		if(!$owner) $owner = 'Foxy';
		if(!$style_id) {
			$styles = $db->fetch('styles');
			foreach($styles as $style) {
				$insert = $db->insert($this->templates_table, array('style_id', 'template', 'source','owner'), array($style['id'], $template, $source,$owner));
			}
		} else {
			$insert = $db->insert($this->templates_table, array('style_id', 'template', 'source','owner'), array($style_id, $template, $source,$owner));
		}
		if ($insert) return true; else return false;
	}
	
	# updating a template in a style
	function update_template($id, $source, $style_id, $owner) {
		global $db;
		$update = $db->update($this->templates_table, array('source', 'style_id', 'owner'), array($source, $style_id, $owner), 'id', $id);
		if ($update) return true; else return false;
	}
	
	# deleting a template from a style
	function delete_template($id) {
		global $db;
		$delete = $db->delete($this->templates_table, array('style_id', 'template'), array($style_id, template));
		if ($delete) return true; else return false;
	}
	
	# getting the css of a style
	function get_css($style_id = false) {
		if(!$style_id) $style_id = $this->id();
		global $db;
		$get = $db->fetch($this->styles_table, 'id', $style_id);
		if ($get) return $get[0]['css']; else return false;
	}
	
	# styles default headers
	function get_style_headers() {
		global $lang,$settings;
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" dir=\"".$lang->get_field('dir')."\">
<head>
<meta http-equiv=\"content-type\" content=\"text/html; charset=".$lang->get_field('charset')."\" />
<title>".$settings['site_title']."</title>
<style>
".$this->get_css()."
</style>
</head>
<body>";
		echo $this->display_template('header');
	}
	
	# styles default footers
	function get_style_footers() {
		echo $this->display_template('footer');
		echo "</body>
</html>";
	}
	
	# export style in xml
	function export($style_id,$style_name = false,$style_code = false,$owner = false) {
		global $db;
		if($owner) $templates = $db->fetch('templates',array('style_id','owner'),array($style_id,$owner)); else $templates = $db->fetch('templates','style_id',$style_id);
		$xml = '';
		$xml .= "<language>\n";
		$xml .= "<details>\n";
		$xml .= "    <name>$lang_name</name>\n";
		$xml .= "    <code>$lang_code</code>\n";
		$xml .= "</details>\n";
		if(count($templates) > 0) {
			foreach($templates as $template) {
				$xml .= "<template>\n";
				$xml .= "    <template>$template[template]</template>\n";
				$xml .= "    <source>".base64_encode($template['source'])."</source>\n";
				$xml .= "    <owner>".$template['owner']."</owner>\n";
				$xml .= "</template>\n";
			}
		}
		$xml .= '</language>';
		return $xml;

	}
	
	# import xml style file
	function import($xmlstr,$style_id,$owner = 'Foxy') {
		global $db;
		$xml = new SimpleXMLElement($xmlstr);
		if($style_id == 'all') {
			$styles = $db->fetch('styles');
			foreach($styles as $style) {
				$style_id = $style['id'];
				foreach ($xml->template as $template) {
					$db->insert('templates',array('template','style_id','source','owner'),array($template->template,$style_id,base64_decode($template->source),$template->owner));
				}
			}
		} else {
			foreach ($xml->template as $template) {
				$db->insert('templates',array('template','style_id','source','owner'),array($template->template,$style_id,base64_decode($template->source),$template->owner));
			}
		}
		return true;
	}
	
	function style_export($style_id) {
		global $db,$settings;
		$style = $db->fetch('styles','id',$style_id);
		$style = $style[0];
			$zip = new ZipArchive;
			if(file_exists("../cache/$style[style_code].zip")) @unlink("../cache/$style[style_code].zip");

			$res = $zip->open("../cache/$style[style_code].zip", ZipArchive::CREATE);
			if ($res) {
				// Adding the style directory to the .zip file
				if(is_dir("../styles/$style[style_code]")) {
					require '../includes/functions/list_files.php';
					foreach(list_files("../styles/$style[style_code]") as $key=>$file) {
						$zip->addFile($file,str_replace("../",'',$file));
					}
				}
				

				// Adding templates.xml and templates.xml
				$zip->addFromString("styles/$style[style_code]/templates.xml", $this->export($this->id(),false,false,$module['var']));
				# details.text = style_name|style_code|version|designer_name|designer_email
				$zip->addFromString("styles/$style[style_code]/details.text", "$style[style_name]|$style[style_code]|$style[version]|$style[designer_name]|$style[designer_email]");
				# add the css
				$zip->addFromString("styles/$style[style_code]/css.text", $style['css']);
				$zip->close();
				return "$settings[site_url]/cache/$style[style_code].zip";
			} else return false;
	}
	
	function style_import($file) {
		global $db;
		$zip = new ZipArchive;
		
		# open the style file
		if ($zip->open($file) === TRUE) {
			$zip->extractTo('../');
			$zip->close();
		}
		
		# get the installed style's directory
		require '../includes/functions/latest_dir.php';
		$dir = latest_dir('../styles/');
		
		# fetch details.text
		if(file_exists("../styles/$dir")) {
			$details_file = @explode('|',read_file("../styles/$dir/details.text"));
			$style_name = $details_file[0];
			$style_code = $details_file[1];
			$version = $details_file[2];
			$designer_name = $details_file[3];
			$designer_email = $details_file[4];
		}
		
		# insert style entry into the databse
		$css = read_file("../styles/$dir/css.text");
		$insert = $db->insert('styles',array('style_name','style_code','version','designer_name','designer_email','css'),
		array($style_name,$style_code,$version,$designer_name,$designer_email,$css));
		$get_r = $db->fetch('styles',false,false,'id','DESC');
		$style_id = $get_r[0]['id'];
		if($insert) {
			# import the templates
			$import = $this->import(read_file("../styles/$dir/templates.xml"),$style_id);
		}
		if($insert AND $import) return true; else return false;
		
	}
	

	function msg($msg, $redirect = '', $type = '') {
		global $smarty;
		$smarty->assign('msg', $msg);
		$smarty->assign('redirect', $redirect);
		$smarty->assign('type', $type);
		$smarty->assign('tmp_page', 'style/msg.htm');
	}
	
	function show_msg($msg, $redirect = '') {
		global $smarty;
		$smarty->assign('msg', $msg);
		$smarty->assign('redirect', $redirect);
		$smarty->assign('tmp_page', '');
		$this->display_template('msg');
	}
	
}

?>

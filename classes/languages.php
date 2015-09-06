<?php

class Languages {

	public $langs_table = 'languages';
	public $phrases_table = 'phrases';
	public $default_lang = 'en';
	
	#set the default language id
	function id() {
		global $db,$settings;
		if(isset($_COOKIE['lang'])) {
			$num_rows = $db->num_rows($this->langs_table, array('id'), array($_COOKIE['lang']));
			if($num_rows < 1) {
				$languages = $db->fetch('languages');
				return $languages[0]['id'];
			} else return intval($_COOKIE['lang']);
		} else {
			$num_rows = $db->num_rows($this->langs_table, array('id'), array($settings['language']));
			if ($num_rows < 1) {
				$languages = $db->fetch('languages');
				return $languages[0]['id'];
			} else return intval($settings['language']);
		}
	}
	
	# creating language
	function create($lang_code, $lang_name) {
		global $db;
		$insert = $db->insert($this->langs_table, array('lang_code', 'lang_name'), array($lang_code, $lang_name));
		if ($insert) return true; else return false;
	}
	
	# setting language in cookies
	function set($lang_id) {
		global $db;
		$num_rows = $db->num_rows($this->langs_table, array('id'), array($lang_id));
		if ($num_rows < 1) {
			setcookie("lang", $this->id(), time()+60*60*24*365);
			return true;
		} else {
			setcookie("lang", $lang_id, time()+60*60*24*365);
			return true;
		}
	}
	
	# getting a phrase from a language
	function get_phrase($var,$lang_id = false, $owner = 'any') {
		if(!$lang_id) $lang_id = $this->id();
		global $db;
		$get = $db->fetch($this->phrases_table,array('lang_id','var'),array($lang_id,$var));
		if ($get) return $get[0]['text']; else return false;
	}
	
	# adding a phrase into a language
	function add_phrase($var, $text, $lang_id = false, $owner = false) {
		global $db;
		if(!$owner) $owner = 'foxy';
		if(!$lang_id) {
			$langs = $db->fetch('languages');
			foreach($langs as $lang) {
				$insert = $db->insert($this->phrases_table, array('lang_id', 'var', 'text',' owner'), array($lang['id'], $var, $text, $owner));
			}
			if ($insert) return true; else return false;
		} else {
			$insert = $db->insert($this->phrases_table, array('lang_id', 'var', 'text', 'owner'), array($lang_id, $var, $text, $owner));
			if ($insert) return true; else return false;
		}
	}
	
	# updating a phrase in a language
	function update_phrase($id, $text, $lang_id, $owner) {
		global $db;
		$update = $db->update($this->phrases_table, array('lang_id', 'text', 'owner'), array($lang_id, $text, $owner), 'id', $id);
		if ($update) return true; else return false;
	}
	
	# deleting a phrase from a language
	function delete_phrase($id) {
		global $db;
		$delete = $db->delete($this->phrases_table, 'id', $id);
		if ($delete) return true; else return false;
	}
	
	# export language in xml
	function export($lang_id,$lang_name = false,$lang_code = false,$owner = false) {
		global $db;
		if($owner) $phrases = $db->fetch('phrases',array('lang_id','owner'),array($lang_id,$owner)); else $phrases = $db->fetch('phrases','lang_id',$lang_id);
		$xml = '';
		$xml .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<language>\n";
		$xml .= "<details>\n";
		$xml .= "    <name>$lang_name</name>\n";
		$xml .= "    <code>$lang_code</code>\n";
		$xml .= "</details>\n";
		if(count($phrases) > 0) {
			foreach($phrases as $phrase) {
				$xml .= "<phrase>\n";
				$xml .= "    <var>".htmlspecialchars($phrase['var'])."</var>\n";
				$xml .= "    <text>".htmlspecialchars($phrase['text'])."</text>\n";
				$xml .= "</phrase>\n";
			}
		}
		$xml .= '</language>';
		return $xml;
	}
	
	# import xml language file
	function import($xmlstr,$lang_id,$owner = 'foxy') {
		global $db;
		$xml = new SimpleXMLElement($xmlstr);
		if($lang_id == 'all') {
			$langs = $db->fetch('languages');
			foreach($langs as $lang) {
				$lang_id = $lang['id'];
				foreach ($xml->phrase as $phrase) {
					$db->insert('phrases',array('var','lang_id','text','owner'),array(htmlspecialchars_decode($phrase->var),$lang_id,htmlspecialchars_decode($phrase->text),$owner));
				}
			}
		} else {
			foreach ($xml->phrase as $phrase) {
				$db->insert('phrases',array('var','lang_id','text','owner'),array($phrase->var,$lang_id,$phrase->text,$owner));
			}
		}
		return true;
	}
	
	function header($what) {
		global $db;
		$lang = $db->fetch('languages','id',$this->id());
		switch($what) {
			case 'dir':
				return $lang[0]['dir'];
				break;
			case 'charset':
				return $lang[0]['charset'];
				break;
		}
	}
	
	function get_field($field) {
		global $db;
		$lang = $db->fetch('languages','id',$this->id());
		return $lang[0][$field];
	}
}

?>

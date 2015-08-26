<?php

class Blocks {
	
	public $blocks_table = 'blocks';
	
	/**
	 * gets all blocks in a specified place
	 *
	 * @param integer $place the number of the wanted place for blocks
	 * 1 = header
	 * 2 = footer
	 * 3 = right menu
	 * 4 = left menu
	 * 5 = center
	 * 6 = content top
	 * 7 = content bottom
	 */
	function get_blocks($place) {
		global $db;
		$blocks = $db->fetch('blocks', array('place', 'status'), array($place, 1), 'order');
		if ($blocks != '') {
			foreach($blocks as $b) {
				$this->get_block($b['id']);
			}
		}
	}
	
	/**
	 * gets a specified block
	 *
	 * @param integer $id the identifier of the wanted block
	 */
	function get_block($id) {
		$content = '';
		global $db, $smarty, $tmpl;
		$block = $db->fetch('blocks', 'id', $id);
		$block_info = $block[0];
		$template = $tmpl->get_template('block');
		if ($block[0]['show_title'] == 0) $template = preg_replace("#([<!--]title_start[-->])(.*)([<!--]title_end[-->])#e", "", $template);
		else {
			$template = str_replace('#title#', $block[0]['title'], $template);
		}
		if ($block[0]['type'] == 1) {
			$template = str_replace('#content#', $block[0]['html_content'], $template);
		} elseif ($block[0]['type'] == 2) {
			include('blocks/'.$block[0]['php_file']);
			$template = str_replace('#content#', $content, $template);
		}
		$tmpl->display_string($template);
	}
	
}

?>

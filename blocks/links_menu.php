<?php

$content = '<ul class="links_menu">';
$links = $db->fetch('links', array('menu_id', 'status'), array($block_info['id'], 1), 'order');
foreach($links as $link) {
	$content .= '<li><a href="'.$link['url'].'" target="'.$link['target'].'">'.$link['title'].'</a></li>';
}
$content .= '</ul>';

?>
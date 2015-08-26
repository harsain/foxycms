<?php
function latest_dir($path){
	$dir = opendir($path);
	$list = array();
		while($file = readdir($dir)){
		if ($file != '.' and $file != '..'){
			$ctime = filectime($path . $file) . ',' . $file;
			$list[$ctime] = $file;
		}
	}
	closedir($dir);
	krsort($list);
	foreach($list as $dir) {
		return $dir;
		break;
	}
}
?>

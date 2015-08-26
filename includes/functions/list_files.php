<?php
function list_files($dir) {
	if($dh = opendir($dir)) {
		$files = Array();
		$inner_files = Array();

		while($file = readdir($dh)) {
			if($file != "." && $file != ".." && $file[0] != '.') {
				if(is_dir($dir . "/" . $file)) {
					$inner_files = list_files($dir . "/" . $file);
					if(is_array($inner_files)) $files = array_merge($files, $inner_files); 
				} else {
					array_push($files, $dir . "/" . $file);
				}
			}
		}

		closedir($dh);
		return $files;
	}
}
?>

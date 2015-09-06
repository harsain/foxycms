<?php
//session_start(); // Not sure why this is commented out? 
if(isset($_GET['REGISTER'])) {
	$id = htmlspecialchars(str_replace('a','',$_GET['id'])); // prevent cross-site-scripting
	echo $id;
	if(isset($_SESSION["controlmenu_$id"])) {
		unset($_SESSION["controlmenu_$id"]);
		echo 'done closed';
	} else {
		$_SESSION["controlmenu_$id"] = 'open';
		echo 'done opened';
	}
} else {
	echo '<style type="text/css">';
	for($i=0; $i<21;$i++) {
		if(isset($_SESSION["controlmenu_$i"])) {
			echo "\n #a$i ul {display:none}\n";
		}
	}
	echo '</style>';
}
?>

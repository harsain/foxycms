<?php

$ext = $_POST['ext'];
$file = "awcm_".$_POST['name'].".$ext";

// Set headers
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=$file");
header("Content-Type: file/$ext");
header("Content-Transfer-Encoding: binary");

echo $_POST['source'];

?>

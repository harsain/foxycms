<?php
function read_file($path) {
	$filename = $path;
	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);
	return $contents;
}
$final_arr = array();
$xmlstr = read_file('charsets.xml');
$xml = new SimpleXMLElement($xmlstr);
$array = (array) $xml;

function xmlToArray($xml, $root = true) {
	if (!$xml->children()) {
		return (string)$xml;
	}
 
	$array = array();
	foreach ($xml->children() as $element => $node) {
		$totalElement = count($xml->{$element});
 
		if (!isset($array[$element])) {
			$array[$element] = "";
		}
 
		// Has attributes
		if ($attributes = $node->attributes()) {
			$data = array(
				'attributes' => array(),
				'value' => (count($node) > 0) ? xmlToArray($node, false) : (string)$node
				// 'value' => (string)$node (old code)
			);
 
			foreach ($attributes as $attr => $value) {
				$data['attributes'][$attr] = (string)$value;
			}
 
			if ($totalElement > 1) {
				$array[$element][] = $data;
			} else {
				$array[$element] = $data;
			}
 
		// Just a value
		} else {
			if ($totalElement > 1) {
				$array[$element][] = xmlToArray($node, false);
			} else {
				$array[$element] = xmlToArray($node, false);
			}
		}
	}
 
	if ($root) {
		return array($xml->getName() => $array);
	} else {
		return $array;
	}
}
$final_arr = xmlToArray($xml);
$final_arr = $final_arr['array'];
foreach($final_arr as $x) {
	print_r($x);
}

?>

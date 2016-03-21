<?php
$xml = simplexml_load_file('pad.xml');
$pad = $xml->pad;
$index = 0;
$json = array(array(), array(), array());
foreach ($pad->$_POST[group]->time as $time) {
	array_push($json[0], (string) $pad->stage[$index]);
	array_push($json[1], (string) $time);
	array_push($json[2], (string) $pad->image[$index]);

	$index++;
}
echo json_encode($json);
?>
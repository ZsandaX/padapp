<?php
$stages = array();
$images = array();
$all_s = array();
$all_i = array();
$url = "http://pad.skyozora.com/";
date_default_timezone_set("Asia/Taipei");

$handle = fopen($url, "rb");
$html = stream_get_contents($handle);
fclose($handle);

$html = explode("table", $html);
$html = preg_replace("/\(.*?\)/", "", $html[9]);
$html = explode(date("m/d", strtotime("+1 day 1 hour")), $html);
$html = '<table style="table' . $html[0] . '</table>';
$html = str_replace("images", "http://pad.skyozora.com/images", $html);
$html = str_replace("/stage", "http://pad.skyozora.com/stage", $html);
$html = str_replace("時", ":00", $html);
$html = str_replace("港澳台:00間", "港澳台時間", $html);

echo $html;
preg_match_all("/rowspan\=\d/", $html, $rowspan);
$rowspan = $rowspan[0];
//echo str_replace("rowspan=", "", $rowspan[0][0]);

preg_match_all('/title\=".*?"/', $html, $stage);
$stage = $stage[0];
$stage = array_count_values($stage);
foreach ($stage as $key => $value) {
	if ($value < 5) {
		array_push($all_s, $key);
		continue;
	}
	for ($i = 0; $i < $value / 5; $i++) {
		array_push($stages, $key);
	}
}
//print_r($stages);

preg_match_all("/\d{2}\:\d{2}/", $html, $time);
$time = $time[0];
//print_r($time);

//print_r($stage);

preg_match_all('/src\=".*?"/', $html, $image);
$image = $image[0];
$image = array_count_values($image);
foreach ($image as $key => $value) {
	if (preg_match("/hogyoku/", $key)) {
		$hogyoku = $key;
		continue;
	} elseif ($value < 5) {
		array_push($all_i, $key);
		continue;
	}
	for ($i = 0; $i < $value / 5; $i++) {
		array_push($images, $key);
	}
}
//print_r($images);

$xml = simplexml_load_file('pad.xml');
unset($xml->pad);
$pad = $xml->addChild("pad");
$pad->addChild('update', date("Y-m-d H:i:s"));
$all = $pad->addChild('all', $hogyoku);
foreach ($all_s as $value) {
	$all->addChild('name', trim(ltrim($value, 'title='), '"'));
}
foreach ($all_i as $value) {
	$all->addChild('picture', trim(ltrim($value, 'src='), '"'));
}
foreach ($stages as $value) {
	$pad->addChild('stage', trim(ltrim($value, 'title='), '"'));
}
foreach ($images as $value) {
	$pad->addChild('image', trim(ltrim($value, 'src='), '"'));
}

$a = $pad->addChild('A');
$b = $pad->addChild('B');
$c = $pad->addChild('C');
$d = $pad->addChild('D');
$e = $pad->addChild('E');
foreach ($time as $key => $value) {
	switch ($key % 5) {
	case 0:

		$a->addChild('time', $value);
		break;
	case 1:

		$b->addChild('time', $value);

		break;
	case 2:

		$c->addChild('time', $value);

		break;
	case 3:

		$d->addChild('time', $value);

		break;
	case 4:

		$e->addChild('time', $value);

		break;

	default:
# code...
		break;
	}
}

$xml->asXML('pad.xml');

?>
<?php

$url = "http://pad.skyozora.com/";
$handle = fopen($url, "rb");
$contents = stream_get_contents($handle);
fclose($handle);

$xml = simplexml_load_file('pad.xml');
$pad = $xml->addChild("time", time());
$pad = $xml->addChild("test", $contents);
$xml->asXML('pad.xml');
?>
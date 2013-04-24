<?php

require('sql.php');
if($_GET['type'] == "map") {
	$sql = 'SELECT id, name, description, AsText(polygon) as polygon FROM okart_maps';
}
else {
	$sql = 'SELECT id, areaid, AsText(polygon) as polygon FROM okart_polygons';
}
	

$sth = $dbh->prepare($sql);
$sth->execute();
$area = $sth->fetchAll(PDO::FETCH_ASSOC);

$numItems2 = count($area);
$j = 0;
$featurecol["type"] = "FeatureCollection";
$featurecol["features"];

foreach($area as $polygon) {
	$feature = array();
	$id = $polygon['id'];
	$coordinates = str_replace('POLYGON', '', $polygon['polygon']);
	$coordinates = str_replace('((', '', $coordinates);
	$coordinates = str_replace('))', '', $coordinates);
	$points = explode(',', $coordinates);
	$coords = array();
	foreach($points as $point) {
		$coord = explode(' ', $point);
		$lat = $coord[0];
		$long = $coord[1];
		$longlat = array($long, $lat);
		$coords[] = $longlat;
	}
	$feature["type"] = "Feature";
	$feature["properties"]["id"] = $id;
	$feature["properties"]["areaid"] = $polygon["areaid"];
	$feature["geometry"]["type"] = "Polygon";
	$feature["geometry"]["coordinates"][0] = $coords;
	$featurecol["features"][] = $feature;

}

$sql = "SELECT * FROM `okart_settings` LIMIT 1";
$sth = $dbh->prepare($sql);
$sth->execute();
$results = $sth->fetch(PDO::FETCH_ASSOC);
$results['textboks'] = replaceNordicChars($results['textboks']);
$results['markertext'] = replaceNordicChars($results['markertext']);
function replaceNordicChars($string) {
	$string = str_replace("ø", "&oslash;", $string);
	$string = str_replace("å", "&aring;", $string);
	$string = str_replace("Ø", "&Oslash;", $string);
	$string = str_replace("Å", "&Aring;", $string);
	$string = str_replace("Å", "&Aring;", $string);
	$string = str_replace("æ", "&aelig;", $string);
	$string = str_replace("Æ", "&AElig;", $string);
	$string = str_replace("\r", "", $string);
	$string = str_replace("\n", "", $string);
	return $string;
}

$sql = 'SELECT id, name, description, AsText(polygon) as polygon FROM okart_maps';

$sth = $dbh->prepare($sql);
$sth->execute();
$area = $sth->fetchAll(PDO::FETCH_ASSOC);

$numItems2 = count($area);
$j = 0;
$maps["type"] = "FeatureCollection";
$maps["features"];

foreach($area as $polygon) {
	$feature = array();
	$id = $polygon['id'];
	$coordinates = str_replace('POLYGON', '', $polygon['polygon']);
	$coordinates = str_replace('((', '', $coordinates);
	$coordinates = str_replace('))', '', $coordinates);
	$points = explode(',', $coordinates);
	$coords = array();
	foreach($points as $point) {
		$coord = explode(' ', $point);
		$lat = $coord[0];
		$long = $coord[1];
		$longlat = array($long, $lat);
		$coords[] = $longlat;
	}
	$feature["type"] = "Feature";
	$feature["properties"]["id"] = $id;
	$feature["properties"]["name"] = replaceNordicChars($polygon["name"]);
	$feature["properties"]["description"] = replaceNordicChars($polygon["description"]);
	$feature["geometry"]["type"] = "Polygon";
	$feature["geometry"]["coordinates"][0] = $coords;
	$maps["features"][] = $feature;

}
$json["maps"] = $maps;
$json["areas"] = $featurecol;
$json["settings"] = $results;
print json_encode($json)

?>
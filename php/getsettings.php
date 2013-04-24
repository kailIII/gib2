<?php
require('sql.php');
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
print json_encode($results);

?>

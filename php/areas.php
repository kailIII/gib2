<?php
require('sql.php');
$sql = "SELECT id, name FROM `okart_areas`";
$sth = $dbh->prepare($sql);
$sth->execute();
$results = $sth->fetchAll();

print "{";
	print "\"areas\": [";
	$i = 0;
	for ($i=0; $i < count($results)-1; $i++) { 
		print $results[$i]['id']. ", ";
	}
	print $results[$i]['id'];
	
	print "], ";
	print "\"names\": [";
	$i = 0;
	for ($i=0; $i < count($results)-1; $i++) { 
		print '"'.$results[$i]['name']. '", ';
	}
	print '"'.$results[$i]['name'].'"';

print "] }";
?>

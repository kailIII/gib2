<?php

require('sql.php');
if($_GET['type'] == "map") {
	$sql = 'SELECT id, name, description, AsText(polygon) as polygon FROM okart_maps WHERE areaid = ?';
}
else {
	$sql = 'SELECT id, AsText(polygon) as polygon FROM okart_polygons WHERE areaid = ?';
}
	

$sth = $dbh->prepare($sql);
$sth->execute(array($_GET['id']));
$area = $sth->fetchAll(PDO::FETCH_ASSOC);
print 'var geoarea = {
"type": "FeatureCollection",
                                                                                
"features": [
';
$numItems2 = count($area);
$j = 0;
foreach($area as $polygon) {
	$id = $polygon['id'];
	$coordinates = str_replace('POLYGON', '', $polygon['polygon']);
	$coordinates = str_replace('((', '', $coordinates);
	$coordinates = str_replace('))', '', $coordinates);
	$points = explode(',', $coordinates);
	foreach ($points as $key => $tmp_point) {
		$point = explode(' ', $tmp_point);
		$points[$key] = $point;
	}
	$coordinates = "[ [ ";
	$numItems = count($points);
	$i = 0;
	foreach ($points as $key => $point) {
		if(++$i === $numItems) {
		$coordinates .= "[ ".$point[1].', '.$point[0].' ] ';
		}
		else {
			$coordinates .= "[ ".$point[1].', '.$point[0].' ], ';
		}
	}
	$coordinates .= '] ]';
	if($_GET['type'] == "map") {
		$feature = '{ "type": "Feature", "properties": { "id": '.$id.', "name": "'.$polygon['name'].'", "description": "'.$polygon['description'].'" }, "geometry": { "type": "Polygon", "coordinates": '.$coordinates.' } },
	';
	}
	else {
		$feature = '{ "type": "Feature", "properties": { "id": '.$id.' }, "geometry": { "type": "Polygon", "coordinates": '.$coordinates.' } },
	';
	}
	
    print $feature;
}
print ']
}';
?>
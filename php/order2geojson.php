<?php
require('sql.php');
if($_GET['type'] == "order"){
	$sql = 'SELECT id, name, area, AsText(polygon) as polygon FROM okart_orderpolygons WHERE orderid = ?';
}
else {
	$sql = 'SELECT id, name, AsText(polygon) as polygon, description FROM okart_polygons WHERE areaid = ?';
}
$areaid = null;
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
	$name = $polygon['name'];
	$description = $polygon['description'];
	$areaid = $polygon['area'];
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
	$feature = '{ "type": "Feature", "properties": { "id": '.$id.', "area": '.$areaid.', "name": "'.$name.'", "description": "'.$description.'" }, "geometry": { "type": "Polygon", "coordinates": '.$coordinates.' } },
	';
    print $feature;
}
print ']
}';
?>
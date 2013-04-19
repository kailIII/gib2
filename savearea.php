<?php
require('php/Auth.php');
$auth = new Auth();
if($auth->check()) {

require('php/sql.php');

$polygonInsert = "INSERT INTO `okart_polygons`(`areaid`, `polygon`) VALUES (?,POLYGONFROMTEXT(?));";
$sth = $dbh->prepare($polygonInsert);
foreach ($_POST['map-latlngs'] as $latlng) {
	$arguments = array($_POST['areaId'], $latlng);
	$sth->execute($arguments);
}
header('Location: define-maps.php#areaid-'.$_POST['areaId']);
}
?>
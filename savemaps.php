<?php
require('php/Auth.php');
$auth = new Auth();
if($auth->check()) {

require('php/sql.php');

if(isset($_POST['map-latlngs'])) {
	$polygonInsert = "INSERT INTO `okart_maps`(`areaid`, `name`, `description`, `polygon`) VALUES (?,?,?,POLYGONFROMTEXT(?));";
	$sth = $dbh->prepare($polygonInsert);
	foreach ($_POST['map-latlngs']  as $key => $polygon) {
		$name = $_POST['map-names'][$key];
		$desc =  $_POST['map-desc'][$key];
		$args = array($_POST['areaId'], $name, $desc, $polygon);
		$sth->execute($args);
	}
}

header('Location: ./#area-'.$_POST['areaId']);
}

?>
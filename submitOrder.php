<?php
require('php/Auth.php');
$auth = new Auth();
if($auth->check()) {

require('php/sql.php');

$orderInsert = "INSERT INTO `okart_orders` (
`name` ,
`adress` ,
`postcode`,
`place` ,
`email` ,
`tel` ,
`club` ,
`rundate` ,
`comment` 
)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
$sth = $dbh->prepare($orderInsert);
$arguments = array($_POST['orderName'], $_POST['orderStreet'],
 $_POST['orderPostnr'], $_POST['orderPlace'], $_POST['orderEmail'], $_POST['orderTel'],
 $_POST['orderClub'], $_POST['orderDate'], $_POST['orderComments']);
$sth->execute($arguments);
$orderId = $dbh->lastInsertId();
try {
$polygonInsert = "INSERT INTO `okart_orderpolygons`(`orderid`, `area`, `name`, `polygon`) VALUES (?,?,?,POLYGONFROMTEXT(?));";
$sth = $dbh->prepare($polygonInsert);
foreach ($_POST['mapNames'] as $key => $mapName) {
	$mapPolygon = $_POST['mapPolygons'][$key];
	$mapArea = $_POST['mapAreas'][$key];
	$arguments = array($orderId, $mapArea, $mapName, $mapPolygon);
	$sth->execute($arguments);
}
} catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}
header('Location: takk.php#orderid-'.$orderId);
}
?>
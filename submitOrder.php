<?php


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
$polygonInsert = "INSERT INTO `okart_orderpolygons`(`orderid`, `area`, `num`, `name`, `polygon`) VALUES (?,?,?,?,POLYGONFROMTEXT(?));";
$sth = $dbh->prepare($polygonInsert);
foreach ($_POST['mapNames'] as $key => $mapName) {
	$mapPolygon = $_POST['mapPolygons'][$key];
	$mapArea = $_POST['mapAreas'][$key];
	$numMaps= $_POST['numMaps'][$key];
	$arguments = array($orderId, $mapArea, $numMaps, $mapName, $mapPolygon);
	$sth->execute($arguments);
}
} catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}

$sql = "SELECT * FROM okart_users WHERE sendmail = 1;";
$sth = $dbh->prepare($sql);
$sth->execute();
function sendEmailAlert($email) {
	$subject = "Ny kartbestilling fra ".$_POST['orderName'];
	$message = "Hei!
	Det er kommet en ny kartbestilling fra ".$_POST['orderName']."
	Logg inn på http://folk.ntnu.no/torbjvi/okart/login.php for å se den";
	$headers = "From:" . $email;
	mail($email,$subject,$message,$headers);

} 
while($result = $sth->fetch()) {
	sendEmailAlert($result["email"]);
}

header('Location: takk.php#orderid-'.$orderId);

?>
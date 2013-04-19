<?php
require('Auth.php');
$auth = new Auth();
if($auth->check()) {

require('sql.php');
if($_GET['restore']) {
	$sql = "UPDATE `okart_orders` SET `archived` = '0' WHERE `okart_orders`.`id` = ? LIMIT 1";	
}
else {
	$sql = "UPDATE `okart_orders` SET `archived` = '1' WHERE `okart_orders`.`id` = ? LIMIT 1";
}
$sth = $dbh->prepare($sql);
$sth->execute(array($_GET['id']));
}
?>
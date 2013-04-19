<?php

$mysql_host = "";
$mysql_database = "";
$mysql_user = "";
$mysql_password = "";

try {
    $dbh = new PDO('mysql:host='.$mysql_host.';dbname='.$mysql_database, $mysql_user, $mysql_password, array(
    PDO::ATTR_PERSISTENT => true
));

} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>
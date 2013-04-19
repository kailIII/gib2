<?php
require('php/Auth.php');
$auth = new Auth();
$auth->logOut();
$auth->redirectLogIn();
?>
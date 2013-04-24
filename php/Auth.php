<?php
class Auth {
	public $email, $id, $navn, $key;
	function __construct() {
		session_start();
		$this->id = -1;
		$this->check();


	}
	function check() {
		$this->id = $_COOKIE['okart_user'];		
		if($_COOKIE['okart_user'] > -1) {
			$this->key = md5($_SERVER['REMOTE_ADDR'].$_COOKIE["okart_name"].$_COOKIE['okart_user']);
			if($this->key === $_COOKIE["okart_key"]) {
				return $_COOKIE['okart_user'];
			}
			
		}
		$this->redirectLogIn();

	}
	function logIn($user,$password) {
		require('sql.php');
		$query = "SELECT * FROM `okart_users` WHERE `email` = ? LIMIT 1;";
		$sth = $dbh->prepare($query);
		$sth->execute(array($user));
		$bruker = $sth->fetch(PDO::FETCH_ASSOC);
		if(!$bruker['id']){
			setcookie('okart_user', -1);
			setcookie('okart_key', '');
			return false;
		}
		else {
			if($this->comparePassword($password, $bruker['password'])) {
				$this->key = md5($_SERVER['REMOTE_ADDR'].$bruker["name"].$bruker['id']);
				setcookie('okart_user', $bruker['id'], time()+60*60*24*30);
				setcookie('okart_name', $bruker['name'], time()+60*60*24*30);
				setcookie('okart_key', $this->key, time()+60*60*24*30);
				return true;
			}
			else {
				$this->redirectWrongUserPW();
				return false;
			}
		}	
	}
	function logOut() {
			setcookie('okart_user', -1);
			setcookie('okart_key', '');
	}
	function getPasswordSalt()
	{
		return substr( str_pad( dechex( mt_rand() ), 8, '0',
											   STR_PAD_LEFT ), -8 );
	}

	// calculate the hash from a salt and a password
	function getPasswordHash( $salt, $password )
	{
		return $salt . ( hash( 'whirlpool', $salt . $password ) );
	}

	// compare a password to a hash
	function comparePassword( $password, $hash )
	{
		$salt = substr( $hash, 0, 8 );
		return $hash == $this->getPasswordHash( $salt, $password );
	}
	function newUser($email,$password,$name, $alert) {
		if($this->check()) {
			require_once('sql.php');
			$sql = "INSERT INTO `okart_users` (`email`, `password`, `name`, `sendmail`) VALUES (?, ?, ?, ?);";
			$sth = $dbh->prepare($sql);
			$salt = $this->getPasswordSalt();
			$hash = $this->getPasswordHash($salt, $password);
			$sth->execute(array($email,$hash,$name, $alert));
			return true;
		}
		return false;
	}
	function updateUser($email, $password, $name, $alert) {
		if($this->check()) {
			require_once('sql.php');
			if($password == '') {
			$sql = "UPDATE `okart_users` SET `name` = ?, `email` = ?, `sendmail` = ? WHERE `okart_users`.`id` = ?;";
			$sth = $dbh->prepare($sql);
			$sth->execute(array($name,$email,$alert,$this->id));
		} else {
			$sql = "UPDATE `okart_users` SET `name` = ?, `email` = ?, `sendmail` = ?, `password` = ? WHERE `okart_users`.`id` = ?;";
			$sth = $dbh->prepare($sql);
			$salt = $this->getPasswordSalt();
			$hash = $this->getPasswordHash($salt, $password);
			$sth->execute(array($name,$email,$alert,$hash,$this->id));
		}
			
			return true;
		}
	}
	function deleteUser($id) {
		if($this->check()) {
			require_once('sql.php');
			$sql = "DELETE FROM `okart_users` WHERE `okart_users`.`id` = ?;";
			$sth = $dbh->prepare($sql);
			$sth->execute(array($id));
			return true;
		}
	}
	function redirectWrongUserPW() {
		header("Location: ./login.php?wronguserorpw=1");
	}
	function redirectLogIn() {
		header("Location: ./login.php");
	}
}
?>
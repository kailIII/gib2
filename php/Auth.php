<?php
class Auth {
	public $email, $id, $navn;
	function __construct() {
		session_start();
		$this->id = -1;
		$this->check();
	}
	function check() {
		$this->id = $_SESSION['id'];
		$this->email = $_SESSION['email'];
		$this->navn = $_SESSION['name'];
		
		if($this->id > -1) {
			return $this->id;
		}
		$this->redirectLogIn();

	}
	function logIn($user,$password) {
		require_once('sql.php');
		$query = "SELECT * FROM `okart_users` WHERE `email` = ? LIMIT 1;";
		$sth = $dbh->prepare($query);
		$sth->execute(array($user));
		$bruker = $sth->fetch(PDO::FETCH_ASSOC);
		if(!$bruker['id']){
			$_SESSION['email'] = '';
			$_SESSION['userid'] = 0;
			return false;
		}
		else {
			if($this->comparePassword($password, $bruker['password'])) {
				$_SESSION['email'] = $bruker['email'];
				$_SESSION['name'] = $bruker['name'];
				$_SESSION['id'] = $bruker['id'];
				return true;
			}
			else {
				$this->redirectWrongUserPW();
				return false;
			}
		}	
	}
	function logOut() {
			$_SESSION['name'] = '';
			$_SESSION['email'] = '';
			$_SESSION['userid'] = -1;
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
	function newUser($email,$password,$name) {
		if($this->check()) {
			require_once('sql.php');
			$sql = "INSERT INTO `okart_users` (`email`, `password`, `name`) VALUES (?, ?, ?);";
			$sth = $dbh->prepare($sql);
			$salt = $this->getPasswordSalt();
			$hash = $this->getPasswordHash($salt, $password);
			$sth->execute(array($email,$hash,$name));
			return true;
		}
		return false;
	}
	function updateUser($email, $password, $name) {
		if($this->check()) {
			require_once('sql.php');
			if($password == '') {
			$sql = "UPDATE `okart_users` SET `name` = ?, `email` = ? WHERE `okart_users`.`id` = ?;";
			$sth = $dbh->prepare($sql);
			$sth->execute(array($name,$email,$this->id));
		} else {
			$sql = "UPDATE `okart_users` SET `name` = ?, `email` = ?, `password` = ? WHERE `okart_users`.`id` = ?;";
			$sth = $dbh->prepare($sql);
			$salt = $this->getPasswordSalt();
			$hash = $this->getPasswordHash($salt, $password);
			$sth->execute(array($name,$email,$hash,$this->id));
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
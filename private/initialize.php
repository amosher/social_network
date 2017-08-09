<?php
	session_start();
	define(SERVERNAME, "localhost");
	define(USERNAME_DB, "root");
	define(PASSWORD_DB, "mysql");
	define (DBNAME, "realdb");

	function requireLogin(){
		if(!userLoggedIn()){
			redirectToLogin();
		}
	}

	function userLoggedIn(){
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true){
			return true;
		}
		else {
			return false;
		}
	}

	function redirectToLogin(){
		header("Location: index.php"); 
	}
	function redirect($location){
		header("Location: " . $location);
	}
?>
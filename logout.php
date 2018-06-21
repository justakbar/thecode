<?php 
	session_start();
	session_unset();
	session_destroy();
	
	unset($_COOKIE['cookie']);
	unset($_COOKIE['hash']);
	unset($_COOKIE['PHPSESSID']);
	setcookie('cookie', '', time() - 3600, '/');
	setcookie('hash', '', time() - 3600, '/');
	setcookie('PHPSESSID','', time() - 3600, '/');
	header("Location: login");
?>

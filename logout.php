<?php
	session_start();
	if (isset($_COOKIE['token']) && isset($_COOKIE['token_id'])) {
		setcookie('token', '', time()-7000000, '/');
		setcookie('token_id', '', time()-7000000, '/');
		session_unset();
		session_destroy();
		if (!isset($_SESSION['userName'])) {
			header('location: index.php');
		}
	}
?>

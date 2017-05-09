<?php
	// session_start();
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	$baseUrl    = 'http://floorplanner.techossaur.com/';
	$admin_mail = 'abhaymilestogo@gmail.com';
	$dbHost     = 'localhost';
	$dbUsername = 'dipankar';
	$dbPassword = 'dipankar@123';
	$dbName     = 'db_floorplanner';
	$con        = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);
?>
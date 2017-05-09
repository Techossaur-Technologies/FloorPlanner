<?php
include('config.php');


function createSidebar($menu,$sub_menu){
	global $baseUrl,$dbHost,$dbUsername,$dbPassword,$dbName;
	// $baseUrl = 'http://floorplanner.techossaur.com/';

	require_once("class.sidebar.php");
	$side = new sidebar($menu,$sub_menu);
	$side->initConnection($dbHost,$dbUsername,$dbPassword,$dbName);
	$side->set_baseUrl($baseUrl);
	$side->createMenu();
}

?>
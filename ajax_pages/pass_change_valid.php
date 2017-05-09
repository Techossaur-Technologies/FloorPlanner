<?php
	include_once('../includes/config.php');
	include('../includes/all_functions.php');
	$id = $_SESSION['user_id'];
	$oldpassword = mysqli_real_escape_string($con, md5($_POST["oldpassword"]));
	$newpassword = mysqli_real_escape_string($con, md5($_POST["newpassword"]));
	// $connewpassword = mysqli_real_escape_string($con, md5($_POST["connewpassword"]));

	$sql = "SELECT * FROM tb_user WHERE id = '".$id."' AND pass = '".$oldpassword."'";
	$res = mysqli_query($con, $sql);
	// print_r($res);
	// exit;
	$count=mysqli_num_rows($res);
	$row = mysqli_fetch_array($res);


	if($count==1){
	$sql1 = mysqli_query($con, "UPDATE `tb_user` SET `pass`='".$newpassword."' WHERE `id` = '".$id."'");
	echo json_encode(array("status" => "success", "message" => "<p style = 'color: green'>Password Changed!</p>"));
	}
	else {
	echo json_encode(array("status" => "error", "message" => "<p style = 'color: red'>Invalid old Password!</p>"));
	}
?>
<?php
	include_once('../includes/config.php');
	include('../includes/all_functions.php');
	$id = encryptorDecryptor('decrypt', mysqli_real_escape_string($con, $_POST["data_id"]));
	$sql = mysqli_query($con, "SELECT `company_status` FROM `tb_company` WHERE `company_id` = '".$id."'");
	$row = mysqli_fetch_array($sql);
	// echo $row['client_status'];

	if ($row['company_status'] == "Active" ) {
		# code...
		$sql2 = "UPDATE `tb_company` SET `company_status`= 'InActive' WHERE `company_id` = '".$id."'";
		if( mysqli_query($con, $sql2)) {
		echo json_encode(array('status' => "success", 'message' => "<p style = 'color: green'>Company Id# '".$id."' InActive successfull </p>"));
		}
	} else if ($row['company_status'] == "InActive") {
		$sql1 = "UPDATE `tb_company` SET `company_status`= 'Active' WHERE `company_id` = '".$id."'";
		if( mysqli_query($con, $sql1)) {
		echo json_encode(array('status' => "success", 'message' => "<p style = 'color: green'>Company Id# '".$id."' Active successfull </p>"));
		}
	} else {
		echo json_encode(array('status' => "error", 'message' => "<p style = 'color: red'>Error!!!</p>"));
	}

?>
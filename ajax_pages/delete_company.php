<?php
//	header('Content-Type: application/json');
	include_once('../includes/config.php');
	include('../includes/all_functions.php');
	$company_id = encryptorDecryptor('decrypt', mysqli_real_escape_string($con, $_POST["data_id"]));

	$sql1 = "UPDATE `tb_company` SET `company_status`='Inactive', `company_delete`='Yes',`company_delete_on`= NOW() WHERE `company_id` = '".$company_id."'";

	if (mysqli_query($con, $sql1)) {
    echo json_encode(array('status' => "success", 'message' => "<p style = 'color: green'>Delete Company Successfull</p>"));
	} else {
    echo json_encode(array('status' => "error", 'message' => "<p style = 'color: red'>Error to delete!!!</p>"));
	}

?>
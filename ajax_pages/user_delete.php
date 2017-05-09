<?php
	include('../includes/config.php');
	include('../includes/all_functions.php');
	$client_id = encryptorDecryptor('decrypt', mysqli_real_escape_string($con, $_POST["data_id"]));

	$sql1 = "UPDATE `tb_user` SET `user_status`='Inactive', `user_delete`='yes' WHERE `id` = '".$client_id."'";

	if (mysqli_query($con, $sql1)) {
    echo json_encode(array('status' => 'success', 'message' => '<p style="color:green;">#'.$client_id.' Client delete succesfully...</p>'));
	} else {
    echo json_encode(array('status' => 'error', 'message' => '<p style="color:red;">#'.$client_id.' Error...</p>'));
	}

?>
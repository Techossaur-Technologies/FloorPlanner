<?php
	include('../includes/config.php');
	include('../includes/all_functions.php');
	$id = encryptorDecryptor('decrypt', mysqli_real_escape_string($con, $_POST["data_id"]));
	$sql = mysqli_query($con, "SELECT `user_status` FROM `tb_user` WHERE `id` = '".$id."'");
	$row = mysqli_fetch_array($sql);
	// echo $row['client_status'];

	if ($row['user_status'] == "Active" ) {
		# code...
		$sql2 = "UPDATE `tb_user` SET `user_status`= 'InActive' WHERE `id` = '".$id."'";
		if( mysqli_query($con, $sql2)) {
			echo json_encode(array('status' => 'success', 'message' => '<p style="color:green;">#'.$id.' Client InActive succesfully...</p>'));
		}
	} else if ($row['user_status'] == "InActive") {
		$sql1 = "UPDATE `tb_user` SET `user_status`= 'Active' WHERE `id` = '".$id."'";
		if( mysqli_query($con, $sql1)) {
			echo json_encode(array('status' => 'success', 'message' => '<p style="color:green;">#'.$id.' Client Active succesfully...</p>'));
		}
	} else {
		echo json_encode(array('status' => 'error', 'message' => '<p style="color:red;">#'.$id.' Error...</p>'));
	}

?>
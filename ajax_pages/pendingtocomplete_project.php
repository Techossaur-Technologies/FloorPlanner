<?php
include_once('../includes/config.php');
include('../includes/all_functions.php');
// your datebase connection and delete logic goes here
$id   = encryptorDecryptor('decrypt', mysqli_real_escape_string($con, $_POST["data_id"]));
$sql1 = "SELECT * FROM `tb_record` WHERE `prj_id` = '".$id."'";
$sql2 = "SELECT * FROM `tb_record` WHERE `prj_id` = '".$id."' AND `project_status` = 'Pending'";
$sql3 = "UPDATE `tb_record` SET `project_status`= 'Completed' WHERE `prj_id` = '".$id."'";
$res1 = mysqli_query($con, $sql1);
$res2 = mysqli_query($con, $sql2);
if (mysqli_num_rows($res1) == 1) {
	if (mysqli_num_rows($res2) == 1) {
		if (mysqli_query($con, $sql3)) {
    		echo json_encode(array("status" => "success", "message" => "<p style = 'color: green'>Project Id#'".$id."' Updated!</p>"));
		} else {
    		echo json_encode(array("status" => "error", "message" => "<p style = 'color: red'>Connection Failed!</p>"));
		}
	} else {
			echo json_encode(array("status" => "error", "message" => "<p style = 'color: red'>Project Already Completed!</p>"));
	}
} else {
	echo json_encode(array("status" => "error", "message" => "<p style = 'color: red'>Project not Found!</p>"));
}
?>
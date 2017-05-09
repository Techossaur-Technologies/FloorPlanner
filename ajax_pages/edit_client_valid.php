<?php
	header('Content-Type: application/json');
	include('../includes/config.php');
	include('../includes/all_functions.php');
	$id = encryptorDecryptor('decrypt',mysqli_real_escape_string($con, $_POST["clientid"]));
	$fname = mysqli_real_escape_string($con, $_POST["fname"]);
	$lname = mysqli_real_escape_string($con, $_POST["lname"]);
	$sql1 = "SELECT * FROM `tb_user` where `id` = '".$id."'";
	$sql = "UPDATE `tb_user` SET `fname`='".$fname."',`lname`='".$lname."' WHERE `id` = '".$id."'";
	$result = mysqli_query($con, $sql1);
	$arr = mysqli_fetch_array($result);
	// print_r($arr);

	if ($fname == NULL || $lname == NULL) {
		echo json_encode(array("status" => "error", "message" => "<p style = 'color: red'>Please add all input fields!</p>"));
	} elseif ($fname == $arr['fname'] & $lname == $arr['lname']) {

		echo json_encode(array("status" => "error", "message" => "<p style = 'color: red'>No Changes are there!</p>"));
	} else {
		if (mysqli_query($con, $sql)) {

			echo json_encode(array("status" => "success", "message" => "<p style='color:green;'>Information added successfully!</p>"));

		} else {
			echo json_encode(array("status" => "error", "message" => "<p style = 'color: red'>Please contact system administrator!</p>"));
		}
}
?>
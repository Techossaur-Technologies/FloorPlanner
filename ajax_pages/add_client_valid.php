<?php
	header('Content-Type: application/json');
	include('../includes/config.php');
	$fname = mysqli_real_escape_string($con, $_POST["fname"]);
	$lname = mysqli_real_escape_string($con, $_POST["lname"]);
	$client_mail = mysqli_real_escape_string($con, $_POST["clientmail"]);
	$activation_code = substr(number_format(time() * rand(),0,'',''),0,10);

	$sql1 = "SELECT * FROM `tb_user` WHERE `email` = '".$client_mail."'";
	$sql2 = "INSERT INTO `tb_user`(`fname`, `lname`, `email`, `pass`, `user_type`, `activation_link`) VALUES ('".$fname."', '".$lname."', '".$client_mail."', '".md5(random_password())."', 'Client', '".$activation_code."')";

	$que1 = mysqli_query($con, $sql1);
	$count = mysqli_num_rows($que1);
	if ($fname == NULL || $lname == NULL || $client_mail == NULL) {
		# code...
		echo json_encode(array("status" => "error", "message" => "<p style='color:red;'>Please add all input fields!</p>"));
	} else {

		if ($count == 0) {
			# code...
			if (mysqli_query($con, $sql2)) {
				# code...
				echo json_encode(array("status" => "success", "message" => "<p style='color:green;'>Information added successfully!</p>"));
			} else {
				echo json_encode(array("status" => "error", "message" => "<p style='color:red;'>Please contact system administrator!</p>"));
			}
		} else {
			echo json_encode(array("status" => "error", "message" => "<p style='color:red;'>Client Already Exist!</p>"));
		}
	}
	function random_password( $length = 8 ) {
	 $chars    = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	 $password = substr( str_shuffle( $chars ), 0, $length );
	    return $password;
	}
?>
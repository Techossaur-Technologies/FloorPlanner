<?php
	include_once('../includes/config.php');
	require_once('../class/class.phpmailer.new.php');
	require_once('../class/Class.EmailerApi.php');

	$email           = $_POST["email"];
	$password        = mysqli_real_escape_string($con, md5($_POST["password"]));
	$fname           = mysqli_real_escape_string($con, $_POST["fname"]);
	$lname           = mysqli_real_escape_string($con, $_POST["lname"]);

	$us_type         = 'Client';
	$activation_code = substr(number_format(time() * rand(),0,'',''),0,10);

	// Check for existing account
	if( mysqli_num_rows(mysqli_query($con, "SELECT `email` FROM `tb_user` WHERE `email` = '".$email."' ")) >= '1' ){
		echo json_encode(array('status' => "error", 'message' => "<p style='color:red;'>Account already exist using this email ID</p>" ));
	}
	else {

		// Sending Mail
		if($us_type == 'Client'){
			$mail_query = mysqli_query($con, "SELECT * FROM `email_templates` WHERE tpltype = 'REGISTRATIONMAILTOUSER' AND mailtype = 'USER'");
		}

		$rows           = mysqli_fetch_assoc($mail_query);

		$actlink        = $baseUrl."activation.php?activation=".$activation_code."&user=".$us_type."";
		$user_parum     = array('__FULLNAME__','__USEREMAIL__','__PASSWORD__','__ACTIVATIONLINK__');
		$data           = base64_decode($rows['messagebody']);
		$msg            = stripslashes(html_entity_decode($data,ENT_QUOTES));
		$user_rep       = array($fname,$email,$_POST["password"],$actlink);
		$email_message  = str_replace($user_parum,$user_rep,$msg);
		$mail           = new PHPMailer();
		$mail->Subject  = $rows['subject'];
		$mail->From     = $rows['mailfrom'];
		$mail->FromName = $rows['mailfromname'];
		$mail->addAddress($email,$fname);
		// $emailA = 'admin@coredigita.com';
		// $mail->addAddress($emailA);
		$mail->Body     = $email_message;
		$mail->IsHTML(true);

		if($mail->Send()) {

			// Adding user to application
			mysqli_query($con, "INSERT INTO `tb_user`(`fname`, `lname`, `email`, `pass`, `activation_link`) VALUES ('".$fname."','".$lname."', '".$email."', '".$password."', '".$activation_code."')");
			echo json_encode(array('status' => "success",'message' => "<p style='color:green;'>Your account has been succesfully created. Please verify your email</p>" ));
		}
		else {
			echo json_encode(array('status' => "failed",'message' => "<p style='color:red;'><strong>Sorry, registration process failed!</strong> Please try again later or contact site administrator!.</p>" ));
		}
	}


?>

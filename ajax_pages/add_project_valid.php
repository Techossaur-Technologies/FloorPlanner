<?php
	header('Content-Type: application/json');
	include('../includes/config.php');
	require_once('../class/class.phpmailer.new.php');
	require_once('../class/Class.EmailerApi.php');
	$project = htmlentities($_POST["projectname"]);
	$client  = mysqli_real_escape_string($con, $_POST["client_id"]);
	$company = mysqli_real_escape_string($con, $_POST["company_id"]);

	$path = date('Y').'/'.date('m').'/';
	if (!file_exists('../uploads/'.$path)) {
	    mkdir(('../uploads/'.$path), 0777, true);
	    $target = '../uploads/'.$path;
	} else {
	    $target = '../uploads/'.$path;
	}

	$image_array = array();

	$file_names = array();
	$fil = 0;
	$sql     = "INSERT INTO `tb_record`(`project_name`, `client_id`, `company_id`, `creation_time`) VALUES ('".$project."','".$client."','".$company."', CURDATE())";
	if (mysqli_query($con, $sql)) {
		$prj_id        = mysqli_insert_id($con);
		if(isset($_FILES['file'])) {
			foreach($_FILES['file']['tmp_name'] as $key => $tmp_name) {
				$file_name     = $_FILES['file']['name'][$key];
				$file_size     = $_FILES['file']['size'][$key];
				$file_tmp      = $_FILES['file']['tmp_name'][$key];
				$file_type     = $_FILES['file']['type'][$key];

				$parts         = pathinfo(basename($_FILES['file']['name'][$key]));
				$file_id       = date('d').time().mt_rand(9,99);
				$file_new_name = "BP-ID".$prj_id.'-'.$file_id.".".$parts['extension'];
				$img           = $target.$file_new_name;
				$file_names[]  = $img;
				move_uploaded_file($file_tmp,$img);


				array_push($image_array, $file_new_name);

				if ($parts['extension'] != 'pdf') {
					$fil = $fil + 1;
					mysqli_query($con, "INSERT INTO `tb_raw_img`(`prj_id_link`, `raw_img_name`, `raw_file_path`, `raw_creation_time`) VALUES ('".$prj_id."', '".$file_new_name."', '".$path."', NOW())");
				} else {
					mysqli_query($con, "INSERT INTO `tb_raw_pdf`(`raw_pdf_name`, `raw_pdf_prj_id`, `raw_pdf_path`) VALUES ('".$file_new_name."','".$prj_id."','".$path."')");
				}

			}
			$image_series = join(',', $image_array);
			if ($parts['extension'] != 'pdf') {

				mysqli_query($con, "INSERT INTO `tb_comment`(`project_id`, `comment_type`, `image_name`, `image_path`) VALUES ('".$prj_id."', 'raw', '".$image_series."', '".$path."')");

			} else {
				mysqli_query($con, "INSERT INTO `tb_comment`(`project_id`, `comment_type`, `image_name`, `image_path`, `user` ) VALUES ('".$prj_id."', 'cmt', '".$image_series."', '".$path."', '".$_SESSION['user_id']."')");
			}
			mysqli_query($con, "UPDATE `tb_record` SET `blueprint` = '".$fil."' WHERE `prj_id` = '".$prj_id."'");

		} else {
			echo json_encode(array("data"=>"error","message"=>"<p style='color:red;'><i class='icon-remove-sign'></i> Please select files!</p>"));
		}

		// Mail Configuration
		$mail_query = mysqli_query($con, "SELECT * FROM `email_templates` WHERE tpltype = 'RAWBLUEPRINTUPLOADMAIL' AND mailtype = 'Admin'");
		$project_details = mysqli_fetch_assoc(mysqli_query($con, "SELECT  * FROM `tb_record`  INNER JOIN `tb_company` ON `tb_record`.`company_id`=`tb_company`.`company_id` INNER JOIN `tb_user` ON `tb_record`.`client_id`=`tb_user`.`id` WHERE prj_id = '".$prj_id."'"));
		$rows           = mysqli_fetch_assoc($mail_query);

		$user_parum     = array(' __ASSID__',' __PRNAME__',' __COMPANYNAME__ ',' __CLIENTNAME__ ');
		$user_parum_sub = array(' __ASSID__',' __PRNAME__');
		$data           = base64_decode($rows['messagebody']);
		$data_sub       = $rows['subject'];
		$msg            = stripslashes(html_entity_decode($data,ENT_QUOTES));
		$msg_sub        = stripslashes(html_entity_decode($data_sub,ENT_QUOTES));
		$user_rep       = array($prj_id,$project,$project_details['company_name'],$project_details['fname'].' '.$project_details['lname']);
		$user_rep_sub   = array($prj_id,$project);
		$email_message  = str_replace($user_parum,$user_rep,$msg);
		$email_message_sub  = str_replace($user_parum_sub,$user_rep_sub,$msg_sub);
		$mail           = new PHPMailer();
		$mail->Subject  = $email_message_sub;
		$mail->From     = $rows['mailfrom'];
		$mail->FromName = $rows['mailfromname'];

		foreach ($file_names as $value) {
			$attachmentLink = '../uploads/'.$value;
			$mail->addAttachment($attachmentLink);
		}
		$email_id = $admin_mail;
		$mail->addAddress($email_id);
		$mail->Body = $email_message;
		$mail->IsHTML(true);
		$mail->Send();

		echo json_encode(array('data' => 'success', 'message' => "<p style='color:green;'>Assignment ID# '".$prj_id."' has been succesfully created!!</p>"));
	} else {
		echo json_encode(array('data' => 'failed', 'message' => '<p style=color:red;">Error!!</p>'));
	}
?>
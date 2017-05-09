<?php
	header('Content-Type: application/json');
	include('../includes/config.php');
	include('../includes/all_functions.php');
	require_once('../class/class.phpmailer.new.php');
	require_once('../class/Class.EmailerApi.php');
	$prj_id = encryptorDecryptor('decrypt',$_POST["prj_id"]);
	$comments = htmlspecialchars($_POST["comments"], ENT_QUOTES);
	$path = date('Y').'/'.date('m').'/';
	if (!file_exists('../uploads/'.$path)) {
	    mkdir(('../uploads/'.$path), 0777, true);
	    $target = '../uploads/'.$path;
	} else {
	    $target = '../uploads/'.$path;
	}
	$image_array = array();
	$file_names = array();
	
	if ($comments != '') {
		if(isset($_FILES['file'])) {
			foreach($_FILES['file']['tmp_name'] as $key => $tmp_name) {
				$file_name     = $_FILES['file']['name'][$key];
				$file_size     = $_FILES['file']['size'][$key];
				$file_tmp      = $_FILES['file']['tmp_name'][$key];
				$file_type     = $_FILES['file']['type'][$key];
				
				$parts         = pathinfo(basename($_FILES['file']['name'][$key]));
				$file_id       = date('d').time().mt_rand(9,99);
				$file_new_name = "CM-ID".$prj_id.'-'.$file_id.".".$parts['extension'];
				$img           = $target.$file_new_name;
				$file_names[]  = $img;
				move_uploaded_file($file_tmp,$img);
				array_push($image_array, $file_new_name);
			}
			$image_series = join(',', $image_array);
			$tb_comment = mysqli_query($con, "INSERT INTO `tb_comment`(`project_id`, `comment_type`, `image_name`, `image_path`, `comments`, `user`) VALUES ('".$prj_id."', 'cmt', '".$image_series."', '".$path."', '".$comments."', '".$_SESSION['user_id']."')");
			
			echo json_encode(array("data"=>"success","message"=>"<p style='color:green;'>Your comment posted successfully with image</p>"));
		} else {
			mysqli_query($con, "INSERT INTO `tb_comment`(`project_id`, `comment_type`, `comments`, `user`) VALUES ('".$prj_id."', 'cmt', '".$comments."', '".$_SESSION['user_id']."')");
			echo json_encode(array("data"=>"success","message"=>"<p style='color:green;'>Your comment posted successfully without any image</p>"));
		}

		// Mail Configuration
		$project_details = mysqli_fetch_assoc(mysqli_query($con, "SELECT  * FROM `tb_record`  INNER JOIN `tb_company` ON `tb_record`.`company_id`=`tb_company`.`company_id` INNER JOIN `tb_user` ON `tb_record`.`client_id`=`tb_user`.`id` WHERE prj_id = '".$prj_id."'"));

		if ($_SESSION['user_type'] == 'Client') {
			$mail_query = mysqli_query($con, "SELECT * FROM `email_templates` WHERE tpltype = 'COMMENTMAILFROMUSER' AND mailtype = 'Admin'");
			$mail_id = $admin_mail;
		} else {
			$mail_query = mysqli_query($con, "SELECT * FROM `email_templates` WHERE tpltype = 'COMMENTMAILFROMADMIN' AND mailtype = 'User'");
			$mail_id = $project_details['email'];
		}
		
		$rows           = mysqli_fetch_assoc($mail_query);

		$user_parum     = array(' __ASSID__',' __PRNAME__',' __COMPANYNAME__ ',' __CLIENTNAME__ ',' __MESSAGE__');
		$user_parum_sub = array(' __ASSID__',' __PRNAME__',' __USER__');
		$data           = base64_decode($rows['messagebody']);
		$data_sub       = $rows['subject'];
		$msg            = stripslashes(html_entity_decode($data,ENT_QUOTES));
		$msg_sub        = stripslashes(html_entity_decode($data_sub,ENT_QUOTES));
		$user_rep       = array($prj_id,$project_details['project_name'],$project_details['company_name'],$project_details['fname'].' '.$project_details['lname'],htmlspecialchars_decode($comments));
		$user_rep_sub   = array($prj_id,$project_details['project_name'], $_SESSION['name']);
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
		$mail->addAddress($mail_id);
		$mail->Body = $email_message;
		$mail->IsHTML(true);
		$mail->Send();
	} else {
		echo json_encode(array("data"=>"error","message"=>"<p style='color:red;'><i class='icon-remove-sign'></i>Comment posted failed</p>"));
	}
	
?>
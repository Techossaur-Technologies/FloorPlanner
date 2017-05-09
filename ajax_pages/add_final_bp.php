<?php
	header('Content-Type: application/json');
	// Content type
	header('Content-Type: image/jpeg');
	include('../includes/config.php');
	include('../includes/all_functions.php');
	include('../class/imageGrid.class.php');
	require_once('../class/class.phpmailer.new.php');
	require_once('../class/Class.EmailerApi.php');

	$prj_id     = encryptorDecryptor('decrypt',$_POST["prj_id"]);
	$comments   = htmlspecialchars($_POST["comments"]);
	$company_id = encryptorDecryptor('decrypt',$_POST["company_id"]);
	$collage 	= '';
	if(isset($_POST["collage"])){
		$collage    = $_POST["collage"];
	}
	$path       = date('Y').'/'.date('m').'/';

	if (!file_exists('../uploads/'.$path)) {
	    mkdir(('../uploads/'.$path), 0777, true);
	    $target = '../uploads/'.$path;
	} else {
	    $target = '../uploads/'.$path;
	}

	$count              = 0;
	$company_logo_fetch = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tb_company WHERE company_id = '".$company_id."'"));
	$company_logo       = '../logo/'.$company_logo_fetch['logo_path'].$company_logo_fetch['company_logo'];
	$image_array        = array();
	$file_names         = array();

	if(isset($_FILES['file'])) {
		foreach($_FILES['file']['tmp_name'] as $key => $tmp_name) {

			$file_name     = $_FILES['file']['name'][$key];
			$file_size     = $_FILES['file']['size'][$key];
			$file_tmp      = $_FILES['file']['tmp_name'][$key];
			$file_type     = $_FILES['file']['type'][$key];

			$parts         = pathinfo(basename($_FILES['file']['name'][$key]));
			$file_id       = date('d').time().mt_rand(9,99);
			$file_new_name = "FP-ID".$prj_id.'-'.$file_id;
			$t_img         = $target.$file_new_name.".".$parts['extension'];


			move_uploaded_file($file_tmp,$t_img);
			if ($parts['extension'] == 'png') {
				$image = imagecreatefrompng($t_img);
				$img = $target.$file_new_name.".jpg";
			    imagejpeg($image, $img, 100);
			    imagedestroy($image);
			    unlink($t_img);
			    $file_names[]  = $img;
			} else {
				$img = $t_img;
				$file_names[]  = $img;
			}
			$count = $count + 1;
			if ($collage == '') {

				if ($company_logo_fetch['company_logo'] != '') {
					$percent = 0.75;
					list($width, $height) = getimagesize($img);
					$newwidth = $width * $percent;
					$newheight = $height * $percent;
					// Load
					$thumb = imagecreatetruecolor($newwidth, $newheight);
					$source = imagecreatefromjpeg($img);

					// Resize
					imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

					// Output
					imagejpeg($thumb, $img, 100);

					$imageGrid = new imageGrid(3000, 2000, 300, 200);
					// if ($parts['extension'] == 'jpg') {
						$img1 = imagecreatefromjpeg($img);
						$imageGrid->putImage($img1, 210, 157, 40, 0);
					// } else if ($parts['extension'] == 'png') {
					// 	$img1 = imagecreatefrompng($img);
					// 	$imageGrid->putImage($img1, 280, 210, 0, 0);
					// }
					$img2 = imagecreatefromjpeg($company_logo);
						$imageGrid->putImage($img2, 300, 40, 0, 158);

					$collageFlag = $imageGrid->display($img);

					imagedestroy($img1);
					imagedestroy($img2);
				} else {
					$percent = 0.75;
					list($width, $height) = getimagesize($img);
					$newwidth = $width * $percent;
					$newheight = $height * $percent;
					// Load
					$thumb = imagecreatetruecolor($newwidth, $newheight);
					$source = imagecreatefromjpeg($img);

					// Resize
					imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

					// Output
					imagejpeg($thumb, $img, 100);

					$imageGrid = new imageGrid(3000, 2000, 300, 200);
						$img1 = imagecreatefromjpeg($img);
						$imageGrid->putImage($img1, 210, 157, 40, 21);

					$collageFlag = $imageGrid->display($img);

					imagedestroy($img1);
					imagedestroy($img2);
				}
				$tb_final_img = mysqli_query($con, "INSERT INTO tb_final_img(final_image_name, prj_id, final_file_path, final_img_type) VALUES ('".$file_new_name.".jpg','".$prj_id."','".$path."','".$parts['extension']."')");
			}
			array_push($image_array, $file_new_name.'.jpg');

		}
		$image_series = join(',', $image_array);

		if ($collage == 'on') {
			$file_id       = date('d').time().mt_rand(9,99);
			$file_col_name = "FPC-ID".$prj_id.'-'.$file_id.".jpg";
			switch ($count) {
				case 2:
					$imageGrid = new imageGrid(2800, 2600, 280, 260);
					$ext1 = end(explode('.', $file_names['0']));
					if ($ext1 == 'jpg') {
						$img1 = imagecreatefromjpeg($file_names['0']);
					} else {
						$img1 = imagecreatefrompng($file_names['0']);
					}
					$imageGrid->putImage($img1, 140, 187, 0, 10);
					imagedestroy($img1);
					$ext3 = end(explode('.', $file_names['1']));
					if ($ext3 == 'jpg') {
						$img3 = imagecreatefromjpeg($file_names['1']);
					} else {
						$img3 = imagecreatefrompng($file_names['1']);
					}
					$imageGrid->putImage($img3, 140, 187, 140, 10);
					imagedestroy($img3);
					$img2 = imagecreatefromjpeg($company_logo);
					$imageGrid->putImage($img2, 280, 60, 0, 200);
					imagedestroy($img2);
					$collageFlag = $imageGrid->display($target.$file_col_name);
					unlink($file_names['0']);
					unlink($file_names['1']);
					break;
				case 3:
					$imageGrid = new imageGrid(2800, 2000, 280, 200);
					$ext1 = end(explode('.', $file_names['0']));
					if ($ext1 == 'jpg') {
						$img1 = imagecreatefromjpeg($file_names['0']);
					} else {
						$img1 = imagecreatefrompng($file_names['0']);
					}
					$imageGrid->putImage($img1, 93, 124, 0, 10);
					imagedestroy($img1);
					$ext3 = end(explode('.', $file_names['1']));
					if ($ext3 == 'jpg') {
						$img3 = imagecreatefromjpeg($file_names['1']);
					} else {
						$img3 = imagecreatefrompng($file_names['1']);
					}
					$imageGrid->putImage($img3, 93, 124, 93, 10);
					imagedestroy($img3);
					$ext4 = end(explode('.', $file_names['2']));
					if ($ext4 == 'jpg') {
						$img4 = imagecreatefromjpeg($file_names['2']);
					} else {
						$img4 = imagecreatefrompng($file_names['2']);
					}
					$imageGrid->putImage($img4, 93, 124, 186, 10);
					imagedestroy($img4);
					$img2 = imagecreatefromjpeg($company_logo);
					$imageGrid->putImage($img2, 280, 60, 0, 140);
					imagedestroy($img2);
					$collageFlag = $imageGrid->display($target.$file_col_name);
					unlink($file_names['0']);
					unlink($file_names['1']);
					unlink($file_names['2']);
					break;
				case 4:
					$imageGrid = new imageGrid(2800, 4500, 280, 450);
					$ext1 = end(explode('.', $file_names['0']));
					if ($ext1 == 'jpg') {
						$img1 = imagecreatefromjpeg($file_names['0']);
					} else {
						$img1 = imagecreatefrompng($file_names['0']);
					}
					$imageGrid->putImage($img1, 140, 187, 0, 10);
					imagedestroy($img1);
					$ext3 = end(explode('.', $file_names['1']));
					if ($ext3 == 'jpg') {
						$img3 = imagecreatefromjpeg($file_names['1']);
					} else {
						$img3 = imagecreatefrompng($file_names['1']);
					}
					$imageGrid->putImage($img3, 140, 187, 140, 10);
					imagedestroy($img3);
					$ext4 = end(explode('.', $file_names['2']));
					if ($ext4 == 'jpg') {
						$img4 = imagecreatefromjpeg($file_names['2']);
					} else {
						$img4 = imagecreatefrompng($file_names['2']);
					}
					$imageGrid->putImage($img4, 140, 187, 0, 202);
					imagedestroy($img4);
					$ext5 = end(explode('.', $file_names['3']));
					if ($ext5 == 'jpg') {
						$img5 = imagecreatefromjpeg($file_names['3']);
					} else {
						$img5 = imagecreatefrompng($file_names['3']);
					}
					$imageGrid->putImage($img5, 140, 187, 140, 202);
					imagedestroy($img5);
					$img2 = imagecreatefromjpeg($company_logo);
					$imageGrid->putImage($img2, 280, 60, 0, 390);
					imagedestroy($img2);
					$collageFlag = $imageGrid->display($target.$file_col_name);
					unlink($file_names['0']);
					unlink($file_names['1']);
					unlink($file_names['2']);
					unlink($file_names['3']);
					break;
				default:
					# code...
					break;
			}
			$tb_final_img = mysqli_query($con, "INSERT INTO tb_final_img(final_image_name, prj_id, final_file_path, final_img_type) VALUES ('".$file_col_name."','".$prj_id."','".$path."','jpg')");
			$tb_comment = mysqli_query($con, "INSERT INTO `tb_comment`(`project_id`, `comment_type`, `image_name`, `image_path`, `comments`, `user`) VALUES ('".$prj_id."', 'final', '".$file_col_name."', '".$path."', '".$comments."', '".$_SESSION['name']."')");
		} else {
			$tb_comment = mysqli_query($con, "INSERT INTO `tb_comment`(`project_id`, `comment_type`, `image_name`, `image_path`, `comments`, `user`) VALUES ('".$prj_id."', 'final', '".$image_series."', '".$path."', '".$comments."', '".$_SESSION['name']."')");
		}


		// Mail Configuration
		$mail_query = mysqli_query($con, "SELECT * FROM `email_templates` WHERE tpltype = 'DELIVERCONFIRMATIONTOCLIENT' AND mailtype = 'User'");
		$project = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tb_record WHERE prj_id = '".$prj_id."'"));
		$email = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `tb_user` WHERE id = '".$project['client_id']."'"));
		$rows           = mysqli_fetch_assoc($mail_query);

		$user_parum     = array(' __PROJECTID__',' __COMPANYNAME__',' __ADDRESS__');
		$user_parum_sub = array(' __PROJECTID__',' __ADDRESS__');
		$data           = base64_decode($rows['messagebody']);
		$data_sub       = $rows['subject'];
		$msg            = stripslashes(html_entity_decode($data,ENT_QUOTES));
		$msg_sub        = stripslashes(html_entity_decode($data_sub,ENT_QUOTES));
		$user_rep       = array($prj_id,$company_logo_fetch['company_name'],$project['project_name']);
		$user_rep_sub   = array($prj_id,$project['project_name']);
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
		if ($collage == 'on') {
			$attachmentLink1 = $target.$file_col_name;
			$mail->addAttachment($attachmentLink1);
		}
		$email_id = $email['email'];
		$mail->addAddress($email_id);
		$mail->Body = $email_message;
		$mail->IsHTML(true);
		$mail->Send();

		echo json_encode(array("data"=>"success","message"=>"<p style='color:green;'>Output Blueprints Uploaded Succesfully</p>"));
	} else {
		echo json_encode(array("data"=>"error","message"=>"<p style='color:red;'><i class='icon-remove-sign'></i> Please select files!</p>"));
	}
?>
<?php
	include_once('../includes/config.php');
	include('../includes/all_functions.php');
	$company_id = encryptorDecryptor('decrypt',$_POST["company_id"]);
	$company_logo_name = $_POST['company_logo_name'];
	$companyname = htmlentities($_POST["companyname"]);
	$client_id = encryptorDecryptor('decrypt',$_POST["client_id"]);
	$target = 'company_logo/';
	$file_names = array();
	if (mysqli_num_rows(mysqli_query($con, "SELECT * FROM tb_company WHERE `company_id` = '".$company_id."'")) == 1) {
		if(isset($_FILES['file'])) {
			foreach($_FILES['file']['tmp_name'] as $key => $tmp_name) {
				$file_name     = $_FILES['file']['name'][$key];
				$file_size     = $_FILES['file']['size'][$key];
				$file_tmp      = $_FILES['file']['tmp_name'][$key];
				$imagedetails = getimagesize($_FILES['file']['tmp_name'][$key]);
				$width = $imagedetails[0];
				$height = $imagedetails[1];
				$file_type     = $_FILES['file']['type'][$key];
				if ($width != '2800' || $height != '600') {
					echo json_encode(array("data"=>"error","message"=>"<p style='color:red;'>Please resize your image in width= 2800 and height= 600</p>"));
					exit;
				}
				if ($file_type != 'image/jpeg'){
					echo json_encode(array("data"=>"error","message"=>"<p style='color:red;'>Image format must be in jpeg.</p>"));
					exit;
				}

				$parts         = pathinfo(basename($_FILES['file']['name'][$key]));
				$file_id       = date('d').time().mt_rand(9,99);
				$file_new_name = "LOGO-ID".$client_id.'-'.$file_id.".".$parts['extension'];
				$img           = '../logo/'.$target.$file_new_name;
				$file_names[]  = $img;
				move_uploaded_file($file_tmp,$img);
				unlink('../logo/'.$company_logo_name);
			}
			mysqli_query($con, "UPDATE `tb_company` SET `company_name`= '".$companyname."',`company_logo`='".$file_new_name."',`logo_path`='".$target."',`assigned_client`='".$client_id."' WHERE `company_id` = '".$company_id."'");
			echo json_encode(array("data"=>"success","message"=>"<p style='color:green;'>Your company Id# '".$company_id."' successfully Updated</p>"));
		} else {
			mysqli_query($con, "UPDATE `tb_company` SET `company_name`= '".$companyname."', `assigned_client`='".$client_id."' WHERE `company_id` = '".$company_id."'");
			echo json_encode(array("data"=>"success","message"=>"<p style='color:green;'>Your company Id# '".$company_id."' successfully Updated</p>"));
		}
	} else {
		echo json_encode(array("data"=>"error","message"=>"<p style='color:red;'>Record not in database</p>"));
	}
?>
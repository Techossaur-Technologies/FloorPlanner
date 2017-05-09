<?php
	//	header('Content-Type: application/json');
	include('../includes/config.php');
	include('../includes/all_functions.php');
	$company = htmlentities($_POST["companyname"]);
	$client_id = encryptorDecryptor('decrypt',$_POST["client_id"]);
	$sql_check = "SELECT * FROM `tb_company` WHERE `company_name` = '".$company."' AND assigned_client = '".$client_id."'";
	$sql2    = "INSERT INTO `tb_company`(`company_name`, `company_creation`, `assigned_client`) VALUES ('".$company."', NOW(), '".$client_id."')";
	$que_sql_check = mysqli_query($con, $sql_check);
	$count_row = mysqli_num_rows($que_sql_check);
	$target = 'company_logo/';
	$file_names = array();

	if ($count_row == 0) {
		if(isset($_FILES['file'])) {
			foreach($_FILES['file']['tmp_name'] as $key => $tmp_name) {
				$file_name     = $_FILES['file']['name'][$key];
				$file_size     = $_FILES['file']['size'][$key];
				$file_tmp      = $_FILES['file']['tmp_name'][$key];
				$file_type     = $_FILES['file']['type'][$key];
				$imagedetails = getimagesize($_FILES['file']['tmp_name'][$key]);
				$width = $imagedetails[0];
				$height = $imagedetails[1];
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
			}
			mysqli_query($con, "INSERT INTO `tb_company`(`company_name`, `company_creation`, `assigned_client`, `logo_path`, `company_logo`) VALUES ('".$company."', NOW(), '".$client_id."', '".$target."','".$file_new_name."')");
			echo json_encode(array("data"=>"success","message"=>"<p style='color:green;'>Company Information added succesfully</p>"));
		} else {
			mysqli_query($con, "INSERT INTO `tb_company`(`company_name`, `company_creation`, `assigned_client`) VALUES ('".$company."', NOW(), '".$client_id."')");
			echo json_encode(array("data"=>"success","message"=>"<p style='color:green;'>Company Information added succesfully</p>"));
		}
	} else {
		echo json_encode(array('status' => "error", 'message' => "<p style = 'color: red'>Error!!</p>"));
	}
?>
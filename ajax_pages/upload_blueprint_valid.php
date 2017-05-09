<?php
	header('Content-Type: application/json');
	include('../includes/config.php');

	$raw_img_id = mysqli_real_escape_string($con, $_POST["raw_img_id"]);
	$prj_id = mysqli_real_escape_string($con, $_POST["prj_id"]);

	$path = date('Y').'/'.date('m').'/';
	if (!file_exists('../uploads/'.$path)) {
	    mkdir(('../uploads/'.$path), 0777, true);
	    $target = '../uploads/'.$path;
	} else {
	    $target = '../uploads/'.$path;
	}

	$file_names = array();
	$count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `tb_raw_img` WHERE `raw_img_id` = '".$raw_img_id."' AND `prj_id_link` = '".$prj_id."'"));

	if ($count == '1') {
		if(isset($_FILES['file'])) {
			foreach($_FILES['file']['tmp_name'] as $key => $tmp_name) {
				$file_name     = $_FILES['file']['name'][$key];
				$file_size     = $_FILES['file']['size'][$key];
				$file_tmp      = $_FILES['file']['tmp_name'][$key];
				$file_type     = $_FILES['file']['type'][$key];
				
				$parts         = pathinfo(basename($_FILES['file']['name'][$key]));
				$file_id       = date('d').time().mt_rand(9,9);
				$file_new_name = "FP-".$prj_id.'-'.$raw_img_id.'-'.$file_id.".".$parts['extension'];
				$img           = $target.$file_new_name;
				$file_names[]  = $img;
				move_uploaded_file($file_tmp,$img);
				$sql2          = "INSERT INTO `tb_final_img`(`raw_img_id`, `final_image_name`, `prj_id`, `final_file_path`,`final_img_type`, `final_img_status`) VALUES ('".$raw_img_id."', '".$file_new_name."', '".$prj_id."', '".$path."', '".$parts['extension']."', 'Disapproved')";
				mysqli_query($con, $sql2);
			}
			echo json_encode(array('data' => 'success', 'message' => "<p style='color:green;'>Blueprint ID# '".$file_new_name."' has uploaded.</p>"));
		} else {
			echo json_encode(array("data"=>"error","message"=>"<p style='color:red;'><i class='icon-remove-sign'></i> Please select files!</p>"));
		}
	} else {
		echo json_encode(array("data"=>"error","message"=>"<p style='color:red;'><i class='icon-remove-sign'></i>Database Not Found..</p>"));
	}

?>
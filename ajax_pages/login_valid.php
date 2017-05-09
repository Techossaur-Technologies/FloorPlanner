<?php
	include_once('../includes/config.php');
	include('../includes/all_functions.php');
	date_default_timezone_set('Asia/Kolkata');

	if (isset($_COOKIE['token']) && isset($_COOKIE['token_id'])) {
		$email = $_COOKIE['token_id'];
		$password = encryptorDecryptor('decrypt',$_COOKIE['token']);
		$autologin = 'on';
		$goto = '1';
		create_session($con, $email, $password, $autologin, $baseUrl, $goto);
	} else {
		$autologin = $_POST["autologin"];
		$email    = mysqli_real_escape_string($con, $_POST["email"]);
		$password = mysqli_real_escape_string($con, md5($_POST["password"]));
		$goto = '0';
		create_session($con, $email, $password, $autologin, $baseUrl, $goto);
	}

	

	function create_session($con, $email, $password, $autologin, $baseUrl, $goto){
		$sql      = "SELECT * FROM tb_user WHERE email = '".$email."' AND pass = '".$password."'";
		$res      = mysqli_query($con, $sql);
		$count    = mysqli_num_rows($res);
		$row      = mysqli_fetch_array($res);

		if($count == 1){
			if ($row['user_status'] == 'Active') {
				$_SESSION['name'] = $row['fname'].' '.$row['lname'];
				$_SESSION['user_name'] = $email;
				$_SESSION['user_id']   = $row['id'];
				$_SESSION['user_type'] = $row['user_type'];
				$qry                   = mysqli_query($con, "UPDATE `tb_user` SET `last_login`= 'NOW()'  WHERE `id`= '".$row['id']."'");
				if ($autologin == 'on') {
					$password_hash = encryptorDecryptor('encrypt',$password);
					setcookie('token', $password_hash, time() + (86400 * 20), "/"); // 86400 = 1 day
					setcookie('token_id', $email, time() + (86400 * 20), "/"); // 86400 = 1 day
				}
				if ($goto == '1') {
					header( "Location:".$baseUrl."../dashboard.php" );
				} else {
					echo 'success';
				}
			} else {
				echo "inactive";
			}
		}
		else {
			echo "failure";
		}
	}
	
	
?>
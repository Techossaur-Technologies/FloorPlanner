<?php
include('../includes/config.php');
include('../includes/all_functions.php');
$templateId = encryptorDecryptor('decrypt', $_POST['templateId']);
$subject = $_POST['subject'];
$mailfrom = $_POST['mailfrom'];
$mailfromname = $_POST['mailfromname'];
$message = $_POST['message'];

$sql = "UPDATE `email_templates` SET `subject`= '".$subject."',`mailfrom`= '".$mailfrom."',`mailfromname`= '".$mailfromname."',`messagebody`= '".$message."' WHERE `id`= '".$templateId."'";

if(mysqli_query($con, $sql)){
	echo json_encode(array('status' => 'success' , 'message' => "<p style='color:green;'>Update Successfully</p>"));
} else {
	echo json_encode(array('status' => 'failed' , 'message' => "<p style='color:red;'>Error in Database</p>"));
}
?>
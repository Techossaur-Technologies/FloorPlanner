<?php
header('Content-Type: application/json');
include_once('../includes/config.php');
include('../includes/all_functions.php');
// your datebase connection and delete logic goes here
$id   = encryptorDecryptor('decrypt', mysqli_real_escape_string($con, $_POST["data_id"]));
// echo $id;
$sql1 = "DELETE FROM tb_record WHERE prj_id='".$id."'";
if (mysqli_query($con, $sql1)) {
	echo json_encode(array("status" => "success", "message" => "<p style = 'color: green'>Project Id#'".$id."' deleted!</p>"));
} else {
	echo json_encode(array("status" => "error", "message" => "<p style = 'color: red'>Deletion Not Succesfull!</p>"));
}
// echo "done";

?>
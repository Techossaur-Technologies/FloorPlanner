<?php

header('Content-Type: application/json');
include_once('../includes/config.php');
include('../includes/all_functions.php');

$target_url = 'http://boligfotograf.net/admin_test/accept_floorplanner_assignment.php';
$url        = "http://floorplanner.coredigita.com/uploads/";

$id         = encryptorDecryptor('decrypt', mysqli_real_escape_string($con, $_POST["data_id"]));
$app_id     = mysqli_real_escape_string($con, $_POST["application_id"]);
$sql        = "SELECT `final_file_path` , `final_image_name` FROM `tb_final_img` WHERE `prj_id` = '".$id."'";
$query      = mysqli_query($con, $sql);
$file_array = array();

while($result = mysqli_fetch_assoc($query)) {
    array_push($file_array, $url.$result['final_file_path'].$result['final_image_name']);
}

$file_string = implode(",", $file_array);

if (is_numeric($app_id)) {
    $post   = array('bolig_app_id' => $app_id, 'bolig_file_array' => $file_string);
    $ch     = curl_init();
    curl_setopt($ch, CURLOPT_URL,$target_url);
    curl_setopt($ch, CURLOPT_POST,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $result = curl_exec ($ch);
    curl_close ($ch);

    if ($result == 'success') {
        $update_sql = 'UPDATE `tb_record` SET `bolig_app_id`="'.$app_id.'" WHERE `prj_id`= "'.$id.'"';
        $update_table = mysqli_query($con, $update_sql);
        echo json_encode(array("status" => "success", "message" => 'Floorpan succesfully uploaded to Bolig application!'));
    } else {
        echo json_encode(array("status" => "error", "message" => 'Error in Processing!!!'));

    }

}
else {
    echo json_encode(array("status" => "error", "message" => 'App Id of boligofotografer should be numeric.'));
}
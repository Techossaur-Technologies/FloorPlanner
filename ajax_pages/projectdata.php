<?php
include('../includes/all_functions.php');
include_once('../includes/config.php');
/* Database connection start */
$dbHost     = 'localhost';
$dbUsername = 'dipankar';
$dbPassword = 'dipankar@123';
$dbName     = 'db_floorplanner';

$conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName) or die("Connection failed: " . mysqli_connect_error());

/* Database connection end */


// storing  request (ie, get/post) global array to a variable
$requestData= $_REQUEST;

if ($_SESSION['user_type'] == 'Admin') {
	$columns = array(
	0 => 'prj_id',
	1 => 'project_name',
	2 => 'fname',
	3 => 'company_name',
	4 => 'blueprint',
	5 => 'bolig_app_id',
	6 => 'project_status',
	7 => 'creation_time'
	);
} else {
	$columns = array(
	0 => 'prj_id',
	1 => 'project_name',
	2 => 'company_name',
	3 => 'blueprint',
	4 => 'bolig_app_id',
	5 => 'project_status',
	6 => 'creation_time'
	);
}



// getting total number records without any search
$sql = "SELECT  * ";
$sql.= user_type();
$query=mysqli_query($conn, $sql) or die("Error1");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$sql = "SELECT  * ";
$sql.= user_type();
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( prj_id LIKE '".$requestData['search']['value']."%' ";

	$sql.=" OR project_name LIKE '".htmlentities($requestData['search']['value'])."%' ";

	$sql.=" OR blueprint LIKE '".$requestData['search']['value']."%' ";

	$sql.=" OR bolig_app_id LIKE '".$requestData['search']['value']."%' ";

	if ($_SESSION['user_type'] == 'Admin') {
		$sql .= " OR fname LIKE '".htmlentities($requestData['search']['value'])."%' ";
		$sql .= " OR lname LIKE '".htmlentities($requestData['search']['value'])."%' ";
	}

	$sql .= " OR company_name LIKE '".htmlentities($requestData['search']['value'])."%' ";

	$sql .= " OR creation_time LIKE '".$requestData['search']['value']."%' ";

	$sql .= " OR project_status LIKE '".$requestData['search']['value']."%' )";
}
$query         = mysqli_query($conn, $sql) or die("Error2");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
$sql           .= " ORDER BY CASE WHEN project_status = 'Pending' THEN '1' WHEN project_status = 'Completed' THEN '2' ELSE project_status END ASC, `creation_time` DESC, ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
// /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
//echo $sql; exit;
$query         = mysqli_query($conn, $sql) or die("Error3");

$data          = array();



while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData   = array();
	$actionIcons  = '';
	
	$nestedData[] = $row["prj_id"];
	$nestedData[] = "<a href =view_project.php?id=".encryptorDecryptor('encrypt',$row["prj_id"]).">".html_entity_decode($row["project_name"])."</a>";
	if ($_SESSION['user_type'] == 'Admin') {
		$nestedData[] = html_entity_decode($row["fname"].' '.$row["lname"]);
	}
	$nestedData[] = html_entity_decode($row["company_name"]);
	$nestedData[] = $row["blueprint"];
	if ($row["bolig_app_id"] == 0) {
		$nestedData[] = 'No';
	}
	else {
		$nestedData[] = $row["bolig_app_id"];
	}	
	$nestedData[] = $row["project_status"];
	$nestedData[] = $row["creation_time"];

	if ($_SESSION['user_type'] == 'Admin') {
		$actionIcons = '<button type="button" name = "check" id = "'.encryptorDecryptor('encrypt', $row['prj_id']).'" value= "'.$row['prj_id'].'" class="fa fa-check"></button><button type="button" name = "edit" id = "'.encryptorDecryptor('encrypt', $row['prj_id']).'" value= "'.$row['prj_id'].'" class="fa fa-edit"></button><button type="button" name = "delete" id = "'.encryptorDecryptor('encrypt', $row['prj_id']).'" value= "'.$row['prj_id'].'" class="fa fa-trash"></button>';
		
	} else {
		$actionIcons = "<a href =view_project.php?id=".encryptorDecryptor('encrypt',$row["prj_id"])."><button type='button' class='fa fa-search'></button></a>".'<button type="button" name = "edit" id = "'.encryptorDecryptor('encrypt', $row['prj_id']).'" value= "'.$row['prj_id'].'" class="fa fa-edit"></button>';		
	}
	if ($row["bolig_app_id"] == 0 && $row["project_status"] == 'Completed') {
		$actionIcons .= '<button type="button" name = "curl" id = "'.encryptorDecryptor('encrypt', $row['prj_id']).'" value= "'.$row['prj_id'].'" class="fa fa-magnet"></button>';
	}
	$nestedData[] = $actionIcons;

	$data[] = $nestedData;
}


// print_r($data);
// quit;

$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

// print_r($json_data); exit;
echo json_encode($json_data);  // send data as json format
function user_type(){
	if ($_SESSION['user_type'] == 'Client') {
		return $sql_condition = " FROM `tb_record`  INNER JOIN `tb_company` ON `tb_record`.`company_id`=`tb_company`.`company_id` INNER JOIN `tb_user` ON `tb_record`.`client_id`=`tb_user`.`id` WHERE client_id = '".$_SESSION['user_id']."'";
	} else {
		return $sql_condition = " FROM `tb_record`  INNER JOIN `tb_company` ON `tb_record`.`company_id`=`tb_company`.`company_id` INNER JOIN `tb_user` ON `tb_record`.`client_id`=`tb_user`.`id`";
	}
}
?>

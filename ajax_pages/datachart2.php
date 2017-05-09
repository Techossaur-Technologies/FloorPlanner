<?php
// include('../includes/config.php');

$year  = date('Y');
$sql1  = "SELECT * FROM `tb_record` WHERE YEAR(`creation_time`) = '".$year."' ";
$sql1  .= usertype();
$sumBP = array();
$sumPr = array();

if (mysqli_query($con, $sql1)) {
	# code...
	echo "{ name: 'Projects', data: [";
	for ($i = 1 ; $i <= 12 ; $i++) {
		$sql2 = "SELECT COUNT(`prj_id`) AS project FROM `tb_record` WHERE MONTH(`creation_time`)= '".$i."' AND YEAR(`creation_time`) = '".$year."' ";
		$sql2  .= usertype();
		$data1_arr =mysqli_query($con, $sql2);
		while ($row1 = mysqli_fetch_array($data1_arr)) {
		  	if($row1["project"]){
		  		$sumPr[] =  $row1["project"];
		  	}
		  	else{
		  		$sumPr[] = '0';
		  	}
		}
	}
	echo implode(", ",$sumPr);
	echo "]}, {name: 'Blueprints', data: [";
	for ($i = 1 ; $i <= 12 ; $i++) {
		$sql3 = "SELECT SUM(`blueprint`) AS bprint FROM `tb_record` WHERE MONTH(`creation_time`)= '".$i."' AND YEAR(`creation_time`) = '".$year."' ";
		$sql3  .= usertype();
		$data1_arr =mysqli_query($con, $sql3);
		while ($row1 = mysqli_fetch_array($data1_arr)) {
		  	if($row1["bprint"]){
		  		//echo $row1["bprint"];
		  		$sumBP[] = $row1["bprint"];
		  	}
		  	else{
		  		//echo 0;
		  		$sumBP[] = '0';
		  	}
		}
	}
	echo implode(", ",$sumBP);
	echo "] }";
}
function usertype(){
	if($_SESSION['user_type'] != 'Admin'){
		return $sql = " AND client_id = '".$_SESSION['user_id']."'";
	}
}


?>
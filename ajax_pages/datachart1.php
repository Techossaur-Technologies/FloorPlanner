<?php
// include('../includes/config.php');
$date_break = '2016-07-04';
if($_SESSION['user_type'] == 'Admin'){
	$client = mysqli_query($con, "SELECT id, fname FROM `tb_user` WHERE user_type = 'Client' AND user_status != 'Inactive'  ORDER BY `id` ASC ");
} else {
	$client = mysqli_query($con, "SELECT id, fname FROM `tb_user` WHERE id = '".$_SESSION['user_id']."' ORDER BY `id` ASC ");
}

while ($row = mysqli_fetch_array($client)) {

	echo "{name: '".$row["fname"]." (Projects)', data: [";
	for ($i = 0 ; $i < 15 ; $i++) {
		$curr_date1 = date("Y-m-d", strtotime('-'.$i.' days'));
		$date_array[] = $curr_date1;
	    $prj = mysqli_query($con, "SELECT COUNT(`prj_id`) AS PROJECT FROM `tb_record` WHERE client_id = '".$row["id"]."' AND creation_time LIKE '".$curr_date1."' ");
	    while ($row2 = mysqli_fetch_array($prj)) {
		  	if($row2["PROJECT"]){
		  		echo $row2["PROJECT"].',';
		  	}
		  	else{
		  		echo '0 ,';
		  	}
		}
	}
 	echo '], stack: "Projects"},';

	echo "{name: '".$row["fname"]." (Blueprints)', data: [";
	for ($i = 0 ; $i < 15 ; $i++) {
	$curr_date = date("Y-m-d", strtotime('-'.$i.' days'));
		if ($curr_date < $date_break) {
			$bprint = mysqli_query($con, "SELECT SUM(`blueprint`) AS BPRINT FROM `tb_record` WHERE client_id = '".$row["id"]."' AND creation_time LIKE '".$curr_date."' ");
		  	while ($row1 = mysqli_fetch_array($bprint)) {
			  	if($row1["BPRINT"]){
			  		echo $row1["BPRINT"].',';
			  	}
			  	else{
			  		echo '0 ,';
			  	}
		  	}
		} else {
			//echo "SELECT COUNT(raw_img_id) AS BPRINT FROM `tb_raw_img` INNER JOIN tb_record on `tb_raw_img`.`prj_id_link` = `tb_record`.`prj_id` WHERE client_id = '".$row["id"]."' AND creation_time LIKE '".$curr_date."'";
			$bprint = mysqli_query($con, "SELECT COUNT(raw_img_id) AS BPRINT FROM `tb_raw_img` INNER JOIN tb_record on `tb_raw_img`.`prj_id_link` = `tb_record`.`prj_id` WHERE client_id = '".$row["id"]."' AND creation_time LIKE '".$curr_date."'");
			while ($row1 = mysqli_fetch_array($bprint)) {
			  	if($row1["BPRINT"]){
			  		echo $row1["BPRINT"].',';
			  	}
			  	else{
			  		echo '0 ,';
			  	}
		  	}
		}
	  	
	}
 	echo '], stack: "Blueprints"},';
}

?>
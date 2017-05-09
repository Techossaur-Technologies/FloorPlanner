<?php
require_once("../includes/config.php");

$client_id = $_POST["client"];
$query = 'SELECT company_id, company_name FROM tb_company WHERE assigned_client IN ("'.$client_id.'", "0")';
$res = mysqli_query($con, $query);
$num_row = mysqli_num_rows($res);
if($num_row > 0)
{
	while($result = @mysqli_fetch_assoc($res))
	{ $company[] = $result; }
 }
echo json_encode($company);
?>
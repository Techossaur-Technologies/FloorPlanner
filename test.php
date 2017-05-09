<?php
    $target_url = 'http://boligfotograf.net/admin_test/accept_floorplanner_assignment.php';
    $post = array('app_id' => '123456');
 
 	$post = array('bolig_app_id' => '123456', 'bolig_file_array' => array('http://floorplanner.coredigita.com/uploads/2016/09/FP-ID922-23147462273530.jpg', 'http://floorplanner.coredigita.com/uploads/2016/09/FP-ID922-23147462273830.jpg'));


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$target_url);
    curl_setopt($ch, CURLOPT_POST,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $result = curl_exec ($ch);
    curl_close ($ch);
    echo $result;
?>



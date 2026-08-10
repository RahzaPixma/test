<?php

include 'conn_cli.php';

$sql = "SELECT * FROM tbm_duration WHERE item = 'blinking'";

$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);

if ($result){
		$ss = json_encode(array(
			'set_azan_blinking' => $row['duration']
		));
	echo $ss;
	
//	file_put_contents("out_submit.txt", $ss);
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}

mysqli_free_result($result);

?>
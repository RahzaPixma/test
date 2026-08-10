<?php

include 'conn_cli.php';

$sql = "SELECT text,speed FROM tbm_scroller";

$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_row($result);

if ($result){
		$ss = json_encode(array(
			'set_scroller_text' => $row[0],
			'set_scroller_speed' => $row[1]
		));
	echo $ss;
	
//	file_put_contents("out_submit.txt", $ss);
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}


?>
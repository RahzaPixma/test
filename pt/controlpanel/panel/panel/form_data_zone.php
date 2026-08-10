<?php

include 'conn_cli.php';

$sql = "SELECT zone, lokasi FROM tbm_zone WHERE id=1";

$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_row($result);

if ($result){
		$ss = json_encode(array(
			'set_zone' => $row[0],
			'set_lokasi' => $row[1]
		));
	echo $ss;
//	file_put_contents("out_submit.txt", $ss);
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}

?>
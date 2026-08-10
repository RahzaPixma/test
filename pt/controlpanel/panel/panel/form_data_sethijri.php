<?php

include 'conn_cli.php';

$sql = "SELECT hijri_offset FROM tbm_hijrioffset";

$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_row($result);

if ($result){
		$ss = json_encode(array(
			'set_hijrioffset' => $row[0]
		));
	echo $ss;
	
//	file_put_contents("out_submit.txt", $ss);
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}

mysqli_free_result($result);
mysqli_close($conn);

?>
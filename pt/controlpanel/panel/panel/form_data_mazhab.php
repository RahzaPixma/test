<?php

include 'conn.php';

$sql = "SELECT type FROM tbm_mazhab WHERE id=1";

$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_row($result);

if ($result){
		$ss = json_encode(array(
			'mazhab_status' => $row[0]
		));
	echo $ss;
//	file_put_contents("out_submit.txt", $ss);
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}

?>
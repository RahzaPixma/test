<?php

include 'conn_cli.php';

$sql = "SELECT anim FROM tbm_anim WHERE id=1";

$result = mysqli_query($sql);
$row = mysqli_fetch_row($result);

if ($result){
		$ss = json_encode(array(
			'set_anim' => $row[0],
		));
	echo $ss;
//	file_put_contents("out_submit.txt", $ss);
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}

?>
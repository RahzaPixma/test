<?php

include 'conn_cli.php';

$sql = "SELECT * FROM tbm_azan";

$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);

if ($result){
		$ss = json_encode(array(
			'set_azan_zohor' => $row['zohor'],
			'set_azan_asar' => $row['asar'],
			'set_azan_maghrib' => $row['maghrib'],
			'set_azan_isyak' => $row['isyak'],
			'set_azan_imsak' => $row['imsak'],
			'set_azan_subuh' => $row['subuh'],
			'set_azan_syuruk' => $row['syuruk'],
			'set_azan_jumaat' => $row['jumaat']			
		));
	echo $ss;
	
//	file_put_contents("out_submit.txt", $ss);
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}

mysqli_free_result($result);

?>
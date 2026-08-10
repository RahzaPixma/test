<?php

include 'conn_cli.php';

$sql = "SELECT * FROM sleep_event where id = 1";


$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_row($result);

$tarikh_mulasleep = substr($row[1],0,10);
$masa_mulasleep = substr($row[1],11,8);

//$tarikh_tamatsleep = substr($row[2],0,10);
$tarikh_tamatsleep = substr($row[1],0,10);
$masa_tamatsleep = substr($row[2],11,8);
$ulang_sleep = $row[3];

if ($result){
		$ss = json_encode(array(
			'set_tarikh_mulasleep' => $tarikh_mulasleep,
			'set_tarikh_tamatsleep' => $tarikh_tamatsleep,
			'set_masa_mulasleep' => $masa_mulasleep,
			'set_masa_tamatsleep' => $masa_tamatsleep,
			'ulang_sleep' => $ulang_sleep
		));
	echo $ss;
//	file_put_contents("out_submit.txt", $ss);
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}

?>
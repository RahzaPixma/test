<?php

$set_tarikh_mula = htmlspecialchars($_REQUEST['set_tarikh_mulasleep']);
//$set_tarikh_tamat = htmlspecialchars($_REQUEST['set_tarikh_tamatsleep']);
$set_tarikh_tamat = htmlspecialchars($_REQUEST['set_tarikh_mulasleep']);

$set_masa_mula = htmlspecialchars($_REQUEST['set_masa_mulasleep']);
$set_masa_tamat = htmlspecialchars($_REQUEST['set_masa_tamatsleep']);
$ulang_sleep = htmlspecialchars($_REQUEST['ulang_sleep']);

$set_datetime_mula = $set_tarikh_mula . ' ' . $set_masa_mula;
$set_datetime_tamat = $set_tarikh_tamat . ' ' . $set_masa_tamat;


include 'conn_cli.php';

$sql = "update sleep_event set startdate='$set_datetime_mula', enddate='$set_datetime_tamat', ulang=$ulang_sleep where id=1";

$result = mysqli_query($conn,$sql);

if ($result){
   echo 'Kemaskini = Mula ' . $set_datetime_mula . ', Tamat ' . $set_datetime_tamat . ', Ulang ' . $ulang_sleep;
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>
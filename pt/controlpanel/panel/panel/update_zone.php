<?php

$set_zone = htmlspecialchars($_REQUEST['set_zone']);
$set_lokasi = htmlspecialchars($_REQUEST['set_lokasi']);


include 'conn_cli.php';

$sql = "update tbm_zone set zone='$set_zone', lokasi='$set_lokasi' where id=1";

$result = mysqli_query($conn,$sql);

if ($result){

echo 'zone = ' . $set_zone;
echo '<br>';
echo 'lokasi = ' . $set_lokasi;
 
/*
	echo json_encode(array(
		'set_zone' => $set_zone,		
		'set_lokasi' => $set_lokasi	
	));
*/	
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>
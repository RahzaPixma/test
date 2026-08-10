<?php

$id = intval($_REQUEST['id']);
$event = htmlspecialchars($_REQUEST['event']);
$tarikh = htmlspecialchars($_REQUEST['tarikh']);
$status = htmlspecialchars($_REQUEST['status']);
$autohide = htmlspecialchars($_REQUEST['autohide']);


include 'conn_cli.php';

$sql = "update countdown set event='$event',tarikh='$tarikh',status=$status, autohide=$autohide where id=$id";

/*
//debug
echo $sql;
$file = "debug.txt";
file_put_contents($file, $sql);
*/

$result = mysqli_query($conn,$sql);
if ($result){
	echo json_encode(array(
		'id' => $id,
		'event' => $event,
		'tarikh' => $tarikh,
		'status' => $status,
		'autohide' => $autohide
		
	));
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>
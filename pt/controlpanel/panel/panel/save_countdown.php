<?php

$event = htmlspecialchars($_REQUEST['event']);
$tarikh = htmlspecialchars($_REQUEST['tarikh']);
$status = htmlspecialchars($_REQUEST['status']);
$autohide = htmlspecialchars($_REQUEST['autohide']);

include 'conn_cli.php';

$sql = "insert into countdown(event,tarikh,status,autohide) values('$event','$tarikh',$status,$autohide)";

/*
//debug
echo $sql;
$file = "debug.txt";
file_put_contents($file, $sql);
*/

$result = mysqli_query($conn,$sql);
if ($result){
	echo json_encode(array(
//		'id' => mysqli_insert_id(),
		'event' => $event,
		'tarikh' => $tarikh,
		'status' => $status,
		'autohide' => $autohide
		
	));
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}

?>
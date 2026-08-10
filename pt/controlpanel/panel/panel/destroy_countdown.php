<?php

$id = intval($_REQUEST['id']);

include 'conn_cli.php';

$sql = "delete from countdown where id=$id";
$result = @mysqli_query($conn,$sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>
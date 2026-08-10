<?php

$set_anim = htmlspecialchars($_REQUEST['set_anim']);

include 'conn_cli.php';

$sql = "update tbm_anim set anim='$set_anim' where id=1";

$result = mysqli_query($conn, $sql);

if ($result){
   echo 'Animation = ' . $set_anim;
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>
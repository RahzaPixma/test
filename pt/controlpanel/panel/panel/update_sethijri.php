<?php
$set_hijrioffset = htmlspecialchars($_REQUEST['set_hijrioffset']);

include 'conn_cli.php';

$sql = "update tbm_hijrioffset set hijri_offset='$set_hijrioffset'";

$result = @mysqli_query($conn,$sql);


if ($result){
	echo 'Offset Hijri = ' . $set_hijrioffset;
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>


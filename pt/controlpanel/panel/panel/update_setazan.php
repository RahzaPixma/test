<?php

$set_azan_zohor = htmlspecialchars($_POST['set_azan_zohor']);
$set_azan_asar = htmlspecialchars($_POST['set_azan_asar']);
$set_azan_maghrib = htmlspecialchars($_POST['set_azan_maghrib']);
$set_azan_isyak = htmlspecialchars($_POST['set_azan_isyak']);
$set_azan_imsak = htmlspecialchars($_POST['set_azan_imsak']);
$set_azan_subuh = htmlspecialchars($_POST['set_azan_subuh']);
$set_azan_syuruk = htmlspecialchars($_POST['set_azan_syuruk']);
$set_azan_jumaat = htmlspecialchars($_POST['set_azan_jumaat']);


include 'conn_cli.php';

$sql = "update tbm_azan set zohor='$set_azan_zohor', asar='$set_azan_asar', maghrib='$set_azan_maghrib', isyak='$set_azan_isyak', imsak='$set_azan_imsak', subuh='$set_azan_subuh', syuruk='$set_azan_syuruk', jumaat='$set_azan_jumaat'";
$result = @mysqli_query($conn,$sql);

?>
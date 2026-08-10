<?php
$hari = htmlspecialchars($_REQUEST['hari']);
$subuh = htmlspecialchars($_REQUEST['set_solat_' . strtolower($hari) . '_subuh']);
$zohor = htmlspecialchars($_REQUEST['set_solat_' . strtolower($hari) . '_zohor']);
$asar = htmlspecialchars($_REQUEST['set_solat_' . strtolower($hari) . '_asar']);
$maghrib = htmlspecialchars($_REQUEST['set_solat_' . strtolower($hari) . '_maghrib']);
$isyak = htmlspecialchars($_REQUEST['set_solat_' . strtolower($hari) . '_isyak']);
$screen = htmlspecialchars($_REQUEST['set_solat_' . strtolower($hari) . '_screen']);
$beep = htmlspecialchars($_REQUEST['set_solat_' . strtolower($hari) . '_beep']);

include 'conn_cli.php';

$sql = "update tbm_solat set subuh=$subuh,zohor=$zohor,asar=$asar,maghrib=$maghrib,isyak=$isyak,screen='$screen',beep=$beep where hari='$hari'";
$result = @mysqli_query($conn,$sql);
?>
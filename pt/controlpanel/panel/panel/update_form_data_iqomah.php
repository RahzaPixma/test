<?php
$hari = htmlspecialchars($_REQUEST['hari']);
$subuh = htmlspecialchars($_REQUEST['set_iqomah_' . strtolower($hari) . '_subuh']);
$zohor = htmlspecialchars($_REQUEST['set_iqomah_' . strtolower($hari) . '_zohor']);
$asar = htmlspecialchars($_REQUEST['set_iqomah_' . strtolower($hari) . '_asar']);
$maghrib = htmlspecialchars($_REQUEST['set_iqomah_' . strtolower($hari) . '_maghrib']);
$isyak = htmlspecialchars($_REQUEST['set_iqomah_' . strtolower($hari) . '_isyak']);

include 'conn_cli.php';

$sql = "update tbm_iqomah set subuh=$subuh,zohor=$zohor,asar=$asar,maghrib=$maghrib,isyak=$isyak where hari='$hari'";

//$sql = "SELECT subuh,zohor,asar,maghrib,isyak FROM tbm_iqomah WHERE hari = '$hari'";

$result = @mysqli_query($conn,$sql);

if ($result){
		$ss = json_encode(array(
			'set_iqomah_' . strtolower($hari) . '_subuh' => $subuh,
			'set_iqomah_' . strtolower($hari) . '_zohor' => $zohor,
			'set_iqomah_' . strtolower($hari) . '_asar' => $asar,
			'set_iqomah_' . strtolower($hari) . '_maghrib' => $maghrib,
			'set_iqomah_' . strtolower($hari) . '_isyak' => $isyak		
		));
	echo $ss;
//	file_put_contents("out_submit.txt", $ss);
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}

?>
<?php
$hari = htmlspecialchars($_REQUEST['hari']);
include 'conn_cli.php';

$sql = "SELECT subuh,zohor,asar,maghrib,isyak FROM tbm_iqomah WHERE hari = '$hari'";

$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_row($result);

if ($result){
		$ss = json_encode(array(
			'set_iqomah_' . strtolower($hari) . '_subuh' => $row[0],
			'set_iqomah_' . strtolower($hari) . '_zohor' => $row[1],
			'set_iqomah_' . strtolower($hari) . '_asar' => $row[2],
			'set_iqomah_' . strtolower($hari) . '_maghrib' => $row[3],
			'set_iqomah_' . strtolower($hari) . '_isyak' => $row[4]		
		));
	echo $ss;
//	file_put_contents("out_submit.txt", $ss);
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>
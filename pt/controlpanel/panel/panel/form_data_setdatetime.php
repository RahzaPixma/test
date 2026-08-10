<?php

$flag = trim(file_get_contents('/var/www/html/pt/controlpanel/panel/panel/chktime/flagsync.dat'));
//echo "jjj".$flag."kkk";
$ss="";
if (strlen($flag)){

	$ss="";
	if( $flag === '1') {
		$ss = json_encode(array(
			'set_autosync' => 'Ya',
		));
	} 
	else {
		$ss = json_encode(array(
			'set_autosync' => 'Tidak',
		));
	}

	echo $ss;
//	file_put_contents("out_submit.txt", $ss);
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}

?>
<?php

include 'conn_cli.php';

$sql = "SELECT item,duration FROM tbm_duration";

$result = mysqli_query($conn,$sql);
//var_dump($result);

///
//$rows = mysqli_fe
///

if ($result){
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
 //  echo "row = " . $row["item"];
		if ($row['item'] === 'slide') $values['slide'] = $row['duration'];
		if ($row['item'] === 'worldclock') $values['worldclock'] = $row['duration'];
		if ($row['item'] === 'taqwim') $values['taqwim'] = $row['duration'];
		if ($row['item'] === 'countdown') $values['countdown'] = $row['duration'];
		if ($row['item'] === 'jadualkuliah') $values['jadualkuliah'] = $row['duration'];		
//		$kk=$kk . $row['item'] . ',' . $row['duration'] . '\n';
	}
	
	$ss = json_encode(array(
			'masa_slide' => $values['slide'],
			'masa_worldclock' => $values['worldclock'],
			'masa_taqwim' => $values['taqwim'],
			'masa_countdown' => $values['countdown'],
			'masa_jadualkuliah'  => $values['jadualkuliah']		
		));
	echo $ss;
//	file_put_contents("out_submit.txt", $kk);
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
	

/*

$values = array();
$kk="jjjjjjjjj";
echo $kk;
if ($result){
	                    //while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
echo "dghdhhd";
//echo $row["item"];
		if ($row['item'] === 'slide') $values['slide'] = $row['duration'];
		if (tch_array($result);
//var_dump($rows);$row['item'] === 'worldclock') $values['worldclock'] = $row['duration'];
		if ($row['item'] === 'taqwim') $values['taqwim'] = $row['duration'];
		if ($row['item'] === 'countdown') $values['countdown'] = $row['duration'];
		if ($row['item'] === 'jadualkuliah') $values['jadualkuliah'] = $row['duration'];		
		$kk=$kk . $row['item'] . ',' . $row['duration'] . '\n';
	}
	
	$ss = json_encode(array(
			'masa_slide' => $values['slide'],
			'masa_worldclock' => $values['worldclock'],
			'masa_taqwim' => $values['taqwim'],
			'masa_countdown' => $values['countdown'],
			'masa_jadualkuliah'  => $values['jadualkuliah']		
		));
	echo $ss;
	
//	file_put_contents("out_submit.txt", $kk);
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
*/
mysqli_free_result($result);

?>
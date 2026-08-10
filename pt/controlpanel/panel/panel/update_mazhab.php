<?php

$type = htmlspecialchars($_REQUEST['mazhab_status']);


include 'conn_cli.php';

$sql = "update tbm_mazhab set type=$type where id=1";

/*
//debug
echo $sql;
$file = "debug.txt";
file_put_contents($file, $sql);
*/

$result = @mysqli_query($conn,$sql);
if ($result){
/*
	echo json_encode(array(
		'mazhab_status' => $type		
	));
*/
  echo "Mazhab = " . $type;	
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>
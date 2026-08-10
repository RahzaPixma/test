<?php

$masa_slide = $_POST['masa_slide'];
$masa_taqwim = $_POST['masa_taqwim'];
$masa_worldclock = $_POST['masa_worldclock'];
$masa_jadualkuliah = $_POST['masa_jadualkuliah'];
$masa_countdown = $_POST['masa_countdown'];


include 'conn_cli.php';

$sql = "UPDATE tbm_duration SET duration=$masa_slide WHERE item = 'slide'";
$result = @mysqli_query($conn,$sql);

$sql = "UPDATE tbm_duration SET duration=$masa_taqwim WHERE item = 'taqwim'";
$result = @mysqli_query($conn,$sql);

$sql = "UPDATE tbm_duration SET duration=$masa_worldclock WHERE item = 'worldclock'";
$result = @mysqli_query($conn,$sql);

$sql = "UPDATE tbm_duration SET duration=$masa_jadualkuliah WHERE item = 'jadualkuliah'";
$result = @mysqli_query($conn,$sql);

$sql = "UPDATE tbm_duration SET duration=$masa_countdown WHERE item = 'countdown'";
$result = @mysqli_query($conn,$sql);


if ($result){

echo 'slide = ' . $masa_slide . ', ';
echo 'taqwim = ' . $masa_taqwim . ', ';
echo 'worldclock = ' . $masa_worldclock . ', ';
echo 'jadualkuliah = ' . $masa_jadualkuliah . ', ';
echo 'countdown = ' . $masa_countdown;

} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}

//echo "slide: $masa_slide <br>taqwim: $masa_taqwim";
//$ss = "slide: $masa_slide \n taqwim: $masa_taqwim";
//file_put_contents("out_submit.txt", $ss);

?>
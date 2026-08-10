<?php

$set_tarikh = $_POST['set_tarikh'];
$set_time = $_POST['set_time'];
//$set_hijrioffset = htmlspecialchars($_REQUEST['set_hijrioffset']);


//include 'conn.php';

//$sql = "update tbm_hijrioffset set hijri_offset='$set_hijrioffset'";

//$result = @mysql_query($sql);


/************ASAL*************
$set_tarikh = $_POST['set_tarikh'];
$set_time = $_POST['set_time'];

//include 'conn.php';

echo "tarikh: $set_tarikh <br>time: $set_time:00";
$ss = "set_tarikh: $set_tarikh \n set_time: $set_time";
//file_put_contents("out_submit.txt", $ss);


$d = $set_tarikh;

$t = $_POST["time"] . ':00';
$dt = '"' . $d . ' ' . $t .'"';
**********/

//echo 'sudo date -s ' . $dt . '</br>';
//shell_exec('sudo date -s "2013-01-31 23:59:00"'); -success

shell_exec('sudo date -s ' . '"' . $set_tarikh . ' ' . $set_time . ':00"');
shell_exec('sudo hwclock -w');
echo shell_exec('date');
///



?>


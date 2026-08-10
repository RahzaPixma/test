<?php
date_default_timezone_set('Asia/Singapore');
include 'conn_cli.php';
$today = new DateTime();
$format_today = $today->format('Y-m-d');

/*
$rs = mysqli_query($conn,"select * from template_kuliah where autodelete = 0 and tarikh < '$format_today'");
//var_dump($rs);	
while(($row = mysqli_fetch_array($rs,MYSQLI_ASSOC))) {
    echo "tajuk = " . $row['tajuk'] . "<br>";
    echo "autodelete = " . $row['autodelete'] . "<br>";
}
*/

$sql = "delete from template_kuliah where autodelete = 0 and tarikh < '$format_today'";
$result = mysqli_query($conn,$sql);
?>

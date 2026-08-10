<?php

$mazhab_status = $_POST['mazhab_status'];


//include 'conn.php';

$ss = "mazhab = $mazhab_status";
echo $ss;
file_put_contents("out_submit.txt", $ss);

?>
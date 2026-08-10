<?php

$set_scroller_text = htmlspecialchars($_REQUEST['set_scroller_text']);
$set_scroller_speed = htmlspecialchars($_REQUEST['set_scroller_speed']);

include 'conn_cli.php';

$sql = "update tbm_scroller set text = '$set_scroller_text', speed =$set_scroller_speed";

$result = mysqli_query($conn,$sql);

?>

<?php

$set_azan_blinking = htmlspecialchars($_POST['set_azan_blinking']);

include 'conn_cli.php';

$sql = "update tbm_duration set duration = $set_azan_blinking where item = 'blinking'";
$result = @mysqli_query($conn,$sql);

?>
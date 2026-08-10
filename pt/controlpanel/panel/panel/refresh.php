<?php

include 'conn_cli.php';

$sql = "update tmp_indexfiles set flag = 1";
$result = @mysqli_query($sql);

?>
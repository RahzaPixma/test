<?php

//$link = mysqli_connect("localhost","root","suhair007");

include 'conn_cli.php';
//mysqli_select_db($link,"pt"); 

$result = mysqli_query($conn, "SELECT * FROM test");
//$num_rows = mysqli_num_rows($result);
//$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
   echo "row = " . $row["name"];
}

mysqli_free_result($result);

mysqli_close($link);


//echo "$num_rows Rows\n";


?>

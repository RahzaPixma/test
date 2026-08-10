<?php

// Connect to DB
$conn=mysqli_connect("localhost",'root','suhair007','pt');
//or die(mysqli_connect_error());
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  die;
  }

?>
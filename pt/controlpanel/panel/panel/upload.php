<?php

//$targetPath = '../../../templateslides/kuliahslides/um-screen.jpg';
//$file_slide = $_FILES['file1']['tmp_name'];

$file_slide = $_FILES['file_slide']['tmp_name'];
//echo $file_slide;
$targetPath = '../../../templateslides/kuliahslides/' . $_FILES['file_slide']['name'];

if(move_uploaded_file($file_slide,$targetPath)) {
	echo "<img src=\"$targetPath\" width=\"100px\" height=\"56px\" />";	
  }
  else echo "<h3 style=\"color:red;\">Error upload file</h3>";
?>

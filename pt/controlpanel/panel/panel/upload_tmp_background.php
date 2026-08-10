<?php

//$targetPath = '../../../templateslides/kuliahslides/um-screen.jpg';
//$file_slide = $_FILES['file1']['tmp_name'];

$file_background = $_FILES['file_background']['tmp_name'];
//echo $file_slide;
$targetPath = '../../../bg/tmp_bg_taqwim.jpg';

if(move_uploaded_file($file_background,$targetPath)) {
	echo "<img src=\"$targetPath\" width=\"200px\" height=\"118px\" />";	
  }
  else echo "<h3 style=\"color:red;\">Error upload file</h3>";
?>

<?php

//$targetPath = '../../../templateslides/kuliahslides/um-screen.jpg';
//$file_slide = $_FILES['file1']['tmp_name'];

$file_background = '../../../bg/tmp_bg_taqwim.jpg';
//echo $file_slide;
$targetPath = '../../../bg/bg-taqwim.jpg';

//$b = unlink($targetPath);

//$a = rename($file_background, $targetPath);
//$b = unlink($targetPath);
//$c = copy($file_background, $targetPath);

if( file_exists($file_background) ) {

	if( rename($file_background, $targetPath) ) {
		//$result["result"] = "Berjaya upload image background taqwim";
		echo "Berjaya upload image background taqwim"; // json_encode($result); 
	}
	  else {
		//$result["result"] = "Gagal upload file";
		echo "Gagal upload file"; // json_encode($result); 
	  }
}
else echo "Error!!! <br>File belum upload";

?>

<?php

$id = intval($_REQUEST['id']);

include 'conn_cli.php';

$rs = mysqli_query($conn,"SELECT * FROM template_kuliah_tetap WHERE id=$id");
$row = mysqli_fetch_array($rs, MYSQL_ASSOC);
$file_slide = $row["file_slide"];
mysqli_free_result($rs);

$sql = "delete from template_kuliah_tetap where id=$id";
$result = @mysqli_query($conn,$sql);
if ($result){
	echo json_encode(array('success'=>true));
	$targetPath = "../../../templateslideskt/kuliahslides/" . $file_slide;
	unlink($targetPath);
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>
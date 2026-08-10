<?php

$id = intval($_REQUEST['id']);
$header = htmlspecialchars($_REQUEST['header']);
$tarikh = htmlspecialchars($_REQUEST['tarikh']);
$tajuk = htmlspecialchars($_REQUEST['tajuk']);
$hari = htmlspecialchars($_REQUEST['hari']);
$waktu = htmlspecialchars($_REQUEST['waktu']);
$penceramah = htmlspecialchars($_REQUEST['penceramah']);
$tempat = htmlspecialchars($_REQUEST['tempat']);
$catatan = htmlspecialchars($_REQUEST['catatan']);
$susunan = htmlspecialchars($_REQUEST['susunan']);
$status = htmlspecialchars($_REQUEST['status']);
$filetemplate = htmlspecialchars($_REQUEST['filetemplate']);
$show_slide = htmlspecialchars($_REQUEST['show_slide']);
$file_slide = htmlspecialchars($_FILES['file_slide']['name']);
//$masa = htmlspecialchars($_REQUEST['masa']);
$batal = htmlspecialchars($_REQUEST['batal']);
$autodelete = htmlspecialchars($_REQUEST['autodelete']);

//cater error x amik blk name file_slide (jgn update file_slide kalu diorg x usik)
if( strlen($file_slide) < 2 )
  $sql = "update template_kuliah set header='$header',tarikh='$tarikh',tajuk='$tajuk',hari='$hari',waktu='$waktu',penceramah='$penceramah',tempat='$tempat',catatan='$catatan',susunan=$susunan,status=$status,filetemplate='$filetemplate',show_slide='$show_slide', batal=$batal, autodelete=$autodelete where id=$id";
else 
  $sql = "update template_kuliah set header='$header',tarikh='$tarikh',tajuk='$tajuk',hari='$hari',waktu='$waktu',penceramah='$penceramah',tempat='$tempat',catatan='$catatan',susunan=$susunan,status=$status,filetemplate='$filetemplate',show_slide='$show_slide',file_slide='$file_slide', batal=$batal, autodelete=$autodelete where id=$id";

include 'conn_cli.php';

/*
//debug
echo $sql;
$file = "debug.txt";
file_put_contents($file, $sql);
*/

$result = mysqli_query($conn,$sql);
if ($result){
	echo json_encode(array(
		'id' => $id,
		'header' => $header,
		'tarikh' => $tarikh,
		'tajuk' => $tajuk,
		'hari' => $hari,
		'waktu' => $waktu,
		'penceramah' => $penceramah,
		'tempat' => $tempat,
		'catatan' => $catatan,
		'susunan' => $susunan,
		'status' => $status,
		'filetemplate' => $filetemplate,
		'show_slide' => $show_slide,
		'file_slide' => $file_slide,
//		'masa' => $masa,
		'batal' => $batal,
		'autodelete' => $autodelete
		
	));
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>
<?php

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


include 'conn_cli.php';

//bypass susunan
//$susunan = 0;

$sql = "insert into template_kuliah(header,tarikh,tajuk,hari,waktu,penceramah,tempat,catatan,susunan,status,filetemplate,show_slide,file_slide,batal,autodelete) values('$header','$tarikh','$tajuk','$hari','$waktu','$penceramah','$tempat','$catatan',$susunan,$status,'$filetemplate','$show_slide','$file_slide',$batal,$autodelete)";

/*
//debug
echo $sql;
$file = "debug.txt";
file_put_contents($file, $sql);
*/

$result = mysqli_query($conn,$sql);
if ($result){
	echo json_encode(array(
//		'id' => mysqli_insert_id(),
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
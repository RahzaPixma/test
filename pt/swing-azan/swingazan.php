<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Azan</title>


  <style type="text/css">

body {
  overflow: hidden;
  height: 90%;
  width: 100%;
  min-height: 100%; 
  min-width: 105%;
  margin: 0;
  padding: 0;
}

html {
 overflow: hidden;
 height: 90%;
 width: 105%;
 min-height: 100%;
 min-width: 100%;
}

.fullheight {
  display: block;
  position: relative;
  background: white;
  height: 90%;
  width: 105%;
}

</style>


		<link rel="stylesheet" type="text/css" href="swing.css" media="all" />
		<script src="jquery-1.7.1.min.js"></script>
		<script src="swing.js"></script>
<?php 
//echo shell_exec('whoami');
//echo "jkdjflskjfkldsjgl<br/>";

//Load config file
//$config = parse_ini_file('../setting/setting.ini');
//include "../controlpanel/panel/panel/conn_cli.php";

?>		

<script type="text/javascript">
 var masukwaktuduration = 1000; 




 //to move to next page
		<!--
			var time = null
			function move() {
			window.location = '../videoazan.php?wnow=<?php	echo  $_GET['wnow']; ?>';
			}
			//-->
 
 
</script>		
</head>
		
	<body onload="timer=setTimeout('move()',masukwaktuduration)" class="fullheight">
	

		<header>
			<img id='swing' src="red-sign.jpg?v=9" alt="">  
		</header>	

<?php 

date_default_timezone_set('Asia/Singapore');

$namasolat = array ( "Awalpg",  "Imsak", "Subuh", "Syuruk", "Zohor", "Asar", "Maghrib", "Isyak", "Tghmlm");
$wnow = $_GET['wnow'];

//$text_scroll = 'AZAN!!! - Sekarang telah masuk waktu ' . $namasolat[$wnow] . ' bagi ' . $config['lokasi'] . ' dan kawasan-kawasan yang sewaktu dengannya....';

$text_scroll = 'Sekarang telah masuk waktu ' . $namasolat[$wnow];
//' bagi ' . $config['lokasi'] . ' dan </br>kawasan-kawasan sama waktu dengannya...';
//$space = strlen($text_scroll) * 60; 

?>
		
		
<div id="marquee2" style="position: absolute; top: 480px; left: 80px; width: 1280px; height: 150px; z-index: 5; font-family: Arial Black; font-size: 60px; color: red; z-index:5;">


<?php 
echo $text_scroll;
?>

</div>  			

    <div  id="jomsolat" style="position: absolute; top: 100px; left: 820px; width: 400px; height: 125px; font-family: Arial Black; font-size: 63px; color: white;">

  <?php 
 //  $harini = time();
 //  echo date('g : i',$harini) . "</br>";
 //echo shell_exec('mpg321 ../sound/azanmekah.mp3'); - success
 
 //echo shell_exec('omxplayer -o hdmi ../sound/' . $config['azanvideo']); //success

 ?>
  <img src="../bg/jomsolat.jpg"> </img> 
  </div>				
	</body>
</html>
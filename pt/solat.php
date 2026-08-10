<html>
			<head>



  <style type="text/css">

body {
  overflow: hidden;
  height: 90%;
  width: 100%;
  min-height: 100%; 
  min-width: 105%;
  margin: 0;
  padding: 0;
  background: black;
}

html {
 overflow: hidden;
 height: 90%;
 width: 105%;
 min-height: 100%;
 min-width: 100%;
 background: black;
}

.fullheight {
  display: block;
  position: relative;
  background: black;
  height: 90%;
  width: 105%;
}

</style>


			<?php
			//Load config file
//			$config = parse_ini_file('setting/setting.ini');
date_default_timezone_set('Asia/Singapore');
include "./controlpanel/panel/panel/conn_cli.php";
include "./hijrah/Hijri_GregorianConvert.class";

$namahari_rujuk_solat = getNamaHari( Date("D") );
$query = "SELECT * FROM tbm_solat";
$result = mysqli_query($conn, $query);
$config = array();
$config['subuh'] =  0;
$config['zohor'] =  0;
$config['asar'] =  0;
$config['maghrib'] =  0;
$config['isyak'] =  0;
$config['jumaat'] =  0;
$config['screen'] = '';
$config['beep'] = 0;

while(($row = mysqli_fetch_array($result,MYSQLI_ASSOC))) {

	if ( $row['hari'] === $namahari_rujuk_solat ) {
		$config['subuh'] =  $row['subuh'];
		$config['zohor'] =  $row['zohor'];
		$config['asar'] =  $row['asar'];
		$config['maghrib'] =  $row['maghrib'];
		$config['isyak'] =  $row['isyak'];
		$config['jumaat'] =  $row['zohor'];
		$config['screen'] = $row['screen'];
		$config['beep'] = $row['beep'];
	}
}

$solat_filename = '';
$namasolat = array ( "awalpg",  "imsak", "subuh", "syuruk", "zohor", "asar", "maghrib", "isyak", "tghmlm");
$wnow = $_GET['wnow'];
$namasolat_now = $namasolat[$wnow];
//var_dump($config);
//echo $namasolat_now;
//die;

//Ver 4.1////////////////////////////////////////////////////////////////////////////////////////////////
//echo "namahari=" . $namahari_rujuk_solat . "<br>";
//echo "waktu=" . $namasolat_now . "<br>";

if( $config['screen'] === 'Hitam' )
  $solat_filename = 'bg/blank.jpg';
 
if( $config['screen'] === 'Standard' )
  $solat_filename = 'bg/solat.jpg';

if( $config['screen'] === 'Khas' )
  $solat_filename = 'bg/khas.jpg';



if( $namahari_rujuk_solat === "JUMAAT" && $namasolat_now === "zohor" ) {
	if( $config['screen'] === 'Hitam' )
  		$solat_filename = 'bg/blank.jpg';
	else 
  		$solat_filename = 'bg/solat_jumaat.jpg';
 }

if( $config['beep'] == 1 ) {
//////////////////////////////////////////////////////////////////////////////////////////////////
//NOT STABLE 
  shell_exec('omxplayer -o hdmi ./sound/beepsolat.mp4 > /dev/null 2>&1'); //success
///var/www/html/pt/sound/beepsolat.mp4
//////////////////////////////////////////////////////////////////////////////////////////////////
}

if( $config['beep'] == 2 ) {
//////////////////////////////////////////////////////////////////////////////////////////////////
//NOT STABLE 
  shell_exec('omxplayer -o hdmi ./sound/beduksolat.mp4 > /dev/null 2>&1'); //success
///var/www/html/pt/sound/beepsolat.mp4
//////////////////////////////////////////////////////////////////////////////////////////////////
}

mysqli_free_result($result);


			?>



	<link href="./style-clock/assets/css/style.css" rel="stylesheet" type="text/css" />
    <script src="./src/jquery.min.js" type="text/javascript"></script>
    <script src="./src/jquery.counter.js" type="text/javascript"></script>


			
			<title>Solat</title>
			<script language="JavaScript">
			
			setTimeout('move()',<?php
				   $harini_today = getdate();
				   $harini_day = $harini_today['weekday'];
				   if( strcmp($harini_day,"Friday") == 0 && strcmp($wnow,"4") == 0 )  echo $config['jumaat']*1000*60; 
				   else  echo $config[$namasolat_now]*1000*60; 				
		 	?>);			

			function move() {
			window.location = '/pt/slides/src/slide.php';
			}
			</script>
			</head>
			<body class="fullheight">
			
			<div class="background" style="position: absolute; top: 0; left: 0; width: 1280px; height: 720px; background-image:url('<?php echo $solat_filename; ?>');
background-repeat:no-repeat; background-attachment:fixed; background-position:center;"> 
</div>
			
<div id="clock" class="dark" style="position: absolute; top: 427px; left: 870px; width: 350px; height: 100px; z-index: 6;">

			<div class="display">
				<div class="weekdays"></div>
				<div class="ampm"></div>
				<div class="alarm"></div>
				<div class="digits"></div>
			</div>
</div>



</div>        
  
	<!-- JavaScript Includes -->
		<script src="./style-clock/moment.min.js"></script>
		<script src="./style-clock/assets/js/script.js"></script>
  


</body>
			
</html>
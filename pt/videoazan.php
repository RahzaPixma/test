<html>
<head>
<?php
include "./controlpanel/panel/panel/conn_cli.php";
$query = "SELECT * FROM tbm_azan";
$result = mysqli_query($conn, $query);
$config = array();
$config['imsak'] = 0;
$config['subuh'] = 0;
$config['syuruk'] = 0;
$config['zohor'] = 0;
$config['asar'] = 0;
$config['maghrib'] = 0;
$config['isyak'] = 0;
$config['jumaat'] = 0;

while(($row = mysqli_fetch_array($result,MYSQLI_ASSOC))) {
	//	$rows[] = $row;
	$config['imsak'] = $row['imsak'];
	$config['subuh'] = $row['subuh'];
	$config['syuruk'] = $row['syuruk'];
	$config['zohor'] = $row['zohor'];
	$config['asar'] = $row['asar'];
	$config['maghrib'] = $row['maghrib'];
	$config['isyak'] = $row['isyak'];		
	$config['jumaat'] = $row['jumaat'];		
}

//var_dump($config);
//die;
mysqli_free_result($result);

date_default_timezone_set('Asia/Singapore');
include "./hijrah/Hijri_GregorianConvert.class";
//calculate hijrah
$query = "SELECT * FROM tbm_hijrioffset";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
//$day_offset = $config['hijri_offset'];
$day_offset = 0; //supaya jadi integer
$day_offset = $row['hijri_offset'];
mysqli_free_result($result);

$query = "SELECT * FROM tbm_zone";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$zone = $row['zone'];
mysqli_free_result($result);

//$datatext = array();
$todays_date = date("Y-m-d", strtotime('today'));
$query = "SELECT * FROM taqwim WHERE tarikh = '$todays_date' AND kodlokasi=" . $zone;
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$tarikh = $row['tarikh'];

if($day_offset == 0) $harini = date("Y-m-d", strtotime('today'));
else $harini = date("Y-m-d", strtotime("$day_offset day"));
$DateConv=new Hijri_GregorianConvert;
$format="YYYY-MM-DD";
$tarikh_hijrah = $DateConv->GregorianToHijri($harini,$format,0);

$harini_long = date("d-m-Y", strtotime('today'));
$tarikh_harini_long = getFullDate($harini_long,0);

$harini_short = date("d-m-Y", strtotime('today'));
$harini_full = getFullDate($harini_short,0) ;

$imsak = $row['imsak'];
$subuh = $row['subuh'];
$syuruk = $row['syuruk'];
$zohor = $row['zohor'];
$asar = $row['asar'];
$maghrib = $row['maghrib'];
$isyak = $row['isyak'];
$tghmlm = "23:59";
$awalpg = "00:01";

//-debug echo "imsak = " . $imsak;

$s = "$tarikh " . $awalpg . ":00";
$wawalpg = strtotime($s);
$th[0] = "00";
$tm[0] = "01";

$s = "$tarikh " . $imsak . ":00";
$wimsak = strtotime($s);
$th[1] = substr($imsak,0, -3);
$tm[1] = substr($imsak,-2);

$s = "$tarikh " . $subuh . ":00";
$wsubuh = strtotime($s);
$th[2] = substr($subuh,0, -3);
$tm[2] = substr($subuh,-2);

$s = "$tarikh " . $syuruk . ":00";
$wsyuruk = strtotime($s);
$th[3] = substr($syuruk,0, -3);
$tm[3] = substr($syuruk,-2);

$s = "$tarikh " . $zohor . ":00";
$wzohor = strtotime($s);
$th[4] = substr($zohor,0, -3);
$tm[4] = substr($zohor,-2);

$s = "$tarikh " . $asar . ":00";
$wasar = strtotime($s);
$th[5] = substr($asar,0, -3);
$tm[5] = substr($asar,-2);

$s = "$tarikh " . $maghrib . ":00";
$wmaghrib = strtotime($s);
$th[6] = substr($maghrib,0, -3);
$tm[6] = substr($maghrib,-2);

$s = "$tarikh " . $isyak . ":00";
$wisyak = strtotime($s);
$th[7] = substr($isyak,0, -3);
$tm[7] = substr($isyak,-2);

$s = "$tarikh " . $tghmlm . ":00";
$wtghmlm = strtotime($s);
$th[8] = "23"; //substr($isyak,0, -3);
$tm[8] = "59"; //substr($isyak,-2);


$alldatewaktu = array ( $wawalpg, $wimsak, $wsubuh, $wsyuruk, $wzohor, $wasar, $wmaghrib, $wisyak, $wtghmlm );
$allwaktu = array ( $awalpg, $imsak, $subuh, $syuruk, $zohor, $asar, $maghrib, $isyak, $tghmlm );
$namasolat = array ( "awalpg",  "imsak", "subuh", "syuruk", "zohor", "asar", "maghrib", "isyak", "tghmlm");



//if ( strlen($wnow2) == 0 ) {
$wnow = 0;
$current_time = strtotime('now'); // + 8*60*60;

///////////////////////////////////////
//tambah utk ubah waktu selepas maghrib
// > maghrib and < awalpagi (hari lain) 
if(  ($current_time >  $alldatewaktu[6] ) && ($current_time <  $alldatewaktu[8] ) ) {
//calculate blk hijrah utk sehari ke dpn
$day_offset = $day_offset + 1;
$harini = date("Y-m-d", strtotime("$day_offset day"));
$tarikh_hijrah = $DateConv->GregorianToHijri($harini,$format,0);
}

?>
			
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
  background: black;
  height: 90%;
  width: 105%;
}

</style>


			<script language="JavaScript">
			var time = null
			function move() {
			  var indexSolat = <?php echo $_GET['wnow']; ?>; 
			  if( indexSolat == 1 || indexSolat == 3  ) window.location = "/pt/slides/src/slide.php?first=1";
			  else {
					window.location = "/pt/clock/taqwim-iqo.php?masihi=" + "<?php echo "$tarikh_harini_long" . '&hijrah=' . "$tarikh_hijrah"; ?>";
					}
			}
			</script>
			</head>
			<body onload="timer=setTimeout('move()', 5)" class="fullheight">
			<?php
			$wnow = $_GET['wnow'];
			switch ($wnow) {
				case 2:
					//subuh
					shell_exec('omxplayer -o hdmi ./sound/' . $config['subuh'] . ' > /dev/null 2>&1'); //success
					break;					
				case 1:
					//imsak
					shell_exec('omxplayer -o hdmi ./sound/' . $config['imsak']  . ' > /dev/null 2>&1'); //success
					break;					
				case 3:
					//syuruk
					shell_exec('omxplayer -o hdmi ./sound/' . $config['syuruk']  . ' > /dev/null 2>&1'); //success
					break;
				case 4:
					//zohor
					
					//get harijumaat
					$harini_today = getdate();
					$harini_day = $harini_today['weekday'];
					if( strcmp($harini_day,"Friday") == 0 ) shell_exec('omxplayer -o hdmi ./sound/' . $config['jumaat']  . ' > /dev/null 2>&1'); //success
 				    	else shell_exec('omxplayer -o hdmi ./sound/' . $config['zohor']  . ' > /dev/null 2>&1'); //success
					break;				
				case 5:
					//asar
					shell_exec('omxplayer -o hdmi ./sound/' . $config['asar']  . ' > /dev/null 2>&1'); //success
					break;
				case 6:	
					//maghrib
					shell_exec('omxplayer -o hdmi ./sound/' . $config['maghrib']  . ' > /dev/null 2>&1'); //success
					break;
				case 7:	
					//isyak
					shell_exec('omxplayer -o hdmi ./sound/' . $config['isyak']  . ' > /dev/null 2>&1'); //success
					break;
				}
				
			?>
			</body>
			
</html>

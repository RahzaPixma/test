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

</head>
<body> <!-- class="fullheight"> -->

<!--
<div style="visibility:visible; position: absolute; top: 0px; left: 0px; width: 1280px; height: 720px; z-index: 7; background-color: black;">
</div>
-->

<?php
include "../../controlpanel/panel/panel/conn_cli.php";
date_default_timezone_set('Asia/Singapore');
include "../../hijrah/Hijri_GregorianConvert.class";


//Load config file - dah ganti dgn read table tbm_duration
$query = "SELECT * FROM tbm_zone";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$zone = $row['zone'];
//echo $zone;
//die;
mysqli_free_result($result);

$query = "SELECT * FROM tbm_duration";
$result = mysqli_query($conn, $query);
$config = array();
$config['slide'] = 0;
$config['worldclock'] = 0;
$config['taqwim'] = 0;
$config['jadualkuliah'] = 0;
$config['countdown'] = 0;
$config['blinking'] = 0;


while(($row = mysqli_fetch_array($result,MYSQLI_ASSOC))) {
	//	$rows[] = $row;
	switch( $row['item'] ) {
	   case 'slide': 
					 $config['slide'] =  $row['duration'];
					 break;
	   case 'worldclock': 
					 $config['worldclock'] =  $row['duration'];
					 break;
	   case 'taqwim': 
					 $config['taqwim'] =  $row['duration'];
					 break;
	   case 'jadualkuliah': 
					 $config['jadualkuliah'] =  $row['duration'];
					 break;
	   case 'countdown': 
					 $config['countdown'] =  $row['duration'];
					 break;
	   case 'blinking': 
					 $config['blinking'] =  $row['duration'];
					 break;

	}			 
				
}

//var_dump($config);
//die;
mysqli_free_result($result);

//echo $config['slide'] . "," . $config['worldclock'] . "," . $config['taqwim'] . "," . $config['jadualkuliah'] . "," . $config['countdown'];

//var_dump( $config );
//die;

//calculate hijrah
$query = "SELECT * FROM tbm_hijrioffset";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
//$day_offset = $config['hijri_offset'];
$day_offset = 0; //supaya jadi integer
$day_offset = $row['hijri_offset'];
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


//$config = parse_ini_file('../setting/setting.ini');
$namahari_rujuk_iqo = getNamaHari( Date("D") );
$query = "SELECT * FROM tbm_iqomah";
$result = mysqli_query($conn, $query);
$config_iqomah = array();
$config_iqomah['iqomah_subuh'] =  0;
$config_iqomah['iqomah_zohor'] =  0;
$config_iqomah['iqomah_asar'] =  0;
$config_iqomah['iqomah_maghrib'] =  0;
$config_iqomah['iqomah_isyak'] =  0;
$config_iqomah['iqomah_jumaat'] =  0;

while(($row = mysqli_fetch_array($result,MYSQLI_ASSOC))) {

	if ( $row['hari'] === $namahari_rujuk_iqo ) {
		$config_iqomah['iqomah_subuh'] =  $row['subuh'];
		$config_iqomah['iqomah_zohor'] =  $row['zohor'];
		$config_iqomah['iqomah_asar'] =  $row['asar'];
		$config_iqomah['iqomah_maghrib'] =  $row['maghrib'];
		$config_iqomah['iqomah_isyak'] =  $row['isyak'];
		$config_iqomah['iqomah_jumaat'] =  $row['zohor'];
	}
}

//echo "<br>$namahari_rujuk_iqo<br>";
mysqli_free_result($result);
//$diqomah =  array ( 0, 0, $config['iqomah_subuh'], 0, $config['iqomah_zohor'], $config['iqomah_asar'], $config['iqomah_maghrib'], $config['iqomah_isyak'], 0 );
//$diqomah_jumaat =  array ( 0, 0, $config['iqomah_subuh'], 0, $config['iqomah_jumaat'], $config['iqomah_asar'], $config['iqomah_maghrib'], $config['iqomah_isyak'], 0 );
$diqomah =  array ( 0, 0, $config_iqomah['iqomah_subuh'], 0, $config_iqomah['iqomah_zohor'], $config_iqomah['iqomah_asar'], $config_iqomah['iqomah_maghrib'], $config_iqomah['iqomah_isyak'], 0 );
$diqomah_jumaat =  array ( 0, 0, $config_iqomah['iqomah_subuh'], 0, $config_iqomah['iqomah_jumaat'], $config_iqomah['iqomah_asar'], $config_iqomah['iqomah_maghrib'], $config_iqomah['iqomah_isyak'], 0 );


//if ( strlen($wnow2) == 0 ) {
$wnow = 0;
$current_time = strtotime('now'); // + 8*60*60;
//-debug echo "</br>current_time = " . $current_time . "</br>";
//die;
//$current_time = strtotime('2012-11-05 5:44:00');

$flg_iqo = 0;
for ($i = 0; $i < 8; $i++) {

//-debug echo $namasolat[$i] . " - " . $alldatewaktu[$i] . "<br>";

	if($i != 7 && $i != 1) {   //skip imsak dan syuruk
	  //this is waktu to iqomah
	  if ($current_time > $alldatewaktu[$i] && $current_time < $alldatewaktu[$i+1]  &&  $current_time < ($alldatewaktu[$i] + $diqomah[$i]*60) ) {
		$wnow = $i - 1;
		$flg_iqo = 1;
		break;
	  }
	  
	//this is waktu after solat and calculate to next solat  
	if ($current_time > $alldatewaktu[$i] && $current_time < $alldatewaktu[$i+1] &&  $current_time > ($alldatewaktu[$i] + $diqomah[$i]*60) ) {
		$wnow = $i;
		$flg_iqo = 0;
		break;
	  }	  
	}
	else {
		if ($current_time > $alldatewaktu[7] ) {
				$wnow = 8;
				$i=8;
				$flg_iqo = 0;
				break;
			}
			
		//this is waktu after solat and calculate to next solat  - selain subuh or selepas imsak
		if ( $i==1 && $current_time > $alldatewaktu[$i] && $current_time < $alldatewaktu[$i+1] ) {
			$wnow = $i;
			$flg_iqo = 0;
			break;
		  }	  
	}	

}

//echo $namasolat[$i+1] . " - " . $alldatewaktu[$i+1] . "<br>";

///echo "current_time = " . $current_time;
///echo "<br>";
///echo "wnow (current) = " . $wnow . "($namasolat[$i])";
///echo "<br>";
//echo "wnow_wak " . $alldatewaktu[$wnow] . "<br>";
if($wnow != 8) {
   $wnow = $wnow + 1;
   $nextday = 0;
   ///echo "next waktu to check = " . $namasolat[$wnow] . " iaitu = " . $alldatewaktu[$wnow];
   $duration_blinking_in_secs = $config['blinking'] * 60;
   $nextwaktusolat_in_secs = $alldatewaktu[$wnow] - $duration_blinking_in_secs;
   }
else {
	$wnow = 0;
	$nextday = 1;
	///echo "next waktu to check = " . $namasolat[$wnow] . " iaitu = " . ($alldatewaktu[$wnow] + 24*60*60);
  	$duration_blinking_in_secs = $config['blinking'] * 60;
   	$nextwaktusolat_in_secs = ($alldatewaktu[$wnow] + 24*60*60) - $duration_blinking_in_secs;
}


//8/1/2013 check waktu iqomah utk isyak
	  //this is waktu to iqomah isyak
	  if ($current_time > $alldatewaktu[7] && $current_time < $alldatewaktu[8]  &&  $current_time < ($alldatewaktu[7] + $diqomah[7]*60) ) {
		$wnow = 7;
		$flg_iqo = 1;
		$nextday = 0;
	  }

//$wnow = $wnow - 1;	
	
//}


///////////////////////////////////////
//tambah utk ubah waktu selepas maghrib
// > maghrib and < awalpagi (hari lain) 
if(  ($current_time >  $alldatewaktu[6] ) && ($current_time <  $alldatewaktu[8] ) ) {
//calculate blk hijrah utk sehari ke dpn
$day_offset = $day_offset + 1;
$harini = date("Y-m-d", strtotime("$day_offset day"));
$tarikh_hijrah = $DateConv->GregorianToHijri($harini,$format,0);
}
///////////////////////////////////////

/*	
///////////////////////////////////////
 echo "time now = " . Date('Y-m-d H:m:s') . "<br>";
 echo "next waktu to check = " . $namasolat[$wnow] . " iaitu = " . ($alldatewaktu[$wnow] + 24*60*60);
 echo "<br>tarikh_hijrah = <br>" . $tarikh_hijrah;		
 echo "<br/>";
 echo "wnow = $wnow<br/>";
 echo "th = $th[$wnow]<br/>";
 echo "tm = $tm[$wnow]<br/>";
 echo "namasolat = $namasolat[$wnow]<br/>";
 echo "nextday = $nextday<br/>";
 echo "diqo = $diqomah[$wnow]<br/>";

 echo "L = " . strtotime("2016-07-05 13:32:00") . "<br>";
 echo "C = " . $alldatewaktu[$wnow];
die;
*/

?>

			
<?php
		
	//cari yg blm run
			$sql4 = 'SELECT * FROM tmp_video_indexfiles WHERE flag = 0 ORDER BY id';
			$result = mysqli_query($conn, $sql4);
			
			$begin_folder = 0; 
			$s_slides = array();
			$ktmp=0;
			$sql5 = "";

		$rows = array();
		$masih_ada_lagi=0;
		while(($row = mysqli_fetch_array($result,MYSQLI_ASSOC))) {
			$rows[] = $row;
			$masih_ada_lagi++;
		}

		
		//reset balik video ke 1st		
		if( $masih_ada_lagi == 0 ) {	


			   $sql4 = 'DELETE FROM tmp_video_indexfiles';
			   mysqli_query($conn, $sql4);

			   $sql4 = 'UPDATE tmp_flag_first SET flag = 1';
			   mysqli_query($conn, $sql4);			   
		
			//utk video
				$dir = './slides/video_';
				$files = array();
						$sql4 = 'INSERT INTO tmp_video_indexfiles(id, folder, filename, flag) VALUES ';
						$sql5 = '';
						$idx_idfile = 0;
						for ($folder_idx=0; $folder_idx < 9; $folder_idx++)  {
							$files[$folder_idx] = scandir($dir . strval($folder_idx+1) );
							//file_put_contents($files);
							foreach ( $files[$folder_idx] as $filejumpa ) {
								if(  (strcmp($filejumpa, '.')==0) || (strcmp($filejumpa, '..') == 0) ) {
								  //skip
								}
								else {
									if( $idx_idfile==0 ) $sql5 = $sql5 . "($idx_idfile,$folder_idx + 1,'$filejumpa',0)";
									else $sql5 = $sql5 . ", ($idx_idfile,$folder_idx + 1,'$filejumpa',0)";

									$idx_idfile++;
								}
							}
						}
						mysqli_query($conn, $sql4 . $sql5);
						

						//cari balik semula yg blm run
						$sql4 = 'SELECT * FROM tmp_video_indexfiles WHERE flag = 0 ORDER BY id';
						$result = mysqli_query($conn, $sql4);							

						$rows = array();
						$masih_ada_lagi=0;
						while(($row = mysqli_fetch_array($result,MYSQLI_ASSOC))) {
							$rows[] = $row;
							$masih_ada_lagi++;
						}

			}

			foreach ($rows as $row) {
			   if( $ktmp == 0 ) $begin_folder = intval($row["folder"]);
			   if(  $begin_folder !=  intval($row["folder"]) ) break;
			   else {
				$s_slides[] = './slides/video_' . $row["folder"] . '/' . $row["filename"];		
				$sql5 = 'UPDATE tmp_video_indexfiles SET flag = 1 WHERE id  = ' . intval($row["id"]);
				mysqli_query($conn, $sql5);				
			   }
			   
			   $ktmp++;
			}

		$bilangan_files =  $ktmp;
		// Free result set
		mysqli_free_result($result);	
		
		
//		var_dump($files);
//echo $s_slides;
//	   die;

mysqli_close($conn);
?>


<?php

//				$files = scandir( './slides/videos' );
				file_put_contents('./debug-video.txt',var_dump($s_slides) );
				
				foreach ( $s_slides as $filejumpa ) {
					if(  (strcmp($filejumpa, '.')==0) || (strcmp($filejumpa, '..') == 0) ) {
					  //skip
					}
					else {

					file_put_contents('./debug.txt',$filejumpa );
					
					ulang:
							$err = '';
							exec('pgrep omxplayer', $pids);  //omxplayer
							if ( empty($pids) ) {
//$file = 'a.mp4';
//$nextwaktusolat = '2016-06-29 15:25:00';
//$nextwaktusolat_in_secs = 1467604296;
//dah ada kat atas $nextwaktusolat_in_secs = $alldatewaktu[$wnow];

$output = exec("./runningomx.sh '" . $filejumpa . "' " . $nextwaktusolat_in_secs );

//file_put_contents('./debug.txt',"./runningomx.sh " . $filejumpa . " " . $nextwaktusolat_in_secs . " now= " . $alldatewaktu[$wnow] );



								//shell_exec ('omxplayer -o hdmi ./slides/videos/' . $filejumpa );
							} else {
								//sleep(2);
								//goto ulang;
							}	
		
					}
				}


?>

<script>
//var omx = require('omxdirector');
//omx.play('/pt/slides/src/slides/videos/a.mp4');
//alert("Tamat video");

//B4 V5 - sleepmode
 window.location = "/pt/clock/taqwim.php?masihi=" + "<?php echo "$tarikh_harini_long" . '&hijrah=' . "$tarikh_hijrah"; ?>";

</script>



</body>
</html>				

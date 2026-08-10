<?php
date_default_timezone_set('Asia/Singapore');
include "../../controlpanel/panel/panel/conn_cli.php";
include "../../hijrah/Hijri_GregorianConvert.class";
//Load config file - dah ganti dgn read table tbm_duration
$query = "SELECT * FROM tbm_zone";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$zone = $row['zone'];
//echo $zone;
//die;
mysqli_free_result($result);


$query = "SELECT * FROM tbm_anim";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$anim = $row['anim'];
//echo $anim;
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
/*
foreach ($rows as $row) {
if ( $row['item'] === 'slide' ) $config['slide'] =  $row['duration'];
if ( $row['item'] === 'worldclock' ) $config['slide'] =  $row['duration'];
if ( $row['item'] === 'taqwim' ) $config['slide'] =  $row['duration'];
if ( $row['item'] === 'jadualkuliah' ) $config['slide'] =  $row['duration'];
if ( $row['item'] === 'countdown' ) $config['slide'] =  $row['duration'];
}
*/

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



//chk sleep mode
///////////////////////////////////////////////////////////////////////////////////////
$query = "SELECT * FROM sleep_event";
$result = mysqli_query($conn, $query);

$current_time_to_compare = time();
$gbl_secs_startdate_next = 0;
$gbl_secs_enddate_next = 0;


if ( $row = mysqli_fetch_array($result,MYSQLI_ASSOC) ) {
	$gbl_secs_startdate_next = strtotime($row["startdate"]);
	$gbl_secs_enddate_next = strtotime($row["enddate"]);

//	echo "curr=" . $current_time_to_compare . "<br>";
//	echo "start=" . $secs_startdate . "," . $secs_enddate . "<br>";

}

mysqli_free_result($result);
///////////////////////////////////////////////////////////////////////////////////////

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
if($th[4] > 12)  $zscroll = $th[4]-12 . ":" . $tm[4];
else $zscroll = $th[4] . ":" . $tm[4];

$s = "$tarikh " . $asar . ":00";
$wasar = strtotime($s);
$th[5] = substr($asar,0, -3);
$tm[5] = substr($asar,-2);
if($th[5] > 12)  $ascroll = $th[5]-12 . ":" . $tm[5];
else $ascroll = $th[5] . ":" . $tm[5];

$s = "$tarikh " . $maghrib . ":00";
$wmaghrib = strtotime($s);
$th[6] = substr($maghrib,0, -3);
$tm[6] = substr($maghrib,-2);
if($th[6] > 12)  $mscroll = $th[6]-12 . ":" . $tm[6];
else $mscroll = $th[6] . ":" . $tm[6];


$s = "$tarikh " . $isyak . ":00";
$wisyak = strtotime($s);
$th[7] = substr($isyak,0, -3);
$tm[7] = substr($isyak,-2);
if($th[7] > 12)  $iscroll = $th[7]-12 . ":" . $tm[7];
else $iscroll = $th[7] . ":" . $tm[7];

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
   }
else {
	$wnow = 0;
	$nextday = 1;
	///echo "next waktu to check = " . $namasolat[$wnow] . " iaitu = " . ($alldatewaktu[$wnow] + 24*60*60);
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
//die;
*/
?>
			
<?php

    $dir = './slides/kategori_';
	$files = array();
	if( isset($_GET['first']) ) {
		   if(	$_GET['first'] === '1' ) {
			   $sql4 = 'DELETE FROM tmp_indexfiles';
			   mysqli_query($conn, $sql4);
			   
			   $sql4 = 'DELETE FROM tmp_video_indexfiles';
			   mysqli_query($conn, $sql4);

			   $sql4 = 'UPDATE tmp_flag_first SET flag = 1';
			   mysqli_query($conn, $sql4);			   
		   }

			$sql4 = 'INSERT INTO tmp_indexfiles(id, folder, filename, flag) VALUES ';
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
//			echo "<br>" . $sql4 . $sql5 . "<br>";
			mysqli_query($conn, $sql4 . $sql5);
			
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
//			echo "<br>" . $sql4 . $sql5 . "<br>";
			mysqli_query($conn, $sql4 . $sql5);
				
			
			
	}
   else {
	   $sql4 = 'UPDATE tmp_flag_first SET flag = 0';
	   mysqli_query($conn, $sql4);			   			   
   }
	
		
	//run blk utk 1st folder
			$sql4 = 'SELECT * FROM tmp_indexfiles WHERE flag = 0 ORDER BY id';
			$result = mysqli_query($conn, $sql4);
			
			$begin_folder = 0; 
			$s_slides = "";
			$ktmp=0;
			$sql5 = "";

		$rows = array();

		$slide_last_dlm_folder = '';

		$masih_ada_lagi=0;
		while(($row = mysqli_fetch_array($result,MYSQLI_ASSOC))) {
			$rows[] = $row;
			$masih_ada_lagi++;
		}
		
		if( $masih_ada_lagi == 0 ) {		
				header('Location: /pt/slides/src/slide.php?first=1');
				exit();
		}

			foreach ($rows as $row) {
			   if( $ktmp == 0 ) $begin_folder = intval($row["folder"]);
			   if(  $begin_folder !=  intval($row["folder"]) ) break;
			   else {
				$s_slides = $s_slides . '<div> <img u=image src="./slides/kategori_' . $row["folder"] . '/' . $row["filename"] . '" /> </div>';		
				$sql5 = 'UPDATE tmp_indexfiles SET flag = 1 WHERE id  = ' . intval($row["id"]);
				mysqli_query($conn, $sql5);			

				$slide_last_dlm_folder = '<div> <img u=image src="./slides/kategori_' . $row["folder"] . '/' . $row["filename"] . '" /> </div>';		
			   }
			   
			   $ktmp++;
			}

		$bilangan_files =  $ktmp;

		//nk elak hung 1 slide
		if( $bilangan_files == 1) {
			$s_slides = $s_slides . $slide_last_dlm_folder;
		}
		// Free result set
		mysqli_free_result($result);		
		
//		var_dump($files);
//echo $s_slides;
//	   die;

mysqli_close($conn);

//wnow adalah waktu utk chk nxt solat
//0 tgh mlm
//1 imsak

$color_wnow_subuh = "white";
$color_wnow_syuruk = "white"; //xyah compare
$color_wnow_zohor = "white";
$color_wnow_asar = "white";
$color_wnow_maghrib = "white";
$color_wnow_isyak = "white";

//chk waktu syuruk = now adalah subuh
//ubah warna green jadi lain or htmlcodes
if($wnow == 3) $color_wnow_subuh = "FDF905";  
//if($wnow == 4) $color_wnow_syuruk = "FDF905";

//chk waktu asar = now adalah zohor
if($wnow == 5) $color_wnow_zohor = "FDF905";
if($wnow == 6) $color_wnow_asar = "FDF905";
if($wnow == 7) $color_wnow_maghrib = "FDF905";
if($wnow == 8 || $wnow == 0 || $wnow == 1 || $wnow == 2) $color_wnow_isyak = "FDF905";


$text_fixed_taqwim ="

<table style=\"text-align:center;\";>
<tr style=\"font-family: Arial Black; font-size: 30px; color: black;\">

<td style=\"width:210;\"></td>
<td style=\"width:210;\"></td>
<td style=\"width:210;\"></td>
<td style=\"width:210;\"></td>
<td style=\"width:210;\"></td>
<td style=\"width:210;\"></td>
</tr>

<tr style=\"font-family: fantasy; font-size: 50px; color: white; font-weight:300;\">

<td style=\"width:210;color: $color_wnow_subuh;\">$subuh</td>
<td style=\"width:210;color: $color_wnow_syuruk;\">$syuruk</td>
<td style=\"width:210;color: $color_wnow_zohor;\">$zscroll</td>
<td style=\"width:210;color: $color_wnow_asar;\">$ascroll</td>
<td style=\"width:210;color: $color_wnow_maghrib;\">$mscroll</td>
<td style=\"width:210;color: $color_wnow_isyak;\">$iscroll</td>
</tr>
</table>
";

$text_fixed_taqwim_black ="

<table style=\"text-align:center;\";>
<tr style=\"font-family: Arial Black; font-size: 30px; color: black;\">

<td style=\"width:210;\"></td>
<td style=\"width:210;\"></td>
<td style=\"width:210;\"></td>
<td style=\"width:210;\"></td>
<td style=\"width:210;\"></td>
<td style=\"width:210;\"></td>
</tr>

<tr style=\"font-family: fantasy; font-size: 50px; color: black; font-weight:300;\">
<td style=\"width:210;\">$subuh</td>
<td style=\"width:210;\">$syuruk</td>
<td style=\"width:210;\">$zscroll</td>
<td style=\"width:210;\">$ascroll</td>
<td style=\"width:210;\">$mscroll</td>
<td style=\"width:210;\">$iscroll</td>
</tr>
</table>
";
	
?>			

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


<script>
//declare globals
var gblTotalFileSlide = parseInt(<?php echo ($bilangan_files - 1); ?>);

//nk elak hung
if (gblTotalFileSlide == 0) gblTotalFileSlide=1;

</script>
    <!-- it works the same with all jquery version from 1.x to 2.x -->
    <script type="text/javascript" src="./js/jquery-1.9.1.min.js"></script>
    <!-- use jssor.slider.mini.js (40KB) instead for release -->
    <!-- jssor.slider.mini.js = (jssor.js + jssor.slider.js) -->
    <script type="text/javascript" src="./js/jssor.js"></script>
    <script type="text/javascript" src="./js/jssor.slider.js"></script>
    <script>
        jQuery(document).ready(function ($) {
		
			var anim = '<?php echo $anim; ?>';
			var anim_seq = 0;
		
			if ( anim == 'Random' ) {
			
             var _SlideshowTransitions = [
{$Duration:1500,y:-0.5,$Delay:60,$Cols:12,$Formation:$JssorSlideshowFormations$.$FormationStraightStairs,$Easing:$JssorEasing$.$EaseInWave,$Round:{$Top:1.5}},
{$Duration:1500,x:0.5,$Cols:2,$ChessMode:{$Column:3},$Easing:{$Left:$JssorEasing$.$EaseInOutCubic},$Opacity:2,$Brother:{$Duration:1500,$Opacity:2}},
{$Duration:1000,x:0.2,$Delay:40,$Cols:12,$Formation:$JssorSlideshowFormations$.$FormationStraightStairs,$Easing:{$Left:$JssorEasing$.$EaseInOutExpo,$Opacity:$JssorEasing$.$EaseInOutQuad},$Assembly:260,$Opacity:2,$Outside:true,$Round:{$Top:0.5} },
{$Duration:1500,x:-0.1,y:-0.7,$Rotate:0.1,$During:{$Left:[0.6,0.4],$Top:[0.6,0.4],$Rotate:[0.6,0.4]},$Easing:{$Left:$JssorEasing$.$EaseInQuad,$Top:$JssorEasing$.$EaseInQuad,$Opacity:$JssorEasing$.$EaseLinear,$Rotate:$JssorEasing$.$EaseInQuad},$Opacity:2,$Brother:{$Duration:1000,x:0.2,y:0.5,$Rotate:-0.1,$Easing:{$Left:$JssorEasing$.$EaseInQuad,$Top:$JssorEasing$.$EaseInQuad,$Opacity:$JssorEasing$.$EaseLinear,$Rotate:$JssorEasing$.$EaseInQuad},$Opacity:2}},
{$Duration:1000,$Cols:3,$Rows:2,$Clip:15,$Formation:$JssorSlideshowFormations$.$FormationStraight,$Easing:$JssorEasing$.$EaseInBounce},
{$Duration:800,$Delay:300,$Cols:8,$Rows:4,$Clip:15,$SlideOut:true,$Formation:$JssorSlideshowFormations$.$FormationSquare,$Easing:$JssorEasing$.$EaseOutQuad},
{$Duration:500,$Delay:30,$Cols:8,$Rows:4,$Clip:15,$SlideOut:true,$Formation:$JssorSlideshowFormations$.$FormationSwirl,$Easing:$JssorEasing$.$EaseOutQuad},
{$Duration:1600,y:-1,$Cols:2,$ChessMode:{$Column:12},$Easing:{$Top:$JssorEasing$.$EaseInOutQuart,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$Brother:{$Duration:1600,y:1,$Cols:2,$ChessMode:{$Column:12},$Easing:{$Top:$JssorEasing$.$EaseInOutQuart,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2}},
{$Duration:1500,x:-1,y:0.5,$Delay:60,$Cols:8,$Rows:4,$Formation:$JssorSlideshowFormations$.$FormationRectangleCross,$Easing:{$Left:$JssorEasing$.$EaseSwing,$Top:$JssorEasing$.$EaseInWave},$Assembly:260,$Round:{$Top:1.5}},
{$Duration:600,y:1,$Delay:50,$Cols:8,$Rows:4,$SlideOut:true,$Formation:$JssorSlideshowFormations$.$FormationZigZag,$Easing:{$Top:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseOutQuad},$Assembly:264,$Opacity:2},
{$Duration:600,x:-1,y:1,$Delay:100,$Cols:8,$Rows:4,$SlideOut:true,$Formation:$JssorSlideshowFormations$.$FormationSwirl,$Easing:{$Top:$JssorEasing$.$EaseInQuart,$Opacity:$JssorEasing$.$EaseLinear},$Assembly:264,$Opacity:2},
{$Duration:1500,x:-1,y:-0.5,$Delay:50,$Cols:8,$Rows:4,$Formation:$JssorSlideshowFormations$.$FormationCircle,$Easing:{$Left:$JssorEasing$.$EaseSwing,$Top:$JssorEasing$.$EaseInJump},$Assembly:260,$Round:{$Top:1.5}},
{$Duration:1500,x:-1,y:-0.5,$Delay:50,$Cols:8,$Rows:4,$Formation:$JssorSlideshowFormations$.$FormationSwirl,$Easing:{$Left:$JssorEasing$.$EaseSwing,$Top:$JssorEasing$.$EaseInJump},$Assembly:260,$Round:{$Top:1.5}},
{$Duration:600,x:-1,y:1,$Delay:30,$Cols:8,$Rows:4,$Formation:$JssorSlideshowFormations$.$FormationStraightStairs,$Easing:{$Left:$JssorEasing$.$EaseInQuart,$Top:$JssorEasing$.$EaseInQuart,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2},
{$Duration:1500,y:-0.5,$Delay:60,$Cols:15,$Formation:$JssorSlideshowFormations$.$FormationCircle,$Easing:$JssorEasing$.$EaseInWave,$Round:{$Top:1.5}},
{$Duration:1500,x:-1,y:0.5,$Delay:60,$Cols:8,$Rows:4,$Formation:$JssorSlideshowFormations$.$FormationRectangleCross,$Easing:{$Left:$JssorEasing$.$EaseSwing,$Top:$JssorEasing$.$EaseInWave},$Assembly:260,$Round:{$Top:1.5}},
{$Duration:1500,x:0.3,y:-0.3,$Delay:20,$Cols:8,$Rows:4,$Clip:15},
{$Duration:1200,x:0.3,y:0.3,$Delay:60,$Zoom:1,$Formation:$JssorSlideshowFormations$.$FormationStraightStairs,$Easing:{$Left:$JssorEasing$.$EaseInJump,$Top:$JssorEasing$.$EaseInJump,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$Round:{$Left:0.8,$Top:0.8}},
{$Duration:1800,x:1,y:0.2,$Delay:30,$Cols:10,$Rows:5,$Clip:15},
{$Duration:1000,$Delay:80,$Cols:8,$Rows:4,$Clip:15,$SlideOut:true,$Easing:$JssorEasing$.$EaseOutQuad},
{$Duration:1200,x:1,$Delay:40,$Cols:6,$Formation:$JssorSlideshowFormations$.$FormationStraight,$Easing:{$Left:$JssorEasing$.$EaseInOutQuart,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$ZIndex:-10,$Brother:{$Duration:1200,x:1,$Delay:40,$Cols:6,$Formation:$JssorSlideshowFormations$.$FormationStraight,$Easing:{$Top:$JssorEasing$.$EaseInOutQuart,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$ZIndex:-10,$Shift:-100}},

            ];
}

			if ( anim == 'Fixed' ) {
			
             var _SlideshowTransitions = [
{$Duration:400,x:1,$Easing:$JssorEasing$.$EaseInQuad},
{$Duration:1000,$Cols:8,$Clip:1},
{$Duration:1200,y:-1,$Easing:{$Top:$JssorEasing$.$EaseInOutQuart,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$ZIndex:-10,$Brother:{$Duration:1200,y:-1,$Easing:{$Top:$JssorEasing$.$EaseInOutQuart,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2,$ZIndex:-10,$Shift:-100}}
            ];
}



            var options = {
                $SlideDuration: 1000,                                //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
				$Loop: 0,
				$PauseOnHover: 0,
                $DragOrientation: 3,                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)
                $AutoPlay: true,                                    //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
                $AutoPlayInterval: <?php echo $config['slide'] * 1000; ?>,                            //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
                $SlideshowOptions: {                                //[Optional] Options to specify and enable slideshow or not
                    $Class: $JssorSlideshowRunner$,                 //[Required] Class to create instance of slideshow
                    $Transitions: _SlideshowTransitions,            //[Required] An array of slideshow transitions to play slideshow
                    $TransitionsOrder: 0,                           //[Optional] The way to choose transition to play slide, 1 Sequence, 0 Random
                    $ShowLink: false                                    //[Optional] Whether to bring slide link on top of the slider when slideshow is running, default value is false
                }
            };

            var jssor_slider1 = new $JssorSlider$("slider1_container", options);
			
        });
			
    </script>
	
	
	<link href="../../style-clock/assets/css/style.css" rel="stylesheet" type="text/css" />
    <script src="../../src/jquery.min.js" type="text/javascript"></script>
    <script src="../../src/jquery.counter.js" type="text/javascript"></script>
	
<title>Slide Show</title>
			
		
<script type="text/javascript">

			var chkIntervalId = 0;
			var chgdate_now1 = new Date();  //now.getFullYear(), now.getMonth(), now.getDate(),1, 0, 0, 0);
			
			var wnow = <?php echo $wnow; ?>;
			var hSolatNext = <?php echo intval($th[$wnow]); ?>;
			var mSolatNext = <?php echo intval($tm[$wnow]); ?>;
			var waktu = "<?php echo $allwaktu[$wnow]; ?>";
			var namaSolat = "<?php echo $namasolat[$wnow]; ?>";
			var nextday = <?php echo $nextday; ?>;
			var timeSlides = <?php echo $bilangan_files; ?>;
			var slideduration = <?php echo ($config['slide'] * 1000); ?>;  //1000 tu adalah fade
			var blinkingduration = <?php echo $config['blinking']; ?>; // 5 min sebelum azan

			var gbl_secs_startdate_next = <?php echo $gbl_secs_startdate_next; ?>;
			var gbl_secs_enddate_next = <?php echo $gbl_secs_enddate_next; ?>;

///////////////////////////////////////////////////////////////
//skip hang single slide
			if( timeSlides <= 2 && timeSlides > 0) {
//alert('ye 1 slide');
			   setTimeout('move()', 30*1000);
			}
///////////////////////////////////////////////////////////////

			function chkWaktuHandler ( )
			{
			
			var now = new Date();
			
			
			if(	wnow == 1 && nextday ==1 ) {
			    var datesol = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hSolatNext + 24, mSolatNext, 0, 0);
				var mSec =  datesol - now;
//				var mSec2 = datesol.setMinutes(datesol.getMinutes() + dIqomah) - now;
				}
			else {
				var datesol = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hSolatNext, mSolatNext, 0, 0);
				var mSec =  datesol - now;
//				var mSec2 = datesol.setMinutes(datesol.getMinutes() + dIqomah) - now;
				}

			//setTimeout('move()',3000);
//			var totalTimeSlide = (slideduration + 5500 + slideduration) * timeSlides;
//			setTimeout('move()',  9000);

			  //chk startdate is touching
			   var chkIntervalId4 = setInterval ( "chkStartdateTouch()", 1000 );


			  //chk change date
			   var chkIntervalId3 = setInterval ( "chkChangeDate()", 1000 );
			
			 //activate interval function for periodically chk waktu if blm time (still +ve value)
			 if( mSec > 0 && nextday == 0 ) 
			   chkIntervalId = setInterval ( "chkWaktuBlinking(waktu,namaSolat)", 1000 );

	
		 	}

			
		    //chk if startdate is touching
			function chkStartdateTouch()
			{
//alert('jjjjj');
				if ( gbl_secs_startdate_next != 0 && gbl_secs_enddate_next != 0 && gbl_secs_startdate_next != gbl_secs_enddate_next ) {
				//alert('masuk');
					var date_now = new Date();  
					var secs =  (date_now.getTime())/1000 ;
	//				console.log(secs);

					//trigger dlm range
					if ( secs >= gbl_secs_startdate_next && secs <= gbl_secs_enddate_next ) {
					 // alert ('now=' + secs + ',begin=' + gbl_secs_startdate_next + ',end=' + gbl_secs_enddate_next);
					   window.location = "../../clock/taqwim-sleep.php";  //pergi ke sleep mode
					}					   
				}
			}
		

		    //chk if date changed (date tak sama)
			function chkChangeDate()
			{
				var chgdate_now2 = new Date();  //now.getFullYear(), now.getMonth(), now.getDate(),0, 0, 0, 0);

				if( chgdate_now1 < (chgdate_now2 - (10*60*1000)) )   //&&  chgdate_now1 > (chgdate_now2 - (10*60*1000)) )
				{
 				  window.location = "/pt/slides/src/slide.php";
				}

				if( chgdate_now1 > (chgdate_now2 + (10*60*1000)) )   //&&  chgdate_now1 > (chgdate_now2 - (10*60*1000)) )
				{
 				  window.location = "/pt/slides/src/slide.php";
				}
								
			}

			
			//chk waktu blinking
			function chkWaktuBlinking ( waktu, namaSolat)
			{
				var now = new Date();
				var taqsolat = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hSolatNext, mSolatNext, 0, 0);
				var mSec = taqsolat - now;

					if( mSec <= blinkingduration*60*1000 )
					  {
					  //if dah 5 min b4 azan then clear interval  
					  clearInterval ( chkIntervalId );
					  window.location = "../../clock/waktublinking.php?masihi=" + "<?php echo "$tarikh_harini_long" . '&hijrah=' . "$tarikh_hijrah"; ?>";
					}  	  						
			}

					
			function move() {	
				//releks 2 secs
				setTimeout(continueExecution, slideduration);			
//				window.location = "../../clock/taqwim.php?masihi=" + "<?php echo "$tarikh_harini_long" . '&hijrah=' . "$tarikh_hijrah"; ?>";
//				window.location = "../../clock/taqwim.php?masihi=" + "<?php echo "$tarikh_harini_long" . '&hijrah=' . "$tarikh_hijrah"; ?>";
//				window.location = "runvideo.php";
//				alert ("habis");
			}
		    function continueExecution() {
//				window.location = "../../clock/taqwim.php?masihi=" + "<?php echo "$tarikh_harini_long" . '&hijrah=' . "$tarikh_hijrah"; ?>";
				window.location = "transit.php";
			}
			</script>
	

	
			</head>

<body onload="chkWaktuHandler()" class="fullheight">

    <!-- Jssor Slider Begin -->
    <!-- To move inline styles to css file/block, please specify a class name for each element. --> 
    <div id="slider1_container" style="position: relative; width: 600px;
        height: 300px;">

        <!-- Loading Screen -->
        <div u="loading" style="position: absolute; top: 0px; left: 0px;">
            <div style="filter: alpha(opacity=70); opacity:0.7; position: absolute; display: block;
                background-color: #000; top: 0px; left: 0px;width: 100%;height:100%;">
            </div>
            <div style="position: absolute; display: block; background: url(./img/loading.gif) no-repeat center center;
                top: 0px; left: 0px;width: 100%;height:100%;">
            </div>
        </div>

       <!-- Slides Container -->   
        <div u="slides" style="cursor: move; position: absolute; left: 0px; top: 0px; width: 1227px; height: 686px;overflow: hidden;">
			<?php echo $s_slides; ?>
        </div>

    </div>

	
<span id="countdowniqomah" style="font-size: 83px; color:black; position: absolute; top: 250px; left: 50px; z-index: 23;" class="counter counter-analog2" data-direction="down" data-format="59: 59" data-stop="00:00" data-interval="1000"></span>	
<div id="iqomahtext" style="z-index: 22; position: absolute; top: 180px; left: 50px; width: 100px; height: 125px;  font-size: 60px; font-family: Verdana Bold;  font-weight: bold; color: black;">
</div>




<div id="showlegendtaqwim" style="position: absolute; top: 580px; left: 22px; width: 1280px; height: 100px; z-index: 5;">
<img src="../../bg/text-waktu-bawah.png"></img>
</div>

<div id="showfixedtaqwim" style="position: absolute; top: 620px; left: 22px; width: 850px; height: 100px; z-index: 6;  font-size: 60px; font-weight:800; font-family: fantasy;  color: black;">
<?php echo $text_fixed_taqwim;
?>
</div>  	


<!--kanan-->
<div id="showfixedtaqwim" style="position: absolute; top: 620px; left: 25px; width: 850px; height: 100px; z-index: 5;  font-size: 60px; font-weight:800; font-family: fantasy;  color: black;">
<?php echo $text_fixed_taqwim_black;
?>
</div>  

<!--kiri-->
<div id="showfixedtaqwim" style="position: absolute; top: 620px; left: 19px; width: 850px; height: 100px; z-index: 5;  font-size: 60px; font-weight:800; font-family: fantasy;  color: black;">
<?php echo $text_fixed_taqwim_black;
?>
</div>  

<!--atas-->
<div id="showfixedtaqwim" style="position: absolute; top: 617px; left: 22px; width: 850px; height: 100px; z-index: 5;  font-size: 60px; font-weight:800; font-family: fantasy;  color: black;">
<?php echo $text_fixed_taqwim_black;
?>
</div>  

<!--bawah-->
<div id="showfixedtaqwim" style="position: absolute; top: 623px; left: 22px; width: 850px; height: 100px; z-index: 5;  font-size: 60px; font-weight:800; font-family: fantasy;  color: black;">
<?php echo $text_fixed_taqwim_black;
?>
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
		<script src="../../style-clock/moment.min.js"></script>
		<script src="../../style-clock/assets/js/script.js"></script>
  
			

  
</body>
</html>

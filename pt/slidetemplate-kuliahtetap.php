<?php
if (isset($_GET['curr_process_event']) ) {
	if( intval($_GET['curr_process_event']) == intval($_GET['total_event']) ) {
		header('Location: /pt/slidetemplate.php?passing_id=0');
		exit();
		}
}
?>

<?php
date_default_timezone_set('Asia/Singapore');
include "./controlpanel/panel/panel/conn_cli.php";
include "./hijrah/Hijri_GregorianConvert.class";
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
$config['jadualkuliahtetap'] = 0;
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
					 $config['jadualkuliahtetap'] =  $row['duration'];
					 break;
	  // case 'jadualkuliahtetap': 
	  //				 $config['jadualkuliahtetap'] =  $row['duration'];
	  //				 break;
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



$query = "SELECT * FROM template_kuliah_tetap WHERE status=1 ORDER BY susunan";
$result = mysqli_query($conn, $query);
$arr_kuliah_header = array();
$arr_kuliah_tajuk = array();
$arr_kuliah_tarikh = array();
$arr_kuliah_hari = array();
$arr_kuliah_waktu = array();
$arr_kuliah_tempat = array();
$arr_kuliah_penceramah = array();
$arr_kuliah_catatan = array();
$arr_kuliah_file_template = array();
$arr_kuliah_show_slide = array();
$arr_kuliah_file_slide = array();
$arr_kuliah_masa = array();
$arr_kuliah_batal = array();
$arr_kuliah_susunan = array();


$bil_event = 0;
while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC) ) {
				$arr_kuliah_header[$bil_event] = $row["header"];
				$arr_kuliah_tajuk[$bil_event] = $row["tajuk"];
				$arr_kuliah_tarikh[$bil_event] = $row["tarikh"];
				$arr_kuliah_hari[$bil_event] = $row["hari"];
				$arr_kuliah_waktu[$bil_event] = $row["waktu"];
				$arr_kuliah_tempat[$bil_event] = $row["tempat"];
				$arr_kuliah_penceramah[$bil_event] = $row["penceramah"];
				$arr_kuliah_catatan[$bil_event] = $row["catatan"];
				$arr_kuliah_file_template[$bil_event] = $row["filetemplate"];
				$arr_kuliah_show_slide[$bil_event] = $row["show_slide"];
				$arr_kuliah_file_slide[$bil_event] = $row["file_slide"];
				$arr_kuliah_masa[$bil_event] = $row["masa"];
				$arr_kuliah_batal[$bil_event] = $row["batal"];
				$arr_kuliah_susunan[$bil_event] = $row["susunan"];

   $bil_event++;
}

//skip drp show kuliah jika data xde
if( $bil_event == 0 ) {
   header('Location:/pt/slidetemplate.php?passing_id=0');
   exit();
}


$curr_process_event = 0;

if ( isset($_GET['curr_process_event']) ) $curr_process_event = intval($_GET['curr_process_event']);
else $curr_process_event = 0;

if ( $curr_process_event < $bil_event ) {

$kuliah_header = $arr_kuliah_header[$curr_process_event];
$kuliah_tajuk = $arr_kuliah_tajuk[$curr_process_event];
$kuliah_tarikh = $arr_kuliah_tarikh[$curr_process_event];
$kuliah_hari = $arr_kuliah_hari[$curr_process_event];
$kuliah_waktu = $arr_kuliah_waktu[$curr_process_event];
$kuliah_tempat = $arr_kuliah_tempat[$curr_process_event];
$kuliah_penceramah = $arr_kuliah_penceramah[$curr_process_event];
$kuliah_catatan = $arr_kuliah_catatan[$curr_process_event];
$kuliah_file_template = $arr_kuliah_file_template[$curr_process_event];
$kuliah_show_slide = $arr_kuliah_show_slide[$curr_process_event];
$kuliah_file_slide = $arr_kuliah_file_slide[$curr_process_event];
$kuliah_masa = $arr_kuliah_masa[$curr_process_event];
$kuliah_batal = $arr_kuliah_batal[$curr_process_event];
$kuliah_susunan = $arr_kuliah_susunan[$curr_process_event];

//	if( ($curr_process_event + 1) < $bil_event ) 
//		$kuliah_file_template_next = $arr_kuliah_file_template[$curr_process_event+1];
//	else 
//		 $kuliah_file_template_next = $arr_kuliah_file_template[0];

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
die;
*/


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
	
	<link href="./style-clock/assets/css/style.css" rel="stylesheet" type="text/css" />
    <script src="./src/jquery.min.js" type="text/javascript"></script>
    <script src="./src/jquery.counter.js" type="text/javascript"></script>
	
<title>Slide Show</title>
			

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
		
		
<script type="text/javascript">

			var chkIntervalId = 0;
			var chgdate_now1 = new Date();  //now.getFullYear(), now.getMonth(), now.getDate(),1, 0, 0, 0);
			
	
			//var zohor = 
			
			var wnow = <?php echo $wnow; ?>;
			var hSolatNext = <?php echo $th[$wnow]; ?>;
			var mSolatNext = <?php echo $tm[$wnow]; ?>;
			var waktu = "<?php echo $allwaktu[$wnow]; ?>";
			var namaSolat = "<?php echo $namasolat[$wnow]; ?>";
			var nextday = <?php echo $nextday; ?>;
			var gblFlagIqo = 0;
//			var slideduration = <?php echo $config['jadualkuliah'] * 1000; ?>;  //1000 tu adalah fade
			var slideduration = <?php echo $config['jadualkuliahtetap'] * 1000; ?>;  //1000 tu adalah fade
			var blinkingduration = <?php echo $config['blinking']; ?>; // 5 min sebelum azan

			var gbl_secs_startdate_next = <?php echo $gbl_secs_startdate_next; ?>;
			var gbl_secs_enddate_next = <?php echo $gbl_secs_enddate_next; ?>;


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

//			setTimeout('move()',10000);
			setTimeout('move()', slideduration );

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
					   window.location = "./clock/taqwim-sleep.php";  //pergi ke sleep mode
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
//				var now = new Date();
//				var mSec = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hSolatNext, mSolatNext, 0, 0) - now;
//				mins = Math.ceil((mSec/1000)/60);
				var now = new Date();
				var taqsolat = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hSolatNext, mSolatNext, 0, 0);
				var mSec = taqsolat - now;
				//mins = Math.floor((mSec/1000)/60);
				//secs = Math.ceil( ((mSec/1000)/60 - mins) * 60 );  
				//dBlinkMin = waktublinkingduration - mins;

				//if mSec before azan, so chk waktu blinking
//				if( mSec <= 0 && nextday == 0 ) {
					//waktublinkingduration = blinkingduration * 60 * 1000;
					if( mSec <= blinkingduration*60*1000 )
					  {
					  //if dah 5 min b4 azan then clear interval  
					  clearInterval ( chkIntervalId );
					  window.location = "clock/waktublinking.php?masihi=" + "<?php echo "$tarikh_harini_long" . '&hijrah=' . "$tarikh_hijrah"; ?>";
					}  	  	
			}
				
			function move() {
			    var curr_process_event = <?php echo $curr_process_event; ?>;
				var total_event = <?php echo $bil_event; ?>;
				//alert("bil event = " + total_event + ", " + "curr_process_event = " + curr_process_event);
				if( curr_process_event < total_event ) {
					curr_process_event = curr_process_event+1;
					window.location = 'slidetemplate-kuliahtetap.php?curr_process_event=' + curr_process_event + '&total_event=' + total_event;
				}
			    else window.location = '/pt/slidetemplate.php?passing_id=0';

			}

			</script>
	


<style>
.container2 {
    width: 100%;
    height: 100%;
    background: #000;
    margin: 0 auto;
}
.container2 img.wide {
    max-width: 100%;
    max-height: 100%;
    height: auto;
}
.container2 img.tall {
    max-height: 100%;
    max-width: 100%;
    width: auto;
}
</style>
	
</head>
<body onload="chkWaktuHandler()" class="fullheight">
			    <!-- Jssor Slider Begin -->
    <!-- To move inline styles to css file/block, please specify a class name for each element. 
<!--    <div id="slider1_container" style="position: relative; width: 600px;
        height: 300px;">
-->

<div class="container2">

<?php 

//$show_slide='Data';
if ( $curr_process_event < $bil_event ) {

		if($kuliah_show_slide==='Slide') {
			include('./templateslideskt/kuliahslideskt.php');
?>

</div>

<div id="showlegendtaqwim" style="position: absolute; top: 580px; left: 22px; width: 1280px; height: 100px; z-index: 5;">
<img src="./bg/text-waktu-bawah.png"></img>
</div>

<?php

		} //if slide

		else {
		?>
			   <!-- Slides Container  
				<div u="slides" style="cursor: move; position: absolute; left: 0px; top: 0px; width: 1227px; height: 686px;overflow: hidden;">
					<div>
						<img style="object-fit: contain;" src="./templateslides/<?php //echo $kuliah_file_template; ?>"/>
					</div>
			        </div>
			  -->



 <img src="./templateslideskt/<?php echo $kuliah_file_template; ?>"/>


</div>
		<?php
			switch($kuliah_file_template) {
			   case 'kuliah.jpg':
								include('./templateslideskt/kuliah.php');
								break;
			   case 'umum.jpg':
								include('./templateslideskt/umum.php');
								break;
			   case 'pengajian.jpg':
								include('./templateslideskt/pengajian.php');
								break;
								
								
			   case 'ceramah.jpg':
								include('./templateslideskt/ceramah.php');
								break;
								
			   case 'kelas.jpg':
								include('./templateslideskt/kelas.php');
								break;

			   case 'khutbah.jpg':
								include('./templateslideskt/khutbah.php');
								break;
			}	

?>

<div id="showlegendtaqwim" style="position: absolute; top: 580px; left: 22px; width: 1280px; height: 100px; z-index: 5;">
<img src="./bg/text-waktu-bawah.png"></img>
</div>

<?php	

		} //else slide	
}		
?>


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



<div id="clock" class="dark" style="position: absolute; top: 427px; left: 865px; width: 350px; height: 100px; z-index: 6;">

			<div class="display">
				<div class="weekdays"></div>
				<div class="ampm"></div>
				<div class="alarm"></div>
				<div class="digits"></div>
			</div>
</div>

<?php
if( $kuliah_batal == 0 ) {
?>
<div id="showbatal" style="position: absolute; top: 0px; left: 0px; width: 1280px; height: 720px; z-index: 5;">
<img src="./bg/batal2.png"></img>
</div>
<?php
}
?>

        
	<!-- JavaScript Includes -->
		<script src="style-clock/moment.min.js"></script>
		<script src="style-clock/assets/js/script.js"></script>

<script>

$(window).load(function(){
 $('.container2').find('img').each(function(){
  var imgClass = (this.width/this.height > 1) ? 'wide' : 'tall';
  $(this).addClass(imgClass);
 })
})

</script>			


</body>
			
</html>

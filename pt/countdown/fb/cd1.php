<?php
if (isset($_GET['curr_process_event']) ) {
	if( intval($_GET['curr_process_event']) == intval($_GET['total_event']) ) {
		header('Location: /pt/slides/src/slide.php');
		exit();
		}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<title>CD 1</title>
<!--<link href="../analog/style.css" media="screen" rel="stylesheet" type="text/css" />-->
<link rel="stylesheet" type="text/css" href="../analog/css/analog.css"> 
<!--<link rel="stylesheet" type="text/css" href="../analog/css/digital.css">-->


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



<script src="../analog/js/jquery.js" type="text/javascript"></script>
<script src="../analog/js/jquery.clock.js" type="text/javascript"></script>

<script type="text/javascript">
$.noConflict();
  jQuery(document).ready(function($) {
    // Code that uses jQuery's $ can follow here.
	$('#analog-clock').clock({offset: '+8', type: 'analog'});
  });
</script>

<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="jquery.flightboard.js"></script>



<?php
//include "../../hijrah/Hijri_GregorianConvert.class";

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

$harini_long = date("d-m-Y", strtotime('today'));
$tarikh_masihi_countdown =  strtoupper(date("d-M-Y", strtotime('today')));
$tarikh_hijrah_countdown =  $DateConv->GregorianToHijri($harini,$format,0);

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

$query = "SELECT * FROM countdown WHERE status=1 AND datediff (CURDATE(), tarikh) <= autohide ORDER BY tarikh";
$result = mysqli_query($conn, $query);

$config_countdown_event = array();
$config_countdown_tarikh = array();

$bil_event = 0;
while(($row = mysqli_fetch_array($result,MYSQLI_ASSOC))) {
   $config_countdown_event[$bil_event] = $row['event'];
   $config_countdown_tarikh[$bil_event] = $row['tarikh'];  
   $bil_event++;
}

//////////////////////////////////////////////////////////////////
//skip drp show countdown jika data xde
if( $bil_event == 0 ) {
   header('Location:/pt/slides/src/slide.php');
   exit();
}
/////////////////////////////////////////////////////////////////


//var_dump($config_countdown_event);
//echo '<br>';
//var_dump($config_countdown_tarikh);
//die;
mysqli_free_result($result);

$curr_process_event = 0;

if ( isset($_GET['curr_process_event']) ) $curr_process_event = intval($_GET['curr_process_event']);
else $curr_process_event = 0;

if ( $curr_process_event < $bil_event ) {
	$txt_event = $config_countdown_event[$curr_process_event];
	$event_length = strlen($txt_event);
	$start_left = 640 - round($event_length * 50)/2;

	$date_event = strtotime($config_countdown_tarikh[$curr_process_event]);
	//$date_event = strtotime('2013-10-20');
	$date_event_array = getdate($date_event);
	$date_event_day = substr( "0" . $date_event_array['mday'], -2);
	$date_event_month = strtoupper(substr($date_event_array['month'],0,3));
	$date_event_year = $date_event_array['year'];

}

//$bil_hari = '231';
     
$datenow = time(); // or your date as well
//$datediff = $datenow - $date_event;
//$debug =  "BIL HARI = " . ceil($datediff/(60*60*24));

//$start = strtotime('2013-05-15'); 
//$start = strtotime($config_countdown['eventdate1']);  //succ
//$date_event = strtotime($config_countdown['eventdate1']);
//$end = strtotime('2010-02-25');


$harini = time();
$bil_hari = ceil(abs($date_event - $harini) / 86400);
$text_hari_lagi = "";
if ( $date_event < $harini ) {
$text_hari_lagi = "Hari lepas";
$bil_hari = $bil_hari - 1;
}
else $text_hari_lagi = "Hari lagi";

if($date_event < $harini && abs($date_event - $harini) < 86400) {
$text_hari_lagi = "Hari ini";
$bil_hari = 0;
}


/**
$debug =  "dateevent = " . $date_event . " harini = " . $harini;
$debug =  "BIL HARI = " . $bil_hari;
$debug = "day=" . $date_event_day . ",month=" . $date_event_month . ",year=" . $date_event_year;
**/
?>
<script type="text/javascript">
// $.noConflict();
//  jQuery(document).ready(function($) {
$(function () {
    //title
	var txt_event = "<?php echo strtoupper($txt_event); ?>";
	$('#basicBoard').flightboard({messages: ['   ',txt_event],
		//maxLength: 15, // Maximum length of flight board
		lettersImage: '37charswhite.png', // Amalgamated image for letters background
		maxLength: txt_event.length, // Maximum length of flight board
		});
		
    $('#basicBoard').flightboard('flip'); 		
		
	var date_event_day = "<?php echo $date_event_day; ?>";
	
$('#basicBoardDate').flightboard({messages: ['  ',date_event_day],
		lettersImage: '11charsyellow.png', // Amalgamated image for letters background
		lettersSeq: ' 0123456789', // Positioning of letters within image
		maxLength: 2, // Maximum length of flight board
		});
	$('#basicBoardDate').flightboard('flip');
		
	var date_event_month = "<?php echo $date_event_month; ?>";
$('#basicBoardMonth').flightboard({messages: ['   ',date_event_month],
		lettersImage: '37charsblack.png', // Amalgamated image for letters background
		maxLength: 3, // Maximum length of flight board
		});
	$('#basicBoardMonth').flightboard('flip');
				
	var date_event_year = "<?php echo $date_event_year; ?>";
$('#basicBoardYear').flightboard({messages: ['   ',date_event_year],
		lettersImage: '11charsblack.png', // Amalgamated image for letters background
		lettersSeq: ' 0123456789', // Positioning of letters within image
		maxLength: 4, // Maximum length of flight board
		});
	$('#basicBoardYear').flightboard('flip');

				
	//totalday
<?php
	$bil_hari2 = $bil_hari + 1000;
	$bil_hari3 = substr($bil_hari2, -3);
	//$bil_hari3 = str_replace("0","*",$bil_hari3);
	//$bil_hari3 = "**4";
?>
	var bilhari = "<?php echo $bil_hari3; ?>";
	$('#basicBoardBilDay').flightboard({messages: ['   ',bilhari],
		lettersImage: '10chars.png', // Amalgamated image for letters background
//		lettersImage: 'flightBoardTargetDay.png', // Amalgamated image for letters background
//		shadingImages: ['flightBoardHigh.png', 'flightBoardShad.png'],
//asal		lettersSize: [100, 136], // Width and height of individual letters
		lettersSize: [75, 102], // Width and height of individual letters
		maxLength: bilhari.length, // Maximum length of flight board
		lettersSeq: '0123456789', // Positioning of letters within image
		});		
		
		 $('#basicBoardBilDay').flightboard('flip'); 		
		
});
</script>

<script type="text/javascript">

			var chkIntervalId = 0;
			var chgdate_now1 = new Date();  //now.getFullYear(), now.getMonth(), now.getDate(),1, 0, 0, 0);
			//var zohor = 
			
			var wnow = <?php echo $wnow; ?>;
			var hSolatNext = <?php echo $th[$wnow]; ?>;
			var mSolatNext = <?php echo $tm[$wnow]; ?>;
			//var mSolatNext2 = <?php echo $tm[$wnow] + $diqomah[$wnow]; ?>;
			
			var waktu = "<?php echo $allwaktu[$wnow]; ?>";
			var namaSolat = "<?php echo $namasolat[$wnow]; ?>";
			var nextday = <?php echo $nextday; ?>;
			var countdownduration = <?php echo $config['countdown']; ?>;
			var blinkingduration = <?php echo $config['blinking']; ?>; // 5 min sebelum azan
			var gblFlagIqo = 0;

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

			//setTimeout('move()',3000);
			setTimeout('move()',countdownduration * 1000);

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
					   window.location = "../../taqwim-sleep.php";  //pergi ke sleep mode
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

					//waktublinkingduration = blinkingduration * 60 * 1000;
					if( mSec <= blinkingduration*60*1000 )
					  {
					  //if dah 5 min b4 azan then clear interval  
					  clearInterval ( chkIntervalId );
					  window.location = "../../clock/waktublinking.php?masihi=" + "<?php echo "$tarikh_harini_long" . '&hijrah=' . "$tarikh_hijrah"; ?>";		  
					}  	  	
			}



			function move() {
			    var curr_process_event = <?php echo $curr_process_event; ?>;
				var total_event = <?php echo $bil_event; ?>;
				//alert("bil event = " + total_event + ", " + "curr_process_event = " + curr_process_event);
				if( curr_process_event < total_event ) {
					curr_process_event = curr_process_event+1;
					window.location = 'cd1.php?curr_process_event=' + curr_process_event + '&total_event=' + total_event;
				}
			    else window.location = '/pt/slides/src/slide.php';

			}
</script>
</head>

<body onload="chkWaktuHandler()" class="fullheight">

<div class="background" style="position: absolute; top: 0px; left: 0px; width: 1280px; height: 720px; background-image:url('../../bg/bg-countdown.jpg');
background-repeat:no-repeat; background-attachment:fixed; background-position:center;" </div>

<div id="basicBoard" style="position: absolute; top: 200px; left: <?php echo $start_left; ?>px; width:1200px; height: 320px"> </div>

<div id="basicBoardBilDay" style="position: absolute; top: 400px; left: 528px; width:400px; height: 120px;"> </div>

<div id="basicBoardDate" style="position: absolute; top: 290px; left: 380px; width:200px; height: 320px"> </div>
<div id="basicBoardMonth" style="position: absolute; top: 290px; left: 500px; width:800px; height: 320px"> </div>
<div id="basicBoardYear" style="position: absolute; top: 290px; left: 670px; width:200px; height: 320px"> </div>

<div id="debug" style="position: absolute; top: 350px; left: 670px; width:200px; height: 320px">
<?php //echo $debug; ?>
</div>

<div id="tarikh_masihi" style="position: absolute; top: 580px; left: 320px; width:600px; height: 120px; font-size: 30px; font-family: Verdana Bold; font-weight: bold; color: black;">
<?php echo $tarikh_masihi_countdown; ?>
</div>

<div id="tarikh_hijri" style="position: absolute; top: 610px; left: 320px; width:600px; height: 80px;  font-size: 25px; font-family: Verdana Bold; font-weight: bold; color: red;">
<?php echo $tarikh_hijrah_countdown; ?>
</div>
<div id="hari_countdown" style="position: absolute; top: 585px; left: 770px; width:300px; height: 100px;  font-size: 45px; font-family: Verdana Bold; font-weight: bold; color: blue;">
<?php echo getNamaHari(date("D")); ?>
</div>
<div id="harilagi_countdown" style="visibility:hidden; position: absolute; top: 440px; left: 770px; width:300px; height: 100px;  font-size: 45px; font-family: Verdana Bold; font-weight: bold; color: black;">
<?php echo $text_hari_lagi; ?>
</div>
   <div style="text-align: center; position: absolute; top: 520px; left: 560px;">
       <ul id="analog-clock" class="analog">	
	   	  <li class="hour"></li>
	     	<li class="min"></li>
        <li class="sec"></li>
        <li class="meridiem"></li>
     	</ul>
      </div>

<script>

function showIt2() {
  document.getElementById("harilagi_countdown").style.visibility = "visible";
}
//setTimeout("showIt2()", <?php //echo  (intval($config['slide']) - 1); ?> * 1000 ); // after (slideduration-1) secs
setTimeout("showIt2()", 7 * 1000 ); // fixed after 4 secs (after faded)
</script>
	  
</body>
</html>
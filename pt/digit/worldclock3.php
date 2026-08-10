<?php
date_default_timezone_set('Asia/Singapore');
include "../controlpanel/panel/panel/conn_cli.php";
include "../hijrah/Hijri_GregorianConvert.class";
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
//die;
*/
?>


<html>
<head>
    <script type="text/javascript" src="jquery-1.2.6.min.js"></script>
    

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


    <style type="text/css">
        * {
        	margin: 0;
        	padding: 0;
        }
        
        #clock {
        	position: relative;
        	width: 600px;
        	height: 600px;
        	margin: 70px auto 0 auto;
        	background: url(images/clockface.png);
        	list-style: none;
        	}
        
        #sec, #min, #hour {
        	position: absolute;
        	width: 30px;
        	height: 600px;
        	top: 0px;
        	left: 285px;
        	}
        
        #sec {
        	background: url(images/sechand.png);
        	z-index: 3;
           	}
           
        #min {
        	background: url(images/minhand.png);
        	z-index: 2;
           	}
           
        #hour {
        	background: url(images/hourhand.png);
        	z-index: 1;
           	}
           	
        p {
            text-align: center; 
            padding: 10px 0 0 0;
            }
    </style>
    
    <script type="text/javascript">
    
        $(document).ready(function() {
         
              setInterval( function() {
              var seconds = new Date().getSeconds();
              var sdegree = -1 * (seconds * 6);
              var srotate = "rotate(" + sdegree + "deg)";
              
              $("#sec").css({"-moz-transform" : srotate, "-webkit-transform" : srotate});
                  
              }, 1000 );
              
         
              setInterval( function() {
              var hours = new Date().getHours();
              var mins = new Date().getMinutes();
              var hdegree = -1 * (hours * 30 + (mins / 2));
              var hrotate = "rotate(" + hdegree + "deg)";
              
              $("#hour").css({"-moz-transform" : hrotate, "-webkit-transform" : hrotate});
                  
              }, 1000 );
        
        
              setInterval( function() {
              var mins = new Date().getMinutes();
              var mdegree = -1 * (mins * 6);
              var mrotate = "rotate(" + mdegree + "deg)";
              
              $("#min").css({"-moz-transform" : mrotate, "-webkit-transform" : mrotate});
                  
              }, 1000 );
         
        }); 
    
    </script>

<style type="text/css">
.clockStyle {
	color:#F00;
	font-family:"LCD", Gadget, sans-serif;
        font-size:45px;
        font-weight:bold;
	letter-spacing: 2px;
	display:inline;
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
			var wclockduration = <?php echo $config['worldclock'] * 1000; ?>;
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
			setTimeout('move()',wclockduration);

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
					   window.location = "../clock/taqwim-sleep.php";  //pergi ke sleep mode
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

				//if mSec before azan, so chk waktu blinking
					//waktublinkingduration = blinkingduration * 60 * 1000;
					if( mSec <= blinkingduration*60*1000 )
					  {
					  //if dah 5 min b4 azan then clear interval  
					  clearInterval ( chkIntervalId );
					  window.location = "../clock/waktublinking.php?masihi=" + "<?php echo "$tarikh_harini_long" . '&hijrah=' . "$tarikh_hijrah"; ?>";
		  
					}  	  					
			}

function renderTime() {
	var currentTime = new Date();
	var diem = "AM";
	var h = currentTime.getHours();
	var m = currentTime.getMinutes();
    var s = currentTime.getSeconds();
	setTimeout('renderTime()',1000);
	
//	h=h+12;
//mekah
	h0 = h-5;	
	if(h0<0) h0 = h0+24;
	else
	if (h0 > 23) { 
		h0 = h0 - 24;
	}
	if (h0 < 10) {
		h0 = "0" + h0;
	}
	
//singapore/manila/beijing/hanoi
	h1 = h;	
	if(h1<0) h1 = h1+24;
	else
	if (h1 > 23) { 
		h1 = h1 - 24;
	}
	if (h1 < 10) {
		h1 = "0" + h1;
	}
	
//bangkok/jakarta
	h2 = h-1;	
	if(h2<0) h2 = h2+24;
	else
	if (h2 > 23) { 
		h2 = h2 - 24;
	}
	if (h2 < 10) {
		h2 = "0" + h2;
	}	
	

//dhaka/delhi
	h3 = h-2;	
	if(h3<0) h3 = h3+24;
	else
	if (h3 > 23) { 
		h3 = h3 - 24;
	}
	if (h3 < 10) {
		h3 = "0" + h3;
	}	


//newyork
	h4 = h-13;	
	if(h4<0) h4 = h4+24;
	else
	if (h4 > 23) { 
		h4 = h4 - 24;
	}
	if (h4 < 10) {
		h4 = "0" + h4;
	}	
	

//london
	h5 = h-8;	
	if(h5<0) h5 = h5+24;
	else
	if (h5 > 23) { 
		h5 = h5 - 24;
	}
	if (h5 < 10) {
		h5 = "0" + h5;
	}	
	

//tokyo
	h6 = h+1;	
	if(h6<0) h6 = h6+24;
	else
	if (h6 > 23) { 
		h6 = h6 - 24;
	}
	if (h6< 10) {
		h6 = "0" + h6;
	}	
	

//canberra
	h7 = h+3;	
	if(h7<0) h6 = h7+24;
	else
	if (h7 > 23) { 
		h7 = h7 - 24;
	}
	if (h7< 10) {
		h7 = "0" + h7;
	}	

	
	if (m < 10) {
		m = "0" + m;
	}
	if (s < 10) {
		s = "0" + s;
	}
 //   var myClock = document.getElementById('clock1');
//	myClock.textContent = h + ":" + m + ":" + s + " " + diem;
//	myClock.innerText = h + ":" + m + ":" + s + " " + diem;

// document.getElementById('clock1').textContent = h + ":" + m + ":" + s + " " + diem;
document.getElementById('clockmekah').innerHTML = h0 + ":" + m;

 document.getElementById("clock1").innerHTML = h1 + ":" + m;
 document.getElementById("clock2").innerHTML = h2 + ":" + m;
 document.getElementById('clock3').innerHTML = h1 + ":" + m;
 document.getElementById('clock4').innerHTML = h1 + ":" + m;
 document.getElementById('clock5').innerHTML = h2 + ":" + m;
 //document.getElementById('clock6').innerHTML = h2 + ":" + m;
 
document.getElementById('clock7').innerHTML = h4 + ":" + m;
document.getElementById('clock8').innerHTML = h5 + ":" + m;
document.getElementById('clock9').innerHTML = h6 + ":" + m;
document.getElementById('clock10').innerHTML = h2 + ":" + m;
document.getElementById('clock11').innerHTML = h7 + ":" + m;
//document.getElementById('clock12').innerHTML = h3 + ":" + m;

}

			var time = null
			function move() {
//			window.location = "../swing-azan/swingazan.php";
			window.location = "../slidetemplate-kuliahtetap.php?passing_id=0";

			}

</script>
</head>

<body onload="chkWaktuHandler()" class="fullheight">


<script>
renderTime();
</script>

<div class="background" style="position: absolute; top: 0; left: 0; width: 1280px; height: 720px; background-image:url('../bg/bg-wclock.jpg');
background-repeat:no-repeat; background-attachment:fixed; background-position:center;"> 
</div>

<div style="position: absolute; top: -10; left: 330;">
<ul id="clock">	
	   	<li id="sec"></li>
	   	<li id="hour"></li>
		<li id="min"></li>
</ul>
</div>
	
<div style="position: absolute; top: -10; left: -30;">

	
<div id="clockmekah" class="clockStyle" style="position: absolute; top: 70px; left: 560px;  font-size: 65px; "></div>

<div id="clock1" class="clockStyle" style="position: absolute; top: 130px; left: 860px;"></div>
<div id="clock2" class="clockStyle" style="position: absolute; top: 245px; left: 1030px;"></div>
<div id="clock3" class="clockStyle" style="position: absolute; top: 365px; left: 45px;"></div>
<div id="clock4" class="clockStyle" style="position: absolute; top: 615px; left: 900px;"></div>
<div id="clock5" class="clockStyle" style="position: absolute; top: 365px; left: 1090px;"></div>

<div id="clock7" class="clockStyle" style="position: absolute; top: 235px; left: 120px;"></div>
<div id="clock8" class="clockStyle" style="position: absolute; top: 115px; left: 300px;"></div>
<div id="clock9" class="clockStyle" style="position: absolute; top: 500px; left: 1030px;"></div>
<div id="clock10" class="clockStyle" style="position: absolute; top: 490px; left: 120px;"></div>
<div id="clock11" class="clockStyle" style="position: absolute; top: 610px; left: 240px;"></div>

</div>


</body>
</html>
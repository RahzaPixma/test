<html>
<head>
    <script type="text/javascript" src="jquery-1.2.6.min.js"></script>
    
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
              var sdegree = seconds * 6;
              var srotate = "rotate(" + sdegree + "deg)";
              
              $("#sec").css({"-moz-transform" : srotate, "-webkit-transform" : srotate});
                  
              }, 1000 );
              
         
              setInterval( function() {
              var hours = new Date().getHours();
              var mins = new Date().getMinutes();
              var hdegree = hours * 30 + (mins / 2);
              var hrotate = "rotate(" + hdegree + "deg)";
              
              $("#hour").css({"-moz-transform" : hrotate, "-webkit-transform" : hrotate});
                  
              }, 1000 );
        
        
              setInterval( function() {
              var mins = new Date().getMinutes();
              var mdegree = mins * 6;
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


<?php
include "../hijrah/Hijri_GregorianConvert.class";

//Load config file
$config = parse_ini_file('../setting/setting.ini');

// Configure connection settings
$db = 'pt';
$db_admin = 'root';
$db_password = 'suhair007';
$tablename = 'taqwim';
$zone = $config['zon'];
 
date_default_timezone_set('Asia/Singapore');

// Connect to DB
$sql = mysql_connect("localhost", $db_admin, $db_password)
or die(mysql_error());

mysql_select_db($db, $sql);

$datatext = array();
$todays_date = date("Y-m-d", strtotime('today'));
$query = "SELECT * FROM $tablename WHERE tarikh = '$todays_date' AND kodlokasi=" . $zone;
$result = mysql_query($query);
$row = mysql_fetch_array($result);

$tarikh = $row['tarikh'];

//calculate hijrah
$day_offset = $config['hijri_offset'];
if($day_offset == '0') $harini = date("Y-m-d", strtotime('today'));
else $harini = date("Y-m-d", strtotime("$day_offset day"));
$DateConv=new Hijri_GregorianConvert;
$format="YYYY-MM-DD";
$tarikh_hijrah = $DateConv->GregorianToHijri($harini,$format,0);

$harini_long = date("d-m-Y", strtotime('today'));
$tarikh_harini_long = getFullDate($harini_long,0);

$harini_short = date("d-m-Y", strtotime('today'));
$harini_full = getFullDate($harini_short,0) ;
//, str_replace("-11-","-Nov-",$harini);

//-debug echo "harini full = " . $harini_full . "</br>";
//-debug echo "harini hijrah = " . $tarikh_hijrah . "</br>";

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
$diqomah =  array ( 0, 0, $config['iqomah_subuh'], 0, $config['iqomah_zohor'], $config['iqomah_asar'], $config['iqomah_maghrib'], $config['iqomah_isyak'], 0 );
$diqomah_jumaat =  array ( 0, 0, $config['iqomah_subuh'], 0, $config['iqomah_jumaat'], $config['iqomah_asar'], $config['iqomah_maghrib'], $config['iqomah_isyak'], 0 );


$file = fopen("../scroller/scrolling.txt","r");
  if(!file)
    {
      //-debug echo("ERROR:cant open file");
	  $text_scroll2 = "";
    }
    else
    {
      $text_scroll2 = fread ($file,filesize("../scroller/scrolling.txt"));
 //     print $buff;
    }
	
//$text_scroll2 = "Pastikan anda berniat iktikaf ketika berada di dalam masjid....  ??????? ?????? ??????? ?????????? ????????? ??????????? ?????????????? ???????????? ???? ?????????? ????????????? ????????? ? Kamu (wahai umat Muhammad) adalah sebaik-baik umat yang dilahirkan bagi (faedah) umat manusia, (kerana) kamu menyuruh berbuat segala perkara yang baik dan melarang daripada segala perkara yang salah (buruk dan keji),  serta kamu pula beriman kepada Allah (dengan sebenar-benar iman).  (Surah Ali `Imran: 110)";
$text_scroll = $text_scroll2;
$space = strlen($text_scroll) * 40;   //asal 60



//if ( strlen($wnow2) == 0 ) {
$wnow = 0;
$current_time = strtotime('now'); // + 8*60*60;
//-debug echo "</br>current_time = " . $current_time . "</br>";
//die;
//$current_time = strtotime('2012-11-05 5:44:00');

$flg_iqo = 0;
for ($i = 0; $i < 8; $i++) {

//-debug echo $namasolat[$i] . " - " . $alldatewaktu[$i] . "<br>";

	if($i != 7 && $i != 1) {
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

//-debug echo "next waktu to check = " . $namasolat[$wnow] . " iaitu = " . ($alldatewaktu[$wnow]) . "</br>";

    $directory="../slides";
   // create a handler to the directory
    $dirhandler = opendir($directory);
 
    // read all the files from directory
    $nofiles=0;
	$s_slides = "";
	
   while ($file = readdir($dirhandler)) {
 
		
        // if $file isn't this directory or its parent 
        //add to the $files array
        if ($file != '.' && $file != '..')
        {
			$nofiles++;
			$files[$nofiles]= $file; 
			//echo $file;
			if ($nofiles > 1) {
			   $s_slides = $s_slides . ",{ src : '../slides/" . $file . "' }";
			} else {
			   $s_slides = $s_slides . "{ src : '../slides/" . $file . "' }";
			}
       }   
		
   }
 
    //close the handler
    closedir($dirhandler);
	//-debug echo "file slides = " . $s_slides . "</br>";
	//-debug echo "no fo files = " . $nofiles . "</br>";
	


//echo "next waktu to check = " . $namasolat[$wnow] . " iaitu = " . ($alldatewaktu[$wnow] + 24*60*60);

//$rest = substr("abcdef", 0, -1);  // returns "abcde"


		
			//-debug echo "<br/>";
			//-debug echo "wnow = $wnow<br/>";
			//-debug echo "th = $th[$wnow]<br/>";
			//-debug echo "tm = $tm[$wnow]<br/>";
			//-debug echo "namasolat = $namasolat[$wnow]<br/>";
			//-debug echo "nextday = $nextday<br/>";
			//-debug echo "diqo = $diqomah[$wnow]<br/>";

?>

</style>
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
			var dIqomah = <?php
				$harini_today = getdate();
				$harini_day = $harini_today['weekday'];
				if( strcmp($harini_day,"Friday") == 0 ) echo $diqomah_jumaat[$wnow];
				else  echo $diqomah[$wnow];
			?>;
			var dIqomahNeg = -1 * dIqomah;
			var wclockduration = <?php echo $config['worldclock'] * 1000; ?>;
			var blinkingduration = <?php echo $config['waktublinking']; ?>;
			var gblFlagIqo = 0;


			function chkWaktuHandler ( )
			{
			
			var now = new Date();
			if(	wnow == 1 && nextday ==1 ) {
			    var datesol = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hSolatNext + 24, mSolatNext, 0, 0);
				var mSec =  datesol - now;
				var mSec2 = datesol.setMinutes(datesol.getMinutes() + dIqomah) - now;
				}
			else {
				var datesol = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hSolatNext, mSolatNext, 0, 0);
				var mSec =  datesol - now;
				var mSec2 = datesol.setMinutes(datesol.getMinutes() + dIqomah) - now;
				}

			//setTimeout('move()',3000);
			setTimeout('move()',wclockduration);

			  //chk change date
			   var chkIntervalId3 = setInterval ( "chkChangeDate()", 1000 );
			
			 //activate interval function for periodically chk waktu if blm time (still +ve value)
			 if( mSec > 0 && nextday == 0 ) 
			   chkIntervalId = setInterval ( "chkWaktuBlinking(waktu,namaSolat)", 1000 );

			if( (mSec2 > 0) && (mSec2 < dIqomah*60*1000 ) && nextday == 0 ) {
				//alert ("between 0 - 10mins");
				chkIntervalId = setInterval ( "chkScreenSolat(waktu,namaSolat)", 1000 );
				//chkScreenSolat(waktu,namaSolat);				

				}				
		
		 	}


		    //chk if date changed (date tak sama)
			function chkChangeDate()
			{
				var chgdate_now2 = new Date();  //now.getFullYear(), now.getMonth(), now.getDate(),0, 0, 0, 0);

				if( chgdate_now1 < (chgdate_now2 - (10*60*1000)) )   //&&  chgdate_now1 > (chgdate_now2 - (10*60*1000)) )
				{
 				  window.location = "../slide.php";
				}

				if( chgdate_now1 > (chgdate_now2 + (10*60*1000)) )   //&&  chgdate_now1 > (chgdate_now2 - (10*60*1000)) )
				{
 				  window.location = "../slide.php";
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
					  window.location = "../clock/waktublinking.php?masihi=" + "<?php echo "$tarikh_harini_long" . '&hijrah=' . "$tarikh_hijrah"; ?>";

/**					  
					  window.location = "./waktublinking.php?wnow=<?php 
		  $tth = $th[$wnow];
		  $ttm = $tm[$wnow];
		  echo $wnow . "&th=$tth" . "&tm=$ttm" . "&$tarikh_harini_long" . '</br>' . $tarikh_hijrah; ?>";
*/		  
		  
					}  	  	
//				}					
			}

			
			function chkScreenSolat ( waktu, namaSolat)
			{
				//document.getElementById(namaSolat).innerHTML = waktu;
				//document.getElementById(namaSolat).style.color = 'red';
				//setTimeout ( 'document.getElementById(namaSolat).innerHTML = ""', 500 );

			
				var now = new Date();
				var taqsolat = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hSolatNext, mSolatNext, 0, 0);
				var mSec = taqsolat.setMinutes(taqsolat.getMinutes() + dIqomah) - now;
				mins = Math.floor((mSec/1000)/60);
				secs = Math.ceil( ((mSec/1000)/60 - mins) * 60 ); 
				//minWaktuBlinking = Math.floor((waktublinkingduration/1000)/60);
				
				
				dSolatMin = mins;
				//secs = 0;

				if (nextday == 0  && wnow != 1) {
					if( mSec <= 0 )
					  {
					  //if dah masuk waktu then azan then clear interval  
					  clearInterval ( chkIntervalId );
					  //window.location = "../solat.php";
					  window.location = "../solat.php?wnow=<?php echo $wnow; ?>";
					}  	
					else {
						//
					} //else
				} //if
			} //func
	


/**
$(function () {
	$('#london').flightboard({messages: ['LONDON', 'UNITED KINGDOM'],
		lettersImage: 'flightBoardLarge.png',
		shadingImages: ['flightBoardHigh.png', 'flightBoardShad.png']});
		
	$('#usa').flightboard({messages: ['NEW YORK', 'USA'],
		lettersImage: 'flightBoardLarge.png',
		shadingImages: ['flightBoardHigh.png', 'flightBoardShad.png']});		
});
**/
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

	
/**	
    if (h1 == 0) {
		h1 = 12;
	} else if (h1 > 12) { 
		h1 = h1 - 12;
		diem="PM";
	}
	if (h1 < 10) {
		h1 = "0" + h1;
	}
		
//bangkok
	h1 = h;	
	if(h1<0) h1 = h1+12;
	else
    if (h1 == 0) {
		h1 = 12;
	} else if (h1 > 12) { 
		h1 = h1 - 12;
		diem="PM";
	}
	if (h1 < 10) {
		h1 = "0" + h1;
	}
**/			
	
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
			window.location = "../countdown/fb/cd1.php";
			}

</script>
</head>
<!--	<body onload="timer=setTimeout('move()',<?php echo $config['worldclock']*1000; ?>)"> -->

<body onload="chkWaktuHandler()">

<script>
renderTime();
</script>

<div class="background" style="position: absolute; top: 0; left: 0; width: 1280px; height: 720px; background-image:url('../bg/bg-wclock.jpg');
background-repeat:no-repeat; background-attachment:fixed; background-position:center;"> 
</div>

<ul id="clock">	
	   	<li id="sec"></li>
	   	<li id="hour"></li>
		<li id="min"></li>
</ul>
	
	
<div id="clockmekah" class="clockStyle" style="position: absolute; top: 70px; left: 610px;  font-size: 65px; "></div>

<div id="clock1" class="clockStyle" style="position: absolute; top: 130px; left: 910px;"></div>
<div id="clock2" class="clockStyle" style="position: absolute; top: 245px; left: 1080px;"></div>
<div id="clock3" class="clockStyle" style="position: absolute; top: 365px; left: 95px;"></div>
<div id="clock4" class="clockStyle" style="position: absolute; top: 615px; left: 950px;"></div>
<div id="clock5" class="clockStyle" style="position: absolute; top: 365px; left: 1140px;"></div>
<!-- <div id="clock6" class="clockStyle" style="position: absolute; top: 640px; left: 330px;"></div> -->


<div id="clock7" class="clockStyle" style="position: absolute; top: 235px; left: 170px;"></div>
<div id="clock8" class="clockStyle" style="position: absolute; top: 115px; left: 350px;"></div>
<div id="clock9" class="clockStyle" style="position: absolute; top: 500px; left: 1080px;"></div>
<div id="clock10" class="clockStyle" style="position: absolute; top: 490px; left: 170px;"></div>
<div id="clock11" class="clockStyle" style="position: absolute; top: 610px; left: 290px;"></div>
<!--
<div  id="mekah" style="position: absolute; top: 10px; left: 300px; font-size: 55px; font-family: Verdana Bold; ">MAKKAH</div>


<div  id="singapore" style="position: absolute; top: 150px; left: 20px; font-size: 45px; font-family: Verdana Bold; ">SINGAPORE</div>
<div  id="bangkok" style="position: absolute; top: 250px; left: 20px; font-size: 45px; font-family: Verdana Bold; ">BANGKOK</div>
<div  id="manila" style="position: absolute; top: 350px; left: 20px; font-size: 45px; font-family: Verdana Bold; ">MANILA</div>
<div  id="beijing" style="position: absolute; top: 450px; left: 20px; font-size: 45px; font-family: Verdana Bold; ">BEIJING</div>
<div  id="jakarta" style="position: absolute; top: 550px; left: 20px; font-size: 45px; font-family: Verdana Bold; ">JAKARTA</div>

<div  id="newyork" style="position: absolute; top: 150px; left: 650px; font-size: 45px; font-family: Verdana Bold; ">NEW YORK</div>
<div  id="london" style="position: absolute; top: 250px; left: 650px; font-size: 45px; font-family: Verdana Bold; ">LONDON</div>
<div  id="tokyo" style="position: absolute; top: 350px; left: 650px; font-size: 45px; font-family: Verdana Bold; ">TOKYO</div>
<div  id="hanoi" style="position: absolute; top: 450px; left: 650px; font-size: 45px; font-family: Verdana Bold; ">HANOI</div>
<div  id="canberra" style="position: absolute; top: 550px; left: 650px; font-size: 45px; font-family: Verdana Bold; ">CANBERRA</div>
-->
<!-- <div  id="newdelhi" style="position: absolute; top: 650px; left: 700px; font-size: 45px; font-family: Verdana Bold; ">NEW DELHI</div> -->

</body>
</html>
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

	
	/*
	if ( $row['item'] === 'slide' ) $config['slide'] =  $row['duration'];
	if ( $row['item'] === 'worldclock' ) $config['worldclock'] =  $row['duration'];
	if ( $row['item'] === 'taqwim' ) $config['taqwim'] =  $row['duration'];
	if ( $row['item'] === 'jadualkuliah' ) $config['jadualkuliah'] =  $row['duration'];
	if ( $row['item'] === 'countdown' ) $config['countdown'] =  $row['duration'];
	*/

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


//-debug echo "next waktu to check = " . $namasolat[$wnow] . " iaitu = " . ($alldatewaktu[$wnow]) . "</br>";
/*
$datatext = array();
$todays_date = date("Y-m-d", strtotime('today'));
$query = "SELECT * FROM taqwim WHERE tarikh = '$todays_date' AND kodlokasi=" . $zone;
$result = mysqli_query($conn, $query);
$row =  mysqli_fetch_array($result, MYSQLI_ASSOC);

$tarikh = $row['tarikh'];

//calculate hijrah
$query2 = "SELECT * FROM tbm_hijrioffset WHERE id = 1";
$result2 = mysqli_query($conn, $query2);
$row2 =  mysqli_fetch_array($result2, MYSQLI_ASSOC);
//$day_offset = $config['hijri_offset'];
$day_offset = 0; //supaya jadi integer
$day_offset = $row2['hijri_offset'];
if($day_offset == 0) $harini = date("Y-m-d", strtotime('today'));
else $harini = date("Y-m-d", strtotime("$day_offset day"));
$DateConv=new Hijri_GregorianConvert;
$format="YYYY-MM-DD";
$tarikh_hijrah = $DateConv->GregorianToHijri($harini,$format,0);

$harini_long = date("d-m-Y", strtotime('today'));
$tarikh_harini_long = getFullDate($harini_long,0);
*/


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

//tambah 22/10/2018
mysqli_free_result($result);


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

	if ( $row['hari'] === '$namahari_rujuk_iqo' ) {
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

/*****V3*****************
//$text_scroll = "";
$file = fopen("../scroller/scrolling.txt","r");
  if(!$file)
    {
      //-debug echo("ERROR:cant open file");
	  $text_scroll2 = "";
    }
    else
    {
      $text_scroll2 = fread ($file,filesize("../scroller/scrolling.txt"));
 //     print $buff;
    }
*****************/


$query = "SELECT * FROM tbm_scroller";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
//$text_scroll = $row['text'] . '<span width="50"> </span>';	
$text_scroll = $row['text'];	
$scroll_speed = $row['speed'];
$txtlength = strlen($text_scroll);
//$text_scroll = substr(($text_scroll2,2,$txtlength-2);
$space = strlen($text_scroll) * ($scroll_speed * 10);   //asal 60
mysqli_free_result($result);



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
die;
*/

//echo $text_scroll;
//die;
?>

<html>
<head>

	  
    <link href="../src/jquery.counter-analog.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="../src/jquery.counter-analog2.css" media="screen" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/jquery.jdigiclock.css" />
		  <script type="text/javascript" src="../src/jquery.min.js" type="text/javascript"></script> 

		
<script type="text/javascript">		
//var arg_date = '&nbsp&nbsp' + '<?php echo $_GET['masihi'] . '&nbsp&nbsp</br>' . $_GET['hijrah']; ?>';
var arg_date = '&nbsp&nbsp' + '<?php echo $harini_full . '&nbsp&nbsp</br>' . $tarikh_hijrah; ?>';
</script>	

<script type="text/javascript" src="../src/jquery.counter.js" type="text/javascript"></script>
 	
	
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
.container{
position: relative;
width: 200px; /*marquee width */
height: 80px; /*marquee height */
overflow: hidden;
background-color: white;
border: 2px solid orange;
padding: 2px;
padding-left: 4px;
}

</style>


      <script type="text/javascript">
            $(document).ready(function() {
 
			$('#digiclock').jdigiclock({
				proxyType: 'php',
				am_pm: true,
				weatherLocationCode: 'ASI|IN|IN012|AHMEDABAD',
		 
 });
 
 });  //doc
 
        </script>
</head>

<script type="text/javascript">
			var chkIntervalId = 0;
			var chgdate_now1 = new Date();  //now.getFullYear(), now.getMonth(), now.getDate(),1, 0, 0, 0);


			//var zohor = 
			
			var wnow = <?php echo $wnow; ?>;
			var hSolatNext = <?php echo $th[$wnow]; ?>;
			var mSolatNext = <?php echo $tm[$wnow]; ?>;
			var waktu = "<?php
				  $x = substr($allwaktu[$wnow], 0, 2);
				   $y = substr($allwaktu[$wnow], -2, 2);
				   if($x > 12) $x = $allwaktu[$wnow] - 12;
				   //showdatawaktu($zohor);
			if( $th[$wnow] > 12 ) echo substr($x,0,1) . ":" . $y;
			else echo $allwaktu[$wnow]; ?>";
			var namaSolat = "<?php echo $namasolat[$wnow]; ?>";
			var nextday = <?php echo $nextday; ?>;

			var blinkingduration = <?php echo $config['blinking']; ?>; // 5 min sebelum azan
			var gblFlagIqo = 0;


			function chkWaktuHandler ( )
			{
			
			var now = new Date();
			if(	wnow == 1 && nextday ==1 ) 
				var mSec = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hSolatNext + 24, mSolatNext, 0, 0) - now;
			else
				var mSec = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hSolatNext, mSolatNext, 0, 0) - now;

			//setTimeout('move()',90000);
			//setTimeout('move()',taqwimduration);

			  //chk change date
			  // var chkIntervalId3 = setInterval ( "chkChangeDate()", 1000 );

			
			 //activate interval function for periodically chk waktu if blm time (still +ve value)
//			 if( mSec > 0 && nextday == 0 ) 
//			   chkIntervalId = setInterval ( "chkMasukWaktu(waktu,namaSolat)", 1000 );
		   
			//activate interval function for periodically chk waktu if dh msk waktu and sblm 10mins
			// mins = Math.ceil((mSec/1000)/60);
// 4/10/2020 (+5000 utk elank hang)
//if( (mSec > 0) && (mSec < blinkingduration*60*1000 ) && nextday == 0 ) {

			if( (mSec > 0) && (mSec < ((blinkingduration*60*1000) + 5000)   ) && nextday == 0 ) {  //version 5.4.2 (+ elak hang)
				//alert ("between 0 - 10mins");
				if(wnow != 1 && wnow != 3) chkIntervalId = setInterval ( "chkScreenSolat(waktu,namaSolat)", 1000 );
				else {
				if(wnow == 1) chkIntervalId = setInterval ( "chkScreenImsak(waktu,namaSolat)", 1000 );
				if(wnow == 3) chkIntervalId = setInterval ( "chkScreenSyuruk(waktu,namaSolat)", 1000 );
				}
				//chkScreenSolat(waktu,namaSolat);				

				}			   
				   
		 	}

/*
		    //chk if date changed (date tak sama)
			function chkChangeDate()
			{
				var chgdate_now2 = new Date();  //now.getFullYear(), now.getMonth(), now.getDate(),0, 0, 0, 0);

				if( chgdate_now1 < (chgdate_now2 - (10*60*1000)) )   //&&  chgdate_now1 > (chgdate_now2 - (10*60*1000)) )
				{
 				  window.location = "/pt/slides/src/slide.php?first=1";
				}

				if( chgdate_now1 > (chgdate_now2 + (10*60*1000)) )   //&&  chgdate_now1 > (chgdate_now2 - (10*60*1000)) )
				{
 				  window.location = "/pt/slides/src/slide.php?first=1";
				}
								
			}

*/


		//check azan hide or show (nak elak heng)
		function chkAzanCountdown() 
		{

			//azan
			if(wnow != 1 && wnow != 3) {
				if( document.getElementById("based-azan").style.visibility == "visible"){
					 //alert("The paragraph  is visible.");
					 //ok xyah buat apa2
				} else{
						//alert("The paragraph  is hidden.");
						move();
					 }
			}
						 
						 
			//imsak
			if(wnow == 1) {
				if( document.getElementById("based-imsak").style.visibility == "visible"){
							//alert("The paragraph  is visible.");
							//ok xyah buat apa2
						} else{
								//alert("The paragraph  is hidden.");
								move();
							 }
			}
						 						 
						 
			//syuruk
			if(wnow == 3) {
				if( document.getElementById("based-syuruk").style.visibility == "visible"){
                         //alert("The paragraph  is visible.");
						 //ok xyah buat apa2
                    } else{
                            //alert("The paragraph  is hidden.");
							move();
                         }
			}
						 						 
						 
		}




			function chkScreenSolat ( waktu, namaSolat)
			{
				document.getElementById(namaSolat).innerHTML = waktu;
				document.getElementById(namaSolat).style.color = 'red';
				setTimeout ( 'document.getElementById(namaSolat).innerHTML = ""', 500 );

			
				var now = new Date();
				var taqsolat = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hSolatNext, mSolatNext, 0, 0);
				var mSec = taqsolat - now;
				mins = Math.floor((mSec/1000)/60);
				secs = Math.ceil( ((mSec/1000)/60 - mins) * 60 ); 
				//minWaktuBlinking = Math.floor((waktublinkingduration/1000)/60);
				
				
				dSolatMin = mins;
				//secs = 0;

				if (nextday == 0  && wnow != 1 && wnow != 3) {
					if( mSec <= 0 )
					  {
					  //if dah masuk waktu then azan then clear interval  
					  clearInterval ( chkIntervalId );
					  window.location = "../swing-azan/swingazan.php?wnow=<?php 
	  $tth = $th[$wnow];
	  $ttm = $tm[$wnow];
	  echo $wnow . "&th=$tth" . "&tm=$ttm"; ?>";
					}  	
					else {
					    if( gblFlagIqo == 0 ) {
							document.getElementById("azantext").innerHTML = "AZAN";
							//document.getElementById("based-azan").innerHTML = "<img src='../bg/based-azan.jpg'></img>";
							document.getElementById("based-azan").style.visibility = "visible";
							document.getElementById("countdowniqomah").innerHTML = "<span style='font-size: 83px; color:black; position: absolute; top: 250px; left: 850px; z-index: 23;' class='counter counter-analog2' data-direction='down' data-format='59: 59' data-stop='00:00' data-interval='1000'>" + dSolatMin + ":" + secs + "</span>";
							$('.counter').counter({});
							gblFlagIqo = 1;
						}
					} //else
				} //if

			//check azan hide or show (nak elak heng)
			chkAzanCountdown();

			} //func
	
			function chkScreenImsak ( waktu, namaSolat)
			{
				document.getElementById(namaSolat).innerHTML = waktu;
				document.getElementById(namaSolat).style.color = 'red';
				setTimeout ( 'document.getElementById(namaSolat).innerHTML = ""', 500 );

			
				var now = new Date();
				var taqsolat = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hSolatNext, mSolatNext, 0, 0);
				var mSec = taqsolat - now;
				mins = Math.floor((mSec/1000)/60);
				secs = Math.ceil( ((mSec/1000)/60 - mins) * 60 ); 
				//minWaktuBlinking = Math.floor((waktublinkingduration/1000)/60);
				
				
				dSolatMin = mins;
				//secs = 0;

				if (nextday == 0 && wnow == 1) {
					if( mSec <= 0 )
					  {
					  //if dah masuk waktu then azan then clear interval  
					  clearInterval ( chkIntervalId );
					  window.location = '../swing-azan/swingimsak.php?wnow=1';
					}  	
					else {
					    if( gblFlagIqo == 0 ) {
							document.getElementById("imsaktext").innerHTML = "IMSAK";
							//document.getElementById("based-imsak").innerHTML = "<img src='../bg/based-imsak.jpg'></img>";
							document.getElementById("based-imsak").style.visibility = "visible";
							document.getElementById("countdowniqomah").innerHTML = "<span style='font-size: 83px; color:black; position: absolute; top: 250px; left: 850px; z-index: 23;' class='counter counter-analog2' data-direction='down' data-format='59: 59' data-stop='00:00' data-interval='1000'>" + dSolatMin + ":" + secs + "</span>";
							$('.counter').counter({});
							gblFlagIqo = 1;
						}
					} //else
				} //if


			//check azan hide or show (nak elak heng)
			chkAzanCountdown();


			} //func
	
			function chkScreenSyuruk ( waktu, namaSolat)
			{
				document.getElementById(namaSolat).innerHTML = waktu;
				document.getElementById(namaSolat).style.color = 'red';
				setTimeout ( 'document.getElementById(namaSolat).innerHTML = ""', 500 );
			
				var now = new Date();
				var taqsolat = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hSolatNext, mSolatNext, 0, 0);
				var mSec = taqsolat - now;
				mins = Math.floor((mSec/1000)/60);
				secs = Math.ceil( ((mSec/1000)/60 - mins) * 60 ); 
				//minWaktuBlinking = Math.floor((waktublinkingduration/1000)/60);
				
				
				dSolatMin = mins;
				//secs = 0;

				if (nextday == 0 && wnow == 3) {
					if( mSec <= 0 )
					  {
					  //if dah masuk waktu then azan then clear interval  
					  clearInterval ( chkIntervalId );
					  window.location = '../swing-azan/swingsyuruk.php?wnow=3';
					}  	
					else {
					    if( gblFlagIqo == 0 ) {
							document.getElementById("syuruktext").innerHTML = "SYURUK";
							//document.getElementById("based-syuruk").innerHTML = "<img src='../bg/based-syuruk.jpg'></img>";
							document.getElementById("based-syuruk").style.visibility = "visible";
							document.getElementById("countdowniqomah").innerHTML = "<span style='font-size: 83px; color:black; position: absolute; top: 250px; left: 850px; z-index: 23;' class='counter counter-analog2' data-direction='down' data-format='59: 59' data-stop='00:00' data-interval='1000'>" + dSolatMin + ":" + secs + "</span>";
							$('.counter').counter({});
							gblFlagIqo = 1;
						}
					} //else
				} //if


			//check azan hide or show (nak elak heng)
			chkAzanCountdown();


			} //func

	
			function move() {
				window.location = "/pt/slides/src/slide.php";
			}
			
			</script>
			
			</head>

<body onload="chkWaktuHandler()" class="fullheight">
			

       <script type="text/javascript" src="lib/jquery.jdigiclock.js"></script>
  			<div  id="digiclock" style="position: absolute; top: 0px; left: 30px; width: 100px; height: 12px; z-index: 20;  font-size: 43px; font-family: Arial Black; color: darkgrey;">
			</div>
 			<div  id="hari" style="position: absolute; top: 260px; left: 340px; width: 100px; height: 12px; z-index: 21;  font-size: 43px; font-family: Arial Black; color: white;">
			<?php echo getNamaHari(date("D")); ?>
			</div>

<span id="countdowniqomah" style="font-size: 83px; color:black; position: absolute; top: 250px; left: 850px; z-index: 23;" class="counter counter-analog2" data-direction="down" data-format="59: 59" data-stop="00:00" data-interval="1000"></span>	
			
<div id="azantext" style="z-index: 22; position: absolute; top: 180px; left: 910px; width: 100px; height: 125px;  font-size: 60px; font-family:  Verdana Bold;  font-weight: bold; color: black;">
</div>

  <div id="based-azan" style="z-index: 19; position: absolute; top: 185px; left: 900px; width:380px; height:75px; visibility: hidden;">
      <img src='../bg/based-azan.jpg'></img>
  </div>

  <div id="based-syuruk" style="z-index: 19; position: absolute; top: 180px; left: 872px; width:380px; height:175px; visibility: hidden;">
      <img src='../bg/based-syuruk.jpg'></img> 
  </div>

  <div id="based-imsak" style="z-index: 19; position: absolute; top: 185px; left: 890px; width:380px; height:75px; visibility: hidden;">
       <img src='../bg/based-imsak.jpg'></img>
  </div>

<div id="syuruktext" style="z-index: 23; position: absolute; top: 180px; left: 882px; width: 100px; height: 125px;  font-size: 60px; font-family: Arial Black; color: black;">
</div>  
  
<div id="imsaktext" style="z-index: 23; position: absolute; top: 180px; left: 900px; width: 100px; height: 125px;  font-size: 60px; font-family: Arial Black; color: black;">
</div>
	
			
			<p>
			

     <!-- Taqwim BG-->   
        <div style="background-image:url('../bg/bg-taqwim.jpg');height: 100%;background-position: center;background-repeat: no-repeat; background-size: cover;">
        </div>

		
<marquee width="60%" direction="left" height="100px" style="background-image: url('../bg/white.jpg'); position: absolute; top: 630px; left: 0px; width: 1280px; height: 80px; z-index: 5; font-family: calibri; font-size: 40px; color: green; z-index:5;"  >
<div style="position: absolute; z-index:6; width: <?php echo $space;?>px;">
<?php echo $text_scroll;
?>
</div>
</marquee>


		
<!--
<div class="background" style="position: absolute; top: 0; left: 0; width: 1280px; height: 720px; background-image:url('../bg/bg-taqwim.jpg');
background-repeat:no-repeat; background-attachment:fixed; background-position:center;"> 
</div>
-->

<div class="background" style="position: absolute; top: 0; left: 0; width: 1280px; height: 720px; background-image:url('../bg/bg-taqwim-box.png');
background-repeat:no-repeat; background-attachment:fixed; background-position:center;"> 
</div>



<div style="position: absolute; top: -5; left: -13;">

   <div id="imsak" style="position: absolute; top: 570px; left: 1080px; width: 100px; height: 125px; font-size: 55px; font-family: Verdana Bold;   font-weight: bold; color: black;">
  <?php 
   $ximsak = substr($imsak, 0, 2);
   $yimsak = substr($imsak, -2, 2);
   if($ximsak > 12) $ximsak = $imsak - 12;
   echo substr($ximsak,0,1) . ":" . $yimsak . "</br>";
 ?>
  </div>

   <div  id="subuh" style="position: absolute; top: 570px; left: 910px; width: 100px; height: 125px;  font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: black;">
  <?php 
   $xsubuh = substr($subuh, 0, 2);
   $ysubuh = substr($subuh, -2, 2);   
   if($xsubuh > 12) $xsubuh = $subuh - 12;
   echo substr($xsubuh,0,1) . ":" . $ysubuh . "</br>";
 ?>
  </div>

   <div  id="syuruk" style="position: absolute; top: 570px; left: 740px; width: 100px; height: 125px;  font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: black;">
  <?php 
   $xsyuruk = substr($syuruk, 0, 2);
   $ysyuruk = substr($syuruk, -2, 2);
   if($xsyuruk > 12) $xsyuruk = $syuruk - 12;
   echo substr($xsyuruk,0,1) . ":" . $ysyuruk . "</br>";
 ?>
  </div>
  
 <!--
   <div id="zohor" style="position: absolute; top: 570px; left: 555px; width: 100px; height: 125px;  font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: black; text-align:right"> 
-->

  <?php 
   $xzohor = intval(substr($zohor, 0, 2));
   $yzohor = substr($zohor, -2, 2);

   //new sabah
  if($xzohor == 11) {
      echo "<div id=\"zohor\" style=\"position: absolute; top: 570px; left: 546px; width: 100px; height: 125px;  font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: black; text-align:right\">"; 
   }
   
   if($xzohor == 12) {
      echo "<div id=\"zohor\" style=\"position: absolute; top: 570px; left: 550px; width: 100px; height: 125px;  font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: black; text-align:right\">"; 
   }

  if($xzohor < 11) {
       echo "<div id=\"zohor\" style=\"position: absolute; top: 570px; left: 570px; width: 100px; height: 125px;  font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: black; text-align:right\">"; 
   }

  if($xzohor > 12) {
      $xzohor = $xzohor - 12;
      echo "<div id=\"zohor\" style=\"position: absolute; top: 570px; left: 570px; width: 100px; height: 125px;  font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: black; text-align:right\">"; 
   }

     echo  $xzohor . ":" . $yzohor . "</br>";
   //showdatawaktu($zohor);
 ?>

  </div>
   
   <div  id="asar" style="position: absolute; top: 570px; left: 390px; width: 100px; height: 125px; font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: black;">
  <?php 
   $xasar = substr($asar, 0, 2);
   $yasar = substr($asar, -2, 2);   
   if($xasar > 12) $xasar = $asar - 12;
   echo substr($xasar,0,1) . ":" . $yasar . "</br>";
 ?>
  </div>  
  
  
 <div  id="maghrib" style="position: absolute; top: 570px; left: 220px; width: 100px; height: 125px;  font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: black;">
  <?php 
   $xmaghrib = substr($maghrib, 0, 2);
   $ymaghrib = substr($maghrib, -2, 2);
   if($xmaghrib > 12) $xmaghrib = $maghrib - 12;
   echo substr($xmaghrib,0,1) . ":" . $ymaghrib . "</br>";
 ?>
  </div>

    <div  id="isyak" style="position: absolute; top: 570px; left: 60px; width: 100px; height: 125px;  font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: black;">
  <?php 
   $xisyak = substr($isyak, 0, 2);
   $yisyak = substr($isyak, -2, 2);
   if($xisyak > 12) $xisyak = $isyak - 12; 
   echo substr($xisyak,0,1) . ":" . $yisyak . "</br>";
 ?>
  </div>
  
  
</div>


</body>
</html>
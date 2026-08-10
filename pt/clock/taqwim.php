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
$gbl_ulang = 0;
$gbl_secs_todaydate_start = 0;
$gbl_secs_todaydate_end = 0;


if ( $row = mysqli_fetch_array($result,MYSQLI_ASSOC) ) {
	$gbl_secs_startdate_next = strtotime($row["startdate"]);
	$gbl_secs_enddate_next = strtotime($row["enddate"]);
	$gbl_ulang = $row["ulang"];

	$masa_mulasleep = substr($row["startdate"],11,8);
	$masa_tamatsleep = substr($row["enddate"],11,8);
	$gbl_secs_todaydate_start = strtotime( date('Y-m-d') . ' ' . $masa_mulasleep );
	$gbl_secs_todaydate_end = strtotime( date('Y-m-d') . ' ' . $masa_tamatsleep );
	
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

/*
var zxcMarquee={

 init:function(o){
  var mde=o.Mode,mde=typeof(mde)=='string'&&mde.charAt(0).toUpperCase()=='H'?['left','offsetWidth','top','width']:['top','offsetHeight','left','height'],id=o.ID,srt=o.StartDelay,ud=o.StartDirection,p=document.getElementById(id),obj=p.getElementsByTagName('DIV')[0],sz=obj[mde[1]],clone,nu=Math.ceil(p[mde[1]]/sz)+1,z0=1;
  p.style.overflow='hidden';
  obj.style.position='absolute';
  obj.style[mde[0]]='0px';
  obj.style[mde[3]]=sz+'px';
  for (;z0<nu;z0++){
   clone=obj.cloneNode(true);
   clone.style[mde[0]]=sz*z0+'px';
   clone.style[mde[2]]='0px';
   obj.appendChild(clone);
  }
  o=this['zxc'+id]={
   obj:obj,
   mde:mde[0],
   sz:sz*(z0-1)
  }
  if (typeof(srt)=='number'){
   o.dly=setTimeout(function(){ zxcMarquee.scroll(id,typeof(ud)=='number'?ud:-1); },srt);
  }
  else {
   this.scroll(id,0)
  }
 },

 scroll:function(id,ud){
  var oop=this,o=this['zxc'+id],p;
  if (o){
   ud=typeof(ud)=='number'?ud:0;
   clearTimeout(o.dly);
   p=parseInt(o.obj.style[o.mde])+ud;
   if ((ud>0&&p>0)||(ud<0&&p<-o.sz)){
    p+=o.sz*(ud>0?-1:1);
   }
   o.obj.style[o.mde]=p+'px';
//o.dly=setTimeout(function(){ oop.scroll(id,ud); },50);  //asal
o.dly=setTimeout(function(){ oop.scroll(id,ud); },1);
  }
 }

}

function init(){

 zxcMarquee.init({
  ID:'marquee2',     // the unique ID name of the parent DIV.                        (string)
  Mode:'Horizontal', //(optional) the mode of execution, 'Vertical' or 'Horizontal'. (string, default = 'Vertical')
  StartDelay:2000,   //(optional) the auto start delay in milli seconds'.            (number, default = no auto start)
  StartDirection:-1  //(optional) the auto start scroll direction'.                  (number, default = -1)
 });

}

if (window.addEventListener)
 window.addEventListener("load", init, false)
else if (window.attachEvent)
 window.attachEvent("onload", init)
else if (document.getElementById)
 window.onload=init
*/

</script>

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

			setTimeout('move()',30*1000);


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
				if( strcmp($harini_day, "Friday" ) == 0 ) echo $diqomah_jumaat[$wnow];
				else  echo $diqomah[$wnow];
			?>;
			var dIqomahNeg = -1 * dIqomah;
			var taqwimduration = <?php echo $config['taqwim']; ?>;
			var blinkingduration = <?php echo $config['blinking']; ?>; // 5 min sebelum azan
			var gblFlagIqo = 0;

			var gbl_secs_startdate_next = <?php echo $gbl_secs_startdate_next; ?>;
			var gbl_secs_enddate_next = <?php echo $gbl_secs_enddate_next; ?>;
			var gbl_ulang = <?php echo $gbl_ulang; ?>;
			var gbl_secs_todaydate_start = <?php echo $gbl_secs_todaydate_start; ?>;
			var gbl_secs_todaydate_end = <?php echo $gbl_secs_todaydate_end; ?>;


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

			
			setTimeout('move()',taqwimduration*1000);

			  //chk startdate is touching
			   var chkIntervalId4 = setInterval ( "chkStartdateTouch()", 1000 );

			  //chk change date
			  // var chkIntervalId3 = setInterval ( "chkChangeDate()", 1000 );


	

			 //activate interval function for periodically chk waktu if blm time (still +ve value)
			 if( mSec > 0 && nextday == 0 ) 
			   chkIntervalId = setInterval ( "chkWaktuBlinking(waktu,namaSolat)", 1000 );

			if( (mSec2 > 0) && (mSec2 < dIqomah*60*1000 ) && nextday == 0 ) {
				//alert ("between 0 - 10mins");
				chkIntervalId = setInterval ( "chkScreenSolat(waktu,namaSolat)", 1000 );
				//chkScreenSolat(waktu,namaSolat);				

				}	
	
		 	}



			
		    //chk if startdate is touching
			function chkStartdateTouch()
			{
//alert('jjjjj');
				
				if( gbl_ulang == 1 ) {
					
					if ( gbl_secs_todaydate_start != 0 && gbl_secs_todaydate_end != 0 && gbl_secs_todaydate_start != gbl_secs_todaydate_end ) {
					//alert('masuk');
						var date_now = new Date();  
						var secs =  (date_now.getTime())/1000 ;
		//				console.log(secs);

						//trigger dlm range
						if ( secs >= gbl_secs_todaydate_start && secs <= gbl_secs_todaydate_end ) {
						 // alert ('now=' + secs + ',begin=' + gbl_secs_startdate_next + ',end=' + gbl_secs_enddate_next);
						   window.location = "taqwim-sleep.php";  //pergi ke sleep mode
						}					   
					}				
				}
				else {
					if ( gbl_secs_startdate_next != 0 && gbl_secs_enddate_next != 0 && gbl_secs_startdate_next != gbl_secs_enddate_next ) {
					//alert('masuk');
						var date_now = new Date();  
						var secs =  (date_now.getTime())/1000 ;
		//				console.log(secs);

						//trigger dlm range
						if ( secs >= gbl_secs_startdate_next && secs <= gbl_secs_enddate_next ) {
						 // alert ('now=' + secs + ',begin=' + gbl_secs_startdate_next + ',end=' + gbl_secs_enddate_next);
						   window.location = "taqwim-sleep.php";  //pergi ke sleep mode
						}					   
					}
				}
				
				
			}
		
/*
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
*/

			

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
					  //alert ("dah 5 min b4 azan");
					  clearInterval ( chkIntervalId );
					  window.location = "waktublinking.php?masihi=" + "<?php echo "$tarikh_harini_long" . '&hijrah=' . "$tarikh_hijrah"; ?>";
					}  	  		
			}


			function move() {
			window.location = "../digit/worldclock3.php";
//				window.location = "/pt/slides/src/slide.php";
			}
			
			
			//-->
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
  <div id="iqomahtext" style="z-index: 22; position: absolute; top: 180px; left: 850px; width: 100px; height: 125px;  font-size: 60px; font-family: Verdana Bold; font-weight: bold; color: black;">
  </div>

     <!-- Taqwim BG-->   
        <div style="background-image:url('../bg/bg-taqwim.jpg');height: 100%;background-position: center;background-repeat: no-repeat; background-size: cover;">"

  		</div>

		
<!--
<div class="background" style="position: absolute; top: 0; left: 0; width: 1280px; height: 720px; background-image:url('../bg/bg-taqwim.jpg');
background-repeat:no-repeat; background-attachment:fixed; background-position:center;"> 
</div>
-->

<div class="background" style="position: absolute; top: 0; left: 0; width: 1280px; height: 720px; background-image:url('../bg/bg-taqwim-box.png');
background-repeat:no-repeat; background-attachment:fixed; background-position:center;"> 
</div>

<div style="position: absolute; top: -5; left: -13;">


<?php
//ubah warna green jadi lain or htmlcodes

if($wnow == 2) {	
?>
   <div id="imsak" style="position: absolute; top: 570px; left: 1080px; width: 80px; height: 125px; font-size: 55px; font-family: Verdana Bold; font-weight: bold; color: green;">
 <?php 
}
else {
?>
   <div id="imsak" style="position: absolute; top: 570px; left: 1080px; width: 80px; height: 125px; font-size: 55px; font-family: Verdana Bold; font-weight: bold; color: black;">
<?php
}
?>

  <?php 
   $ximsak = substr($imsak, 0, -3);
   $yimsak = substr($imsak, -2, 2);
   if($ximsak > 12) $ximsak = $imsak - 12;
   echo substr($ximsak,0,1) . ":" . $yimsak . "</br>";
 ?>
  </div>


<?php
if($wnow == 3) {	
?>
   <div  id="subuh" style="position: absolute; top: 570px; left: 910px; width: 100px; height: 125px;  font-size: 55px; font-family: Verdana Bold; font-weight: bold; color: green;">
 <?php 
}
else {
?>
   <div  id="subuh" style="position: absolute; top: 570px; left: 910px; width: 100px; height: 125px;  font-size: 55px; font-family: Verdana Bold; font-weight: bold; color: black;">
<?php
}
?>


  <?php 
   $xsubuh = substr($subuh, 0, 2);
   $ysubuh = substr($subuh, -2, 2);   
   if($xsubuh > 12) $xsubuh = $subuh - 12;
   echo substr($xsubuh,0,1) . ":" . $ysubuh . "</br>";
 ?>
  </div>

   <div  id="syuruk" style="position: absolute; top: 570px; left: 740px; width: 100px; height: 125px;  font-size: 55px; font-family: Verdana Bold; font-weight: bold; color: black;">
  <?php 
   $xsyuruk = substr($syuruk, 0, 2);
   $ysyuruk = substr($syuruk, -2, 2);
   if($xsyuruk > 12) $xsyuruk = $syuruk - 12;
   echo substr($xsyuruk,0,1) . ":" . $ysyuruk . "</br>";
 ?>
  </div>
  


  <?php 
    $color_wzohor = "black";
    if($wnow == 5) $color_wzohor = "green";
 
   $xzohor = intval(substr($zohor, 0, 2));
   $yzohor = substr($zohor, -2, 2);

   //new sabah
  if($xzohor == 11) {
      echo "<div id=\"zohor\" style=\"position: absolute; top: 570px; left: 546px; width: 100px; height: 125px;  font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: $color_wzohor; text-align:right\">"; 
   }
   
   if($xzohor == 12) {
      echo "<div id=\"zohor\" style=\"position: absolute; top: 570px; left: 550px; width: 100px; height: 125px;  font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: $color_wzohor; text-align:right\">"; 
   }

  if($xzohor < 11) {
       echo "<div id=\"zohor\" style=\"position: absolute; top: 570px; left: 570px; width: 100px; height: 125px;  font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: $color_wzohor; text-align:right\">"; 
   }

  if($xzohor > 12) {
      $xzohor = $xzohor - 12;
      echo "<div id=\"zohor\" style=\"position: absolute; top: 570px; left: 570px; width: 100px; height: 125px;  font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: $color_wzohor; text-align:right\">"; 
   }

     echo  $xzohor . ":" . $yzohor . "</br>";
   //showdatawaktu($zohor);
 ?>

  </div>

<?php
if($wnow == 6) {	
?>
   <div  id="asar" style="position: absolute; top: 570px; left: 390px; width: 100px; height: 125px; font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: green;">
 <?php 
}
else {
?>
   <div  id="asar" style="position: absolute; top: 570px; left: 390px; width: 100px; height: 125px; font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: black;">
<?php
}
?>

   
  <?php 
   $xasar = substr($asar, 0, 2);
   $yasar = substr($asar, -2, 2);   
   if($xasar > 12) $xasar = $asar - 12;
   echo substr($xasar,0,1) . ":" . $yasar . "</br>";
 ?>
  </div>  
  
 
<?php
if($wnow == 7) {	
?>
<div  id="maghrib" style="position: absolute; top: 570px; left: 220px; width: 100px; height: 125px;  font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: green;">
 <?php 
}
else {
?>
	<div  id="maghrib" style="position: absolute; top: 570px; left: 220px; width: 100px; height: 125px;  font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: black;">
<?php
}
?>

 
  <?php 
   $xmaghrib = substr($maghrib, 0, 2);
   $ymaghrib = substr($maghrib, -2, 2);
   if($xmaghrib > 12) $xmaghrib = $maghrib - 12;
   echo substr($xmaghrib,0,1) . ":" . $ymaghrib . "</br>";
 ?>
  </div>

<?php
if($wnow == 8 || $wnow == 0 || $wnow == 1) {	
?>
       <div  id="isyak" style="position: absolute; top: 570px; left: 60px; width: 100px; height: 125px;  font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: green;">
<?php 
}
else {
?>
    <div  id="isyak" style="position: absolute; top: 570px; left: 60px; width: 100px; height: 125px;  font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: black;">
<?php
}
?>

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
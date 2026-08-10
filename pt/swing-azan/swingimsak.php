<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
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
  background: white;
  height: 90%;
  width: 105%;
}

</style>

		<link rel="stylesheet" type="text/css" href="swing.css" media="all" />
		<script src="jquery-1.7.1.min.js"></script>
		<script src="swing.js"></script>
<?php 
//echo shell_exec('aplay ../sound/Front_Center.wav');
//echo shell_exec('whoami');
//echo "jkdjflskjfkldsjgl<br/>";

//Load config file
$config = parse_ini_file('../setting/setting.ini');
?>		
<style>
/**
.container{
position: relative;
width: 200px; 
height: 80px; 
overflow: hidden;
background-color: white;
border: 2px solid orange;
padding: 2px;
padding-left: 4px;
}

#slideshow {
  width: 1024px;
  height: 768px;
}
**/

</style>	

<script type="text/javascript">
var masukwaktuduration = <?php echo $config['masukwaktu'] * 1000; ?>;

/**
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
**/
 
 //to move to next page
		<!--
			var time = null
			function move() {
			window.location = '../videoazan.php?wnow=<?php	echo  $_GET['wnow']; ?>';
			}
			//-->
 
 
</script>		
</head>
		
	<body onload="timer=setTimeout('move()',masukwaktuduration)" class="fullheight">
	
		<header>
			<img id='swing' src="red-sign-imsak.jpg?v=9" alt="">  
		</header>
		

<?php 

$namasolat = array ( "Awalpg",  "Imsak", "Subuh", "Syuruk", "Zohor", "Asar", "Maghrib", "Isyak", "Tghmlm");
$wnow = $_GET['wnow'];

//$text_scroll = 'AZAN!!! - Sekarang telah masuk waktu ' . $namasolat[$wnow] . ' bagi ' . $config['lokasi'] . ' dan kawasan-kawasan yang sewaktu dengannya....';

$text_scroll = 'Sekarang telah masuk waktu ' . $namasolat[$wnow];
//' bagi ' . $config['lokasi'] . ' dan </br>kawasan-kawasan sama waktu dengannya...';
//$space = strlen($text_scroll) * 60; 

?>
		
		
<div id="marquee2" style="position: absolute; top: 480px; left: 80px; width: 1280px; height: 150px; z-index: 5; font-family: Arial Black; font-size: 60px; color: red; z-index:5;">

<?php 
echo $text_scroll;
?>
</div>  			

    <div  id="jomsolat" style="position: absolute; top: 100px; left: 820px; width: 400px; height: 125px; font-family: Arial Black; font-size: 63px; color: white;">
  <img src="../bg/jomsolat.jpg"> </img> 
  </div>				
	</body>
</html>
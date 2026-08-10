<?php
date_default_timezone_set('Asia/Singapore');

$kuliah_file_template = "ceramah.jpg";
$kuliah_header = "KULIAH MAGHRIB";
$kuliah_tarikh = "2 Feb 2015";
$kuliah_penceramah = "Ust. Rashidy Jamil";
$kuliah_tajuk = "Kitab Hikam";
$kuliah_waktu = "Lepas Maghrib";
$kuliah_hari = "Selasa";



$subuh="5:20";
$syuruk="7:08";
$zscroll="1:19";
$ascroll="4:25";
$mscroll="7:22";
$iscroll="8:31";

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
<td style=\"width:210;\">$subuh</td>
<td style=\"width:210;\">$syuruk</td>
<td style=\"width:210;\">$zscroll</td>
<td style=\"width:210;\">$ascroll</td>
<td style=\"width:210;\">$mscroll</td>
<td style=\"width:210;\">$iscroll</td>
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

    <script src="../../../src/jquery.min.js" type="text/javascript"></script>
 	
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
<body class="fullheight">

<div class="container2">

<?php 

//$show_slide='Data';
//bypass
//$kuliah_file_template="ceramah.jpg";

?>
 <img src="../../../templateslideskt/<?php echo $kuliah_file_template; ?>"/>


</div>
		<?php
			switch($kuliah_file_template) {
			   case 'kuliah.jpg':
								include('../../../templateslideskt/kuliah.php');
								break;
			   case 'umum.jpg':
								include('../../../templateslideskt/umum.php');
								break;
			   case 'pengajian.jpg':
								include('../../../templateslideskt/pengajian.php');
								break;
								
								
			   case 'ceramah.jpg':
								include('../../../templateslideskt/ceramah.php');
								break;
								
			   case 'kelas.jpg':
								include('../../../templateslideskt/kelas.php');
								break;

			   case 'khutbah.jpg':
								include('../../../templateslideskt/khutbah.php');
								break;
			}	
	
?>


<div id="showlegendtaqwim" style="position: absolute; top: 595px; left: 22px; width: 1280px; height: 100px; z-index: 5;">
<img src="../../../bg/text-waktu-bawah.png"></img>
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



<div id="clock" class="dark" style="position: absolute; top: 427px; left: 865px; width: 350px; height: 100px; z-index: 6;">

			<div class="display">
				<div class="weekdays"></div>
				<div class="ampm"></div>
				<div class="alarm"></div>
				<div class="digits"></div>
			</div>
</div>

<?php
$kuliah_batal=1;
if( $kuliah_batal == 0 ) {
?>
<div id="showbatal" style="position: absolute; top: 0px; left: 0px; width: 1280px; height: 720px; z-index: 5;">
<img src="../../../bg/batal2.png"></img>
</div>
<?php
}
?>

        
	<!-- JavaScript Includes --
		<script src="style-clock/moment.min.js"></script>
		<script src="style-clock/assets/js/script.js"></script>
-->
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


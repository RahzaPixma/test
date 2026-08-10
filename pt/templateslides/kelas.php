<!-- begin jadual kuliah -->
<div id="showjadualkuliah" style="visibility:visible; position: absolute; top: 0px; left: -30px; width: 1280px; height: 720px; z-index: 7; color: black">

<div id="showheader" style="position: absolute; top: 50px; left: 100px; width: 980px; text-align: center;  font-size: 60px; font-family: Verdana Bold;  font-weight: bold; color: white">
<?php
// $kuliah_header = "KULIAH MAGHRIB";
 echo $kuliah_header ;
 ?>
</div>

<div id="showhari" style="position: absolute; top: 200px; left: 130px; width: 800px; text-align: center; font-size: 60px; font-family: Verdana Bold;  font-weight: bold; color: yellow">
<?php
//$kuliah_hari = "Selasa";
 echo $kuliah_hari ;
 $tt = date_create($kuliah_tarikh);
 
 echo " ( " . date_format($tt,"d M") . " ) ";
 ?>
</div>


<div id="showpenceramah" style="position: absolute; top: 280px; left: 130px; width: 800px; text-align: center; font-size: 50px; font-family: Verdana Bold;  font-weight: bold; color: gold">
<?php
//$kuliah_penceramah = "Ust. Rashidy Jamil";
 echo $kuliah_penceramah;
 ?>
</div>


<div id="showwaktu" style="position: absolute; top: 400px; left: 130px; width: 800px; text-align: center; font-size: 40px; font-family: Verdana Bold;  font-weight: bold; color: yellow">
<?php
//$kuliah_waktu = "Lepas Maghrib";
 echo $kuliah_waktu ;
 ?>
</div>


<div id="showtempat" style="position: absolute; top: 460px; left: 130px; width: 800px; text-align: center; font-size: 40px; font-family: Verdana Bold;  font-weight: bold; color: yellow">
<?php
//$kuliah_tempat = "Dewan Solat Masjid";
 echo $kuliah_tempat ;
 ?>
</div>


</div>  
<!--end jadual kuliah -->

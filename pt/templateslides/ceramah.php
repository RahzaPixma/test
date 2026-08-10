<!-- begin jadual kuliah -->
<div id="showjadualkuliah" style="visibility:visible; position: absolute; top: 0px; left: 0px; width: 1280px; height: 720px; z-index: 7; color: black ">

<div id="showheader" style="position: absolute; top: 50px; left: 0px; width: 1280px; text-align: center; font-size: 80px; font-family: Verdana Bold;  font-weight: bold; color: purple">
<?php
// $kuliah_header = "KULIAH MAGHRIB";
 echo $kuliah_header ;
 ?>
</div>

<div id="showhari" style="position: absolute; top: 140px; font-size: 70px; left: 0px; width: 1280px; text-align: center;font-size: 60px; font-family: Verdana Bold;  font-weight: bold; color: black">
<?php
//$kuliah_hari = "Selasa";
 echo strtoupper($kuliah_hari) ;
 ?>
</div>


<div id="showtarikh" style="position: absolute; top: 210px; left: 0px; width: 1280px; text-align: center;font-size: 60px; font-family: Verdana Bold;  font-weight: bold; color: black">
<?php
//$kuliah_tarikh = "2 Feb 2015";
 $tt = date_create($kuliah_tarikh);
 
 echo date_format($tt,"d M Y");
 ?>
</div>


<div id="showpenceramah" style="position: absolute; top: 310px; left: 0px; width: 1280px; text-align: center;font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: darkblue">
<?php
//$kuliah_penceramah = "Ust. Rashidy Jamil";
 echo $kuliah_penceramah;
 ?>
</div>


<div id="showwaktu" style="position: absolute; top: 390px; left: 0px; width: 1280px; text-align: center;font-size: 50px; font-family: Verdana Bold;  font-weight: bold; color: black">
<?php
//$kuliah_tajuk = "Kitab Hikam";
 echo $kuliah_tajuk ;
 //echo "p=$passing_id, currrid = $kuliah_id";
 ?>
</div>

<div id="showtajuk" style="position: absolute; top: 480px; left: 0px; width: 1280px; text-align: center;font-size: 45px; font-family: Verdana Bold;  font-weight: bold; color: maroon">
<?php
//$kuliah_waktu = "Lepas Maghrib";
 echo $kuliah_waktu ;
 ?>
</div>


</div>  
<!--end jadual kuliah -->

<!-- begin jadual kuliah -->

<div id="showjadualkuliah" style="visibility:visible; position: absolute; top: 0px; left: 0px; width: 1280px; height: 720px; z-index: 7; color: black">


<div  align="center" style="position: absolute; width: 420px; left:840px; top: 90px; font-size: 45px; font-family: Verdana Bold;  font-weight: bold; color: maroon;">
<?php
echo $kuliah_hari; 
 ?>
</div>

<div align="center" style="position: absolute; width: 420px; left:840px; top: 190px; font-size: 30px; font-family: Verdana Bold;  font-weight: bold; color: #333300;">
<?php 
 $tt = date_create($kuliah_tarikh);
 echo strtoupper(date_format($tt,"d M Y"));
 ?>
</div>

<div align="center" style="position: absolute; width: 420px; left:840px; top: 240px; font-size: 30px; font-family: Verdana Bold;  font-weight: bold; color: #333300;">
<?php 
if($day_offset == 0) $tarikh_toconv = $kuliah_tarikh;
else $tarikh_toconv = date("Y-m-d", strtotime("$kuliah_tarikh $day_offset day"));
$DateConv=new Hijri_GregorianConvert;
$format4="YYYY-MM-DD";
$tarikh_hijrah4 = $DateConv->GregorianToHijri($tarikh_toconv,$format4,0);
$rpl = str_replace("-"," ",$tarikh_hijrah4);
echo $rpl;
 ?>
</div>

<div  align="center" style="position: absolute; width: 420px; left:840px; top: 380px; font-size: 45px; font-family: Verdana Bold; color: white;">
Masa :
</div>

<div  align="center" style="position: absolute; width: 200px; left:940px; top: 440px; font-size: 45px; font-family: Verdana Bold;  font-weight: bold; color: white;">
<?php
//$kuliah_waktu = "Isyak";
echo $kuliah_waktu; 
 ?>
</div>


<div align="center"  style="position: absolute; width:900px; left:10px; top: 40px; font-size: 75px; font-family: Verdana Bold;  font-weight: bold; color: #19194d;">
<?php echo $kuliah_header; ?>
</div>

<?php
//$kuliah_tajuk = "gkjshgkjsgs gjsdhg kjsh sgsjk";
if( strlen($kuliah_tajuk) < 23 ) {
?>
<div  style="position: absolute; width: 800px; left:90px; top: 230px; font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: white">
<?php
} else {
?>
<div  style="position: absolute; width: 800px; left:90px; top: 180px; font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: white">
<?php
}

 echo "<u>Tajuk</u> : " . $kuliah_tajuk;

?>
</div>


<div  style="position: absolute; width: 800px; left:90px; top: 360px; font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: white">
<u>Penceramah</u> :
</div>

<div  style="position: absolute; width: 800px; left:90px; top: 420px; font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: white">
<?php 
//$kuliah_penceramah = "jdssdf sgfshf sfhs fffh rhfkrhfrkj sfsjh";
echo $kuliah_penceramah; 
?> 
</div>


</div>  

<!--end jadual kuliah -->

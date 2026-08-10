<!-- begin jadual kuliah -->

<div id="showjadualkuliah" style="visibility:visible; position: absolute; top: 0px; left: 0px; width: 1280px; height: 720px; z-index: 7; color: black">

<style>
div.label1 {
	position: absolute; 
	left:130px; 
	font-size: 40px; 
	font-family: Verdana Bold;  
	color: #99ccff;
}

div.label2 {
	position: absolute; 
	left:380px; 
	font-size: 40px; 
	font-family: Verdana Bold;  
	color: #99ccff;
}

div.label3 {
	position: absolute; 
	left:430px; 
	font-size: 50px; 
	font-family: Verdana Bold;  
	font-weight: bold; 
	color: white;
	width: 700px;
}



</style>


<div class="label1" style="position: absolute; top:150px;">Tarikh</div>
<div class="label2" style="position: absolute; top:150px;">:</div>
<div class="label3" style="position: absolute; top:150px;color: #99ccff;">
<?php 
 $tt = date_create($kuliah_tarikh);
 echo date_format($tt,"d M Y"); 
?>
</div>


<div class="label1" style="position: absolute; top:200px;">Masa</div>
<div class="label2" style="position: absolute; top:200px;" >:</div>
<div class="label3" style="position: absolute; top:200px;color: #99ccff;">
<?php 
 echo $kuliah_waktu;
?>
</div>

<div class="label1" style="position: absolute; top:260px;">Tempat</div>
<div class="label2" style="position: absolute; top:260px;" >:</div>
<div class="label3" style="position: absolute; top:260px;color: #99ccff;">
<?php 
 echo $kuliah_tempat;
?>
</div>

<?php
if( strlen($kuliah_penceramah) < 25 ) {
?>
<div class="label1" style="position: absolute; top:350px;color: white;">Penceramah</div>
<div class="label2" style="position: absolute; top:350px;color: white;" >:</div>
<div class="label3" style="position: absolute; top:350px;">
<?php
} else {
?>
<div class="label1" style="position: absolute; top:320px;color: white;">Penceramah</div>
<div class="label2" style="position: absolute; top:320px;color: white;" >:</div>
<div class="label3" style="position: absolute; top:320px;">
<?php
}

 echo $kuliah_penceramah;

?>
</div>

<div class="label1" style="position: absolute; top:440px;color: white;">Tajuk</div>
<div class="label2" style="position: absolute; top:440px;color: white;" >:</div>
<div class="label3" style="position: absolute; top:440px;">
<?php 
 echo $kuliah_tajuk;
?>
</div>



<div align="center" style="position: absolute; left:0px; width:1280px; top: 7px; font-size: 75px; font-family: Verdana Bold;  font-weight: bold; color: yellow;">
<?php echo $kuliah_header; ?>
</div>


</div>  

<!--end jadual kuliah -->

<!-- begin jadual kuliah -->

<div id="showjadualkuliah" style="visibility:visible; position: absolute; top: 0px; left: 0px; width: 1280px; height: 720px; z-index: 7; color: black">


<div style="position: absolute; left:910px; top: 140px; font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: maroon;">
<?php
//$kuliah_hari = "Selasa";
 $tt = date_create($kuliah_tarikh);
 
 //echo date_format($tt,"d M");
 ?>
</div>

<div style="position: absolute; left:910px; top: 140px; font-size: 55px; font-family: Verdana Bold;  font-weight: bold; color: maroon;">
<?php echo $kuliah_hari; ?>
</div>


<div style="position: absolute; left:30px; top: 140px; font-size: 70px; font-family: Verdana Bold;  font-weight: bold; color: green;">
<?php echo $kuliah_header; ?>
</div>

<div  align="center" style="position: absolute; width: 500px; left:250px; top: 260px; font-size: 50px; font-family: Verdana Bold;  font-weight: bold; color: blue">
<?php echo $kuliah_penceramah; ?> 
</div>


</div>
<!-- right -->
<div align="center" style="position: absolute; width: 700px; left:120px; top: 420px; font-size: 45px; font-family: Verdana Bold; font-weight: bold; color: black">
<?php echo $kuliah_tajuk; ?> 
</div>

</div>  

<!--end jadual kuliah -->

<!-- begin jadual kuliah -->
<div id="showjadualkuliah" style="visibility:visible; position: absolute; top: 0px; left: 0px; width: 1280px; height: 720px; z-index: 7;  font-size: 60px; font-family: Verdana Bold;  font-weight: bold; color: black">


<div style="position: absolute; top: 80px;">
<table style="border: 0px solid black; font-family: Arial; text-align:center">
<tr>
<td width="750" style="border: 0px solid black;">
<div style="font-size: 70px; font-family: Verdana Bold;  font-weight: bold; color: maroon">
<?php
//$kuliah_hari = "Selasa";
 echo $kuliah_hari ;
 ?>
</div>

<div style="font-size: 60px; font-family: Verdana Bold;  font-weight: bold; color: black">
<?php
//$kuliah_hari = "Selasa";
 $tt = date_create($kuliah_tarikh);
 
 echo date_format($tt,"d M");
 ?>
</div>

</td>
<td rowspan="2"  width="530" style="left: -40px; border: 0px solid black; font-size: 75px; font-family: Verdana Bold;  font-weight: bold; color: maroon"><?php echo $kuliah_header; ?></td>
</tr>

<tr>

<td  width="750" style="border: 0px solid black;">
<br>
<div style="font-size: 70px; font-family: Verdana Bold;  font-weight: bold; color: maroon">
<?php echo $kuliah_penceramah; ?> 
</div>

<div style="font-size: 60px; font-family: Verdana Bold;  font-weight: bold; color: black">
<?php echo $kuliah_tajuk; ?> 
</div>

</td>
</tr>

</table>
</div>

</div>  
<!--end jadual kuliah -->

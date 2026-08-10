<!-- begin jadual kuliah -->
<div id="showjadualkuliah" style="visibility:visible; position: absolute; top: 0px; left: 0px; width: 1280px; height: 720px; z-index: 7;  font-size: 80px; font-family: Verdana Bold;  font-weight: bold; color: black">


<div style="position: absolute; top: 80px; left: -50;">
<table style="border: 0px solid black; font-family: Arial; text-align:center">
<tr>
<td width="750" style="border: 0px solid black;">
<div style="font-size: 70px; font-family: Verdana Bold;  font-weight: bold; color: white">
<?php 
 $tt = date_create($kuliah_tarikh);
 
 //echo date_format($tt,"d M Y");
 ?> 
</div>

</td>
<td rowspan="2"  width="530" style="border: 0px solid black; font-size: 75px; font-family: Verdana Bold;  font-weight: bold; color: white"><?php echo $kuliah_header; ?></td>
</tr>

<tr>

<td  width="750" style="border: 0px solid black;">
<br>
<div style="font-size: 65px; font-family: Arial Bold;  font-weight: bold; color: gold">
<?php echo $kuliah_penceramah; ?> 
</div>

<div style="font-size: 60px; font-family: Verdana Bold;  font-weight: bold; color: white">
<?php echo $kuliah_tajuk; ?> 
</div>

</td>
</tr>

</table>
</div>


</div>  
<!--end jadual kuliah -->

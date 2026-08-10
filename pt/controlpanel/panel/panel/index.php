<?php
session_start();
if(isset($_GET['logout']) AND $_GET['logout']=='1'){
    unset($_SESSION['login']);
    session_destroy();
}
if(!isset($_SESSION['login'])){
    header('location: ./login/login_index2.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>IIM 5.5 Panel</title>
	<link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="../../themes/icon.css">
	<link rel="stylesheet" type="text/css" href="../demo.css">
	<script type="text/javascript" src="../../jquery.min.js"></script>
	<script type="text/javascript" src="../../jquery.easyui.min.js"></script>
	<script type="text/javascript" src="../../datagrid-detailview.js"></script>
<!--	<script type="text/javascript" src="jquery.ddslick.min.js"></script> -->

	
<!-- UI Buttons -->	
<link href="buttons/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" href="buttons/css/buttons.css">

<script type="text/javascript" src="buttons/js/buttons.js"></script>	

<style>

input.icon-iim-btn-solat {
     background: url("../../themes/icons/58_32x32.png") no-repeat scroll center center;
 }

</style>
	
</head>
<!-- ASAL background paling luar
<body style="background:#CCCCFF;" onload="setupPanels()">
-->
<body style="background:#97FD8F;" onload="setupPanels()">

	
   <div style="margin:0px 0;"></div>
   

 <script>

//load background
$(document).ready(function(){

     $('#fSetBackground').form({
        url:'upload_tmp_background.php',
        ajax: true,
 
		success: function(data){
                //alert('succes');
				$('#id_target_layer_background').html(data);
				//showMsg2(data);
        },
        onLoadError: function(data){
				//showMsg2(data);
        }
		
    });


    $('#btn_add_background').bind('click', function(){
        if($('#chooseFileBackground').filebox('getValue')!="") { 
            $('#fSetBackground').submit(); 
        }
    });


});     


			
function showIfDataBelumUpdate(year) {
		var textWarn = '<strong>PERINGATAN!!!...</strong> Taqwim data untuk tahun ' + year + ' belum dikemaskini<br>Sila hubungi Perfect Time Solution 04-4846153';
		$.messager.alert('<strong>PERINGATAN!!!!</strong>',textWarn,'warning');
}


<?php

	include 'conn_cli.php';

	//chk zone
	$query = "SELECT * FROM tbm_zone";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$curr_zone = $row['zone'];
	mysqli_free_result($result);

	
	$month = date('m');

    if( $month >= 12) {
		$nxtyear = date('Y', strtotime('+1 year'));	
		$rs = mysqli_query($conn,"select count(*) from taqwim where year(tarikh) = $nxtyear and kodlokasi = $curr_zone");
		$row = mysqli_fetch_row($rs);
		$result = $row[0];

		if($result==0){
		echo "
		    $(document).ready(function() {
					showIfDataBelumUpdate($nxtyear);
			});				
		";
		}

		mysqli_free_result($result);
	}
?>				


</script>  
   
 <!--   <div id="w" class="easyui-window" style="width:1280px;height:720px;padding:1px" title="Image template kuliah" data-options="modal:true,minimizable:true,maximizable:true,collapsible:true,closable:true">
    </div>    
 --> 

     <div id="w" class="easyui-window" style="width:800px;height:500px;padding:10px;" title="Image template kuliah" data-options="modal:true,minimizable:false,maximizable:false,collapsible:false">
    </div>     


	<!--panel paling luar-->
    <div id="panel_paling_luar" class="easyui-layout" style="width:1280px;height:580px;">

<!--  ASAL background atas      
<div data-options="region:'north',border:true" style="height:70px;background:#B3DFDA;background-image:'header.png';">
-->
        <div data-options="region:'north',border:true" style="height:70px;background:#81DC9D;background-image:'header.png';">
			<table>
			<tr>
			<td width="100" align="center"><img src="./images/logo-ptsb-small.png"></td>
			<td width="50" align="right">
			<?php
				if(isset($_SESSION['login'])){
					echo '<br/><a href="?logout=1">LOGOUT</a>';
				}
			?>				
			</td>						
			<td width="300">&nbsp;</td><td><h2><br>IIM 5.5 Konfigurasi Sistem</h2></td>
			</tr>			
			</table>
		</div>
<!-- ASAL background bawah
        <div data-options="region:'south',split:false" style="height:50px;background:#A9FACD;">
-->
<div data-options="region:'south',split:false" style="height:50px;background:#63F99E;">

		Copyright© 2013-2020 MSBE
		</div>
		
 
<!--main-->		
<div data-options="region:'west',split:false,border:false" style="height:550px;">	
		
<div style="width:200px;height:auto;background:#7190E0;padding:5px;">
    <div class="easyui-panel" title="Main Control Panel" collapsible="false" style="width:200px;height:400px;padding:10px;">
 
		    <div style="margin:10px 0 10px 0;">

				<a href="#" class="easyui-linkbutton" onclick="openPanelGeneral()"  data-options="iconCls:'icon-main',iconAlign:'top',size:'large',plain:false" style="width:80px;height:58px;">main</a><span style="padding:5px;"></span>
				<a href="#" class="easyui-linkbutton" onclick="openPanelIqomah()"  data-options="iconCls:'icon-iqomah',iconAlign:'top',size:'large',plain:false" style="width:80px;height:58px;">Iqomah</a><br/><br/>
				<a href="#" class="easyui-linkbutton" onclick="openPanelSolat()"  data-options="iconCls:'icon-solat',iconAlign:'top',size:'large',plain:false" style="width:80px;height:58px;">Solat</a><span style="padding:5px;"></span>
				<a href="#" class="easyui-linkbutton" onclick="openPanelCountdown()"  data-options="iconCls:'icon-countdown',iconAlign:'top',size:'large',plain:false" style="width:80px;height:58px;">Countdown</a><br/><br/>
				<a href="#" class="easyui-linkbutton" onclick="openPanelKuliah()"  data-options="iconCls:'icon-kuliah',iconAlign:'top',size:'large',plain:false" style="width:80px;height:58px;">Kuliah</a><span style="padding:5px;"></span>
				<a href="#" class="easyui-linkbutton" onclick="openPanelAzan()"  data-options="iconCls:'icon-azan',iconAlign:'top',size:'large',plain:false" style="width:80px;height:58px;">Azan</a><br/><br/>

				<div align="center" ><span class="button-wrap"><a href="/filemanager/" class="button button-pill button-primary" >File Manager</a></span></div>
				</br>
				<div align="center"><a href="#" onclick="clickReboot()" class="button button-border-action button-pill button-tiny">Reboot</a>
				<a href="#" onclick="clickRefresh()" class="button button-border-action button-pill button-tiny">Refresh</a>
				</div>
			</div>	
    </div>
</div>				
		
</div>				
<!--main-->	
		
<div data-options="region:'east',split:false,collapsible:false,border:false" style="width:1056px;height:550px">


<!-- BEGIN FORM COUNTDOWN -->	
<div id="pFormCountdown" class="easyui-panel" fit="true"  style="width:auto;height:100%;">	
    <table id="dg_countdown" title="Senarai Countdown" fit="true" style="width:1020px;height:height:100%"
            url="get_countdown.php"
            toolbar="#toolbar" pagination="true"
            fitColumns="true" singleSelect="true"
            title="Load Data" iconCls="icon-countdown"
            sortName="id" sortOrder="asc"
            rownumbers="true" pagination="true">			
        <thead>
            <tr>
                <th field="event" width="50"  sortable="true">Event</th>
                <th field="tarikh" width="50"  sortable="true">Tarikh</th>
                <th field="status" width="50"  sortable="true">Status</th>				
            </tr>
        </thead>
    </table>
    <div id="toolbar" style="padding:10px 0;">`
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add'"  style="width:80px" onclick="newItemCountdown()">Tambah</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove'"  style="width:80px" onclick="destroyItemCountdown()">Buang</a>
    </div>	
</div>				
<!-- END FORM COUNTDOWN -->
		

<!-- BEGIN FORM KULIAH -->	 
<div id="pFormKuliah" class="easyui-panel" fit="true" title="Setting Kuliah" data-options="iconCls:'icon-kuliah'" style="width:auto;height:100%">
 <div id="pFormKuliahTab"  class="easyui-tabs" fit="true" data-options="tabWidth:100,tabHeight:60" title="Kuliah" style="width:auto;height:100%">


 		<!-- BEGIN KULIAH PLANNER -->
		<div title="<span class='tt-inner'><img src='images/32x32_schedule.png'/><br>Planner</span>" style="padding:10px">	
			<iframe src="regal/inline.html" style="width:980px;height:550px"></iframe>
        </div>
		<!-- END KULIAH PLANNER -->




		<!-- BEGIN KULIAH TETAP -->
		<div title="<span class='tt-inner'><img src='images/photos.png'/><br>Kuliah Tetap</span>" style="padding:10px">
            
    <table id="dg_kuliahtetap" title="Senarai Kuliah Tetap"  style="width:1020px;height:100%"
            url="get_kuliah_tetap.php"
            toolbar="#toolbar2" pagination="true"
            fitColumns="true" singleSelect="true"
            title="Load Data" iconCls="icon-kuliah"
            sortName="susunan" sortOrder="asc"
            rownumbers="true" pagination="true">			
        <thead>
            <tr>
                <th field="header" width="50"  sortable="true">Header</th>
<!--                <th field="tarikh" width="50"  sortable="true">Tarikh</th> -->
                <th field="tajuk" width="50"  sortable="true">Tajuk</th>
                <th field="hari" width="50"  sortable="true">Hari</th>
               <th field="waktu" width="50"  sortable="true">Waktu</th>
                <th field="penceramah" width="50"  sortable="true">Penceramah</th>
                <th field="tempat" width="50"  sortable="true">Tempat</th>
                <th field="catatan" width="50"  sortable="true">Catatan</th>
                <th field="status" width="35"  sortable="true">Papar</th>
                <th field="batal" width="35"  sortable="true">Batal</th>
               <th field="susunan" width="50"  sortable="true">Susunan</th>
               <th field="show_slide" width="50"  sortable="true">Paparan</th>
                <th field="filetemplate" width="50"  sortable="true">File Template</th>
                <th field="file_slide" width="50"  sortable="true">File Slide</th>
 				
            </tr>
        </thead>
    </table>
    <div id="toolbar2" style="padding:10px 0;">`
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add'"  style="width:80px" onclick="newItemKuliahTetap()">Tambah</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove'"  style="width:80px" onclick="destroyItemKuliahTetap()">Buang</a>
    </div>	
				
  			
        </div>
		<!-- END KULIAH TETAP -->

		<!-- BEGIN KULIAH SENARAI -->
		<div title="<span class='tt-inner'><img src='images/32x32_korganizer.png'/><br>Bertarikh</span>" style="padding:10px">
            
    <table id="dg" title="Senarai Kuliah Berjadual"  style="width:1020px;height:100%"
            url="get_kuliah.php"
            toolbar="#toolbar3" pagination="true"
            fitColumns="true" singleSelect="true"
            title="Load Data" iconCls="icon-kuliah"
           sortName="susunan" sortOrder="asc" 
            rownumbers="true" pagination="true">			
        <thead>
            <tr>
                <th field="header" width="50"  sortable="true">Header</th>
                <th field="tarikh" width="50"  sortable="true">Tarikh</th>
                <th field="tajuk" width="50"  sortable="true">Tajuk</th>
                <th field="hari" width="50"  sortable="true">Hari</th>
               <th field="waktu" width="50"  sortable="true">Waktu</th>
                <th field="penceramah" width="50"  sortable="true">Penceramah</th>
                <th field="tempat" width="50"  sortable="true">Tempat</th>
                <th field="catatan" width="50"  sortable="true">Catatan</th>
                <th field="status" width="35"  sortable="true">Papar</th>
                <th field="batal" width="35"  sortable="true">Batal</th>
               <th field="susunan" width="50"  sortable="true">Susunan</th>
	           <th field="show_slide" width="50"  sortable="true">Paparan</th>
                <th field="filetemplate" width="50"  sortable="true">File Template</th>
                <th field="file_slide" width="50"  sortable="true">File Slide</th>
 				
            </tr>
        </thead>
    </table>
    <div id="toolbar3" style="padding:10px 0;">`
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add'"  style="width:80px" onclick="newItem()">Tambah</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove'"  style="width:80px" onclick="destroyItem()">Buang</a>
    </div>	
				
  			
        </div>
		<!-- END KULIAH SENARAI -->

		
</div>			

</div>	
<!-- END FORM KULIAH -->
		

<!-- BEGIN FORM IQOMAH -->
    <div  id="pFormIqomah" class="easyui-panel" title="Setting Iqomah"  data-options="iconCls:'icon-iqomah'" style="width:750px;height:500;">
		<div id="tTabIqomah" class="easyui-tabs" data-options="tabWidth:100,tabHeight:60" title="General" style="width:748px;">


		<!-- BEGIN JUMAAT -->
		<div title="<span class='tt-inner'><img src='images/iqomah-f.png'/><br>Jumaat</span>" style="padding:10px;">
            <p>
			  <form id="fSetIqomahJUMAAT" method="post">
					<table cellpadding="5">
						<tr>
							<td>Subuh :</td>
							<td><input class="easyui-textbox" name="set_iqomah_jumaat_subuh" data-options="required:true, width:30">(15)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Zohor :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_iqomah_jumaat_zohor" data-options="required:true, width:30">(10) (minit)</input></td>
						</tr>
						<tr>
							<td>Asar :</td>
							<td><input class="easyui-textbox" name="set_iqomah_jumaat_asar" data-options="required:true, width:30">(10)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Maghrib :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_iqomah_jumaat_maghrib" data-options="required:true, width:30">(10) (minit)</input></td>
						</tr>
						<tr>
							<td>Isyak :</td>
							<td><input class="easyui-textbox" name="set_iqomah_jumaat_isyak" data-options="required:true, width:30">(10)(minit)</input></td>
						</tr>
					</table>
				 <div style="text-align:center;padding:5px">
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetIqomahForm('JUMAAT')">Kemaskini</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetIqomahForm()">Batal</a>
				</div>
			   </form>
			</p>
		</div>
		<!-- END JUMAAT -->
		
		<!-- BEGIN SABTU -->
		<div title="<span class='tt-inner'><img src='images/iqomah-s.png'/><br>Sabtu</span>" style="padding:10px">
            <p>
			  <form id="fSetIqomahSABTU" method="post">
					<table cellpadding="5">
						<tr>
							<td>Subuh :</td>
							<td><input class="easyui-textbox" name="set_iqomah_sabtu_subuh" data-options="required:true, width:30">(15)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Zohor :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_iqomah_sabtu_zohor" data-options="required:true, width:30">(10) (minit)</input></td>
						</tr>
						<tr>
							<td>Asar :</td>
							<td><input class="easyui-textbox" name="set_iqomah_sabtu_asar" data-options="required:true, width:30">(10)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Maghrib :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_iqomah_sabtu_maghrib" data-options="required:true, width:30">(10) (minit)</input></td>
						</tr>
						<tr>
							<td>Isyak :</td>
							<td><input class="easyui-textbox" name="set_iqomah_sabtu_isyak" data-options="required:true, width:30">(10)(minit)</input></td>
						</tr>
					</table>
				 <div style="text-align:center;padding:5px">
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetIqomahForm('SABTU')">Kemaskini</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetIqomahForm()">Batal</a>
				</div>
			   </form>
			</p>
		</div>
		<!-- END SABTU -->
		
		<!-- BEGIN AHAD -->
		<div title="<span class='tt-inner'><img src='images/iqomah-s.png'/><br>Ahad</span>" style="padding:10px">
            <p>
			  <form id="fSetIqomahAHAD" method="post">
					<table cellpadding="5">
						<tr>
							<td>Subuh :</td>
							<td><input class="easyui-textbox" name="set_iqomah_ahad_subuh" data-options="required:true, width:30">(15)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Zohor :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_iqomah_ahad_zohor" data-options="required:true, width:30">(10) (minit)</input></td>
						</tr>
						<tr>
							<td>Asar :</td>
							<td><input class="easyui-textbox" name="set_iqomah_ahad_asar" data-options="required:true, width:30">(10)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Maghrib :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_iqomah_ahad_maghrib" data-options="required:true, width:30">(10) (minit)</input></td>
						</tr>
						<tr>
							<td>Isyak :</td>
							<td><input class="easyui-textbox" name="set_iqomah_ahad_isyak" data-options="required:true, width:30">(10)(minit)</input></td>
						</tr>
					</table>
				 <div style="text-align:center;padding:5px">
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetIqomahForm('AHAD')">Kemaskini</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetIqomahForm()">Batal</a>
				</div>
			   </form>
			</p>
		</div>
		<!-- END AHAD -->

		<!-- BEGIN ISNIN -->
		<div title="<span class='tt-inner'><img src='images/iqomah-m.png'/><br>Isnin</span>" style="padding:10px">
            <p>
			  <form id="fSetIqomahISNIN" method="post">
					<table cellpadding="5">
						<tr>
							<td>Subuh :</td>
							<td><input class="easyui-textbox" name="set_iqomah_isnin_subuh" data-options="required:true, width:30">(15)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Zohor :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_iqomah_isnin_zohor" data-options="required:true, width:30">(10) (minit)</input></td>
						</tr>
						<tr>
							<td>Asar :</td>
							<td><input class="easyui-textbox" name="set_iqomah_isnin_asar" data-options="required:true, width:30">(10)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Maghrib :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_iqomah_isnin_maghrib" data-options="required:true, width:30">(10) (minit)</input></td>
						</tr>
						<tr>
							<td>Isyak :</td>
							<td><input class="easyui-textbox" name="set_iqomah_isnin_isyak" data-options="required:true, width:30">(10)(minit)</input></td>
						</tr>
					</table>
				 <div style="text-align:center;padding:5px">
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetIqomahForm('ISNIN')">Kemaskini</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetIqomahForm()">Batal</a>
				</div>
			   </form>
			</p>
		</div>
		<!-- END ISNIN -->


		<!-- BEGIN SELASA -->
		<div title="<span class='tt-inner'><img src='images/iqomah-t.png'/><br>Selasa</span>" style="padding:10px">
            <p>
			  <form id="fSetIqomahSELASA" method="post">
					<table cellpadding="5">
						<tr>
							<td>Subuh :</td>
							<td><input class="easyui-textbox" name="set_iqomah_selasa_subuh" data-options="required:true, width:30">(15)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Zohor :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_iqomah_selasa_zohor" data-options="required:true, width:30">(10) (minit)</input></td>
						</tr>
						<tr>
							<td>Asar :</td>
							<td><input class="easyui-textbox" name="set_iqomah_selasa_asar" data-options="required:true, width:30">(10)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Maghrib :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_iqomah_selasa_maghrib" data-options="required:true, width:30">(10) (minit)</input></td>
						</tr>
						<tr>
							<td>Isyak :</td>
							<td><input class="easyui-textbox" name="set_iqomah_selasa_isyak" data-options="required:true, width:30">(10)(minit)</input></td>
						</tr>
					</table>
				 <div style="text-align:center;padding:5px">
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetIqomahForm('SELASA')">Kemaskini</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetIqomahForm()">Batal</a>
				</div>
			   </form>
			</p>
		</div>
		<!-- END SELASA -->

		<!-- BEGIN RABU -->
		<div title="<span class='tt-inner'><img src='images/iqomah-w.png'/><br>Rabu</span>" style="padding:10px">
            <p>
			  <form id="fSetIqomahRABU" method="post">
					<table cellpadding="5">
						<tr>
							<td>Subuh :</td>
							<td><input class="easyui-textbox" name="set_iqomah_rabu_subuh" data-options="required:true, width:30">(15)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Zohor :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_iqomah_rabu_zohor" data-options="required:true, width:30">(10) (minit)</input></td>
						</tr>
						<tr>
							<td>Asar :</td>
							<td><input class="easyui-textbox" name="set_iqomah_rabu_asar" data-options="required:true, width:30">(10)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Maghrib :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_iqomah_rabu_maghrib" data-options="required:true, width:30">(10) (minit)</input></td>
						</tr>
						<tr>
							<td>Isyak :</td>
							<td><input class="easyui-textbox" name="set_iqomah_rabu_isyak" data-options="required:true, width:30">(10)(minit)</input></td>
						</tr>
					</table>
				 <div style="text-align:center;padding:5px">
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetIqomahForm('RABU')">Kemaskini</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetIqomahForm()">Batal</a>
				</div>
			   </form>
			</p>
		</div>
		<!-- END RABU -->

		<!-- BEGIN KHAMIS -->
		<div title="<span class='tt-inner'><img src='images/iqomah-t.png'/><br>Khamis</span>" style="padding:10px">
            <p>
			  <form id="fSetIqomahKHAMIS" method="post">
					<table cellpadding="5">
						<tr>
							<td>Subuh :</td>
							<td><input class="easyui-textbox" name="set_iqomah_khamis_subuh" data-options="required:true, width:30">(15)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Zohor :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_iqomah_khamis_zohor" data-options="required:true, width:30">(10) (minit)</input></td>
						</tr>
						<tr>
							<td>Asar :</td>
							<td><input class="easyui-textbox" name="set_iqomah_khamis_asar" data-options="required:true, width:30">(10)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Maghrib :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_iqomah_khamis_maghrib" data-options="required:true, width:30">(10) (minit)</input></td>
						</tr>
						<tr>
							<td>Isyak :</td>
							<td><input class="easyui-textbox" name="set_iqomah_khamis_isyak" data-options="required:true, width:30">(10)(minit)</input></td>
						</tr>
					</table>
				 <div style="text-align:center;padding:5px">
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetIqomahForm('KHAMIS')">Kemaskini</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetIqomahForm()">Batal</a>
				</div>
			   </form>
			</p>
		</div>
		<!-- END KHAMIS -->
		
		</div>
	</div> 
<!-- END FORM IQOMAH -->



<!-- BEGIN FORM SOLAT -->
    <div  id="pFormSolat" class="easyui-panel" title="Setting Skrin Solat"  data-options="iconCls:'icon-solat'" style="width:750px;height:500;">
		<div id="tTabSolat" class="easyui-tabs" data-options="tabWidth:100,tabHeight:60" title="General" style="width:748px;">


		<!-- BEGIN JUMAAT -->
		<div title="<span class='tt-inner'><img src='images/solat-f.png'/><br>Jumaat</span>" style="padding:10px;">
            <p>
			  <form id="fSetSolatJUMAAT" method="post">
					<table cellpadding="5">
						<tr>
							<td>Subuh :</td>
							<td><input class="easyui-textbox" name="set_solat_jumaat_subuh" data-options="required:true, width:30">(15)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Zohor :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_solat_jumaat_zohor" data-options="required:true, width:30">(10) (minit)</input></td>
							<td style="width:40px"></td>
							<td>Paparan Imej:</td>                    
							<td>
								<select id="fSelectJumaatScreen" class="easyui-combobox" name="set_solat_jumaat_screen" data-options="
								    width:100,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'				
								">													
									<option value="Standard">Standard</option>
									<option value="Hitam">Hitam (Off)</option>
									<option value="Khas">Khas</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Asar :</td>
							<td><input class="easyui-textbox" name="set_solat_jumaat_asar" data-options="required:true, width:30">(10)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Maghrib :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_solat_jumaat_maghrib" data-options="required:true, width:30">(10) (minit)</input></td>
							<td style="width:40px"></td>
							<td>Beep Sebelum:</td>                    
							<td>
								<select id="fSelectJumaatBeep" class="easyui-combobox" name="set_solat_jumaat_beep" data-options="
								    width:100,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'				
								">													
									<option value="0">Senyap</option>
									<option value="1">Beep</option>
									<option value="2">Beduk</option>
								</select>
							</td>

						</tr>
						<tr>
							<td>Isyak :</td>
							<td><input class="easyui-textbox" name="set_solat_jumaat_isyak" data-options="required:true, width:30">(10)(minit)</input></td>
						</tr>
					</table>
				 <div style="text-align:center;padding:5px">
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetSolatForm('JUMAAT')">Kemaskini</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetSolatForm()">Batal</a>
				</div>
			   </form>
			</p>
		</div>
		<!-- END JUMAAT -->
		
		<!-- BEGIN SABTU -->
		<div title="<span class='tt-inner'><img src='images/solat-s.png'/><br>Sabtu</span>" style="padding:10px">
            <p>
			  <form id="fSetSolatSABTU" method="post">
					<table cellpadding="5">
						<tr>
							<td>Subuh :</td>
							<td><input class="easyui-textbox" name="set_solat_sabtu_subuh" data-options="required:true, width:30">(15)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Zohor :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_solat_sabtu_zohor" data-options="required:true, width:30">(10) (minit)</input></td>
							<td style="width:40px"></td>
							<td>Paparan Imej:</td>                    
							<td>
								<select id="fSelectSabtuScreen" class="easyui-combobox" name="set_solat_sabtu_screen" data-options="
								    width:100,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'				
								">													
									<option value="Standard">Standard</option>
									<option value="Hitam">Hitam (Off)</option>
									<option value="Khas">Khas</option>
								</select>
							</td>						
						</tr>
						<tr>
							<td>Asar :</td>
							<td><input class="easyui-textbox" name="set_solat_sabtu_asar" data-options="required:true, width:30">(10)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Maghrib :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_solat_sabtu_maghrib" data-options="required:true, width:30">(10) (minit)</input></td>
							<td style="width:40px"></td>
							<td>Beep Sebelum:</td>                    
							<td>
								<select id="fSelectSabtuBeep" class="easyui-combobox" name="set_solat_sabtu_beep" data-options="
								    width:100,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'				
								">													
									<option value="0">Senyap</option>
									<option value="1">Beep</option>
									<option value="2">Beduk</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Isyak :</td>
							<td><input class="easyui-textbox" name="set_solat_sabtu_isyak" data-options="required:true, width:30">(10)(minit)</input></td>
						</tr>
					</table>
				 <div style="text-align:center;padding:5px">
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetSolatForm('SABTU')">Kemaskini</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetSolatForm()">Batal</a>
				</div>
			   </form>
			</p>
		</div>
		<!-- END SABTU -->
		
		<!-- BEGIN AHAD -->
		<div title="<span class='tt-inner'><img src='images/solat-s.png'/><br>Ahad</span>" style="padding:10px">
            <p>
			  <form id="fSetSolatAHAD" method="post">
					<table cellpadding="5">
						<tr>
							<td>Subuh :</td>
							<td><input class="easyui-textbox" name="set_solat_ahad_subuh" data-options="required:true, width:30">(15)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Zohor :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_solat_ahad_zohor" data-options="required:true, width:30">(10) (minit)</input></td>
							<td style="width:40px"></td>
							<td>Paparan Imej:</td>                    
							<td>
								<select id="fSelectAhadScreen" class="easyui-combobox" name="set_solat_ahad_screen" data-options="
								    width:100,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'				
								">													
									<option value="Standard">Standard</option>
									<option value="Hitam">Hitam (Off)</option>
									<option value="Khas">Khas</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Asar :</td>
							<td><input class="easyui-textbox" name="set_solat_ahad_asar" data-options="required:true, width:30">(10)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Maghrib :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_solat_ahad_maghrib" data-options="required:true, width:30">(10) (minit)</input></td>
							<td style="width:40px"></td>
							<td>Beep Sebelum:</td>                    
							<td>
								<select id="fSelectAhadBeep" class="easyui-combobox" name="set_solat_ahad_beep" data-options="
								    width:100,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'				
								">													
									<option value="0">Senyap</option>
									<option value="1">Beep</option>
									<option value="2">Beduk</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Isyak :</td>
							<td><input class="easyui-textbox" name="set_solat_ahad_isyak" data-options="required:true, width:30">(10)(minit)</input></td>
						</tr>
					</table>
				 <div style="text-align:center;padding:5px">
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetSolatForm('AHAD')">Kemaskini</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetSolatForm()">Batal</a>
				</div>
			   </form>
			</p>
		</div>
		<!-- END AHAD -->

		<!-- BEGIN ISNIN -->
		<div title="<span class='tt-inner'><img src='images/solat-m.png'/><br>Isnin</span>" style="padding:10px">
            <p>
			  <form id="fSetSolatISNIN" method="post">
					<table cellpadding="5">
						<tr>
							<td>Subuh :</td>
							<td><input class="easyui-textbox" name="set_solat_isnin_subuh" data-options="required:true, width:30">(15)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Zohor :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_solat_isnin_zohor" data-options="required:true, width:30">(10) (minit)</input></td>
							<td style="width:40px"></td>
							<td>Paparan Imej:</td>                    
							<td>
								<select id="fSelectIsninScreen" class="easyui-combobox" name="set_solat_isnin_screen" data-options="
								    width:100,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'				
								">													
									<option value="Standard">Standard</option>
									<option value="Hitam">Hitam (Off)</option>
									<option value="Khas">Khas</option>
								</select>
							</td>						
						</tr>
						<tr>
							<td>Asar :</td>
							<td><input class="easyui-textbox" name="set_solat_isnin_asar" data-options="required:true, width:30">(10)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Maghrib :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_solat_isnin_maghrib" data-options="required:true, width:30">(10) (minit)</input></td>
							<td style="width:40px"></td>
							<td>Beep Sebelum:</td>                    
							<td>
								<select id="fSelectIsninBeep" class="easyui-combobox" name="set_solat_isnin_beep" data-options="
								    width:100,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'				
								">													
									<option value="0">Senyap</option>
									<option value="1">Beep</option>
									<option value="2">Beduk</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Isyak :</td>
							<td><input class="easyui-textbox" name="set_solat_isnin_isyak" data-options="required:true, width:30">(10)(minit)</input></td>
						</tr>
					</table>
				 <div style="text-align:center;padding:5px">
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetSolatForm('ISNIN')">Kemaskini</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetSolatForm()">Batal</a>
				</div>
			   </form>
			</p>
		</div>
		<!-- END ISNIN -->


		<!-- BEGIN SELASA -->
		<div title="<span class='tt-inner'><img src='images/solat-t.png'/><br>Selasa</span>" style="padding:10px">
            <p>
			  <form id="fSetSolatSELASA" method="post">
					<table cellpadding="5">
						<tr>
							<td>Subuh :</td>
							<td><input class="easyui-textbox" name="set_solat_selasa_subuh" data-options="required:true, width:30">(15)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Zohor :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_solat_selasa_zohor" data-options="required:true, width:30">(10) (minit)</input></td>
							<td style="width:40px"></td>
							<td>Paparan Imej:</td>                    
							<td>
								<select id="fSelectSelasaScreen" class="easyui-combobox" name="set_solat_selasa_screen" data-options="
								    width:100,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'				
								">													
									<option value="Standard">Standard</option>
									<option value="Hitam">Hitam (Off)</option>
									<option value="Khas">Khas</option>
								</select>
							</td>						
						</tr>
						<tr>
							<td>Asar :</td>
							<td><input class="easyui-textbox" name="set_solat_selasa_asar" data-options="required:true, width:30">(10)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Maghrib :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_solat_selasa_maghrib" data-options="required:true, width:30">(10) (minit)</input></td>
							<td style="width:40px"></td>
							<td>Beep Sebelum:</td>                    
							<td>
								<select id="fSelectSelasaBeep" class="easyui-combobox" name="set_solat_selasa_beep" data-options="
								    width:100,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'				
								">													
									<option value="0">Senyap</option>
									<option value="1">Beep</option>
									<option value="2">Beduk</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Isyak :</td>
							<td><input class="easyui-textbox" name="set_solat_selasa_isyak" data-options="required:true, width:30">(10)(minit)</input></td>
						</tr>
					</table>
				 <div style="text-align:center;padding:5px">
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetSolatForm('SELASA')">Kemaskini</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetSolatForm()">Batal</a>
				</div>
			   </form>
			</p>
		</div>
		<!-- END SELASA -->

		<!-- BEGIN RABU -->
		<div title="<span class='tt-inner'><img src='images/solat-w.png'/><br>Rabu</span>" style="padding:10px">
            <p>
			  <form id="fSetSolatRABU" method="post">
					<table cellpadding="5">
						<tr>
							<td>Subuh :</td>
							<td><input class="easyui-textbox" name="set_solat_rabu_subuh" data-options="required:true, width:30">(15)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Zohor :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_solat_rabu_zohor" data-options="required:true, width:30">(10) (minit)</input></td>
							<td style="width:40px"></td>
							<td>Paparan Imej:</td>                    
							<td>
								<select id="fSelectRabuScreen" class="easyui-combobox" name="set_solat_rabu_screen" data-options="
								    width:100,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'				
								">													
									<option value="Standard">Standard</option>
									<option value="Hitam">Hitam (Off)</option>
									<option value="Khas">Khas</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Asar :</td>
							<td><input class="easyui-textbox" name="set_solat_rabu_asar" data-options="required:true, width:30">(10)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Maghrib :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_solat_rabu_maghrib" data-options="required:true, width:30">(10) (minit)</input></td>
							<td style="width:40px"></td>
							<td>Beep Sebelum:</td>                    
							<td>
								<select id="fSelectRabuBeep" class="easyui-combobox" name="set_solat_rabu_beep" data-options="
								    width:100,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'				
								">													
									<option value="0">Senyap</option>
									<option value="1">Beep</option>
									<option value="2">Beduk</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Isyak :</td>
							<td><input class="easyui-textbox" name="set_solat_rabu_isyak" data-options="required:true, width:30">(10)(minit)</input></td>
						</tr>
					</table>
				 <div style="text-align:center;padding:5px">
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetSolatForm('RABU')">Kemaskini</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetSolatForm()">Batal</a>
				</div>
			   </form>
			</p>
		</div>
		<!-- END RABU -->

		<!-- BEGIN KHAMIS -->
		<div title="<span class='tt-inner'><img src='images/solat-t.png'/><br>Khamis</span>" style="padding:10px">
            <p>
			  <form id="fSetSolatKHAMIS" method="post">
					<table cellpadding="5">
						<tr>
							<td>Subuh :</td>
							<td><input class="easyui-textbox" name="set_solat_khamis_subuh" data-options="required:true, width:30">(15)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Zohor :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_solat_khamis_zohor" data-options="required:true, width:30">(10) (minit)</input></td>
							<td style="width:40px"></td>
							<td>Paparan Imej:</td>                    
							<td>
								<select id="fSelectKhamisScreen" class="easyui-combobox" name="set_solat_khamis_screen" data-options="
								    width:100,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'				
								">													
									<option value="Standard">Standard</option>
									<option value="Hitam">Hitam (Off)</option>
									<option value="Khas">Khas</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Asar :</td>
							<td><input class="easyui-textbox" name="set_solat_khamis_asar" data-options="required:true, width:30">(10)(minit)</input></td>
							<td style="width:50px"></td>
							<td>Maghrib :</td>                    
							<td><input class="easyui-textbox" type="text" name="set_solat_khamis_maghrib" data-options="required:true, width:30">(10) (minit)</input></td>
							<td style="width:40px"></td>
							<td>Beep Sebelum:</td>                    
							<td>
								<select id="fSelectKhamisBeep" class="easyui-combobox" name="set_solat_khamis_beep" data-options="
								    width:100,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'				
								">													
									<option value="0">Senyap</option>
									<option value="1">Beep</option>
									<option value="2">Beduk</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Isyak :</td>
							<td><input class="easyui-textbox" name="set_solat_khamis_isyak" data-options="required:true, width:30">(10)(minit)</input></td>
						</tr>
					</table>
				 <div style="text-align:center;padding:5px">
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetSolatForm('KHAMIS')">Kemaskini</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetSolatForm()">Batal</a>
				</div>
			   </form>
			</p>
		</div>
		<!-- END KHAMIS -->
		
		</div>
	</div> 
<!-- END FORM SOLAT -->


<!-- BEGIN FORM GENERAL -->
   <div  id="pFormGeneral" class="easyui-panel" title="Setting Main"  data-options="iconCls:'icon-main'" style="width:840px;height:500;">
    <div class="easyui-tabs" data-options="tabWidth:100,tabHeight:60" title="General" style="width:838px;">
  
		<!-- BEGIN TARIKH MASA -->
		<div title="<span class='tt-inner'><img src='images/hari.png'/><br>Tarikh/Masa</span>" style="padding:10px">
            <p>
			  <form id="fSetDatetime" method="post">
			  <div style="width: 95%;padding: 10px;border: 2px solid #5DADE2;margin: 2px;">
					<div><strong>Input datetime secara manual</strong></div>
					<div style="text-align:left;padding:5px">

							<table cellpadding="5">
								<tr>
									<td>Tarikh :</td>
									<td><input class="easyui-datebox"  style="width:100px;" name="set_tarikh" value="date()" data-options="required:true, formatter:myformatter, parser:myparser"></input></td>
									<td>&nbsp;&nbsp;</td>
									<td>Masa :</td>
									<td><input class="easyui-textbox" style="width:100px;" type="time" name="set_time" value="00:00"></input></td>
									<td>	<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetDatetimeForm()">Kemaskini</a>
											<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetDatetimeForm()">Batal</a>
									</td>
								</tr>
							</table>
			        </div>
			  </div>
			  <br/>
			  <div style="width: 95%;padding: 10px;border: 2px solid #5DADE2;margin: 2px;">
					<div><strong>Sync datetime dengan PC/HP</strong></div>
					 <br/>
				   <div style="text-align:left;padding:5px">Waktu PC/HP: <input id="todaysDate" type="text" class="easyui-textbox" style="width:190px;"  name="today_date"></input>
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetDatetimePCHP()">Sync & Kemaskini</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetDatetimeForm()">Batal</a>
				  </div>
			  </div>
			  <br/>
			  <div style="width: 95%;padding: 10px;border: 2px solid #5DADE2;margin: 2px;">

				<table cellpadding="5">
				<tr>
				<td>
				   <div><strong>Automatic Sync Time (Satellite/Internet)</strong></div>
					 <br/>
				     <div style="text-align:left;padding:5px">
								<select id="fSelectAutosync" class="easyui-combobox" name="set_autosync" data-options="
								    width:80,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'				
								">													
									<option value="Tidak">Tidak</option>
									<option value="Ya">Ya</option>
								</select>
												
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetDatetimeAutosync()">Kemaskini</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetDatetimeForm()">Batal</a>
				    </div>
				</td>
				<td>
                                 <div style="width:50px"></div>
				</td>

				<td>
				   <div><strong>Automatic Reboot (Setiap tengahmalam)</strong></div>
					 <br/>
				     <div style="text-align:left;padding:5px">
								<select id="fSelectAutoreboot" class="easyui-combobox" name="set_autoreboot" data-options="
								    width:80,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'				
								">													
									<option value="Tidak">Tidak</option>
									<option value="Ya">Ya</option>
								</select>
												
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetAutoreboot()">Kemaskini</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetDatetimeForm()">Batal</a>
				    </div>
				</td>
			    </tr>
			    </table>

			  </div>
			  
			</form>
				
        </div>
		
		
		
		<!-- END TARIKH MASA -->

		<!-- BEGIN HIJRI -->
		<div title="<span class='tt-inner'><img src='images/hijri.png'/><br>Hijri</span>" style="padding:10px">
            <p>
			  <form id="fSetHijri" method="post">
					<table cellpadding="5">
						<tr>
							<td>Hijri Offset :</td>
							<td><input class="easyui-textbox"  style="width:30px;" type="text" name="set_hijrioffset" data-options="required:true, formatter:myformatter, parser:myparser"> ( +1, 0 or -1 ) </input></td>
						</tr>
					</table>
			   <div style="text-align:left;padding:5px">
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetHijriForm()">Kemaskini</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetHijriForm()">Batal</a>
			  </div>
			</form>
				
  			</p>
        </div>
		<!-- END HIJRI -->
			
		<!-- BEGIN SELANGMASA DURATION-->		
        <div title="<span class='tt-inner'><img src='images/jam.png'/><br>Selangmasa</span>" style="padding:10px">
            <p>
        <form id="fSetDuration" method="post">
            <table cellpadding="5">
                <tr>
                    <td>Masa Slide:</td>
                    <td><input class="easyui-textbox" type="text" name="masa_slide" data-options="required:true, width:30">(10)(saat perslide)</input></td>
    				<td style="width:50px"></td>
                    <td>Masa Taqwim:</td>                    
	                <td><input class="easyui-textbox" type="text" name="masa_taqwim" data-options="required:true, width:30">(10) (saat)</input></td>
                </tr>
                <tr>
                    <td>Masa World Clock:</td>
                    <td><input class="easyui-textbox" type="text" name="masa_worldclock" data-options="required:true, width:30">(10) (saat)</input></td>
   				<td style="width:50px"></td>
                     <td>Masa Countdown:</td>
                    <td><input class="easyui-textbox" type="text" name="masa_countdown" data-options="required:true, width:30">(10)(saat perslide)</input></td>
                </tr>
                <tr>
                    <td>Masa Jadual Kuliah:</td>
                    <td><input class="easyui-textbox" type="text" name="masa_jadualkuliah" data-options="required:true, width:30">(10)(saat perslide)</input></td>
                 </tr>
               <tr>											
            </table>
         <div style="text-align:center;padding:5px">
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetDurationForm()">Kemaskini</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetDurationForm()">Batal</a>
        </div>
       </form>
		</p>
        </div>

		<!-- BEGIN SLEEP MODE -->
		<div  title="<span class='tt-inner'><img src='images/sign.png'/><br>Sleep Mode</span>" style="padding:10px">
            <p>

			  <form id="fSleepMode" method="post">

					<div style="text-align:left;padding:5px">

							<table cellpadding="5">
								<tr>
								<td colspan="4"><strong>Tarikh sleep adalah untuk sleep sekali sahaja pada tarikh yang dimasukkan</strong></td>
								<td colspan="2"></td>
								</tr>
								<tr>
									<td>Tarikh (sleep pada tarikh ini sekali sahaja):</td>
									<td><input class="easyui-datebox"  style="width:100px;" name="set_tarikh_mulasleep" data-options="required:true, formatter:myformatter, parser:myparser"></input></td>
									<td>&nbsp;&nbsp;</td>
									<td>&nbsp;&nbsp;</td>

									<td><!--Tarikh (tamat sleep):--></td>
									<td><!--<input class="easyui-datebox"  style="width:100px;" name="set_tarikh_mulasleep" data-options="required:true, formatter:myformatter, parser:myparser"></input>--></td>
 								</tr>
								<tr>
									<td>Ulangan Setiap hari :<br>
									(Jika Ya, maka ianya tidak bergantung kepada tarikh)</td>
									<td><input class="easyui-validatebox" type="radio" name="ulang_sleep" value="0" checked="checked">Tidak
										<input class="easyui-validatebox" type="radio" name="ulang_sleep" value="1" >Ya</td>		

									<td>&nbsp;&nbsp;</td>
									<td>&nbsp;&nbsp;</td>


									<td>&nbsp;&nbsp;</td>
									<td>&nbsp;&nbsp;</td>
																	
 								</tr> 		
 
								<tr>
									<td>Masa Mula Sleep :</td>
									<td><input class="easyui-textbox" style="width:100px;" type="time" name="set_masa_mulasleep" ></input></td>									
									<td>&nbsp;&nbsp;</td>
									<td>&nbsp;&nbsp;</td>


									<td></td>
									<td></td>									
 								</tr>
 
								<tr>
									<td>Masa Tamat Sleep :</td>
									<td><input class="easyui-textbox" style="width:100px;" type="time" name="set_masa_tamatsleep" ></input></td>									
									<td>&nbsp;&nbsp;</td>
									<td>&nbsp;&nbsp;</td>


									<td></td>
									<td></td>									
 								</tr>


						
							</table>
							<p>
							<div style="margin-left:150px;">
								<a href="#" class="easyui-linkbutton" onclick="updateSetDatetimeSleep()">Kemaskini</a>
								<a href="#" class="easyui-linkbutton" onclick="cancelSetDatetimeSleep()">Batal</a>
							</div>
			                </div>
	
			</form>
					
  		</p>
        </div>
		<!-- END SLEEP MODE -->




		<!-- BEGIN SCROLLER -->
		<div title="<span class='tt-inner'><img src='images/pda.png'/><br>Scroller</span>" style="padding:10px">
            <p>
			  <form id="fSetScroller" method="post">
					<table cellpadding="5">
						<tr>
							<td>Scroller Text :</td>
							<td><input class="easyui-textbox"  type="text" name="set_scroller_text"  style="width:400px; height:60px" data-options="multiline:true">Max 80 chars</input></td>
						</tr>
						 <tr>
							<td>Scroller Speed :</td>
							<td><input class="easyui-textbox" type="text" name="set_scroller_speed"  data-options="required:true, formatter:myformatter, parser:myparser, width:30"></input>(5) Max 5</td>
						</tr>
					</table>
			   <div style="text-align:left;padding:5px">
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetScrollerForm()">Kemaskini</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetScrollerForm()">Batal</a>
			  </div>
			</form>
				
  			</p>
        </div>
		<!-- END SCROLLER -->


		<!-- BEGIN ZONE -->
		<div title="<span class='tt-inner'><img src='images/bumi.png'/><br>Zon</span>" style="padding:10px">
            <p>
			  <form id="fSetZone" method="post">
					<table cellpadding="5">
						<tr>
							<td>Kod Zon :</td>
							<td><input class="easyui-textbox"  style="width:50px;" type="text" name="set_zone" data-options="required:true, formatter:myformatter, parser:myparser"></input></td>
						</tr>
						 <tr>
							<td>Nama Lokasi :</td>
							<td><input class="easyui-textbox" style="width:200px;" type="text" name="set_lokasi" data-options="required:true, formatter:myformatter, parser:myparser"></input></td>
						</tr>
					</table>
			   <div style="text-align:left;padding:5px">
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetZoneForm()">Kemaskini</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetZoneForm()">Batal</a>
			  </div>
			</form>
				
  			</p>
        </div>
		<!-- END ZONE -->

		<!-- BEGIN ANIMATION -->
		<div title="<span class='tt-inner'><img src='images/animate.png'/><br>Animation</span>" style="padding:10px">
            <p>
			  <form id="fSetAnim" method="post">
					<table cellpadding="5">
						<tr>
							<td>
							
								<select id="fSelectAnim" class="easyui-combobox" name="set_anim" data-options="
								    width:150,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'				
								">													
									<option value="Fixed">Fixed</option>
									<option value="Random">Random</option>
								</select>
							
							
							</td>
						</tr>
					</table>
			   <div style="text-align:left;padding:5px">
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetAnimForm()">Kemaskini</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetAnimForm()">Batal</a>
			  </div>
			</form>
				
  			</p>
        </div>
		<!-- END ANIMATION -->

		<!-- BEGIN BACKGROUND -->
		<div title="<span class='tt-inner'><img src='images/picture_edit.png'/><br>background</span>" style="padding:10px">
            <p>
			  <form id="fSetBackground" method="post" enctype="multipart/form-data">

   <table class="dv-table" style="width:80%;background:#fafafa;padding:5px;margin-top:5px;">
 
               <tr>
			        <td>File Background:</td>
                    <td>                      
					<input id="chooseFileBackground" class="easyui-filebox" buttonText="Pilih file..." name="file_background" data-options="prompt:'Upload file...'" style="width:300px">
					<a id="btn_add_background" href="#" class="easyui-linkbutton" iconCls="icon-save" plain="false">Upload File</a>
					</td>
					<td>
						<div id="id_target_layer_background" for="NoImage" style="padding:5px 0;align:top;padding-right:30px">No Image</div>
					</td>

                </tr> 
    </table>	
			   <div style="text-align:left;padding:5px">
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetBackground()">Kemaskini</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetBackground()">Batal</a>
			  </div>
			</form>
				
  			</p>
        </div>
		<!-- END BACKGROUND -->



		<!-- BEGIN UPLOAD TAQWIM -->
		<div  title="<span class='tt-inner'><img src='images/database_gear.png'/><br>Update Data</span>" style="padding:10px">
            <p>

			  <form id="fUploadTaqwim" method="post" enctype="multipart/form-data">
					<table class="dv-table" style="width:100%;padding:5px;margin-top:5px;">
				 
							   <tr>
									<td>File Taqwim:</td>
									<td> 						                     
										<input class="easyui-filebox" name="file_taqwim" buttonText="Pilih file..." data-options="prompt:'Upload taqwim...'" style="width:300px">
										<a href="javascript:void(0)" name="btnTaqwim" class="easyui-linkbutton" iconCls="icon-save" plain="false" onclick="uploadFileTaqwim()">Upload Taqwim</a>
										<a href="javascript:void(0)" class="easyui-linkbutton" plain="false" onclick="cancelUploadTaqwim()">Batal</a>
									</td>
									<td style="width:15%;">
										<div id="progressFile" class="easyui-progressbar" style="width:100%;"></div>
									</td>
								</tr> 
					</table>	
					<div id="id_target_layer_uploadtaqwim" style="padding:5px 0;align:top;padding-right:30px">&nbsp;</div>

			</form>
				
  			</p>
        </div>
		<!-- END UPLOAD TAQWIM -->




		
    </div>

	
    <style scoped="scoped">
        .tt-inner{
            display:inline-block;
            line-height:12px;
            padding-top:5px;
        }
        .tt-inner img{
            border:0;
        }
    </style>		
</div>
<!-- END FORM GENERAL -->


<!-- BEGIN FORM AZAN -->
  <div  id="pFormAzan" class="easyui-panel" title="Setting Azan"  data-options="iconCls:'icon-azan'" style="width:700px;height:500;">
      <div class="easyui-tabs" data-options="tabWidth:100,tabHeight:60" title="General" style="width:698px;">

  
		<!-- SOUND AZAN -->	  
		<div title="<span class='tt-inner'><img src='images/mic.png'/><br>Sound Azan</span>" style="padding:10px;">
            <p>
			  <form id="fSetAzan" method="post">
					<table cellpadding="5">
						<tr>
							<td>Zohor :</td>
							<td>
								<select id="fSelectAzanZohor" class="easyui-combobox" name="set_azan_zohor" data-options="
								    width:150,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'
								">													
									<option value="bedukzohor.m4a">bedukzohor.m4a</option>
									<option value="bedukmaghrib.m4a">bedukmaghrib.m4a</option>
									<option value="beduksubuh.m4a">beduksubuh.m4a</option>
									<option value="bedukjumaat.m4a">bedukjumaat.m4a</option>
									<option value="beepbeep.mp4">beepbeep.mp4</option>
									<option value="silent.mp4">silent.mp4</option>
									<option value="videoazansubuhmadinah.mp4">videoazansubuhmadinah.mp4</option>
									<option value="videoazanmadinah.mp4">videoazanmadinah.mp4</option>
								</select>
							</td>
							
							<td style="width:50px"></td>
							<td>Asar :</td>                    
							<td>
								<select id="fSelectAzanAsar" class="easyui-combobox" name="set_azan_asar" data-options="
								    width:150,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'
								">													
									<option value="bedukzohor.m4a">bedukzohor.m4a</option>
									<option value="bedukmaghrib.m4a">bedukmaghrib.m4a</option>
									<option value="beduksubuh.m4a">beduksubuh.m4a</option>
									<option value="bedukjumaat.m4a">bedukjumaat.m4a</option>
									<option value="beepbeep.mp4">beepbeep.mp4</option>
									<option value="silent.mp4">silent.mp4</option>
									<option value="videoazansubuhmadinah.mp4">videoazansubuhmadinah.mp4</option>
									<option value="videoazanmadinah.mp4">videoazanmadinah.mp4</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Maghrib :</td>
							<td>
								<select id="fSelectAzanMaghrib" class="easyui-combobox" name="set_azan_maghrib" data-options="
								    width:150,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'										
								">													
									<option value="bedukzohor.m4a">bedukzohor.m4a</option>
									<option value="bedukmaghrib.m4a">bedukmaghrib.m4a</option>
									<option value="beduksubuh.m4a">beduksubuh.m4a</option>
									<option value="bedukjumaat.m4a">bedukjumaat.m4a</option>
									<option value="beepbeep.mp4">beepbeep.mp4</option>
									<option value="silent.mp4">silent.mp4</option>
									<option value="videoazansubuhmadinah.mp4">videoazansubuhmadinah.mp4</option>
									<option value="videoazanmadinah.mp4">videoazanmadinah.mp4</option>
								</select>
							</td>
							<td style="width:50px"></td>
							<td>Isyak :</td>                    
							<td>
								<select id="fSelectAzanIsyak" class="easyui-combobox" name="set_azan_isyak" data-options="
								    width:150,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'				
								">													
									<option value="bedukzohor.m4a">bedukzohor.m4a</option>
									<option value="bedukmaghrib.m4a">bedukmaghrib.m4a</option>
									<option value="beduksubuh.m4a">beduksubuh.m4a</option>
									<option value="bedukjumaat.m4a">bedukjumaat.m4a</option>
									<option value="beepbeep.mp4">beepbeep.mp4</option>
									<option value="silent.mp4">silent.mp4</option>
									<option value="videoazansubuhmadinah.mp4">videoazansubuhmadinah.mp4</option>
									<option value="videoazanmadinah.mp4">videoazanmadinah.mp4</option>
								</select>
							</td>
						</tr>
							<td>Imsak :</td>
							<td>
								<select id="fSelectAzanImsak" class="easyui-combobox" name="set_azan_imsak" data-options="
								    width:150,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'		
								">													
									<option value="bedukzohor.m4a">bedukzohor.m4a</option>
									<option value="bedukmaghrib.m4a">bedukmaghrib.m4a</option>
									<option value="beduksubuh.m4a">beduksubuh.m4a</option>
									<option value="bedukjumaat.m4a">bedukjumaat.m4a</option>
									<option value="beepbeep.mp4">beepbeep.mp4</option>
									<option value="silent.mp4">silent.mp4</option>
									<option value="videoazansubuhmadinah.mp4">videoazansubuhmadinah.mp4</option>
									<option value="videoazanmadinah.mp4">videoazanmadinah.mp4</option>
								</select>
							</td>
							<td style="width:50px"></td>
							<td>Subuh :</td>                    
							<td>
								<select id="fSelectAzanSubuh" class="easyui-combobox" name="set_azan_subuh" data-options="
								    width:150,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'					
								">													
									<option value="bedukzohor.m4a">bedukzohor.m4a</option>
									<option value="bedukmaghrib.m4a">bedukmaghrib.m4a</option>
									<option value="beduksubuh.m4a">beduksubuh.m4a</option>
									<option value="bedukjumaat.m4a">bedukjumaat.m4a</option>
									<option value="beepbeep.mp4">beepbeep.mp4</option>
									<option value="silent.mp4">silent.mp4</option>
									<option value="videoazansubuhmadinah.mp4">videoazansubuhmadinah.mp4</option>
									<option value="videoazanmadinah.mp4">videoazanmadinah.mp4</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Syuruk :</td>
							<td>
								<select id="fSelectAzanSyuruk" class="easyui-combobox" name="set_azan_syuruk" data-options="
								    width:150,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'
								">													
									<option value="bedukzohor.m4a">bedukzohor.m4a</option>
									<option value="bedukmaghrib.m4a">bedukmaghrib.m4a</option>
									<option value="beduksubuh.m4a">beduksubuh.m4a</option>
									<option value="bedukjumaat.m4a">bedukjumaat.m4a</option>
									<option value="beepbeep.mp4">beepbeep.mp4</option>
									<option value="silent.mp4">silent.mp4</option>
									<option value="videoazansubuhmadinah.mp4">videoazansubuhmadinah.mp4</option>
									<option value="videoazanmadinah.mp4">videoazanmadinah.mp4</option>
								</select>
							</td>
							<td style="width:50px"></td>
							<td>Jumaat :</td>                    
							<td>
								<select id="fSelectAzanJumaat" class="easyui-combobox" name="set_azan_jumaat" data-options="
								    width:150,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'				
								">													
									<option value="bedukzohor.m4a">bedukzohor.m4a</option>
									<option value="bedukmaghrib.m4a">bedukmaghrib.m4a</option>
									<option value="beduksubuh.m4a">beduksubuh.m4a</option>
									<option value="bedukjumaat.m4a">bedukjumaat.m4a</option>
									<option value="beepbeep.mp4">beepbeep.mp4</option>
									<option value="silent.mp4">silent.mp4</option>
									<option value="videoazansubuhmadinah.mp4">videoazansubuhmadinah.mp4</option>
									<option value="videoazanmadinah.mp4">videoazanmadinah.mp4</option>
								</select>
							</td>
						</tr>
					</table>
				</form>
			</p>
			<div style="text-align:center;padding:5px">
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetAzanForm()">Kemaskini</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetAzanForm()">Batal</a>
			</div>
		</div>	
		<!-- END SOUND AZAN -->

		<!-- BLINKING SEBELUM AZAN -->	  
		<div title="<span class='tt-inner'><img src='images/jam.png'/><br>Masa Berkelip</span>" style="padding:10px;">
            <p>
			  <form id="fSetBlinkingAzan" method="post">
					<table cellpadding="5">
						<tr>
							<td>Masa berkelip sebelum azan : </td>
							<td><input class="easyui-textbox" name="set_azan_blinking" data-options="required:true, width:30"> (5) minit</input></td>
						</tr>
					</table>
				</form>
			</p>
			<div style="text-align:center;padding:5px">
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="updateSetBlinkingAzanForm()">Kemaskini</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancelSetAzanForm()">Batal</a>
			</div>
		</div>	
		<!-- END BLINKING SEBELUM AZAN -->

	
		
		
	</div>
</div> 
<!-- END FORM AZAN -->
		
    </div> <!--easyui-layout-->
 
 	
<script type="text/javascript" src="indexjs-5.4.js"></script>
	
	
    <style type="text/css">
        form{
            margin:0;
            padding:0;
        }
        .dv-table td{
            border:0;
        }
        .dv-table input{
            border:1px solid #ccc;
        }
    </style>
	

	
</body>
</html>
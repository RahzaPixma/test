<div id="fFillPilihPaparan">
<strong>Paparan Jadual Kuliah Tetap</strong>
<form  id="uploadFileKuliahTetap"  class="form-style-9" method="post" enctype="multipart/form-data">

<table class="dv-table" style="width:100%;height:auto;background:#fafafa;padding:5px;margin-top:10px;">

<tr>
                    <td>Header:</td>
                    <td><input class="easyui-textbox" type="text" name="header"  style="width:250px" data-options="required:true"></input></td>
					
					<td>Papar :</td>
					<td><input class="easyui-validatebox" type="radio" name="status" value="1" checked="checked" data-options="validType:'requireRadio[\'#ff input[name=show_slide]\', \'Yes or no\']'">Ya
					 <input class="easyui-validatebox" type="radio" name="status" value="0" >Tidak</td>	
					
</tr>

<tr>
<td><span width="50"></span></td>
</tr>	
			
<tr>
<td><span width="50"></span></td>
</tr>	

<tr>
<td>Susunan :</td>
					<td>
							<select id="fSusunan" class="easyui-combobox" name="susunan"  style="width:50px" data-options="
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'					
									">
													
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
							</select>				
					</td>
					 
<td>Batal :</td>
<td><input class="easyui-validatebox" type="radio" name="batal" value="1" checked="checked" data-options="validType:'requireRadio[\'#ff input[name=batal]\', \'Yes or no\']'">Tidak
					 <input class="easyui-validatebox" type="radio" name="batal" value="0" >Ya</td>	

</tr>
<tr>
<td></td>
<td></td>
<td></td>
<td></td>

<tr>
<td>Pilihan:</td>
 <td>                       <select id="fShowSlideKT" class="easyui-combobox" name="show_slide"  style="width:120px" data-options="required:true, 
 
                                                onChange: function(newValue,oldValue)
                                                {
                                                	onChangeFieldPilihanKT(newValue);
                                            	},



									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto'					
									">
													
									<option value=""></option>
									<option value="Data">Data</option>
									<option value="Slide">Slide</option>
						    </select>
</td>
<td></td>
<td></td>	

</tr>
</table>
 
<br>
<div id="fBoxDataKT"><strong>Data</strong></div>
<div id="fFormFillDataKT" style="width: 95%;padding: 10px;border: 2px solid navy;margin: 2px;">

    <table class="dv-table" style="width:100%;background:#fafafa;padding:5px;margin-top:5px;">
	
                <tr>
                      <td>Tajuk:</td>
                  <td><input class="easyui-textbox" type="text" name="tajuk"></input></td>
                    <td>Hari:</td>
                    <td><input id="id_kuliah_hari"  class="easyui-textbox" type="text" name="hari"></input></td>
               </tr>
                <tr>
   					<td>Waktu:</td>                 
                    <td><input class="easyui-textbox" type="text"  name="waktu"></input></td>
                    <td>Penceramah:</td>
                    <td><input class="easyui-textbox" type="text" name="penceramah"></input></td>
                </tr>

                <tr>
                    <td>Tempat:</td>
                    <td><input class="easyui-textbox" type="text" name="tempat"></input></td>
                    <td>Catatan:</td>
                    <td><input class="easyui-textbox" type="text" name="catatan" data-options="multiline:true" style="height:60px"></input></td>
               </tr>
 
               <tr>
                    <td>Filetemplate:</td>
                    <td>
                     <select id="fSelectFiletemplateKT" class="easyui-combobox" name="filetemplate" data-options="
									width: 120,
									valueField: 'id',
									textField: 'text',
									editable:false,
									panelHeight:'auto',									
									onSelect: function(rec){
										onChangeFiletemplateKuliahTetap(rec.text);										
									}
									">
													
                            <option value="pengajian.jpg">pengajian.jpg</option>
                            <option value="kuliah.jpg">kuliah.jpg</option>
                            <option value="umum.jpg">umum.jpg</option>
                            <option value="kelas.jpg">kelas.jpg</option>
                            <option value="ceramah.jpg">ceramah.jpg</option>
                            <option  value="khutbah.jpg">khutbah.jpg</option>
    						</select>
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitViewTemplateFormKuliahTetap()">View</a>
					</td>
                    <td></td>
                    <td></td>
					
                </tr> 
 
    </table>
 
 
</div>

<br>
<div id="fBoxSlideKT"><strong>Slide</strong></div>

<div id="fFormFillSlideKT" style="width: 95%;padding: 10px;border: 2px solid navy;margin: 2px;">

    <table class="dv-table" style="width:80%;background:#fafafa;padding:5px;margin-top:5px;">
 
               <tr>
			        <td>File Slide:</td>
                    <td>                      
					<input id="chooseFileKuliahTetap" class="easyui-filebox" buttonText="Pilih file..." name="file_slide" data-options="prompt:'Upload file...'" style="width:300px">
					<a id="btn_add_kuliah_tetap" href="#" class="easyui-linkbutton" iconCls="icon-save" plain="false">Upload File</a>
					</td>
					<td>
						<div id="id_target_layer_kuliah_tetap" for="NoImage" style="padding:5px 0;align:top;padding-right:30px">No Image</div>
					</td>

                </tr> 
    </table>	

</div>


<br>
   <div style="padding:5px 0;text-align:center;padding-right:30px">
        <a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="false" onclick="saveItemKuliahTetap(<?php echo $_REQUEST['index'];?>)">Save</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="false" onclick="cancelItemKuliahTetap(<?php echo $_REQUEST['index'];?>)">Cancel</a>
    </div>

				
</form>
</div>

<script>



function onChangeFieldPilihanKT(newValue) {

//gblPilihanData = newValue;

//alert(newValue);

            switch (newValue) {
                case 'Data':
					//setTimeout(function(){
						$('#fBoxDataKT').show("slow()");						
						$('#fFormFillDataKT').show("slow()");
						$('#fBoxSlideKT').hide("slow()");
						$('#fFormFillSlideKT').hide("slow()");
//						if($('#fBoxSlideKT').is(":visible"))  $('#fBoxSlideKT').hide("slow()");
//						if($('#fFormFillSlideKT').is(":visible"))  $('#fFormFillSlideKT').hide("slow()");
// 						showMsg2('index='+gblIndex + ',select=' + newValue); 
					//},0);		
//					alert('data');		
					break;
				 
                case 'Slide':
					//setTimeout(function(){
//						if($('#fBoxDataKT').is(":visible")) $('#fBoxDataKT').hide("slow()");
//						if($('#fFormFillDataKT').is(":visible")) $('#fFormFillDataKT').hide("slow()");
						$('#fBoxDataKT').hide("slow()");
						$('#fFormFillDataKT').hide("slow()");
						$('#fBoxSlideKT').show("slow()");
						$('#fFormFillSlideKT').show("slow()");
//						 showMsg2('index='+gblIndex + ',select=' + newValue); 
					//},0);				
//					alert('slide');
					break;

            }  
//      alert(newValue);
//      alert('index='+gblIndex + ',select=' + newValue);

}
		

//	$('#w22').window('close');	
	
//	$('#fSelectFiletemplateKT').ddslick();

//	$('#fShowSlide').ddslick();



//});


$(document).ready(function(){


//index = <?php //echo $_GET['index'];?>;			

     $('#dg_kuliahtetap').datagrid('getRowDetail',gblIndex).find('form').form({
        url:'upload_kuliah_tetap.php',
        ajax: true,
 
		success: function(data){
                //alert('succes');
				$('#id_target_layer_kuliah_tetap').html(data);
				//showMsg2('Success');
        },
        onLoadError: function(data){
				showMsg2(data);
        }
		
    });


    $('#btn_add_kuliah_tetap').bind('click', function(){
        if($('#chooseFileKuliahTetap').filebox('getValue')!="") { 
            $('#uploadFileKuliahTetap').submit(); 
        }
    });

    //gblPilihanDataKT = '<?php //echo $_GET['show_slide'];?>';	 
//alert(gblPilihanDataKT);
onChangeFieldPilihanKT(gblPilihanData);

});     


/*
$(window).load(function () {
    onChangeFieldPilihanKT(gblPilihanData);
});
*/

</script>
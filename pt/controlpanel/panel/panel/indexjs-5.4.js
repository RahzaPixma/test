
$(function(){
	
	$('#todaysDate').numberbox('textbox').css('font-size','15px');
	$('#todaysDate').numberbox('textbox').css('font-weight','bold');
	$('#todaysDate').numberbox('textbox').css('text-align','center');
 	//utk time pc/hp
	setInterval(doDate, 1000);
	
 /*   $('#id_kuliah_tarikh').datebox({
        onChange: function(newValue, oldValue) {
           	alert('onchange fired...textbox, newValue:'+newValue+";oldValue:"+oldValue);
			$('#id_kuliah_hari').textbox('initValue',oldValue);
        }
 */
	
});

	var gblSelect;
	var gblSelectKT;
	var gblProgressStatus;
	var gblPilihanData;
	var gblIndex;
	var gblTotalDataKuliah=0;
	var gblMula2Reload=1;


	
		$.extend($.fn.validatebox.defaults.rules, {
			minLength: {
				validator: function(value, param){
					return value.length >= param[0];
				},
				message: 'Please enter at least {0} characters.'
			},
			
		   maxLength: {
				validator: function(value, param){
					return value.length <= param[0];
				},
				message: 'Please enter less than {0} characters.'
			}	
		});
		
        $(function(){
      						

//kuliah berjadual
			$('#dg').datagrid({
                view: detailview,
                detailFormatter:function(index,row){
  //                  return '<div class="ddv"></div>';
					  return '<div class="ddv" style="padding:5px 0"></div>';
                },
                onExpandRow: function(index,row){
                    var ddv = $(this).datagrid('getRowDetail',index).find('div.ddv');
                    ddv.panel({
                        border:false,
                        cache:true,
                        href:'show_form_kuliah.php?index='+index,
                        onLoad:function(){
                            $('#dg').datagrid('fixDetailRowHeight',index);
                            $('#dg').datagrid('selectRow',index);
                            $('#dg').datagrid('getRowDetail',index).find('form').form('load',row);							

							//trigger preset slide/data
//							alert(row.show_slide);
                    }

                    });
 
                   $('#dg').datagrid('fixDetailRowHeight',index);
//					alert(row.show_slide);
//							onChangeFieldPilihan(row.show_slide);    

							gblSelect =  row.filetemplate;
							gblPilihanData =  row.show_slide;		
							gblIndex = index;

					var rows = $('#dg').datagrid('getRows');
					gblTotalDataKuliah = rows.length;
					var h=0;
					if( gblTotalDataKuliah < 6 ) h = 1000;
					else if(gblTotalDataKuliah > 10) h = (gblTotalDataKuliah*20)+1050;
						 else h = (gblTotalDataKuliah*20)+1000;
					//succ
					$('#panel_paling_luar').layout('resize', {
						height:h
					});						


               },

 /*              onCollapseRow: function(index,row){
                    //onChangeFieldPilihan(row.show_slide);
                    $(this).datagrid('reload');
               }
*/

 	onCollapseRow: function(index,row){
			if(gblMula2Reload==0) {
				var rows = $('#dg').datagrid('getRows');
				gblTotalDataKuliah=rows.length;
				var h=0;
				if(gblTotalDataKuliah > 10) h = 870;
				else h = 800;
				//succ				
				$('#panel_paling_luar').layout('resize', {
					height:h
				});		
				
				//showMsg2('tutup balik');
				
			}										 
	},

	onLoadSuccess: function(index,row) {	
//			showMsg2('lps reload data');	
			if(gblMula2Reload==0) {
				var rows = $('#dg').datagrid('getRows');
				gblTotalDataKuliah=rows.length;
				var h=0;
				if(gblTotalDataKuliah > 10) h = 870;
				else h = 800;
				//succ				
				$('#panel_paling_luar').layout('resize', {
					height:h
				});		
//				showMsg2(h);
			}				
		
	}	
    

				
            });
			
			
		
//kuliah tetap		     						
			$('#dg_kuliahtetap').datagrid({
                view: detailview,
               detailFormatter:function(index,row){
					  return '<div class="ddv" style="padding:5px 0"></div>';
               },

	onCollapseRow: function(index,row){
			if(gblMula2Reload==0) {
				var rows = $('#dg_kuliahtetap').datagrid('getRows');
				gblTotalDataKuliah=rows.length;
				var h=0;
				if(gblTotalDataKuliah > 10) h = 870;
				else h = 800;
				//succ				
				$('#panel_paling_luar').layout('resize', {
					height:h
				});		
				
				//showMsg2('tutup balik');
				
			}										 
	},

	onLoadSuccess: function(index,row) {	
//			showMsg2('lps reload data');	
			if(gblMula2Reload==0) {
				var rows = $('#dg_kuliahtetap').datagrid('getRows');
				gblTotalDataKuliah=rows.length;
				var h=0;
				if(gblTotalDataKuliah > 10) h = 870;
				else h = 800;
				//succ				
				$('#panel_paling_luar').layout('resize', {
					height:h
				});		
//				showMsg2(h);
			}				
		
	},	

                onExpandRow: function(index,row){
                    var ddv = $(this).datagrid('getRowDetail',index).find('div.ddv');
                    ddv.panel({
                        border:false,
                        cache:true,
                        href:'show_form_kuliah_tetap.php?index='+index,
                        onLoad:function(){
                            $('#dg_kuliahtetap').datagrid('fixDetailRowHeight',index);
                            $('#dg_kuliahtetap').datagrid('selectRow',index);
                            $('#dg_kuliahtetap').datagrid('getRowDetail',index).find('form').form('load',row);							

							//trigger preset slide/data
//							onChangeFieldPilihanKT(row.show_slide);   

                         }
                    });
                    $('#dg_kuliahtetap').datagrid('fixDetailRowHeight',index);

//							alert(row.show_slide);
//							onChangeFieldPilihanKT(row.show_slide);    
							gblPilihanData =  row.show_slide;		
							gblSelectKT =  row.filetemplate;
							gblIndex = index;
 //                           onChangeFieldPilihanKT(row.show_slide);  

					var rows = $('#dg_kuliahtetap').datagrid('getRows');
					gblTotalDataKuliah = rows.length;
					var h=0;
					if( gblTotalDataKuliah < 6 ) h = 1000;
					else if(gblTotalDataKuliah > 10) h = (gblTotalDataKuliah*20)+1050;
						 else h = (gblTotalDataKuliah*20)+1000;
					//succ
					$('#panel_paling_luar').layout('resize', {
						height:h
					});						

               }

/*               onCollapseRow: function(index,row){
                    //onChangeFieldPilihan(row.show_slide);
                    //alert(row.show_slide);
                    $(this).datagrid('reload');
               }
*/
                				
            });
								

//countdown									
			$('#dg_countdown').datagrid({
                view: detailview,
                detailFormatter:function(index,row){
  //                  return '<div class="ddv"></div>';
					  return '<div class="ddv" style="padding:5px 0"></div>';
                },

 	onCollapseRow: function(index,row){
			if(gblMula2Reload==0) {
				var rows = $('#dg_countdown').datagrid('getRows');
				gblTotalDataKuliah=rows.length;
				var h=0;
				if(gblTotalDataKuliah > 10) h = 770;
				else h = 600;
				//succ				
				$('#panel_paling_luar').layout('resize', {
					height:h
				});		
//				showMsg2(h);
			}				
	},

	onLoadSuccess: function(index,row) {	
//			showMsg2('lps reload data');	
			if(gblMula2Reload==0) {
				var rows = $('#dg_countdown').datagrid('getRows');
				gblTotalDataKuliah=rows.length;
				var h=0;
				if(gblTotalDataKuliah > 10) h = 770;
				else h = 600;
				//succ				
				$('#panel_paling_luar').layout('resize', {
					height:h
				});		
//				showMsg2(h);
			}				
		
	},	
    
                onExpandRow: function(index,row){
                    var ddv = $(this).datagrid('getRowDetail',index).find('div.ddv');
                    ddv.panel({
                        border:false,
                        cache:true,
                        href:'show_form_countdown.php?index='+index,
                        onLoad:function(){
                            $('#dg_countdown').datagrid('fixDetailRowHeight',index);
                            $('#dg_countdown').datagrid('selectRow',index);
                            $('#dg_countdown').datagrid('getRowDetail',index).find('form').form('load',row);
                         }
                    });
                    $('#dg_countdown').datagrid('fixDetailRowHeight',index);

					var rows = $('#dg_countdown').datagrid('getRows');
					gblTotalDataKuliah = rows.length;
					var h=0;
					if( gblTotalDataKuliah < 6 ) h = 600;
					else if(gblTotalDataKuliah > 10) h = (gblTotalDataKuliah*20)+600;
						 else h = (gblTotalDataKuliah*20)+600;
					//succ
					$('#panel_paling_luar').layout('resize', {
						height:h
					});						

                }
				
            });			
			
        });
		
        function saveItemCountdown(index){
 //alert('save cnt');      	
            var row = $('#dg_countdown').datagrid('getRows')[index];
            var url = row.isNewRecord ? 'save_countdown.php' : 'update_countdown.php?id='+row.id;
//  				showMsg2(url);
           $('#dg_countdown').datagrid('getRowDetail',index).find('form').form('submit',{
                url: url,
                onSubmit: function(){
//					showMsg2(url);
                    return $(this).form('validate');
                },
                success: function(data){
//				alert(data);
                    data = eval('('+data+')');
                    data.isNewRecord = false;
                    $('#dg_countdown').datagrid('collapseRow',index);
                    $('#dg_countdown').datagrid('updateRow',{
                        index: index,
                        row: data
                    });
					showMsg();
                }

            });
        }
        function cancelItemCountdown(index){
            var row = $('#dg_countdown').datagrid('getRows')[index];
            if (row.isNewRecord){
                $('#dg_countdown').datagrid('deleteRow',index);
            } else {
                $('#dg_countdown').datagrid('collapseRow',index);
            }
        }
        function destroyItemCountdown(){
            var row = $('#dg_countdown').datagrid('getSelected');
            if (row){
                $.messager.confirm('Confirm','Anda pasti untuk delete item untuk jadual ini?',function(r){
                    if (r){
                        var index = $('#dg_countdown').datagrid('getRowIndex',row);
                        $.post('destroy_countdown.php',{id:row.id},function(){
                            $('#dg_countdown').datagrid('deleteRow',index);
                        });
                    }
                });
            }
        }
        function newItemCountdown(){
            $('#dg_countdown').datagrid('appendRow',{isNewRecord:true});
            var index = $('#dg_countdown').datagrid('getRows').length - 1;
            $('#dg_countdown').datagrid('expandRow', index);
            $('#dg_countdown').datagrid('selectRow', index);
		}

		
//kuliah tetap

        function newItemKuliahTetap(){
            $('#dg_kuliahtetap').datagrid('appendRow',{isNewRecord:true});
            var index = $('#dg_kuliahtetap').datagrid('getRows').length - 1;
            $('#dg_kuliahtetap').datagrid('expandRow', index);
            $('#dg_kuliahtetap').datagrid('selectRow', index);

            gblIndex = index;
  //          gblPilihanData = 'Data';  sbb dh ada sila pilih
		}

        function saveItemKuliahTetap(index){

            var row = $('#dg_kuliahtetap').datagrid('getRows')[index];
            var url = row.isNewRecord ? 'save_kuliah_tetap.php' : 'update_kuliah_tetap.php?id='+row.id;
//  				showMsg2(url);
           $('#dg_kuliahtetap').datagrid('getRowDetail',index).find('form').form('submit',{
                url: url,
                onSubmit: function(){
//					showMsg2(url);
                    return $(this).form('validate');
                },
                success: function(data){
//				alert(data);
                    data = eval('('+data+')');
                    data.isNewRecord = false;
                    $('#dg_kuliahtetap').datagrid('collapseRow',index);
                    $('#dg_kuliahtetap').datagrid('updateRow',{
                        index: index,
                        row: data
                    });
					showMsg();
                },
               error: function(data){
//				alert(data);
					showMsg2(data);
                }
				
            });


        }
		
		
        function cancelItemKuliahTetap(index){


            var row = $('#dg_kuliahtetap').datagrid('getRows')[index];
            if (row.isNewRecord){
                $('#dg_kuliahtetap').datagrid('deleteRow',index);
            } else {
                $('#dg_kuliahtetap').datagrid('collapseRow',index);
            }
        }		


       function destroyItemKuliahTetap(){
            var row = $('#dg_kuliahtetap').datagrid('getSelected');
            if (row){
                $.messager.confirm('Confirm','Anda pasti untuk delete item untuk jadual ini?',function(r){
                    if (r){
                        var index = $('#dg_kuliahtetap').datagrid('getRowIndex',row);
                        $.post('destroy_kuliah_tetap.php',{id:row.id},function(){
                            $('#dg_kuliahtetap').datagrid('deleteRow',index);
                        });
                    }
                });
            }
        }

		function submitViewTemplateFormKuliahTetap() {
//			showMsg2(gblSelect);
			if( gblSelectKT == null) gblSelectKT = "pengajian.jpg";
			$("#w").html("<img src = /pt/templateslideskt/templates/" + gblSelectKT + " style=\"max-width:100%; max-height:100%;\"><div><a class=\"easyui-linkbutton\" data-options=\"iconCls:'icon-ok'\" href=\"javascript:void(0)\" onclick=\"$('#w').window('close')\" style=\"width:80px\">Close</a></div>");
			$('#w').window('open');
		}
 
		
		function onChangeFiletemplateKuliahTetap(val) {
			gblSelectKT = val;
//showMsg2(val);
		}
 


//kuliah berjadual		
        function newItem(){
            $('#dg').datagrid('appendRow',{isNewRecord:true});
            var index = $('#dg').datagrid('getRows').length - 1;
            $('#dg').datagrid('expandRow', index);
            $('#dg').datagrid('selectRow', index);

            gblIndex = index;
 //           gblPilihanData = 'Data'; sbb dh ada sila pilih
           
		}

        function saveItem(index){

            var row = $('#dg').datagrid('getRows')[index];
            var url = row.isNewRecord ? 'save_kuliah.php' : 'update_kuliah.php?id='+row.id;
//  				showMsg2(url);
           $('#dg').datagrid('getRowDetail',index).find('form').form('submit',{
                url: url,
                onSubmit: function(){
//					showMsg2(url);
                    return $(this).form('validate');
                },
                success: function(data){
	//			alert(data);
                    data = eval('('+data+')');
                    data.isNewRecord = false;
                    $('#dg').datagrid('collapseRow',index);
                    $('#dg').datagrid('updateRow',{
                        index: index,
                        row: data
                    });
					showMsg();
                },
               error: function(data){
	//			alert(data);
					showMsg2(data);
                }
				
            });


        }
		
		
        function cancelItem(index){


            var row = $('#dg').datagrid('getRows')[index];
            if (row.isNewRecord){
                $('#dg').datagrid('deleteRow',index);
            } else {
                $('#dg').datagrid('collapseRow',index);
            }
        }	

        function destroyItem(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $.messager.confirm('Confirm','Anda pasti untuk delete item untuk jadual ini?',function(r){
                    if (r){
                        var index = $('#dg').datagrid('getRowIndex',row);
                        $.post('destroy_kuliah.php',{id:row.id},function(){
                            $('#dg').datagrid('deleteRow',index);
                        });
                    }
                });
            }
        }
       	
		function submitViewTemplateForm() {
//			showMsg2(gblSelect);
			if( gblSelect == null) gblSelect = "pengajian.jpg";
			$("#w").html("<img src = /pt/templateslides/templates/" + gblSelect + " style=\"max-width:100%; max-height:100%;\"><div><a class=\"easyui-linkbutton\" data-options=\"iconCls:'icon-ok'\" href=\"javascript:void(0)\" onclick=\"$('#w').window('close')\" style=\"width:80px\">Close</a></div>");
			$('#w').window('open');
		}
 
		
		function onChangeFiletemplate(val) {
			gblSelect = val;
//showMsg2(val);
		}
 		
		
/*****Dah ada direct dlm show_form_kuliah*************
        function uploadFile(index){
           $('#dg').datagrid('getRowDetail',index).find('form').form('submit',{
                url: 'upload.php',
				type: 'POST',
				data:  new FormData(this),
				contentType: false,
				cache: false,
				processData:false,
				enctype:'multipart/form-data',
				success: function(data){
				$('#id_target_layer').html(data);
//				showMsg2('Success');
				},				
				error: function(data) 
							{
								showMsg2(data);
							} 	  
			});
		}
***************************************************/
		
//fUploadTaqwim

        function uploadFileTaqwim(){

//alert('uploadFileTaqwim');

gblProgressStatus=0;
$('#id_target_layer_uploadtaqwim').html('<h3 style="color:blue;">Sedang upload data, sila tunggu...</h3>');
startProgress();

    $('#fUploadTaqwim').form('submit', {
        url:'update_taqwim.php',
        ajax: true,
        iframe: false, // pour activer le onProgress
        onProgress: function(percent){
            // pendant l'envoi du fichier
            $('#progressFile').progressbar('setValue', percent);     
        },

        success: function(data){

		gblProgressStatus=1;

                // apres l'envoi du fichier en cas de succes
		$('#id_target_layer_uploadtaqwim').html(data);    

                //alert('succes ' + data);
        },
        onLoadError: function(){
                // apres l'envoi du fichier en cas d'erreur
		//alert('error ' +data);
		$('#id_target_layer_uploadtaqwim').html(data); 
        }
       });
    }

        function startProgress(){
            var value = $('#progressFile').progressbar('getValue');
            if (value < 100){
                value += Math.floor(Math.random() * 10);
                $('#progressFile').progressbar('setValue', value);
                setTimeout(arguments.callee, 1200);
            }

			if (gblProgressStatus == 1) {
				$('#progressFile').progressbar('setValue', 100);
			} 
        };
							
							

	  function cancelUploadTaqwim() {
		$('#id_target_layer_uploadtaqwim').html(''); 
		$('#pFormGeneral').panel('close');
 	 }
		
 

        function saveItem2(index){
          $.messager.show({
                title:'IIM Panel',
                msg:'Data telah berjaya disimpan' + index,
                timeout:3000,
                showType:'show'
            });		
		}
		
//message
        function showMsg(){
            $.messager.show({
                title:'IIM Panel',
                msg:'Data telah berjaya disimpan',
                timeout:3000,
                showType:'show'
            });
		}
		
       function showMsg2(varmsg){
            $.messager.show({
                title:'IIM Panel',
                msg:varmsg,
                timeout:3000,
                showType:'show'
            });		
		}
		
	
//panel controller		
		function setupPanels() {
			$('#w').window('close');	
			$('#pFormGeneral').panel('close');
			$('#pFormLokasi').panel('close');
			$('#pFormDatetime').panel('close');
			$('#pFormCountdown').panel('close');			
			$('#pFormScroller').panel('close');
			$('#pFormKuliah').panel('close');			
			$('#pFormIqomah').panel('close');			
			$('#pFormSolat').panel('close');	
			$('#pFormAzan').panel('close');	
		}
		
		function openPanelGeneral() {
//succ
$('#panel_paling_luar').layout('resize', {
	height:580
});						

			$('#pFormGeneral').panel('open');			
			$('#pFormDatetime').panel('close');
			$('#pFormCountdown').panel('close');			
			$('#pFormScroller').panel('close');
			$('#pFormKuliah').panel('close');			
			$('#pFormIqomah').panel('close');	
			$('#pFormSolat').panel('close');	
			$('#pFormAzan').panel('close');	
			loadFormDataMazhab();
			loadFormDataDuration();
			loadFormDataScroller();
			loadFormDataSetDatetime();
			loadFormDataSetHijri();
			loadFormDataZone();			
			loadFormDataAnim();
			loadFormDataDatetimeSleep();
			loadFormDataSetAutoreboot(); //baru tambah
		}

		function openPanelKuliah() {
gblMula2Reload=0;		
$('#dg').datagrid('load');
$('#dg_kuliahtetap').datagrid('load');


			$('#pFormGeneral').panel('close');
			//$('#pFormDuration').panel('close');			
			$('#pFormDatetime').panel('close');
			$('#pFormCountdown').panel('close');			
			$('#pFormScroller').panel('close');
			$('#pFormKuliah').panel('open');			
			$('#pFormIqomah').panel('close');			
			$('#pFormSolat').panel('close');	
			$('#pFormAzan').panel('close');	
		}

		function openPanelCountdown() {
gblMula2Reload=0;		
$('#dg_countdown').datagrid('load');

			$('#pFormGeneral').panel('close');
			//$('#pFormDuration').panel('close');			
			$('#pFormDatetime').panel('close');
			$('#pFormCountdown').panel('open');			
			$('#pFormScroller').panel('close');
			$('#pFormKuliah').panel('close');			
			$('#pFormSolat').panel('close');	
			$('#pFormIqomah').panel('close');			
			$('#pFormAzan').panel('close');	
		}

		function openPanelAzan() {
//succ
$('#panel_paling_luar').layout('resize', {
	height:550
});						

			$('#pFormGeneral').panel('close');
			//$('#pFormDuration').panel('close');			
			$('#pFormDatetime').panel('close');
			$('#pFormCountdown').panel('close');			
			$('#pFormScroller').panel('close');
			$('#pFormAzan').panel('open');			
			$('#pFormIqomah').panel('close');			
			$('#pFormSolat').panel('close');
		    $('#pFormKuliah').panel('close');	
			loadFormDataAzan();	
			loadFormDataBlinkingAzan();	
		}
		
		function openPanelIqomah() {
//succ
$('#panel_paling_luar').layout('resize', {
	height:550
});						

			$('#pFormGeneral').panel('close');
			//$('#pFormDuration').panel('close');			
			$('#pFormDatetime').panel('close');
			$('#pFormCountdown').panel('close');			
			$('#pFormScroller').panel('close');
			$('#pFormKuliah').panel('close');			
			$('#pFormSolat').panel('close');	
			$('#pFormIqomah').panel('open');	
			$('#pFormAzan').panel('close');	
			loadFormDataIqomah('JUMAAT');			
			
			var tabs = $('#tTabIqomah').tabs().tabs('tabs');
            for(var i=0; i<tabs.length; i++){
                tabs[i].panel('options').tab.unbind().bind('click.tabs',{index:i},function(e){
                    $('#tTabIqomah').tabs('select', e.data.index);
					switch (e.data.index) {
						case 0 :
							loadFormDataIqomah('JUMAAT');
							break;
						case 1 :
							loadFormDataIqomah('SABTU');
							break;
						case 2 :
							loadFormDataIqomah('AHAD');
							break;
						case 3 :
							loadFormDataIqomah('ISNIN');
							break;
						case 4 :
							loadFormDataIqomah('SELASA');
							break;
						case 5 :
							loadFormDataIqomah('RABU');
							break;
						case 6 :
							loadFormDataIqomah('KHAMIS');
							break;
					}	
                });
            }
 
		}

		
		function openPanelSolat() {
//succ
$('#panel_paling_luar').layout('resize', {
	height:550
});						

			$('#pFormGeneral').panel('close');
			//$('#pFormDuration').panel('close');			
			$('#pFormDatetime').panel('close');
			$('#pFormCountdown').panel('close');			
			$('#pFormScroller').panel('close');
			$('#pFormKuliah').panel('close');			
			$('#pFormIqomah').panel('close');	
			$('#pFormSolat').panel('open');	
			$('#pFormAzan').panel('close');	
			loadFormDataSolat('JUMAAT');			
			
			var tabs = $('#tTabSolat').tabs().tabs('tabs');
            for(var i=0; i<tabs.length; i++){
                tabs[i].panel('options').tab.unbind().bind('click.tabs',{index:i},function(e){
                    $('#tTabSolat').tabs('select', e.data.index);
					switch (e.data.index) {
						case 0 :
							loadFormDataSolat('JUMAAT');
							break;
						case 1 :
							loadFormDataSolat('SABTU');
							break;
						case 2 :
							loadFormDataSolat('AHAD');
							break;
						case 3 :
							loadFormDataSolat('ISNIN');
							break;
						case 4 :
							loadFormDataSolat('SELASA');
							break;
						case 5 :
							loadFormDataSolat('RABU');
							break;
						case 6 :
							loadFormDataSolat('KHAMIS');
							break;
					}	
                });
            }
 
		}
		
		
		
		
//form controller

//set Iqomah
		function updateSetIqomahForm(hari) {
 			$('#fSetIqomah' + hari).form('submit',{
					url:'update_form_data_iqomah.php?hari=' + hari,
					onSubmit:function(){
						return $(this).form('validate');
					},
					success:function(data){
//						$.messager.alert('Info', data, 'info');
						showMsg2('Data telah berjaya dikemaskini');
					}	  				
			});

//			$('#pFormIqomah').panel('close');
        }
		
		function loadFormDataIqomah(hari) {
          $('#fSetIqomah' + hari).form('load', 'form_data_iqomah.php?hari=' + hari);
        }
 
		function cancelSetIqomahForm() {
          $('#pFormIqomah').panel('close');
        }
  

//set Solat
		function updateSetSolatForm(hari) {
 			$('#fSetSolat' + hari).form('submit',{
					url:'update_form_data_solat.php?hari=' + hari,
					onSubmit:function(){
						return $(this).form('validate');
					},
					success:function(data){
//						$.messager.alert('Info', data, 'info');
						showMsg2('Data telah berjaya dikemaskini');
					}	  				
			});

//			$('#pFormSolat').panel('close');
        }
		
		function loadFormDataSolat(hari) {
          $('#fSetSolat' + hari).form('load', 'form_data_solat.php?hari=' + hari);
        }
 		
		function cancelSetSolatForm() {
          $('#pFormSolat').panel('close');
        }
 
//set datetime manual
      function updateSetDatetimeForm(){	 

			$('#fSetDatetime').form('submit',{
					url:'update_setdatetime.php',
					onSubmit:function(){
						return $(this).form('validate');
					},
					success:function(data){
//						$.messager.alert('Info', data, 'info');
						showMsg2('Telah berjaya dikemaskini <br>' + data);
					}	  				
			});

			$('#pFormGeneral').panel('close');
			
		}
		
//set datetime pc/hp
      function updateSetDatetimePCHP(){	 

			$('#fSetDatetime').form('submit',{
					url:'update_setdatetime_pchp.php',
					onSubmit:function(){
						return $(this).form('validate');
					},
					success:function(data){
//						$.messager.alert('Info', data, 'info');
						showMsg2('Telah berjaya dikemaskini <br>' + data);
					}	  				
			});

			$('#pFormGeneral').panel('close');
			
		}	

//set datetime autosync
      function updateSetDatetimeAutosync(){	 

			$('#fSetDatetime').form('submit',{
					url:'update_setdatetime_autosync.php',
					onSubmit:function(){
						return $(this).form('validate');
					},
					success:function(data){
//						$.messager.alert('Info', data, 'info');
						showMsg2('Telah berjaya dikemaskini <br>' + data);
					}	  				
			});

			$('#pFormGeneral').panel('close');
			
		}
/*
	function loadFormDataDatetimeAutosync() {
          $('#fSetDateTime').form('load', 'form_data_datetime_autosync.php');
alert(data);
        }		
*/	


//set datetime autoreboot
      function updateSetAutoreboot(){	 

			$('#fSetDatetime').form('submit',{
					url:'update_autoreboot.php',
					onSubmit:function(){
						return $(this).form('validate');
					},
					success:function(data){
//						$.messager.alert('Info', data, 'info');
						showMsg2('Telah berjaya dikemaskini <br>' + data);
					}	  				
			});

			$('#pFormGeneral').panel('close');
			
		}
	
	
		
        function cancelSetDatetimeForm(){
			$('#pFormGeneral').panel('close');
        }
		
	function loadFormDataSetDatetime() {
          $('#fSetDatetime').form('load', 'form_data_setdatetime.php');
        }

	function loadFormDataSetAutoreboot() {
          $('#fSetDatetime').form('load', 'form_data_setautoreboot.php');
        }

 				

//set hijri
      function updateSetHijriForm(){	 

			$('#fSetHijri').form('submit',{
					url:'update_sethijri.php',
					onSubmit:function(){
						return $(this).form('validate');
					},
					success:function(data){
//						$.messager.alert('Info', data, 'info');
						showMsg2('Telah berjaya dikemaskini <br>' + data);
					}	  				
			});

			$('#pFormGeneral').panel('close');
			
		}
        function cancelSetHijriForm(){
			$('#pFormGeneral').panel('close');
        }
		
	function loadFormDataSetHijri() {
          $('#fSetHijri').form('load', 'form_data_sethijri.php');
        }

				
        function myformatter(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
 //           return (d<10?('0'+d):d) + '-' + (m<10?('0'+m):m) + '-' + y;
			return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
        }
		
        function myparser(s){
            if (!s) return new Date();
            var ss = (s.split('-'));
            var y = parseInt(ss[0],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[2],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(y,m-1,d);
            } else {
                return new Date();
            }
        }		
		
		
//set Duration (Selangmasa)
      function updateSetDurationForm(){	 

			$('#fSetDuration').form('submit',{
					url:'update_setduration.php',
					onSubmit:function(){
						return $(this).form('validate');
					},
					success:function(data){
//						$.messager.alert('Info', data, 'info');
						showMsg2('Telah berjaya dikemaskini <br>' + data);
					}	  				
			});

			$('#pFormGeneral').panel('close');
			
		}
        function cancelSetDurationForm(){
			$('#pFormGeneral').panel('close');
        }
		
 	function loadFormDataDuration() {
          $('#fSetDuration').form('load', 'form_data_duration.php');
        }
 
		
//set Mazhab
      function updateSetMazhabForm(){	 

			$('#fSetMazhab').form('submit',{
					url:'update_mazhab.php',
					onSubmit:function(){
						return $(this).form('validate');
					},
					success:function(data){
//						$.messager.alert('Info', data, 'info');
						showMsg2('Telah berjaya dikemaskini<br>' + data);
					}	  				
			});

			$('#pFormGeneral').panel('close');			
		}
		
	function loadFormDataMazhab() {
          $('#fSetMazhab').form('load', 'form_data_mazhab.php');
        }
 
		function cancelSetMazhabForm(){
			$('#pFormGeneral').panel('close');
        }
		
		
//set Zone
      function updateSetZoneForm(){	 

			$('#fSetZone').form('submit',{
					url:'update_zone.php',
					onSubmit:function(){
						return $(this).form('validate');
					},
					success:function(data){
//						$.messager.alert('Info', data, 'info');
						showMsg2('Telah berjaya dikemaskini<br>' + data);
					}	  				
			});

			$('#pFormGeneral').panel('close');			
		}
		
		function loadFormDataZone() {
          $('#fSetZone').form('load', 'form_data_zone.php');
        }
 
		function cancelSetZoneForm(){
			$('#pFormGeneral').panel('close');
        }
				

//set Anim
      function updateSetAnimForm(){	 

			$('#fSetAnim').form('submit',{
					url:'update_anim.php',
					onSubmit:function(){
						return $(this).form('validate');
					},
					success:function(data){
//						$.messager.alert('Info', data, 'info');
						showMsg2('Telah berjaya dikemaskini<br>' + data);
					}	  				
			});

			$('#pFormGeneral').panel('close');			
		}
		
		function loadFormDataAnim() {
          $('#fSetAnim').form('load', 'form_data_anim.php');
        }
 
		function cancelSetAnimForm(){
			$('#pFormGeneral').panel('close');
        }


//set background
      function updateSetBackground(){	 

			$('#fSetBackground').form('submit',{
					url:'upload_background.php',
					onSubmit:function(){
						return $(this).form('validate');
					},
					success:function(data){
//						$.messager.alert('Info', data, 'info');
//				alert(data);

						showMsg2(data);
					},

              		error: function(data){
//				alert(data);
						showMsg2(data);
                	}

			});

			$('#pFormGeneral').panel('close');			
		}
		
		function cancelSetBackground(){
			$('#pFormGeneral').panel('close');
        }




//set datetime sleep
      function updateSetDatetimeSleep(){

			$('#fSleepMode').form('submit',{
					url:'update_datetime_sleep.php',
					onSubmit:function(){
						return $(this).form('validate');
					},
					success:function(data){
//						$.messager.alert('Info', data, 'info');
						showMsg2('Telah berjaya dikemaskini<br>' + data);
					}	  				
			});

			$('#pFormGeneral').panel('close');			
		}
		

	function loadFormDataDatetimeSleep() {
          $('#fSleepMode').form('load', 'form_data_datetime_sleep.php');
        }
 
		function cancelSetDatetimeSleep(){
			$('#pFormGeneral').panel('close');
        }


				
//set Scroller
      function updateSetScrollerForm(){	 

			$('#fSetScroller').form('submit',{
					url:'update_setscroller.php',
					onSubmit:function(){
						return $(this).form('validate');
					},
					success:function(data){
//						$.messager.alert('Info', data, 'info');
						showMsg2('Data telah berjaya dikemaskini');
					}	  				
			});

			$('#pFormGeneral').panel('close');			
		}
        function cancelSetScrollerForm(){
			$('#pFormGeneral').panel('close');
        }
		function loadFormDataScroller() {
          $('#fSetScroller').form('load', 'form_data_scroller.php');
        }
 
 //set azan
      function updateSetAzanForm(){	 

			$('#fSetAzan').form('submit',{
					url:'update_setazan.php',
					onSubmit:function(){
						return $(this).form('validate');
					},
					success:function(data){
//						$.messager.alert('Info', data, 'info');
						showMsg2('Telah berjaya dikemaskini<br>' + data);
					}	  				
			});

			$('#pFormAzan').panel('close');			
		}
		
		function loadFormDataAzan() {
          $('#fSetAzan').form('load', 'form_data_setazan.php');
        }
 
		function cancelSetAzanForm(){
			$('#pFormAzan').panel('close');
        }
				
 //set blinking azan
      function updateSetBlinkingAzanForm(){	 

			$('#fSetBlinkingAzan').form('submit',{
					url:'update_setblinkingazan.php',
					onSubmit:function(){
						return $(this).form('validate');
					},
					success:function(data){
//						$.messager.alert('Info', data, 'info');
						showMsg2('Telah berjaya dikemaskini<br>' + data);
					}	  				
			});

			$('#pFormAzan').panel('close');			
		}
		
		function loadFormDataBlinkingAzan() {
          $('#fSetBlinkingAzan').form('load', 'form_data_setblinkingazan.php');
        }
 
		function cancelSetBlinkingAzanForm(){
			$('#fSetBlinkingAzan').panel('close');
        }
				
 
 //reboot
         function clickReboot(){
            $.messager.confirm('Confirmation Reboot', 'Adakan anda pasti untuk reboot?', function(r){
                if (r){
    //                alert('confirmed: '+r);
					//execute command reboot
				    $('#w').form('load', 'reboot.php');
                }
            });
        }
 
  //refresh
         function clickRefresh(){
		    $('#w').form('load', 'refresh.php');
			showMsg2("Slide yang baru telah berjaya diaktifkan");
        }
		

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}

function doDate()
{
    var str = "";

    var now = new Date();
	
	var sdate = formatDate(now);
	var stime = now.toTimeString().split(' ')[0];
	
   // $('#todaysDate').value = sdate + " " + stime;
   $('#todaysDate').textbox('initValue',sdate + " " + stime);

}



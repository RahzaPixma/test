<form method="post">
<div style="width: 95%;padding: 10px;border: 2px solid #5DADE2;margin: 2px;">

					 <br/>

    <table class="dv-table" style="width:100%;padding:5px;margin-top:5px;">
               <tr>
                    <td>Event:</td>
                    <td style="height:40px"><input class="easyui-textbox" type="text" name="event" data-options="required:true, validType:'maxLength[30]'" style="width:200px">Max 30 Chars</input></td>
		    <td>Tarikh:</td>
                    <td><input class="easyui-datebox" name="tarikh" data-options="required:true, formatter:myformatter,parser:myparser"></input></td>
              </tr>

                 <tr>
                    <td>Papar:</td>
 		    <td><input class="easyui-validatebox" type="radio" name="status" value="1" checked="true" data-options="validType:'requireRadio[\'#ff input[name=status]\', \'Yes or no\']'">Ya
					 <input class="easyui-validatebox" type="radio" name="status" value="0">Tidak</td>	
                    <td>Auto Hide:</td>
 					 <td><input class="easyui-textbox" type="text" name="autohide" value="5" data-options="required:true" style="width:50px;height:20px;">&nbsp; Selepas (5) hari</input></td>	
               </tr>
  
    </table>
    <div style="padding:5px 0;text-align:center;padding-right:30px">
        <a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="false" onclick="saveItemCountdown(<?php echo $_REQUEST['index'];?>)">Save</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="false" onclick="cancelItemCountdown(<?php echo $_REQUEST['index'];?>)">Cancel</a>
    </div>
</div>
</form>

	<script type="text/javascript">
		function myformatter(date){
			var y = date.getFullYear();
			var m = date.getMonth()+1;
			var d = date.getDate();
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
	</script>
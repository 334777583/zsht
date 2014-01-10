<?php /* Smarty version 2.6.18, created on 2014-01-08 16:18:47
         compiled from gmtools/gm_email.html */ ?>
<!DOCTYPE html>
<html>
<head>
<title>运营工具-邮件</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?php echo $this->_tpl_vars['res']; ?>
/css/skin.css" rel="stylesheet" type="text/css">
<link href="<?php echo $this->_tpl_vars['res']; ?>
/css/jquery-ui.css" rel="stylesheet" type="text/css">
<link href="<?php echo $this->_tpl_vars['public']; ?>
/kindeditor/themes/default/default.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EEF2FB;
	font-size:12px
}
.mytable td {
	font-size: 12px;
}
-->
</style>
</head>
<body>
	<div>
		<div id="tabs-1" class="tabitem">
			<div>
				<table class="explain">
					<thead>
					</thead>
					<tbody style="font-family:Mingliu">
						<tr>
							<td width="5%"  class="tableleft"><b>说明：</b></td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">1、输入角色名给游戏对应玩家发送邮件，选择<font color = "red"><b>全服</b></font>时不需要输入任何角色名;谨慎使用<font color = "red"><b>全服</b></font>发送邮件！</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">2、批量导入现只支持TXT文件，编辑时间隔符为<font color = "red"><b>英文分号</b></font>；如有不懂，请咨询Admin！</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div>
				<table class="toptable">
					<thead>
					</thead>
					<tbody>
						<tr>
							<td width="5%" class="tableright">
								<span>服务器：</span>
							</td>
							<td width="95%" class="tableleft">
								<select id="sip">
									<?php $_from = $this->_tpl_vars['ipList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ip']):
?>
									<option value="<?php echo $this->_tpl_vars['ip']['s_id']; ?>
"><?php echo $this->_tpl_vars['ip']['s_name']; ?>
</option>
									<?php endforeach; endif; unset($_from); ?>
								</select>
							</td>
						</tr>
						<tr>
							<td width="5%" class="tableright">
								<select id="srole">
									<option value="1" checked='checked'>角色名</option>
									<option value="2">全服</option>
								</select>
							</td>
							<td width="95%" class="tableleft">
								<input type="input" class="input1" id="rolename" size="115"/>
								<label><font color="red"><i><b>注</b>：批量账号则用英文分号隔开区分,例子:a;b;c</i></font></label>
							</td>
						</tr>
						<tr id="level_info" style="display:none">
							<td width="100%" colspan="2" style="padding:0px">
								<table cellspacing = "0" cellpadding = "0" border="0" width="100%">
									<tr>
										<td width="5%" class="tableright">
											<span>最小等级：</span>
										</td>
										<td width="95%" class="tableleft">
											<input type="input" class="input1" size="5" value="0" id="minLv"/><font color="red"><i><b>注</b>：默认为 0,为无最小等级限制</i></font>
										</td>
									</tr>
									<tr>
										<td width="5%" class="tableright">
											<span>最大等级：</span>
										</td>
										<td width="95%" class="tableleft">
											<input type="input" class="input1" size="5" value="0" id="maxLv"/><font color="red"><i><b>注</b>：默认为 0,为无最大等级限制</i></font>
										</td>
									</tr>
									<tr>
										<td width="5%" class="tableright">
											<span>邮件接收截止时间：</span>
										</td>
										<td width="95%" class="tableleft">
											<input type="input" class="input1" size="5" id="emailTime" value="0"/>
											<select id="day">
												<option value="1">天</option>
												<option value="2">周</option>
												<option value="3">时</option>
											</select>			
											<font color="red"><i><b>注</b>：如果为0,则采用系统默认失效时长的时间</i></font>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="5%" class="tableright"><span>批量导入：</span></td>
							<td width="95%" class="tableleft">
								<form name="form" action="" method="POST" enctype="multipart/form-data">
									<input id="fileToUpload" type="file" size="45" name="fileToUpload"/>
									<input type="button" value="上传" class="uploadbtn"/>
									<label><font color="red"><i><b>注</b>：批量账号导入支持txt</i></font></label>
								</form>	
							</td>
						</tr>
						
						<tr>
							<td width="5%" class="tableright">
								<span>发送原因：</span>
							</td>
							<td width="95%" class="tableleft">
								<textarea cols="100" rows="2" class="input1" id="reason"></textarea>
							</td>
						</tr>
						<tr>
							<td width="5%" class="tableright">
								<span>标题：</span>
							</td>
							<td width="95%" class="tableleft">
								<div>(可留空，默认为<b>"系统邮件"</b>)</div>
								<div><input type="text" size="115" class="input1" id="title"/></div>
							</td>
						</tr>
						<tr>
							<td width="5%" class="tableright">
								<span>信件内容：</span>
							</td>
							<td width="95%" class="tableleft">
								<label><textarea cols="100" rows="2" class="input1" id="content"></textarea></label>
								<label class="link"><input type="button" value="增加超链接" id="addlink"/></label>
								<span><input type="button" value="发送" id="sendbtn"/></span>
								<input type="button" value="刷新页面查看处理结果" onclick="showTable(1);"/>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div>
				<table  class="mytable" >
					<thead>
						<tr>
							<th>服务器</th>
							<th>角色名</th>
							<th>时间</th>
							<th>发送原因</th>
							<th>标题</th>
							<th>内容</th>
							<th>状态</th>
							<th>操作者</th>
						</tr>
					</thead>
					<tbody id="etbody">
					</tbody>
				</table>
				<div id="pagehtml" style="float:right;margin-right:20px"></div>
				<div id="example_length" class="dataTables_length" style="display:none">
					<label>每页显示
						<select id="menu" name="example_length" size="1" aria-controls="example">
						<option value="10" selected="selected">10</option>
						<option value="25">25</option>
						<option value="50">50</option>
						<option value="100">100</option>
						</select> 条记录
					</label>
				</div>
			</div>
		</div>
		<input type="hidden" value="" id="ids"/>
		
		<div id="form"  style="display:none">
			<div class="ajaxform">
				<table width="90%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
					<tbody>
						<tr>
							<td width="20%" align="right">文字：</td>
							<td width="30%"><input type="text" class="input1" id="e_text" ></td>
							<td width="30%"><span style="color:red">注：留空只显示链接</span></td>
						</tr>
						<tr>
							<td align="right">链接：</td>
							<td><input type="text" class="input1" id="e_link" value="http://" ></td>
							<td>
								<table  border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td id="colorpicker">
											<span class="s_color">
												<img class="s_img" src="<?php echo $this->_tpl_vars['res']; ?>
/images/yanse.png"/>
											</span>
										</td>
										<td>
											<span style="color:red">默认为#00FFFF</span>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<input type="hidden" id="s_temp" value="#00FFFF"/>
			</div>
		</div>
		
	</div>
	
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery-ui.js" type="text/javascript"></script> 
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/ajaxfileupload.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/function.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['public']; ?>
/kindeditor/kindeditor-all.js" type="text/javascript"></script>
	<script type="text/javascript">
		$.fn.selection = function(){
		 var s,e,range,stored_range;
		 if(this[0].selectionStart == undefined){
			 var selection=document.selection;
			 if (this[0].tagName.toLowerCase() != "textarea") {
				 var val = this.val();
				 range = selection.createRange().duplicate();
				 range.moveEnd("character", val.length);
				 s = (range.text == "" ? val.length:val.lastIndexOf(range.text));
				 range = selection.createRange().duplicate();
				 range.moveStart("character", -val.length);
				 e = range.text.length;
			 }else {
				 range = selection.createRange(),
				 stored_range = range.duplicate();
				 stored_range.moveToElementText(this[0]);
				 stored_range.setEndPoint('EndToEnd', range);
				 s = stored_range.text.length - range.text.length;
				 e = s + range.text.length;
			 }
		 }else{
			 s=this[0].selectionStart,
			 e=this[0].selectionEnd;
		 }
		 var te=this[0].value.substring(s,e);
		 return {start:s,end:e,text:te};
		};	
	
	
		KindEditor.ready(function(K) {
			var colorpicker;
			K('#colorpicker').bind('click', function(e) {
				e.stopPropagation();
				if (colorpicker) {
					colorpicker.remove();
					colorpicker = null;
					return;
				}
				var colorpickerPos = K('#colorpicker').pos();
				colorpicker = K.colorpicker({
					x : colorpickerPos.x,
					y : colorpickerPos.y + K('#colorpicker').height(),
					z : 19811214,
					selectedColor : 'default',
					noColor : '无颜色',
					click : function(color) {
						//K('#color').val(color);
						$("#s_temp").val(color);
						$(".s_color").css("background-color",color);
						colorpicker.remove();
						colorpicker = null;
					}
				});
			});
			K(document).click(function() {
				if (colorpicker) {
					colorpicker.remove();
					colorpicker = null;
				}
			});
		});
	
		showTitle("运营工具:发送邮件");

		//ajax文件上传
		$(".uploadbtn").click(function(){
			var fileId = $(this).prev().attr("id");
			var filename =$(this).prev().attr("name");
			$.ajaxFileUpload({
				url:'<?php echo $this->_tpl_vars['app']; ?>
/upload/upload', //你处理上传文件的服务端
				secureuri:false,
				fileElementId:fileId,
				data:{
					name:filename
				},
				dataType: 'json',
				success: function (data, status)
				{
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							alert(data.error);
						}else
						{
							$("#rolename").val("");
							$("#rolename").val(data.msg);
						}
					}
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			})
		})
		
		//增加超链接
		$("#addlink").click(function(){
			var obj = $("#content").selection();
			var select_text = obj["text"];		//textarea选中文本
			var start = obj["start"];			//选中文本初始位置
			var end = obj["end"];				//选择文本结束位置
			$("#e_text").val("");
			$("#e_link").val("http://");
			$("#e_text").val(select_text);

			$("#form").dialog({
				height: 200,
				width: 550,
				buttons: {
					'确认' : function(){
						var textarea_val = $("#content").val();
						var f_part = textarea_val.substring(0,start);
						var	l_part = textarea_val.substring(end);
						var text = $("#e_text").val();
						var link = $("#e_link").val();
						var color = $("#s_temp").val();
						var replace_text = '<font color="'+color+'"><a href="'+link+'" target="_blank"><u>'+text+'</u></a></font>';
						var new_text = f_part+replace_text+l_part;
						$("#content").val(new_text);
						$(this).dialog("close");
						
						if($("#content")[0].selectionStart != "undefined") {
							$("#content").focus();
							$("#content")[0].selectionStart = start + replace_text.length;
							$("#content")[0].selectionEnd = start + replace_text.length;
						}
					},
					'取消' : function(){
						$(this).dialog("close");
					}
				}
			})
		})
			
		//发送邮件
		$("#sendbtn").click(function(){
			var rolename = $("#rolename").val();
			var ip = $("#sip").val();
			var reason = $("#reason").val();
			var title = $("#title").val();
			var content = $("#content").val();
			var srole =  $('#srole').val();
			var minLv = $('#minLv').val();
			var maxLv = $('#maxLv').val();
			var emailTime = $('#emailTime').val();
			var day = $('#day').val();
			
			$.ajax({
				type : "post",
				dataType : "json",
				url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmemail/sendEmail",
				data :{
					rolename : rolename,
					ip : ip,
					reason : reason,
					title : title,
					content : content,
					srole : srole,
					minLv : minLv,
					maxLv : maxLv,
					emailTime : emailTime,
					day : day
				},
				beforeSend:function(){
					$("#etbody").html("<tr><td colspan='8'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
				},
				success : function(data){
					if(typeof(data.error) != "undefined"){
						if(data.error != ""){
							alert(data.error);
						}
					}else{
						$("#ids").val(data.ids);
						showTable(1);
					}
				}
			})
		})
		
		//标记当前操作角色td
		var colourTd  = function(id,type){
			if("" != $("#"+id).val()){
				var ids = $("#"+id).val();
				var arr  = ids.split(",");
				for(var i in arr){
					$("#"+type+arr[i]).css("background-color","#FFCC66"); 
				}
			}
		}
		
		//table交叉样式
		var color_table = function(table) {
			$("#"+table+" tr:odd").css("background-color", "#edf2f7"); 
			$("#"+table+" tr:odd").css("background-color","#e0f0f0"); 
		}
		
		//切换服务器
		$("#sip").change(function(){
			showTable(1);
		})
		
		//邮件获取表格数据
		var showTable =  function(page){
			$.ajax({
				type:"GET",
				dataType:"json",
				url:"<?php echo $this->_tpl_vars['logicApp']; ?>
/gmemail/getEmailTable",
				data:
				{
					ip : $("#sip").val(),
					pageSize : $("#menu").val(),
					curPage : page
				},
				beforeSend:function(){
					$("#etbody").html("<tr><td colspan='8'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
				},
				success:function(data){
					var list = [];
					$("#etbody").html("");//清空表格，防止叠加
					$("#pagehtml").html("");//清除分页 
					if(typeof(data.list) != 'undefined'){
						list = data.list;
					}
					if(list.length > 0 ){
						$("#example_length").show();//显示每页
						var tbody = "";
						for(var i in list){
							var model = "noclass";
							switch(parseInt(list[i]["e_status"])){
								case -1: 
									list[i]["e_status"] = "正在处理";
									break;
								case 1: 
									list[i]["e_status"] = "成功";
									break;
								case 2: 
									list[i]["e_status"] = "失败";
									model="fail";
									break;
								default: 
									list[i]["e_status"] = "未知";
									model="fail";
							}
							tbody += "<tr id='email"+list[i]["e_id"]+"'>";
							tbody += "<td>"+data.ipList[list[i]["e_ip"]]+"</td>";
							tbody += "<td>"+list[i]["e_name"]+"</td>";
							tbody += "<td>"+list[i]["e_time"]+"</td>";
							tbody += "<td>"+list[i]["e_reason"]+"</td>";
							tbody += "<td>"+list[i]["e_title"]+"</td>";
							tbody += "<td>"+list[i]["e_content"]+"</td>";
							tbody += "<td class='"+model+"'>"+list[i]["e_status"]+"</td>";
							tbody += "<td>"+list[i]["e_operaor"]+"</td>";
							tbody += "</tr>";
						}
						$("#etbody").html(tbody);
						color_table("etbody");
						colourTd("ids","email");//高亮当前记录
						$("#pagehtml").html(data.pageHtml);
					}else{
						$("#etbody").html("");
						$("#etbody").html("<tr><td colspan='8'>没有数据！</td></tr>");
					}
				},
				error:function(){
					$("#etbody").html("");
					$("#etbody").html("<tr><td colspan='8'>没有数据！</td></tr>");
				}
			})
		}
		
		//页面加载，显示email表格
		showTable(1);
		
		//邮件每页显示
		$("#menu").change(function(){
			showTable(1);
		});
		
		//分页ajax函数
		var pageAjax = function(page){
			showTable(page);
		}

		//跳到相应页面 
		var go = function(){
			var pagenum = $("#page").val();
			if(pagenum=='' || isNaN(pagenum) || pagenum <= 0){
				alert('请输入一个正整数！');
				$("#page").val(1);
			}else{
				pageAjax(pagenum);
			}
		}
		
		//全服和角色切换
		$('#srole').change(function(){
			var srole =  $('#srole').val();
			if('1' == srole){
				$('#rolename').val("");
				$('#rolename').removeAttr("disabled");
				$('#rolename').css("background-color","#FFFFFF");
				$('#level_info').hide();
			}else if('2' == srole){
				$('#rolename').val("");
				$('#rolename').attr("disabled","disabled");
				$('#rolename').css("background-color","#CCCCCC");
				$('#level_info').show();
			}
		})
		
	</script>
</body>
</html>
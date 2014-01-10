<?php /* Smarty version 2.6.18, created on 2014-01-02 17:30:11
         compiled from gmtools/gm_new.html */ ?>
<!DOCTYPE html>
<html>
<head>
<title>运营工具-公告管理</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?php echo $this->_tpl_vars['res']; ?>
/css/skin.css" rel="stylesheet" type="text/css">
<link href="<?php echo $this->_tpl_vars['res']; ?>
/css/jquery-ui.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EEF2FB;
	font-size: 12px;
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
							<td width="95%" class="tableleft">1、<font color = "red"><b>即时消息</b></font>：在游戏聊天公告系统信息及游戏顶部滚动一条公告；优先级>滚动公告；</td>
						</tr>
						<!--<tr>
							<td width="95%" class="tableleft">1、<font color = "red"><b>滚动消息</b></font>：在设定的时间区间内，按照时间间隔在游戏顶部重复滚动一条公告；</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">1、<font color = "red"><b>弹窗公告</b></font>：游戏弹出布告，用于长期消息及活动公告等；</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">1、<font color = "red"><b>更新公告</b></font>：游戏弹出布告同一窗体不同标签，用于告知玩家版本更新内容；</td>
						</tr>
						-->
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
								<span>发送类型：</span>
							</td>
							<td width="95%" class="tableleft">
								<select id="stype">
									<option value="1">即时消息</option>
								<!--	<option value="2">滚动公告</option>
									<option value="3">弹窗公告</option>
									<option value="4">更新内容</option>-->
								</select>
								<input value="生成"  type="submit" id="botton" style="display:none"/>
								<input type="button" value="游戏预览" id="html_page" style="display:none" />
							</td>
						</tr>
						
						<tr class="timeinfo" style="display:none"  id="time_range">
							<td width="5%" class="tableright">
								<span>时间段：</span>
							</td>
							<td width="95%" class="tableleft">
								<input type="text" id="starttime" onClick="WdatePicker({maxDate:'#F{$dp.$D(\'endtime\',{s:-1})}'})" class="input1"/>至<input type="text" id="endtime" onClick="WdatePicker({minDate:'%y-%M-#{%d}'})" class="input1"/>
							</td>
						</tr>
						
						<tr class="timeinfo" style="display:none"  id="body_se">
							<td width="5%" class="tableright">
								<span>设置：</span>
							</td>
							<td width="95%" class="tableleft">
								宽度：<input name="b_width" size='6' class="input1" value="450"  />px&nbsp;&nbsp;&nbsp;高度：<input name="b_hegiht" size='6' class="input1" value="500" />px&nbsp;&nbsp;&nbsp;背景设置：<input id="color" name="b_color" size='40' class="input1" value="background-color:#FFFFFF;" /> <input type="button" value="打开取色器" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input value="生成"  type="submit" id="botton" />
							</td>
						</tr>
						
						<tr class="timeinfo" style="display:none">
							<td width="5%" class="tableright">
								<span>时间间隔：</span>
							</td>
							<td width="95%" class="tableleft">
								<input type="text" class="input1" id="interval" size="10"/>
								<span>秒</span>
							</td>
						</tr>
						<tr id="new_content">
							<td width="5%" class="tableright">
								<span>消息内容：</span>
							</td>
							<td width="95%" class="tableleft">
								<label><textarea cols="100" rows="2" class="input1" id="content"></textarea></label>
								<label class="link"><input type="button" value="增加超链接" id="addlink"/></label>
								<input type="button" value="提交" id="commit"/>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div id="new_detail">
				<div>
					<h3>当前滚动公告：</h3>
					<table  class="mytable">
						<thead>
							<tr>
								<th>服务器</th>
								<th>消息类型</th>
								<th>开始日期</th>
								<th>结束日期</th>
								<th>时间间隔</th>
								<th>内容</th>
								<th>编辑日期</th>
								<th>最后操作者</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody id="cur_body">
						</tbody>
					</table>
				</div>
				
				<div style='margin-top:20px'>
					<h3>历史操作：</h3>
					<table  class="mytable">
						<thead>
							<tr>
								<th>服务器</th>
								<th>消息类型</th>
								<th>开始日期</th>
								<th>结束日期</th>
								<th>时间间隔</th>
								<th>内容</th>
								<th>编辑日期</th>
								<th>最后操作者</th>
								<th>请求状态</th>
							</tr>
						</thead>
						<tbody id="newtable">
						</tbody>
					</table>
				</div>
				
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
				<input type="hidden" value="" id="ids"/>
			</div>
			
			
		</div>
		<div id="Dtest" style="display:none;height:800px;width:99.9%;">&nbsp;<textarea name="content"></textarea></div>
		
		
		<!--游戏预览效果-->
		<div class='dialog' style='display:none' id="dialog">
			<table style="width:220px;height:100%;margin:0 auto;">
				<tr>
					<td style="text-align:center;vertical-align:middle">
						<div id="g_new">
							<img id="close_btn" style="position:relative;top:30px;left:170px;cursor:pointer" src="<?php echo $this->_tpl_vars['res']; ?>
/images/close_new.png"/>
							<div class="game_d">
								<div class="game_c" id="game_c">
								</div>	
							</div>
						<div>
					</td>
				<tr>
			</table>
		</div>
		
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
/images/yanse.png" />
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
</body>
<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery-ui.js" type="text/javascript"></script> 
<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/function.js" type="text/javascript"></script>

<link href="<?php echo $this->_tpl_vars['public']; ?>
/kindeditor/themes/default/default.css" rel="stylesheet" type="text/css">
<script src="<?php echo $this->_tpl_vars['public']; ?>
/kindeditor/kindeditor-all.js" type="text/javascript"></script>
<script src="<?php echo $this->_tpl_vars['public']; ?>
/kindeditor/lang/zh_CN.js" type="text/javascript"></script>

<script src="<?php echo $this->_tpl_vars['res']; ?>
/My97DatePicker/WdatePicker.js" type="text/javascript"></script>
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



	var flag = true;//全局变量，防止重复提交
	showTitle("运营工具:公告管理");
	$("#starttime").val(curentTime());
	
	$("#interval").blur(function(){
		var interval = $("#interval").val();
		if(isNaN(interval) || interval == ""){
			alert("请输入数字！");
			return false;
		}else if(parseInt(interval) < 60 ){
			alert("时间间隔至少60秒");
			return false;
		}		
	})
	
	$("#stype").change(function(){
		var type = $("#stype").val();
		if(type == 1){
			$(".timeinfo").hide();
			$("#starttime").val("");
			$("#endtime").val("");
			$("#interval").val(0);
			$("#new_detail").show();
			$("#new_content").show();
			$("#Dtest").hide();
			$("#botton").hide();
			$("#html_page").hide();
			showTable(1);
		}else if(type == 2){
			$(".timeinfo").show();
			$("#interval").val(60);
			$("#new_detail").show();
			$("#new_content").show();
			$("#Dtest").hide();
			$("#botton").hide();
			$("#body_se").hide();
			$("#html_page").hide();
			showTable(1);
		}else if(type == 3){
			$(".timeinfo").hide();
			$("#botton").show();
			$("#new_detail").hide();
			$("#new_content").hide();
			$("#Dtest").show();
			
			get_page();	
		}else if(type == 4) {
			$(".timeinfo").hide();
			$("#botton").show();
			$("#new_detail").hide();
			$("#new_content").hide();
			$("#Dtest").show();
	
			get_page();

		}
	})
	
	$("#sip").change(function(){
		var type = $("#stype").val();
		if(type == 3 || type == 4) {
			get_page();
		} else {
			showTable(1);
			//showCurTable();
		}
	})
	
	$( "#g_new" ).draggable({ 
		addClasses: false,
		scroll: false,
		cancel: '#game_c'
	});
	
	//获取公告内容
	var get_page = function(){
		var ip = $("#sip").val();
		var type = $("#stype").val();
		
		$.ajax({
	
			type : "GET",
			url : "<?php echo $this->_tpl_vars['app']; ?>
/deatil_html/deatil_get",
			dataType : "json",
			data : {
				ip : ip,
				type : type
			},
			success : function(data){
				if(data != 'error'){
					var info = data.info;
					editor.html(info['h_content']);
					$("input[name=b_width]").val(info['h_width']);
					$("input[name=b_hegiht]").val(info['h_height']);
					$("input[name=b_color]").val(info['h_body']);
					
					//$("#iframe_new").attr("src", data.url);
					$("#game_c").html(data.content);
					$("#html_page").show();
				}else{
					editor.html("");
					$("input[name=b_width]").val("400");
					$("input[name=b_hegiht]").val("450");
					$("input[name=b_color]").val("background-color:#FFFFFF;");
					$("#iframe_new").attr("src", "");
					$("#html_page").hide();
				}	
			},
			error : function(){
				alert('失败！');
			}
		
		});	
	}
	
	//关闭公告
	$("#close_btn").click(function(){
		$("#dialog").hide();
	})
	
	//查看效果
	$("#html_page").click(function(){
		$("#dialog").show();
	})
	
	//提交 
	$("#commit").click(function(){
		var ip = $("#sip").val();
		var type = $("#stype").val();;
		var content = $("#content").val();
		var starttime = $("#starttime").val();
		var endtime = $("#endtime").val();
		var interval = $("#interval").val();
		if(type == 1){
			starttime = "";
			endtime = "";
			interval = 0;
		}else if(type == 2){
			if(isNaN(interval) || interval == ""){
				alert("请输入数字！");
				return false;
			}else if(parseInt(interval) < 60 ){
				alert("时间间隔至少60秒");
				return false;
			}else if(endtime == ""){
				alert("请输入结束时间!");
				return false;
			}else if(starttime == ""){
				alert("请输入开始时间!");
				return false;
			}else if(endtime<starttime){
				alert("结束时间要大于开始时间");
				return false;
			}
		}
		var time = Date.parse(new Date());
		$.ajax({
			type:"POST",
			url:'<?php echo $this->_tpl_vars['logicApp']; ?>
/gmnew/sendNew',
			data:
			{
				ip : ip,
				type : type,
				starttime : starttime,
				endtime : endtime, 
				interval :interval,
				content : content,
				time : time
			},
			dataType:"json",
			success:function(data){
				if(typeof(data.error) != 'undefined')
				{
					if(data.error != '')
					{
						alert(data.error);
					}
				}else{
					$("#ids").val(data.ids);
					showTable(1);
					//showCurTable();
				}
			}
		})
	})
	
	//标记当前操作角色td
	var colourTd  = function(id,type){
		var ids = $("#"+id).val();
		var arr  = ids.split(",");
		for(var i in arr){
			//$("#"+type+arr[i]).css("background-color","#7fffd4"); 
			$("#"+type+arr[i]).css("background-color","#FFCC66"); 
		}
	}

	//公告获取表格数据
	var showTable =  function(page){
		$.ajax({
			type:"GET",
			url:"<?php echo $this->_tpl_vars['logicApp']; ?>
/gmnew/getNewInfo",
			data:
			{
				ip : $("#sip").val(),
				type : $("#stype").val(),
				pageSize : $("#menu").val(),
				curPage : page,
				time : Date.parse(new Date())
			},
			dataType:"json",
			beforeSend:function(){
				$("#newtable").html("<tr><td colspan='9'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
			},
			success:function(data){
				$("#newtable").html("");//清空表格，防止叠加
				$("#pagehtml").html("");//清除分页 
				var html = "";
				var list = [];
				if(typeof(data.list) != 'undefined'){
					list = data.list;
				}
				if(list.length > 0 ){
					$("#example_length").show();//显示每页
					for(var i in list){
						var crole = "noclass";
						var ccall = "noclass";
						switch(parseInt(list[i]["n_callstatus"])){
							case 1: list[i]["n_callstatus"] = "成功";break;
							case 2: list[i]["n_callstatus"] = "失败";ccall="fail";break;
							default: list[i]["n_callstatus"] = "未知";ccall="fail";
						}
						switch(parseInt(list[i]["n_status"])){
							case 1: list[i]["n_status"] = "即时消息";break;
							case 2: list[i]["n_status"] = "滚动公告";break;
							default: list[i]["n_status"] = "未知";crole="fail";
						}
						list[i]["n_interval"] = list[i]["n_interval"] + "s";
						
						
						html += "<tr id="+"new"+list[i]["n_id"]+" >";
						html += "<td>"+data.ipList[list[i]["n_id"]]+"</td>";
						html += "<td>"+list[i]["stype"]+"</td>";
						html += "<td>"+list[i]["date"]+"</td>";
						html += "<td>"+list[i]["date"]+"</td>";
						html += "<td>"+list[i]["time"]+"</td>";
						html += "<td>"+list[i]["content"]+"</td>";
						html += "<td>"+list[i]["date"]+"</td>";
						html += "<td>"+list[i]["uname"]+"</td>";
						html +=	"<td class=\""+ccall+"\">"+list[i]["state"]+"</td>";
						html += "</tr>";
										
					}
					$("#newtable").html(html);
					$("#newtable tr:odd").css("background-color", "#edf2f7"); 
					$("#newtable tr:even").css("background-color","#e0f0f0");
					colourTd("ids","new");
					$("#pagehtml").html(data.pageHtml);
				}else{
					$("#newtable").html("<tr><td colspan='9'>没有数据！</td></tr>");
				}
			},
			error:function(){
				$("#newtable").html("<tr><td colspan='9'>没有数据！</td></tr>");
			}
		})
	}
	/*
	//显示当前滚动公告
	var showCurTable = function(){
		$.ajax({
			type : "GET",
			url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmnew/getCurInfo",
			dataType : "json",
			data : {
				ip : $("#sip").val()
			},
			beforeSend : function(){
				$("#cur_body").html("<tr><td colspan='9'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
			},
			success : function(data){
				if(typeof(data.list) != 'undefined'){
					if( data.list.length > 0 ){
						var html = '';
						for(var i in data.list){
							html += "<tr>";
							html += "<td>"+data.ipList[data.list[i]["c_ip"]]+"</td>";
							html += "<td>滚动公告</td>";
							html += "<td>"+data.list[i]["c_starttime"]+"</td>";
							html += "<td>"+data.list[i]["c_endtime"]+"</td>";
							html += "<td>"+data.list[i]["c_interval"]+"</td>";
							html += "<td>"+data.list[i]["c_content"]+"</td>";
							html += "<td>"+data.list[i]["c_date"]+"</td>";
							html += "<td>"+data.list[i]["c_operaor"]+"</td>";
							html += "<td><span class='dtool' id="+data.list[i]["c_id"]+">删除</span></td>";
							html += "</tr>";
						}
						$("#cur_body").html(html);
					}else{
						$("#cur_body").html("<tr><td colspan='9'>没有数据！</td></tr>");
					}
				}else{
					$("#cur_body").html("<tr><td colspan='9'>没有数据！</td></tr>");
				}
			},
			error : function(){
				$("#cur_body").html("<tr><td colspan='9'>没有数据！</td></tr>");
			}
		})
	}
		
	//删除当前滚动公告
	$(".dtool").live('click',function(){
		var id = this.id;
		$.ajax({
			type : "GET",
			url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmnew/deleteNew",
			dataType : "json",
			data : {
				ip : $('#sip').val(),
				gid : id
			},
			cache : false,
			success : function(data){
				if(data == 'success'){
					alert('删除成功！');
					showCurTable();
				}
			},
			error : function(){
				alert('删除失败！');
			}
		})
	})
	*/
	//页面加载公告信息
	showTable(1);
	//showCurTable();
	
	//公告每页显示
	$("#menu").change(function(){
		showTable(1);
	});

	//分页ajax函数
	var newAjax = function(page){
		showTable(page);
	}

	//跳到相应页面 
	var ngo = function(){
		var pagenum = $("#npage").val();
		if(pagenum=='' || isNaN(pagenum) || pagenum <= 0){
			alert('请输入一个正整数！');
			$("#npage").val(1);
		}else{
			newAjax(pagenum);
		}
	}
	
	var editor;	
	KindEditor.ready(function(K) {
		editor = K.create('textarea[name="content"]', {
			allowFileManager : false,
			width:'400px',
			height:'450px',
			items : [
					'fontname', 'fontsize', '|', 'forecolor',  'bold', 'italic', 'underline',
					'removeformat', 'link', 'unlink', 'source'],
			newlineTag : 'br',
			afterCreate : function() {
				this.loadPlugin('autoheight');
			}
			
		});

	});
	
	
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
	
	
	
	var submit_from = function(){
		var html = editor.html();
		var ip = $("#sip").val();
		var type = $("#stype").val();
		
		if(html == ""){
			alert('内容不能为空！');
			return false;
		}

		$.ajax({
			type : "post",
			url : "<?php echo $this->_tpl_vars['app']; ?>
/deatil_html/deatil_post",
			dataType : "json",
			data : {
				content : html,
				ip : ip,
				type : type
			},
			success : function(data){
				if(data.status == 'success'){
					$("#game_c").html(data.content);
					$("#html_page").show();
					if(confirm('生成成功！是否查看页面？')) {
						$("#dialog").show();
					}
				}else{
					alert('生成失败！');
				}				
			},
			error : function(){
				alert('生成失败！');
			}
		
		})

	}
	
	$(document).ready(function(){
		$("#botton").live('click',function(){
			submit_from();
		});
	});
</script>
</html>
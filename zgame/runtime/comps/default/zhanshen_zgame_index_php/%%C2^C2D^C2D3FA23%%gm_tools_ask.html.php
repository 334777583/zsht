<?php /* Smarty version 2.6.18, created on 2014-01-07 15:51:37
         compiled from gmtools/gm_tools_ask.html */ ?>
<!DOCTYPE html>
<html>
<head>
<title>运营工具-道具申请</title>
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
							<td width="95%" class="tableleft">1、输入角色名给游戏对应玩家发送道具<font color = "red"><b>奖励</b></font>/<font color = "red"><b>补偿</b></font>，选择<font color = "red"><b>全服</b></font>时不需要输入任何角色名;谨慎使用<font color = "red"><b>全服</b></font>发起申请！</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">2、批量导入现只支持TXT文件，编辑时间隔符为<font color = "red"><b>英文分号</b></font>；如有不懂，请咨询Admin！</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">3、道具ID尽量通过道具ID关系表获得，减少输入错误；</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">4、确认<font color = "red"><b>货币及道具</b></font>填写正确后，提交申请后由有<font color = "red"><b>审批权限</b></font>的上级帐号审批申请，审批通过后才会给玩家发送带道具的邮件。</td>
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
							<td width="7%" class="tableright">
								<span>服务器：</span>
							</td>
							<td width="93%" class="tableleft">
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
							<td width="7%" class="tableright">
								<select id="srole">
									<option value="1" checked='checked'>角色名</option>
									<option value="2">全服</option>
								</select>
							</td>
							<td width="93%" class="tableleft">
								<input type="input" class="input1" id="rolename" size="115"/>
								<label><font color="red"><i><b>注</b>：批量账号则用英文分号隔开区分,例子:a;b;c</i></font></label>
							</td>
						</tr>
						<tr id="level_info" style="display:none">
							<td width="100%" colspan="2" style="padding:0px">
								<table cellspacing = "0" cellpadding = "0" border="0" width="100%">
									<tr>
										<td width="7%" class="tableright">
											<span>最小等级：</span>
										</td>
										<td width="93%" class="tableleft">
											<input type="input" class="input1" size="5" value="0" id="minLv"/><font color="red"><i><b>注</b>：默认为 0,为无最小等级限制</i></font>
										</td>
									</tr>
									<tr>
										<td width="7%" class="tableright">
											<span>最大等级：</span>
										</td>
										<td width="93%" class="tableleft">
											<input type="input" class="input1" size="5" value="0" id="maxLv"/><font color="red"><i><b>注</b>：默认为 0,为无最大等级限制</i></font>
										</td>
									</tr>
									<tr>
										<td width="7%" class="tableright">
											<span>邮件接收截止时间：</span>
										</td>
										<td width="93%" class="tableleft">
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
							<td width="7%" class="tableright"><span>批量导入：</span></td>
							<td width="93%" class="tableleft">
								<form name="form" action="" method="POST" enctype="multipart/form-data">
									<input id="fileToUpload" type="file" size="45" name="fileToUpload"/>
									<input type="button" value="上传" class="uploadbtn"/>
									<label><font color="red"><i><b>注</b>：批量账号导入支持txt</i></font></label>
								</form>	
							</td>
						</tr>
						
						<tr>
							<td width="7%" class="tableright">
								<span>发送原因：</span>
							</td>
							<td width="93%" class="tableleft">
								<textarea cols="100" rows="2" class="input1" id="reason">活动奖励</textarea>
							</td>
						</tr>
						<tr>
							<td width="7%" class="tableright">
								<span>标题：</span>
							</td>
							<td width="93%" class="tableleft">
								<div>(可留空，默认为<b>"系统邮件"</b>)</div>
								<div><input type="text" size="115" class="input1" id="title"/></div>
							</td>
						</tr>
						<tr>
							<td width="7%" class="tableright">
								<span>信件内容：</span>
							</td>
							<td width="93%" class="tableleft">
								<label><textarea cols="100" rows="2" class="input1" id="content"></textarea></label>
								<label class="link"><input type="button" value="增加超链接" id="addlink"/></label>
							</td>
						</tr>
						<tr>
							<td width="7%" class="tableright">
								<span>申请赠送金额：</span>
							</td>
							<td width="93%" class="tableleft">
								<span>元宝：</span><input type="text" size="10" class="input1" id="gold" value=""/>
								<span>铜币：</span><input type="text" size="10" class="input1" id="copper" value=""/>
							</td>
						</tr>
						<tr>
							<td width="7%" class="tableright">
								<span>申请赠送道具：</span>
							</td>
							<td width="93%" class="tableleft labc">
								<span>道具ID：</span>
								<span  class="input1">
									<input type="text" size="13" id="toolsId" style="border: 0 none;"/>
									<span class="mini-buttonedit-icon" id="tools_icon">&nbsp;&nbsp;&nbsp;</span>
								</span>
								<span>申请数量：</span><input type="text" size="3" class="input1" id="toolsNum" value=""/>
								<input type="hidden" id="tolName"/>
								
								<!--
								<span>绑定要求：</span>
								<input type="radio" name="bdGroup" value="1"/><span>已绑定</span>
								<input type="radio" name="bdGroup" value="2" checked="checked"/><span>未绑定</span>


								<label>
									<span>强化等级：</span>
									<select id="level">
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
									</select>
								</label>
								<label>
									<span>追加等级：</span>
									<select id="addLevel">
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
									</select>
								</label>
								-->
								
								<input type="button" value="添加道具" id="addTools"/>
							</td>
						</tr>
						<tr style="display:none" id="tool_table">
							<td width="7%" class="tableright">
								<span>道具列表：</span>
							</td>
							<td width="93%" class="tableleft">
								<table class="littletable">
									<thead>
										<tr>
											<th>道具ID</th>
											<th>道具名称</th>
											<th>数量</th>
											<th>操作</th>
										</tr>
									</thead>
									<tbody id="toolsBody">
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div style="clear: both"></div>
			<div>
				<input type="button" value="确认申请" id="apply"/>
			</div>
			<div>
				<table  class="mytable">
					<thead>
						<tr>
							<th>服务器</th>
							<th>角色列表</th>
							<th>时间</th>
							<th>金钱与道具详情</th>
							<th>邮件标题</th>
							<th>邮件内容</th>
							<th>发送原因</th>
							<th>状态</th>
							<th>操作者</th>
						</tr>
					</thead>
					<tbody id="askBody">
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
		<div style="clear:both"></div>
		<input type="hidden" value="" id="ids"/>
		<div style="height:50px">&nbsp;</div>
	</div>
	
	<!-- 金钱与道具详情 -->
	<div id="form"  style="display:none">
		<div class="ajaxform">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
				<tbody>
					<tr id="lv_info" style="display:none">
						<td>
							<table cellspacing="0" cellpadding="0" style="border: 1px solid #859497;" width="100%">
								<tr>
									<td>
										<span>最小等级：</span><span id="t_minlv">0</span>
									</td>
								</tr>
								<tr>
									<td>
										<span>最大等级：</span><span id="t_maxlv">0</span>
									</td>
								</tr>
								<tr>
									<td>
										<span>邮件接收截止时间：</span><span id="t_endtime">0</span>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>金钱：</td>
					</tr>
					<tr>
						<td>
							<table class="tooltable">
								<thead>
									<tr>
										<th>
											<span>元宝：</span><span id="f_gold">0</span>
										</th>
										<th>
											<span>铜币：</span>
											<span id="f_copper">0</span>
										</th>
									</tr>
								</thead>
							</table>
						</td>
					</tr>
					<tr>
						<td>道具列表：</td>
					</tr>
					<tr>
						<td>
							<table class="tooltable">
								<thead>
									<tr>
										<th>道具ID</th>
										<th>道具名称</th>
										<th>数量</th>
									</tr>
								</thead>
								<tbody id="form_tools">
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	
	<!--弹出覆盖层-->
	<div class="overlay" style="display:none">
		<table style="width:220px;height:100%;margin:0 auto;">
			<tr>
				<td style="text-align:center;vertical-align:middle">
					<img src='<?php echo $this->_tpl_vars['res']; ?>
/images/ajax-loader.gif'/>
				</td>
			</tr>
		</table>
	</div>
	
	<!-- 道具ID与道具名称 -->
	<div id="dform"  style="display:none">
		<div class="ajaxform">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tbody>
					<tr>
						<td colspan='2'>道具ID与道具名称关系：</td>
					</tr>
					
					<tr>
						<?php if (in_array ( '00400900' , $this->_tpl_vars['code'] )): ?>
						<td width="10%" style="text-align:left">
							<input type="button" value="更新道具列表" id="update_tool"/>
						</td>
						<?php else: ?>
						<td width="10%" style="text-align:left">
							&nbsp;
						</td>
						<?php endif; ?>

						<td width="90%" style="text-align:right">
							<label>
								<span>类型1:</span>
								<select id="t_type1">
									<option value="">请选择</option>
								</select>
							</label>
							<label>
								<span>类型2:</span>
								<select id="t_type2">
									<option value="">请选择</option>
								</select>
							</label>
							<label>
								<span>类型3:</span>
								<select id="t_type3">
									<option value="">请选择</option>
								</select>
							</label>
							<label>
								<span>道具名称或道具ID:</span>
								<input type="text" id="searchKey"/ >
								<input type="button" value="搜索" id="toolSearch"/>
							</label>
						</td>
					</tr>
					
					<tr>
						<td colspan='2'>
							<table class="tooltable">
								<thead>
									<tr>
										<th>道具ID</th>
										<th>道具名称</th>
										<th>类型1</th>
										<th>类型2</th>
										<th>类型3</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody id="dform_body">
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			<div id="pagehtml2" style="float:right;margin-right:20px"></div>
		</div>
	</div>
	
	<!-- 增加超链接 -->
	<div id="lform"  style="display:none">
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
	
	
	
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/ajaxfileupload.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery-ui.js" type="text/javascript"></script> 
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/function.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['public']; ?>
/kindeditor/kindeditor-all.js" type="text/javascript"></script>
	<script type="text/javascript">
		$.fn.selection = function(){	
		 var s,e,range,stored_range;
		 if(this[0].selectionStart == "undefined"){
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
		
		//切换服务器
		$("#sip").change(function(){
			showTable(1);
		})
	
		//增加超链接
		$("#addlink").click(function(){
			var obj = $("#content").selection();
			var select_text = obj["text"];	//textarea选中文本
			var start = obj["start"];			//选中文本初始位置
			var end = obj["end"];				//选择文本结束位置
			$("#e_text").val("");
			$("#e_link").val("http://");
			$("#e_text").val(select_text);

			$("#lform").dialog({
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
	
	
		showTitle("运营工具:道具申请");
		//ajax文件上传
		$(".uploadbtn").click(function(){
			var dom = $(this);
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
							dom.parent().parent().parent().parent().find("textarea:first").val("");
							dom.parent().parent().parent().parent().find("textarea:first").val(data.msg);
						}
					}
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			})
		})
		
		var i = 0;//道具记录（最多只能添加5个道具）
		
		//添加道具
		$("#addTools").click(function(){
			var toolsId = $("#toolsId").val();
			var toolsName = $("#tolName").val();
			var toolsNum = $("#toolsNum").val();
			
			if(i>2){
				alert("道具最多为3个!");
				return false;
			}else if("" == toolsId){
				alert("请输入道具ID！");
				return false;
			}else if( isNaN(toolsId) || isNaN(toolsNum)){
				alert("请输入数字！");
				return false;
			}else if("" == toolsNum){
				alert("请输入申请数量！");
				return false;
			}else if(isNaN($("#toolsId").val())){
				alert("申请数量请输入数字！");
				return false;
			}
			
			var html = "";
			html += "<tr>";
			html += "<td class='tId'>"+toolsId+"</td>";
			html += "<td class='tName'>"+toolsName+"</td>";
			html += "<td class='tNum'>"+toolsNum+"</td>";
			html += "<td class='dtool'>删除</td>";
			html += "</tr>";
			
			$("#tool_table").show();
			$("#toolsBody").append(html);
			i++;
		})
		
	//删除道具
	$(".dtool").live("click",function(){
		var dom = this;
		$(dom).parent().remove();;
		i--;
		if(i== 0) {
			$("#tool_table").hide();
		}
	})
	
	//确认申请
	$("#apply").click(function(){
		var rolename = $("#rolename").val();	//角色名
		var ip = $("#sip").val();				//服务器
		var reason = $("#reason").val();		//发送原因
		var title = $("#title").val()			//标题
		var content = $("#content").val()		//信件内容	
		var gold = $("#gold").val();			//元宝
		var copper = $("#copper").val();		//铜币
		var srole =  $('#srole').val();			//全服和角色切换
		var minLv = $('#minLv').val();
		var maxLv = $('#maxLv').val();
		var emailTime = $('#emailTime').val();
		var day = $('#day').val();
		
		if("" == rolename && '1' == srole){
			alert("请输入角色名！");
			return false;
		}else if(("" != $("#toolsId").val()) && ($("#toolsBody").children().length < 1)){
			alert("请先添加道具");
			return false
		}else if("" != gold || "" != copper){
			if(parseInt(gold) > 100000000  || parseInt(copper) > 100000000){
				alert("最大值为100000000");
				return false
			}	
		}
		
		
		var toolList = [];	//道具列表
		
		if($("#toolsBody").children().length > 0){
			$("#toolsBody").children().each(function(){
				var tool = {};
				tool.toolId = $(this).find(".tId").html();			//道具ID
				tool.toolName = $(this).find(".tName").html();		//道具名称
				tool.toolNum = $(this).find(".tNum").html();		//数量
				toolList.push(tool);
			})
		}
		
		$.ajax({
			type : "post",
			dataType : "json",
			url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmtoolsask/toolsAsk",
			data :{
				rolename : rolename,
				ip : ip,
				reason : reason,
				title : title,
				content : content,
				gold : gold,
				copper : copper,
				toolList : toolList,
				srole : srole,
				minLv : minLv,
				maxLv : maxLv,
				emailTime : emailTime,
				day : day
			},
			beforeSend:function(){
				
			},
			success:function(data){
				if(typeof(data.error) != "undefined"){
					if(data.error != ""){
						alert(data.error);
					}
				}else{
					$("#ids").val(data.ids);
					showTable(1);
				}
			},
			error:function(){
				alert("error");
			}
		})
	})
	
	//显示道具申请table
	var showTable =  function(page){
		$.ajax({
			type:"GET",
			dataType:"json",
			url:"<?php echo $this->_tpl_vars['logicApp']; ?>
/gmtoolsask/getAskTable",
			data:
			{
				ip : $("#sip").val(),
				pageSize : $("#menu").val(),
				curPage : page
			},
			cache : false,
			beforeSend:function(){
				$("#askBody").html("");//清空表格，防止叠加
				$("#askBody").html("<tr><td colspan='9'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
			},
			success:function(data){
				var list = [];
				$("#askBody").html("");	//清空表格，防止叠加
				$("#pagehtml").html("");//清除分页 
				if(typeof(data.list) != 'undefined'){
					list = data.list;
				}
				if(list.length > 0 ){
					$("#example_length").show();//显示每页
					var tbody = "";
					for(var i in list){
						var model = "noclass";
						switch(parseInt(list[i]["t_status"])){
							case 1: 
								list[i]["t_status"] = "申请中...<br/><a href='javascript:void(0)' class='cancle_ask'>取消申请</a>";
								break;
							case 2: 
								list[i]["t_status"] = "申请不通过";
								model="fail";
								break;
							case 3:
								list[i]["t_status"] = "已通过但发送失败";
								model="fail";
								break;
							case 4:	
								list[i]["t_status"] = "已通过并发送成功";
								model="pass";
								break;
							case -2:	
								list[i]["t_status"] = "正在处理";
								model="pass";
								break;	
							default: 
								list[i]["t_status"] = "未知";
								model="fail";
						}
						tbody += "<tr id='tool"+list[i]["t_id"]+"'>";
						tbody += "<td>"+data.ipList[list[i]["t_ip"]]+"</td>";
						tbody += "<td>"+list[i]["t_role_name"]+"</td>";
						tbody += "<td>"+list[i]["t_inserttime"]+"</td>";
						tbody += "<td class='tdetial'>明细</td>";
						tbody += "<td>"+list[i]["t_title"]+"</td>";
						tbody += "<td>"+list[i]["t_content"]+"</td>";
						tbody += "<td>"+list[i]["t_reason"]+"</td>";
						tbody += "<td class='"+model+"'>"+list[i]["t_status"]+"</td>";
						tbody += "<td>"+list[i]["t_operaor"]+"</td>";
						tbody += "</tr>";
					}
					$("#askBody").html(tbody);
					$("#askBody tr:odd").css("background-color", "#edf2f7"); 
					$("#askBody tr:even").css("background-color","#e0f0f0"); 
					colourTd("ids","tool");	//高亮当前记录
					$("#pagehtml").html(data.pageHtml);
				}else{
					$("#askBody").html("");
					$("#askBody").html("<tr><td colspan='9'>没有数据！</td></tr>");
				}
			},
			error:function(){
				$("#askBody").html("");
				$("#askBody").html("<tr><td colspan='9'>没有数据！</td></tr>");
			}
		})	
	}
	
	//道具申请每页显示
	$("#menu").change(function(){
		showTable(1);
	});
		
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
	
	//页面加载显示table
	showTable(1);
	
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
	
	//分页ajax函数(道具ID与名称关系)
	var pageAjax2 = function(page){
		getDetail(page);
	}

	//跳到相应页面 (道具ID与名称关系)
	var go2 = function(){
		var pagenum = $("#page2").val();
		if(pagenum=='' || isNaN(pagenum) || pagenum <= 0){
			alert('请输入一个正整数！');
			$("#page").val(1);
		}else{
			pageAjax2(pagenum);
		}
	}
	
	
	//明细
	$(".tdetial").live("click",function(){
		var id = $(this).parent().attr("id").substr("4");
		var str = $(this).prev().prev().text();
		if(str == "全服") {
			$("#lv_info").show();
		} else {
			$("#lv_info").hide();
		}
		
		$.ajax({
			type : "get",
			url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmtoolsask/getDetail",
			dataType : "json",
			data : {
				id : id
			},
			cache : false,
			beforeSend : function(){
				$(".overlay").show();
			},
			complete : function(){
				$(".overlay").hide();
			},
			success : function(data){
				if(typeof(data.moneyList) != 'undefined'){
					$("#f_gold").html(data.moneyList["t_gold"]);
					$("#f_copper").html(data.moneyList["t_copper"]);
					if(data.moneyList["t_minlv"] == '0') {
						data.moneyList["t_minlv"] = "无限制"
					}
					if(data.moneyList["t_maxlv"] == '0') {
						data.moneyList["t_maxlv"] = "无限制"
					}
					if(data.moneyList["t_endtime"] == '0') {
						data.moneyList["t_endtime"] = "系统默认"
					} 
					
					$("#t_minlv").html(data.moneyList["t_minlv"]);
					$("#t_maxlv").html(data.moneyList["t_maxlv"]);
					$("#t_endtime").html(data.moneyList["t_endtime"]);
				}
				
				if(typeof(data.toolList) != 'undefined'){
					var html = "";
					if(data.toolList.length >0){
						for(var i in data.toolList){
							html += "<tr>";
							html += "<td>"+data.toolList[i]["t_tid"]+"</td>";
							html += "<td>"+data.toolList[i]["t_name"]+"</td>";
							html += "<td>"+data.toolList[i]["t_num"]+"</td>";
							html += "</tr>";
						}
						$("#form_tools").html(html);
					}else{
						$("#form_tools").html("<tr><td colspan='7'>没有记录！</td></tr>");
					}
				}else{
					$("#form_tools").html("<tr><td colspan='7'>没有记录！</td></tr>");
				}
				
				$("#form").dialog({
					height: 500,
					width: 700,
					buttons :{
						"关闭" : function(){
							$(this).dialog("close");
						}
					}
				})
			},
			error:function(){
				alert("error");
			}
		})
	})
	
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
	
	//获取道具ID与道具名称数据
	var getDetail = function(page){
		$.ajax({
			type : "post",
			url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/gmtoolsask/getToolDetail",
			dataType : "json",
			data : {
				searchKey : $("#searchKey").val(),
				type1 : $("#t_type1").val(),
				type2 : $("#t_type2").val(),
				type3 : $("#t_type3").val(),
				pageSize : 10,
				curPage : page,
				ip : $("#sip").val()
			},
			cache : false,
			success : function(data){				
				if(typeof(data.list) != 'undefined' && data.list.length > 0){
					var html = "";
					var type1_html = "<option value=''>请选择</option>";
					
					var type1_map = data.type1_map;
					var type2_map = data.type2_map;
					var type3_map = data.type3_map;
					
					for(var i in type1_map) {
						type1_html += "<option value='"+ i +"'>" + type1_map[i] + "</option>";
					}
					
					if($("body").data('type1_map') != '1') {
						$("#t_type1").html(type1_html);
					}
					
					$("body").data('type1_map', '1');
					$("body").data('type2_map', type2_map);
					$("body").data('type3_map', type3_map);
					
					for(var i in data.list){
						var type2_name = type2_map[data.list[i]["t_type1"]][data.list[i]["t_type2"]];
						var type3_name = '';
						
						if(typeof(type3_map[data.list[i]["t_type1"]][data.list[i]["t_type2"]]) == 'undefined') {
							type3_name = '未知'
						} else if(typeof(type3_map[data.list[i]["t_type1"]][data.list[i]["t_type2"]][data.list[i]["t_type3"]]) == 'undefined'){
							type3_name = '未知'
						} else {
							type3_name = type3_map[data.list[i]["t_type1"]][data.list[i]["t_type2"]][data.list[i]["t_type3"]];
						}	
						
						if(typeof(type2_name) == 'undefined') {
							type2_name = '未知';
						}
						
						html += "<tr>";
						html += "<td class='t_code'>"+data.list[i]["t_code"]+"</td>";
						html += "<td class='t_name'>"+data.list[i]["t_name"]+"</td>";
						html += "<td>"+type1_map[data.list[i]["t_type1"]]+"</td>";
						html += "<td>"+type2_name+"</td>";
						html += "<td>"+type3_name+"</td>";
						html += "<td><span class='choose'>选择</span></td>";
						html += "</tr>";
					}	
					$("#dform_body").html(html);
					$("#pagehtml2").html(data.pageHtml);
				}else{
					$("#pagehtml2").html("");
					$("#dform_body").html("<tr><td colspan='8'>没有记录！</td></tr>");
				}
				
				$("#dform").dialog({
					height: 500,
					width: 1140,
					buttons :{
						"关闭" : function(){
							$(this).dialog("close");
						}
					}
				})
			},
			error:function(){
				alert("error");
			}
		})
	}
	
	$("#t_type1").change(function(){
		var select_value = $("#t_type1").val();
		if(select_value != '') {
			var type2_map = $("body").data("type2_map");
			var list  = type2_map[select_value];
			var type2_html = "<option value=''>请选择</option>";
			for(var i in list) {
				type2_html += "<option value='"+ i +"'>" + list[i] + "</option>";
			}
			$("#t_type2").html(type2_html);
		}
	})
	
	$("#t_type2").change(function(){
		var type1_value = $("#t_type1").val();
		var select_value = $("#t_type2").val();
		if(select_value != '') {
			var type3_map = $("body").data("type3_map");
			var list  = type3_map[type1_value][select_value];
			var type3_html = "<option value=''>请选择</option>";
			for(var i in list) {
				type3_html += "<option value='"+ i +"'>" + list[i] + "</option>";
			}
			$("#t_type3").html(type3_html);
		}
	})

	//选择道具
	$(".choose").live("click",function(){
		var code = $(this).parent().parent().find(".t_code").html();	//道具ID
		var name = $(this).parent().parent().find(".t_name").html();	//道具名称
		
		$("#tolName").val(name);
		$("#toolsId").val(code);
		$("#toolsNum").val(1);
		$("#dform").dialog("close");
	})
	
	//更新道具列表
	$("#update_tool").click(function(){
		if(confirm('确定要更新道具列表吗？')){
			$("#dform").dialog("close");
			$.ajax({
				type : 'post',
				url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/parsexml/parse',
				dataType : 'json',
				data : {
					ip : $("#sip").val()
				},
				cache : false,
				async : false,
				beforeSend : function(){
					$(".overlay").show();
				},
				complete : function(){
					$(".overlay").hide();
				},
				success : function(data){
					$(".overlay").hide();
					if(data == 'success') {
						alert('更新成功！');
						getDetail(1);
					} else if(data == 'error') {
						alert('更新失败！');
					}
				},
				error : function(){
					alert('更新失败！');
				}
			})
		}
	})
	
	//道具ID点击事件
	$("#tools_icon").click(function(){	
		getDetail(1);
	})
	
	//道具搜索
	$("#toolSearch").click(function(){
		getDetail(1);
	})
	
	
	//取消申请
	$(".cancle_ask").live('click', function(){
		var id = $(this).parent().parent().attr("id").substr("4");
		$.ajax({
			type : 'post',
			url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/gmtoolsask/cancleAsk',
			dataType : 'json',
			data : {
				id : id
			},
			async : false,
			beforeSend : function(){
				$(".overlay").show();
			},
			complete : function(){
				$(".overlay").hide();
			},
			success : function(data){
				$(".overlay").hide();
				if(data == 'success') {
					alert('取消成功！');
					showTable(1);
				} else if(data == 'error') {
					alert('取消失败！');
				}
			},
			error : function(){
				alert('取消失败！');
			}
		})
	})
		
	</script>
</body>
</html>
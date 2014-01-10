<?php /* Smarty version 2.6.18, created on 2014-01-07 18:24:02
         compiled from gmtools/gm_operate.html */ ?>
<!DOCTYPE html>
<html>
<head>
<title>运营工具-用户操作</title>
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
		<div  id="user-tabs"  style="margin-top:20px;">
			<span id="1">禁言</span>
			<span id="2" class="user-gray">冻结</span>
			<span id="3" class="user-gray">下线</span>
			<hr/>
		</div>
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
							<td width="95%" class="tableleft">1、输入角色名对玩家实行<font color = "red"><b>禁言</b></font>/<font color = "red"><b>解禁</b></font>；</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">2、批量导入现只支持TXT文件，编辑时间隔符为<font color = "red"><b>英文分号</b></font>；如有不懂，请咨询Admin！</td>
						</tr>
					</tbody>
				</table>
			</div>	
			<div>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left" class="toptable">
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
							<td width="5%" class="tableright">角色名：</td>
							<td width="95%" class="tableleft">
								<input type="input" class="input1" id="rolename1" size='115'/>
								<label><font color="red"><i><b>注</b>：批量账号则用英文分号隔开区分，例子:a;b;c</i></font></label>
							</td>
						</tr>
						<tr>
							<td width="5%" class="tableright"><span>批量导入：</span></td>
							<td width="95%" class="tableleft">
								<form name="form" action="" method="POST" enctype="multipart/form-data">
									<input id="fileToUpload" type="file" size="45" name="fileToUpload"/>
									<input type="button" value="上传" id="1" class="uploadbtn"/>
									<label><font color="red"><i><b>注</b>：批量账号导入支持txt</i></font></label>
								</form>	
							</td>
						</tr>
						
						<tr>
							<td width="5%" class="tableright">
								<span>禁言时长：</span>
							</td>
							<td width="95%" class="tableleft">
								<select id="stoptime">
									<option value="-1">永久</option>
									<option value="60">1分钟</option>
									<option value="300">5分钟</option>
									<option value="600">10分钟</option>
									<option value="1800">30分钟</option>
									<option value="3600">1小时</option>
								</select>
								<!-- <input type="text"  value="120" size="6"/> -->
							</td>
						</tr>
						<tr>
							<td width="5%" class="tableright">
								<span>操作原因：</span>
							</td>
							<td width="95%" class="tableleft">
								<textarea class="input1" cols="100" rows="2" id="reason">玩家因发布不文明信息，禁言</textarea>
								<input type="button" value="禁言" id="stopspeak"/>
								<input type="button" value="解禁" id="allowspeak"/>
								<input type="button" value="刷新页面查看处理结果" onclick="showTable(1);"/>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div style="clear: both"></div>
			<div>
				<table class="mytable">
					<thead>
						<tr>
							<th>服务器</th>
							<th>角色名</th>
							<th>状态</th>
							<th>解禁时间</th>
							<th>禁言时长</th>
							<th>封禁原因</th>
							<th>操作者</th>
							<th>角色状态</th>
							<th>请求状态</th>
						</tr>
					</thead>
					<tbody id="stoptable">
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
				<input type="hidden" value="" id="ids"/>
			</div>
		</div>
		<div id="tabs-2" class="tabitem" style="display:none">	
			<div>
				<table class="explain">
					<thead>
					</thead>
					<tbody style="font-family:Mingliu">
						<tr>
							<td width="5%"  class="tableleft"><b>说明：</b></td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">1、输入角色名对玩家实行<font color = "red"><b>冻结</b></font>/<font color = "red"><b>解冻</b></font>；<font color = "red"><b>冻结</b></font>玩家在所冻结时间内无法再上线,请谨慎操作！</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">2、批量导入现只支持TXT文件，编辑时间隔符为<font color = "red"><b>英文分号</b></font>；如有不懂，请咨询Admin！</td>
						</tr>
					</tbody>
				</table>
			</div>	
			<div>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left" class="toptable">
					<thead>
					</thead>
					<tbody>
						<tr>
							<td width="5%" class="tableright">
								<span>服务器：</span>
							</td>
							<td width="95%" class="tableleft">
								<select id="sip2">
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
							<td width="5%" class="tableright">角色名：</td>
							<td width="95%" class="tableleft">
								<input type="input" class="input1" id="rolename2" size='117'/>
								<label><font color="red"><i><b>注</b>：批量账号则用英文分号隔开区分，例子:a;b;c</i></font></label>
							</td>
						</tr>
						<tr>
							<td width="5%" class="tableright"><span>批量导入：</span></td>
							<td width="95%" class="tableleft">
								<form name="form" action="" method="POST" enctype="multipart/form-data">
									<input id="fileToUpload2" type="file" size="45" name="fileToUpload2"/>
									<input type="button" value="上传" id="2" class="uploadbtn"/>
									<label><font color="red"><i><b>注</b>：批量账号导入支持txt</i></font></label>
								</form>	
							</td>
						</tr>
						
						<tr>
							<td width="5%" class="tableright">
								<span>冻结时长：</span>
							</td>
							<td width="95%" class="tableleft">
								<select id="freezetime">
									<option value="-1">永久</option>
									<option value="60">1分钟</option>
									<option value="300">5分钟</option>
									<option value="600">10分钟</option>
									<option value="1800">30分钟</option>
									<option value="3600">1小时</option>
								</select>
								<!-- <input type="text"  value="120" size="6"/> -->
							</td>
						</tr>
						<tr>
							<td width="5%" class="tableright">
								<span>操作原因：</span>
							</td>
							<td width="95%" class="tableleft">
								<textarea class="input1" cols="100" rows="2" id="freezereason">玩家开挂，冻结</textarea>
								<input type="button" value="冻结" id="freezeRole"/>
								<input type="button" value="解冻" id="unfreezeRole"/>
								<input type="button" value="刷新页面查看处理结果" onclick="showFTable(1);"/>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div style="clear: both"></div>
			<div>
				<table  class="mytable">
					<thead>
						<tr>
							<th>服务器</th>
							<th>角色名</th>
							<th>状态</th>
							<th>解冻时间</th>
							<th>冻结时长</th>
							<th>冻结原因</th>
							<th>操作者</th>
							<th>角色状态</th>
							<th>请求状态</th>
						</tr>
					</thead>
					<tbody id="freezetable">
					</tbody>
				</table>
				<div id="pagehtml2" style="float:right;margin-right:20px"></div>
				<div id="example_length2" class="dataTables_length" style="display:none">
					<label>每页显示
						<select id="menu2" name="example_length" size="1" aria-controls="example">
							<option value="10" selected="selected">10</option>
							<option value="25">25</option>
							<option value="50">50</option>
							<option value="100">100</option>
						</select> 条记录
					</label>
				</div>
				<input type="hidden" value="" id="ids2"/>
			</div>
		</div>
		<div id="tabs-3" class="tabitem" style="display:none">	
			<div>
				<table class="explain">
					<thead>
					</thead>
					<tbody style="font-family:Mingliu">
						<tr>
							<td width="5%"  class="tableleft"><b>说明：</b></td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">1、输入角色名对玩家实行<font color = "red"><b>下线</b></font>操作；</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">2、批量导入现只支持TXT文件，编辑时间隔符为<font color = "red"><b>英文分号</b></font>；如有不懂，请咨询Admin！</td>
						</tr>
					</tbody>
				</table>
			</div>	
			<div>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left" class="toptable">
					<thead>
					</thead>
					<tbody>
						<tr>
							<td width="5%" class="tableright">
								<span>服务器：</span>
							</td>
							<td width="95%" class="tableleft">
								<select id="sip3">
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
							<td width="5%" class="tableright">角色名：</td>
							<td width="95%" class="tableleft">
								<input type="input" class="input1" id="rolename3" size='117'/>
								<label><font color="red"><i><b>注</b>：批量账号则用英文分号隔开区分，例子:a;b;c</i></font></label>
							</td>
						</tr>
						<tr>
							<td width="5%" class="tableright"><span>批量导入：</span></td>
							<td width="95%" class="tableleft">
								<form name="form" action="" method="POST" enctype="multipart/form-data">
									<input id="fileToUpload3" type="file" size="45" name="fileToUpload3"/>
									<input type="button" value="上传" id="3" class="uploadbtn"/>
									<label><font color="red"><i><b>注</b>：批量账号导入支持txt</i></font></label>
								</form>	
							</td>
						</tr>

						<tr>
							<td width="5%" class="tableright">
								<span>操作原因：</span>
							</td>
							<td width="95%" class="tableleft">
								<textarea class="input1" cols="100" rows="2" id="offreason">玩家因发布不文明信息，强制下线</textarea>
								<input type="button" value="强制下线" id="offlines"/>
								<input type="button" value="刷新页面查看处理结果" onclick="showOTable(1);"/>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div style="clear:both"></div>
			<div>
				<table  class="mytable">
					<thead>
						<tr>
							<th>服务器</th>
							<th>角色名</th>
							<th>状态</th>
							<th>下线时间</th>
							<th>下线原因</th>
							<th>操作者</th>
							<th>角色状态</th>
							<th>请求状态</th>
						</tr>
					</thead>
					<tbody id="offlinetable">
					</tbody>
				</table>
				<div id="pagehtml3" style="float:right;margin-right:20px"></div>
				<div id="example_length3" class="dataTables_length" style="display:none">
					<label>每页显示
						<select id="menu3" name="example_length" size="1" aria-controls="example">
						<option value="10" selected="selected">10</option>
						<option value="25">25</option>
						<option value="50">50</option>
						<option value="100">100</option>
						</select> 条记录
					</label>
				</div>
				<input type="hidden" value="" id="ids3"/>
			</div>
		</div>
		<div style="clear:both"></div>
		<div style="height:50px">&nbsp;</div>
	</div>
	
	
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery-ui.js" type="text/javascript"></script> 
<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/function.js" type="text/javascript"></script>
<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/ajaxfileupload.js" type="text/javascript"></script>
<script type="text/javascript">
	var flag = true;//全局变量，防止重复提交
	showTitle("运营工具:禁言");
	
	//ajax文件上传
	$(".uploadbtn").click(function(){
		var id = $(this).attr("id");
		var did = $(this).prev().attr("id");
		var filename =$(this).prev().attr("name");
		$.ajaxFileUpload({
			url:'<?php echo $this->_tpl_vars['app']; ?>
/upload/upload', //你处理上传文件的服务端
			secureuri:false,
			fileElementId:did,
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
						$("#rolename"+id).val("");
						$("#rolename"+id).val(data.msg);
					}
				}
			},
			error: function (data, status, e)
			{
				alert(e);
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
	
	//禁言切换服务器
	$("#sip").change(function(){
		showTable(1);
	})

	//禁言获取表格数据
	var showTable =  function(page){
		$.ajax({
			type:"GET",
			url:"<?php echo $this->_tpl_vars['logicApp']; ?>
/gmoperate/getStopInfo",
			data:
			{
				ip : $("#sip").val(),
				pageSize : $("#menu").val(),
				curPage : page,
				time : Date.parse(new Date())
			},
			dataType:"json",
			beforeSend:function(){
				$("#stoptable").html("");//清空表格，防止叠加
				$("#stoptable").append("<tr><td colspan='10'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
			},
			success:function(data){
				var list = [];
				if(typeof(data.list) != 'undefined'){
					list = data.list;
				}
				if(list.length > 0 ){
					$("#stoptable").html("");//清空表格，防止叠加
					$("#pagehtml").html("");//清除分页 
					$("#example_length").show();//显示每页
					var html = "";
					for(var i in list){
						var crole = "noclass";
						var ccall = "noclass";
						switch(parseInt(list[i]["s_callstatus"])){
							case 0: list[i]["s_callstatus"] = "失败";ccall="fail";break;
							case 1: list[i]["s_callstatus"] = "正在处理";break;
							case 2: list[i]["s_callstatus"] = "成功";break;
							default: list[i]["s_callstatus"] = "未知";ccall="fail";
						}
						switch(parseInt(list[i]["s_rolestatus"])){
							case 0: ccall="fail";break;
							case 1: list[i]["s_rolestatus"] = "正在处理";break;
							case 2: list[i]["s_rolestatus"] = "成功";break;		
							//default: list[i]["s_rolestatus"] = "未知";crole="fail";
						}
					
						switch(parseInt(list[i]["s_status"])){
							case 1: list[i]["s_status"] = "禁言";break;
							case 2: list[i]["s_status"] = "解禁";break;
							default: list[i]["s_status"] = "未知";crole="fail";
						}
						if(parseInt(list[i]["s_secends"]) == -1){
							list[i]["s_secends"] = "永久";
							list[i]["s_time"] = "永久"
						}else{
							list[i]["s_secends"] = list[i]["s_secends"] + "s";
						}
						
						html += "<tr id="+"stop"+list[i]["s_id"]+" >";
						html += "<td>"+data.ipList[list[i]["s_ip"]]+"</td>";
						html += "<td>"+list[i]["s_role_name"]+"</td>";
						html += "<td>"+list[i]["s_status"]+"</td>";
						html += "<td>"+list[i]["s_time"]+"</td>";
						html += "<td>"+list[i]["s_secends"]+"</td>";
						html += "<td>"+list[i]["s_reason"]+"</td>";
						html += "<td>"+list[i]["s_operaor"]+"</td>";
						html += "<td class=\""+crole+"\">"+list[i]["s_rolestatus"]+"</td>";
						html += "<td class=\""+ccall+"\">"+list[i]["s_callstatus"]+"</td>";
						html += "</tr>"
			
					}
					$("#stoptable").append(html);
					$("#stoptable tr:odd").css("background-color", "#edf2f7"); 
					$("#stoptable tr:even").css("background-color","#e0f0f0");
					colourTd("ids","stop");
					$("#pagehtml").html(data.pageHtml);
				}else{
					$("#stoptable").html("");//清空表格，防止叠加
					$("#stoptable").append("<tr><td colspan='10'>没有数据！</td></tr>");
				}
			},
			error:function(){
				$("#stoptable").html("");//清空表格，防止叠加
				$("#stoptable").append("<tr><td colspan='10'>没有数据！</td></tr>");
			}
		})
	}
	
	//禁言每页显示
	$("#menu").change(function(){
		showTable(1);
	});
	
	//冻结切换服务器
	$("#sip2").change(function(){
		showFTable(1);
	})

	//冻结获取表格数据
	var showFTable =  function(page){
		$.ajax({
			type:"GET",
			url:"<?php echo $this->_tpl_vars['logicApp']; ?>
/gmoperate/getFreezeInfo",
			data:
			{
				ip : $("#sip2").val(),
				pageSize : $("#menu2").val(),
				curPage : page,
				time : Date.parse(new Date())
			},
			dataType:"json",
			beforeSend:function(){
				$("#freezetable").html("");//清空表格，防止叠加
				$("#freezetable").append("<tr><td colspan='10'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
			},
			success:function(data){
				var list = [];
				if(typeof(data.list) != 'undefined'){
					list = data.list;
				}
				if(list.length > 0 ){
					$("#freezetable").html("");//清空表格，防止叠加
					$("#pagehtml2").html("");//清除分页 
					$("#example_length2").show();//显示每页
					var html = "";
					for(var i in list){
						var crole = "noclass";
						var ccall = "noclass";
	
						
						switch(parseInt(list[i]["f_callstatus"])){
							case 0: list[i]["f_callstatus"] = "失败";ccall="fail";break;
							case 1: list[i]["f_callstatus"] = "正在处理";break;
							case 2: list[i]["f_callstatus"] = "成功";break;
							default: list[i]["f_callstatus"] = "未知";ccall="fail";
						}
						switch(parseInt(list[i]["f_rolestatus"])){
							case 0: ccall="fail";break;
							case 1: list[i]["f_rolestatus"] = "正在处理";break;
							case 2: list[i]["f_rolestatus"] = "成功";break;		
							//default: list[i]["s_rolestatus"] = "未知";crole="fail";
						}
						
						
						switch(parseInt(list[i]["f_status"])){
							case 1: list[i]["f_status"] = "冻结";break;
							case 2: list[i]["f_status"] = "解冻";break;
							default: list[i]["f_status"] = "未知";crole="fail";
						}
						if(parseInt(list[i]["f_secends"]) == -1){
							list[i]["f_secends"] = "永久";
							list[i]["f_time"] = "永久"
						}else{
							list[i]["f_secends"] = list[i]["f_secends"] + "s";
						}
						
						html += "<tr id="+"freeze"+list[i]["f_id"]+" >";
						html += "<td>"+data.ipList[list[i]["f_ip"]]+"</td>";
						html += "<td>"+list[i]["f_role_name"]+"</td>";
						html += "<td>"+list[i]["f_status"]+"</td>";
						html += "<td>"+list[i]["f_time"]+"</td>";
						html += "<td>"+list[i]["f_secends"]+"</td>";
						html += "<td>"+list[i]["f_reason"]+"</td>";
						html += "<td>"+list[i]["f_operaor"]+"</td>";
						html += "<td class=\""+crole+"\">"+list[i]["f_rolestatus"]+"</td>";
						html += "<td class=\""+ccall+"\">"+list[i]["f_callstatus"]+"</td>";
						html += "</tr>";
					}
					$("#freezetable").append(html);
					$("#freezetable tr:odd").css("background-color", "#edf2f7"); 
					$("#freezetable tr:even").css("background-color","#e0f0f0");
					colourTd("ids2","freeze");
					$("#pagehtml2").html(data.pageHtml);
				}else{
					$("#freezetable").html("");//清空表格，防止叠加
					$("#freezetable").append("<tr><td colspan='10'>没有数据！</td></tr>");
				}
			},
			error:function(){
				$("#freezetable").html("");//清空表格，防止叠加
				$("#freezetable").append("<tr><td colspan='10'>没有数据！</td></tr>");
			}
		})
	}
	
	//冻结每页显示
	$("#menu2").change(function(){
		showFTable(1);
	});
	
	//强制下线切换服务器
	$("#sip3").change(function(){
		showOTable(1);
	})

	//强制下线获取表格数据
	var showOTable =  function(page){
		$.ajax({
			type:"GET",
			url:"<?php echo $this->_tpl_vars['logicApp']; ?>
/gmoperate/getOfflineInfo",
			data:
			{
				ip : $("#sip3").val(),
				pageSize : $("#menu3").val(),
				curPage : page,
				time : Date.parse(new Date())
			},
			dataType:"json",
			beforeSend:function(){
				$("#offlinetable").html("");//清空表格，防止叠加
				$("#offlinetable").append("<tr><td colspan='10'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
			},
			success:function(data){
				var list = [];
				if(typeof(data.list) != 'undefined'){
					list = data.list;
				}
				if(list.length > 0 ){
					$("#offlinetable").html("");//清空表格，防止叠加
					$("#pagehtml3").html("");//清除分页 
					$("#example_length3").show();//显示每页
					var html = "";
					for(var i in list){
						var crole = "noclass";
						var ccall = "noclass";

					
						switch(parseInt(list[i]["f_callstatus"])){
							case 0: list[i]["f_callstatus"] = "失败";ccall="fail";break;
							case 1: list[i]["f_callstatus"] = "正在处理";break;
							case 2: list[i]["f_callstatus"] = "成功";break;
							default: list[i]["f_callstatus"] = "未知";ccall="fail";
						}
						switch(parseInt(list[i]["f_rolestatus"])){
							case 0: ccall="fail";break;
							case 1: list[i]["f_rolestatus"] = "正在处理";break;
							case 2: list[i]["f_rolestatus"] = "成功";break;		
						}
						
						
						switch(parseInt(list[i]["f_status"])){
							case 1: list[i]["f_status"] = "下线";break;
							default: list[i]["f_status"] = "未知";crole="fail";
						}
						
						html += "<tr id="+"offline"+list[i]["f_id"]+" >";
						html += "<td>"+data.ipList[list[i]["f_ip"]]+"</td>";
						html += "<td>"+list[i]["f_role_name"]+"</td>";
						html += "<td>"+list[i]["f_status"]+"</td>";
						html += "<td>"+list[i]["f_time"]+"</td>";
						html += "<td>"+list[i]["f_reason"]+"</td>";
						html += "<td>"+list[i]["f_operaor"]+"</td>";
						html += "<td class=\""+crole+"\">"+list[i]["f_rolestatus"]+"</td>";
						html += "<td class=\""+ccall+"\">"+list[i]["f_callstatus"]+"</td>";
						html += "</tr>"
					}
					$("#offlinetable").append(html);
					$("#offlinetable tr:odd").css("background-color", "#edf2f7"); 
					$("#offlinetable tr:even").css("background-color","#e0f0f0");
					colourTd("ids3","offline");
					$("#pagehtml3").html(data.pageHtml);
				}else{
					$("#offlinetable").html("");//清空表格，防止叠加
					$("#offlinetable").append("<tr><td colspan='10'>没有记录！</td></tr>");
				}
			},
			error:function(){
				$("#offlinetable").html("");//清空表格，防止叠加
				$("#offlinetable").append("<tr><td colspan='10'>没有记录！</td></tr>");
			}
		})
	}
	
	//强制下线每页显示
	$("#menu3").change(function(){
		showOTable(1);
	});

	//切换标签
	$("#user-tabs span").click(function(){
		$(".tabitem").hide();
		var id  = "#tabs-"+this.id;
		if(1 == parseInt(this.id)){
			$("#user-tabs span").attr("class","user-gray");//标签切换颜色
			$(this).removeClass("user-gray");
			showTitle("运营工具:禁言");
			showTable(1);	//禁言
		}else if(2 == parseInt(this.id)){
			$("#user-tabs span").attr("class","user-gray");//标签切换颜色
			$(this).removeClass("user-gray");
			showTitle("运营工具:冻结");
			showFTable(1);	//冻结
		}else if(3 == parseInt(this.id)){
			$("#user-tabs span").attr("class","user-gray");//标签切换颜色
			$(this).removeClass("user-gray");
			showTitle("运营工具:下线");
			showOTable(1);	//下线数据
		}
		
		$(id).show();
	})
	
	//禁言
	$("#stopspeak").click(function(){
		var ip = $("#sip").val();
		var rolename = $("#rolename1").val();
		var stoptime = $("#stoptime").val();
		var reason = $("#reason").val();
		var time = Date.parse(new Date());
		
		if("" == rolename){
			alert("请输入角色名！");
			return false;
		}
		
		$.post(
			'<?php echo $this->_tpl_vars['logicApp']; ?>
/gmoperate/stoptalk',
			{
				ip : ip,
				rolename : rolename,
				stoptime :stoptime,
				reason : reason,
				time : time
			},
			function(data){
				if(typeof(data.error) != 'undefined')
				{
					if(data.error != '')
					{
						alert(data.error);
					}
				}else{
					$("#ids").val(data.ids);
					showTable(1);
				}
			},
			'json'
		)
	})
	
	//页面加载禁言数据
	showTable(1);
	
	//解禁
	$("#allowspeak").click(function(){
		var ip = $("#sip").val();
		var rolename = $("#rolename1").val();
		var stoptime = 0
		var reason = $("#reason").val();
		var time = Date.parse(new Date());
		
		if("" == rolename){
			alert("请输入角色名！");
			return false;
		}
		
		$.post(
			'<?php echo $this->_tpl_vars['logicApp']; ?>
/gmoperate/allowtalk',
			{
				ip : ip,
				rolename : rolename,
				stoptime :stoptime,
				reason : reason,
				time : time
			},
			function(data){
				if(typeof(data.error) != 'undefined')
				{
					if(data.error != '')
					{
						alert(data.error);
					}
				}else{
					$("#ids").val(data.ids);
					showTable(1);
				}
			},
			'json'
		)
	})
	
	
	//冻结
	$("#freezeRole").click(function(){
		var ip = $("#sip2").val();
		var rolename = $("#rolename2").val();
		var freezetime = $("#freezetime").val();
		var reason = $("#freezereason").val();
		var time = Date.parse(new Date());
		
		if("" == rolename){
			alert("请输入角色名！");
			return false;
		}
		
		$.post(
			'<?php echo $this->_tpl_vars['logicApp']; ?>
/gmoperate/freeze',
			{
				ip : ip,
				rolename : rolename,
				freezetime :freezetime,
				reason : reason,
				time : time
			},
			function(data){
				if(typeof(data.error) != 'undefined')
				{
					if(data.error != '')
					{
						alert(data.error);
					}
				}else{
					$("#ids2").val(data.ids);
					showFTable(1);
				}
			},
			'json'
		)
	})
	
	//解冻
	$("#unfreezeRole").click(function(){
		var ip = $("#sip2").val();
		var rolename = $("#rolename2").val();
		var freezetime = 0;
		var reason = $("#freezereason").val();
		var time = Date.parse(new Date());
		
		if("" == rolename){
			alert("请输入角色名！");
			return false;
		}
		
		$.post(
			'<?php echo $this->_tpl_vars['logicApp']; ?>
/gmoperate/unfreeze',
			{
				ip : ip,
				rolename : rolename,
				freezetime :freezetime,
				reason : reason,
				time : time
			},
			function(data){
				if(typeof(data.error) != 'undefined')
				{
					if(data.error != '')
					{
						alert(data.error);
					}
				}else{
					$("#ids2").val(data.ids);
					showFTable(1);
				}
			},
			'json'
		)
	})
	
	//强制下线
	$("#offlines").click(function(){
		var ip = $("#sip3").val();
		var rolename = $("#rolename3").val();
		var reason = $("#offreason").val();
		var time = Date.parse(new Date());
		
		if("" == rolename){
			alert("请输入角色名！");
			return false;
		}
		
		$.post(
			'<?php echo $this->_tpl_vars['logicApp']; ?>
/gmoperate/offline',
			{
				ip : ip,
				rolename : rolename,
				reason : reason,
				time : time
			},
			function(data){
				if(typeof(data.error) != 'undefined')
				{
					if(data.error != '')
					{
						alert(data.error);
					}
				}else{
					$("#ids3").val(data.ids);
					showOTable(1);
				}
			},
			'json'
		)
	});

	//分页ajax函数
	var formAjax = function(page){
		showTable(page);
	}

	var freezeAjax = function(page){
		showFTable(page);
	}

	var offAjax = function(page){
		showOTable(page);
	}

	//跳到相应页面 
	var go = function(){
		var pagenum = $("#page").val();
		if(pagenum=='' || isNaN(pagenum) || pagenum <= 0){
			alert('请输入一个正整数！');
			$("#page").val(1);
		}else{
			formAjax(pagenum);
		}
	}

	var fgo = function(){
		var pagenum = $("#fpage").val();
		if(pagenum=='' || isNaN(pagenum) || pagenum <= 0){
			alert('请输入一个正整数！');
			$("#fpage").val(1);
		}else{
			freezeAjax(pagenum);
		}
	}

	var ogo = function(){
		var pagenum = $("#opage").val();
		if(pagenum=='' || isNaN(pagenum) || pagenum <= 0){
			alert('请输入一个正整数！');
			$("#opage").val(1);
		}else{
			offAjax(pagenum);
		}
	}
</script>
</body>
</html>
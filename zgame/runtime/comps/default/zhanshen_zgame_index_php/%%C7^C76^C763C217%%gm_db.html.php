<?php /* Smarty version 2.6.18, created on 2014-01-06 15:05:04
         compiled from system/gm_db.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>运营设置</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?php echo $this->_tpl_vars['res']; ?>
/css/skin.css" rel="stylesheet" type="text/css">
<link href="<?php echo $this->_tpl_vars['res']; ?>
/css/jquery-ui.css" rel="stylesheet" type="text/css">
<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery-ui.js" type="text/javascript"></script> 
<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/function.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
	//table单双行交叉样式
	$(".mytable tr:odd").css("background-color", "#edf2f7"); 
	$(".mytable tr:even").css("background-color","#e0f0f0"); 
	
	showTitle("其他工具:gm设置");
	
	//编辑
	$(".editbtn").live("click",function(){
		var dom = $(this);
		var cid = dom.parent().parent().attr("id");
		$.get(
				'<?php echo $this->_tpl_vars['app']; ?>
/server/getById',
				{
					id : cid,
					time : Date.parse(new Date())
				},
				function(data){
					if(data){
						$("#name").val(data["s_name"]);
						$("#ip").val(data["s_ip"]);
						$("#port").val(data["s_port"]);
						$("#domain").val(data["s_domain"]);
						$("#sid").val(data["s_sid"]);
						$("#form").dialog({                                                                                                                   
							height: 300,
							width: 400,
							buttons: {
								'保存' : function(){
									$.post(
										'<?php echo $this->_tpl_vars['app']; ?>
/server/save',
										{
											id : cid,
											name : $("#name").val(),
											ip : $("#ip").val(),
											port : $("#port").val(),
											domain : $("#domain").val(),
											sid : $("#sid").val(),
											time : Date.parse(new Date())
										},
										function(data){
											if(data == "error"){
												alert("保存失败!");
											}else if(data == "success"){
												window.location.reload();
											}
										},
										'json'
									)
								},
								'取消' : function(){
									$(this).dialog("close");
								}
							}
						})
					}else{
						alert("编辑失败！");
					}
				},
				'json'
		)
	})
	
	//删除
	$(".deletebtn").live("click",function(){
		var dom = $(this);
		var cid = dom.parent().parent().attr("id");
		$("#confirm").dialog({
			height: 150,
			width: 300,
			buttons: {
				'确定' : function(){
					$.post(
						'<?php echo $this->_tpl_vars['app']; ?>
/server/delete',
						{
							id : cid,
							time : Date.parse(new Date())
						},
						function(data){
							if(data == "error"){
								alert("删除失败!");
							}else if(data == "success"){
								window.location.reload();
							}
						},
						'json'
					)
				},
				'取消' : function(){
					$(this).dialog("close");
				}
			}
		})
	})
	
	
	//添加 
	$("#addbtn").click(function(){
		$("#name").val("");
		$("#ip").val("");
		$("#port").val("");
		$("#domain").val("");
		$("#form").dialog({
			height: 300,
			width: 400,
			buttons: {
				'确定' : function(){
					$.post(
						'<?php echo $this->_tpl_vars['app']; ?>
/server/add',
						{
							name : $("#name").val(),
							ip : $("#ip").val(),
							port : $("#port").val(),
							domain : $("#domain").val(),
							time : Date.parse(new Date())
						},
						function(data){
							if(data == "error"){
								alert("添加失败!");
							}else if(data == "success"){
								window.location.reload();
							}
						},
						'json'
					)
				},
				'取消' : function(){
					$(this).dialog("close");
				}
			}
		})
	})
	
})
</script>
<style type="text/css">
<!-- 
    .mytable td { 
    	font-size: 15px; 
    } 
-->
</style>
</head>
<body>
	<div>
		<div>
			<div>
				<table class="explain">
					<thead>
					</thead>
					<tbody style="font-family:Mingliu">
						<tr>
							<td width="5%"  class="tableleft"><b>说明：</b></td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">1、此页面用于GM服务器配置</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">1、配置优先域名，如果域名不存在，务必留空</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div style="float:right;margin-top:10px;">
 				<!--<img src="<?php echo $this->_tpl_vars['res']; ?>
/images/add-btn2.png" style="cursor: pointer;" id="addbtn"/>-->
 			</div>
 			<div style="clear: both;"></div>
			<div>
				<table  class="mytable" cellspacing="0" align="center">
					<thead>
						<tr>
							<th>游戏服ID</th>
							<th>服务器IP</th>
							<th>域名</th>
							<th>端口</th>
							<th>名称</th>
							<th>更新时间</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<?php unset($this->_sections['key']);
$this->_sections['key']['name'] = 'key';
$this->_sections['key']['loop'] = is_array($_loop=$this->_tpl_vars['plist']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['key']['show'] = true;
$this->_sections['key']['max'] = $this->_sections['key']['loop'];
$this->_sections['key']['step'] = 1;
$this->_sections['key']['start'] = $this->_sections['key']['step'] > 0 ? 0 : $this->_sections['key']['loop']-1;
if ($this->_sections['key']['show']) {
    $this->_sections['key']['total'] = $this->_sections['key']['loop'];
    if ($this->_sections['key']['total'] == 0)
        $this->_sections['key']['show'] = false;
} else
    $this->_sections['key']['total'] = 0;
if ($this->_sections['key']['show']):

            for ($this->_sections['key']['index'] = $this->_sections['key']['start'], $this->_sections['key']['iteration'] = 1;
                 $this->_sections['key']['iteration'] <= $this->_sections['key']['total'];
                 $this->_sections['key']['index'] += $this->_sections['key']['step'], $this->_sections['key']['iteration']++):
$this->_sections['key']['rownum'] = $this->_sections['key']['iteration'];
$this->_sections['key']['index_prev'] = $this->_sections['key']['index'] - $this->_sections['key']['step'];
$this->_sections['key']['index_next'] = $this->_sections['key']['index'] + $this->_sections['key']['step'];
$this->_sections['key']['first']      = ($this->_sections['key']['iteration'] == 1);
$this->_sections['key']['last']       = ($this->_sections['key']['iteration'] == $this->_sections['key']['total']);
?>
						<tr id="<?php echo $this->_tpl_vars['plist'][$this->_sections['key']['index']]['s_id']; ?>
">
							<td><?php echo $this->_tpl_vars['plist'][$this->_sections['key']['index']]['s_sid']; ?>
</td>
							<td><?php echo $this->_tpl_vars['plist'][$this->_sections['key']['index']]['s_ip']; ?>
</td>
							<td><?php echo $this->_tpl_vars['plist'][$this->_sections['key']['index']]['s_domain']; ?>
</td>
							<td><?php echo $this->_tpl_vars['plist'][$this->_sections['key']['index']]['s_port']; ?>
</td>
							<td><?php echo $this->_tpl_vars['plist'][$this->_sections['key']['index']]['s_name']; ?>
</td>
							<td><?php echo $this->_tpl_vars['plist'][$this->_sections['key']['index']]['s_inserttime']; ?>
</td>
							<td>
								<label class="editicon"></label><span class="editbtn">编辑</span>
								<label class="deleteicon"></label><span class="deletebtn">删除</span>
							</td>
						</tr>
						<?php endfor; endif; ?>
					</tbody>
				</table>
			</div>
		</div>
		
	<div id="form"  style="display:none">
		<div class="ajaxform">
			<table width="80%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
				<tbody>
					<tr>
						<td align="right">名称：</td>
						<td><input type="text" class="input-1" id="name" ></td>
					</tr>
					<tr>
						<td align="right">域名：</td>
						<td><input type="text" class="input-1" id="domain" ></td>
					</tr>
					<tr>
						<td align="right">服务器IP：</td>
						<td><input type="text" class="input-1" id="ip" ></td>
					</tr>
					<tr>
						<td align="right">游戏服ID：</td>
						<td><input type="text" class="input-1" id="sid" ></td>
					</tr>
					<tr>
						<td align="right">端口</td>
						<td><input type="text" class="input-1" id="port" ></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	
	<div id="confirm"  style="display:none">
		<div style="text-align: center;">确定要永久删除吗？</div>
	</div>
	</div>
</body>
</html>
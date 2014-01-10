<?php /* Smarty version 2.6.18, created on 2013-12-28 18:32:58
         compiled from recharge/recharge_query.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>充值记录查询</title>
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
							<td width="95%" class="tableleft">1、**********</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="topinfo">
				<div>
					<label>
						<span>服务器:</span>
						<select id="sip">
							<?php $_from = $this->_tpl_vars['ipList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ip']):
?>
								<option value="<?php echo $this->_tpl_vars['ip']['s_id']; ?>
"><?php echo $this->_tpl_vars['ip']['s_name']; ?>
</option>
							<?php endforeach; endif; unset($_from); ?>
						</select>
					</label>
					<label>
						<select id="code">
							<option value="0">全部</option>
							<option value="1">平台账号</option>
							<option value="2">角色名</option>
							<option value="3">角色ID</option>
						</select>
					</label>	
					<input type="text" class="input1" id="key" style="display:none"/>
					<label>
						<span>订单号:<span>
						<input type="text" class="input1" id="orderKey"/>
					</label>
					<span>时间:</span><input type="text" class="input1" id="startdate"/>至<input type="text" class="input1" id="enddate"/>
					<input type="button" value="查询" id="querybtn" style="margin-left:20px"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<div id="tabs-1" class="tabitem">	
				<div>
					<table  class="mytable" cellspacing="0" align="center" id="dtable">
						<thead>
							<tr>
								<th>ID</th>
								<th>订单号</th>
								<th>账号</th>
								<th>角色ID</th>
								<th>角色名</th>
								<th>充值时间</th>
								<th>充值获得元宝</th>
								<th>货币</th>
								<th>充值渠道</th>
							</tr>
						</thead>
						<tbody id="dtatr_body">
						</tbody>
					</table>
					
					<div id="pagehtml" style="float:right;margin-right:20px"></div>
					<div id="example_length" class="dataTables_length"  style="display:none">
						<label>每页显示
							<select id="menu" name="example_length" size="1" aria-controls="example">
							<option value="10">10</option>
							<option value="25">25</option>
							<option value="50" selected="selected">50</option>
							<option value="100">100</option>
							</select> 条记录
						</label>
					</div>
					
				</div>
				
			</div>
			
			<div style="clear:both"></div>
		</div>
		
		<div style="height:50px">&nbsp;</div>
		
	</div>
	
	
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery-ui.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/amcharts.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/function.js" type="text/javascript"></script> 	
	<script type="text/javascript">
		var recharge_query = {
			INIT : function(){
				var self = this;
				
				//时间插件
				$("#startdate").datepicker();
				$("#enddate").datepicker();
				showTitle("充值数据分析:充值记录查询");
				
				$("#code").change(function() {
					var code = $("#code").val();
					code == '0' ? $("#key").hide() : $("#key").show()				
				})
				
				$("#querybtn").click(function(){
					if($("#startdate").val() != "" && $("#enddate").val() != "") {
						if( validator("startdate", "enddate") ){
							self.show(1);
						}
					} else {
						self.show(1);
					}	
				})
			},
			
			show : function(page) {
				$.ajax({
					type : 'POST',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/rechargequery/getRecords',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $('#startdate').val(),
						endDate :	$('#enddate').val(),
						code : $('#code').val(),
						orderKey : $('#orderKey').val(),
						key : $('#key').val(),
						pageSize : $("#menu").val(),
						curPage : page
					},
					beforeSend : function() {
						$("#dtatr_body").html("<tr><td colspan='9'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
					},
					success : function (data) {
						$("#example_length").show();
						var result = data.result;
						if(typeof(result) != 'undefined' && result.length > 0) {
							var tbody = "";
							for(var i in result) {
								tbody += "<tr>";
								tbody += "<td>" + result[i]["p_id"] + "</td>";
								tbody += "<td>" + result[i]["p_order"] + "</td>";
								tbody += "<td>" + result[i]["p_acc"] + "</td>";
								tbody += "<td>" + result[i]["p_playid"] + "</td>";
								tbody += "<td>" + '暂无' + "</td>";
								tbody += "<td>" + result[i]["p_creatdate"] + "</td>";
								tbody += "<td>" + result[i]["p_money"] * data.rate + "</td>";
								tbody += "<td>" + result[i]["p_money"] + "</td>";
								tbody += "<td>" + result[i]["p_pt"] + "</td>";
								tbody += "</tr>";
							}
							$("#dtatr_body").html(tbody);
							$("#pagehtml").html(data.pageHtml);		//分页
						}else {
							$("#example_length").hide();
							$("#pagehtml").html("");
							$("#dtatr_body").html("<tr><td colspan='9'>没有数据！</td></tr>");
						}
					},
					error : function () {
						$("#example_length").hide();
						$("#pagehtml").html("");
						$("#dtatr_body").html("<tr><td colspan='9'>没有数据！</td></tr>");
					}
				})
			
			
			}
		}
		
		
		$(document).ready(function(){
			recharge_query.INIT();
			recharge_query.show(1);
		})
		
		
		//跳到相应页面 
		var go = function(){
			var pagenum = $("#page").val();
			if(pagenum=='' || isNaN(pagenum) || pagenum <= 0){
				alert('请输入一个正整数！');
				$("#page").val(1);
			}else{
				recharge_query.show(pagenum);
			}
		}
		
		//分页ajax函数
		var formAjax = function(page){
			recharge_query.show(page);
		}
	</script>
</body>
</html>
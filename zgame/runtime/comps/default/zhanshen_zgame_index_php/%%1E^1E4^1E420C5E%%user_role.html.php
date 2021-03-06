<?php /* Smarty version 2.6.18, created on 2013-12-29 16:06:45
         compiled from stickiness/user_role.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>登录与流失</title>
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
							<td width="95%" class="tableleft">1、创角率=创建角色数/进入用户数</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">1、Loading流失率=(创建角色数-进入游戏数)/创角色数</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="topinfo">
				<div>
					<span>服务器:</span>
					<select id="sip">
					<?php $_from = $this->_tpl_vars['ipList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ip']):
?>
						<option value="<?php echo $this->_tpl_vars['ip']['s_id']; ?>
" attr="<?php echo $this->_tpl_vars['ip']['g_domain']; ?>
"><?php echo $this->_tpl_vars['ip']['s_name']; ?>
</option>
					<?php endforeach; endif; unset($_from); ?>
					</select>
					<span style="margin-left: 20px">日期:</span>
					<input type="text" id="startdate" class="input1" value="<?php echo $this->_tpl_vars['startDate']; ?>
"/>至<input type="text" id="enddate" class="input1" value="<?php echo $this->_tpl_vars['endDate']; ?>
"/>
					<input type="button" value="查询" id="querybtn"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<div>
				<table  class="mytable" cellspacing="0" align="center" id="dtable">
					<thead>
						<tr>
							
							<th>平台成功跳转数</th>
							<th>平台失败跳转数</th>
							<th>进入创建角色页面数</th>
							<th>创建角色成功数</th>
							<th>创建角色后成功进入游戏数</th>
							<th>创角率</th>
							<th>loading流失率</th>
						</tr>
					</thead>
					<tbody id="dtatr_body">
					</tbody>
				</table>
			</div>
			
			<div style="float:right;margin-right:20px;display:none" id="pagehtml">
				<div class="pages">
					<a id="home_page" href="javascript:void(0)">首页</a>&nbsp;&nbsp;
					<a id="pre_page" href="javascript:void(0)">上一页</a>&nbsp;&nbsp;
					<a id="next_page" href="javascript:void(0)">下一页</a>&nbsp;&nbsp;
					<a id="last_page" href="javascript:void(0)">尾页</a>&nbsp;&nbsp;
					<span>第<span id="cur_page">1</span>/<span id="total_page">1</span>页&nbsp;&nbsp;</span>
					转到<input type="text" class="text" size="3"  id="page" value="1"/>
					<a id="go" class="go" href="javascript:void(0);"></a>页
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
	//时间插件
	$("#startdate").datepicker();
	$("#enddate").datepicker();
	page.listen();
	showTitle("用户数据分析:创角分析");

	$('#querybtn').click(function(){
		$.post("<?php echo $this->_tpl_vars['logicApp']; ?>
/userrole/getRole",{sip:$('#sip').val(),startdate:$('#startdate').val(),enddate:$('#enddate').val()},
			function(data){
				data = JSON.parse(data);
				if(typeof(data['result']) != 'undefined' && data['result'] != "") {
					var result = data.result;
					var fields = ['c_login_suc', 'c_login_fai', 'c_enter', 'c_csuccess', 'c_entergame', 'c_cjv', 'c_load'];
					page.INIT(25, result, fields, '#dtatr_body');
					$("#home_page").trigger('click');
					$("#pagehtml").show();
					$("#startdate").val(data.startdate);
				}else {
					$("#pagehtml").hide();
					$("#dtatr_body").html("<tr><td colspan='7'>没有数据！</td></tr>");
				}
			})
	});
	/*
		var user_pay = {
			object : <?php echo $this->_tpl_vars['ipDetail']; ?>
,
			INIT : function(){
				var self = this;
				
				//时间插件
				$("#startdate").datepicker();
				$("#enddate").datepicker();
				page.listen();
				
				showTitle("用户数据分析:创角分析");
				
				$("#querybtn").click(function(){
					if(validator("startdate", "enddate")){
						self.showRole();
					}
				});
				$("#queryjs").click(function(){
					self.jishi();
				});
				//alert(self.Num);
				self.showRole();
			},
			
			showRole : function() {
				$.ajax({
					'type' : 'GET',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/userrole/getRole',
					dataType : 'json',
					data : {
						startdate : $("#startdate").val(),
						enddate : $("#enddate").val(),
						ip : $("#sip").val()
					},
					beforeSend : function() {
						$("#dtatr_body").html("<tr><td colspan='8'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
					},
					success : function (data) {
						if(typeof(data.result) != 'undefined' && data.result != "") {
							var result = data.result;
							$("#pagehtml").show();
							var fields = ['c_date', 'c_login_suc', 'c_login_fai', 'c_enter', 'c_csuccess', 'c_entergame', 'c_cjv', 'c_load'];
							page.INIT(25, result, fields, '#dtatr_body');
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
						}else {
							$("#pagehtml").hide();
							$("#dtatr_body").html("<tr><td colspan='8'>没有数据！</td></tr>");
						}
					},
					error : function () {
						$("#pagehtml").hide();
						$("#dtatr_body").html("<tr><td colspan='8'>没有数据！</td></tr>");
					}
				})
			},
			jishi : function(){
			
				var si = $("#sip").val();
				var self = this;
				var biaoshi = '';
				var ip = '';
				var domain = $("#sip option:selected").attr('attr');
				for(var i in self.object){
					if(self.object[i].s_id == si){
						biaoshi = self.object[i].s_biaoshi;
					}
				}
				//console.log();
				//alert(biaoshi);
				//return false;
				if(biaoshi){
					$.ajax({
						types : 'get',
						url : '<?php echo $this->_tpl_vars['curl']; ?>
?ip='+domain+'&biaoshi='+biaoshi,
						dataType : 'json',
						success : function (data) {
							self.get_data();
						}
					});
				}else{
					alert('请到GM工具设置标识');
				}
			},
			get_data:function(){
				$.ajax({
					types : 'get',
					url : '<?php echo $this->_tpl_vars['curl']; ?>
/../json.php',
					dataType : 'json',
					success : function (data){
						$("dtatr_body").html(
						'<tr><th>实时</th><th>'+data.pt+'</th><th>无统计</th><th>'+data.f+'</th><th>'+data.s+'</th><th>'+data.t+'</th><th>'+data.s/data.t+'</th><th>'+data.s-data.t/data.s+'</th></tr>'
						);
					}
				});
			}

		}
		
		$(document).ready(function(){
			user_pay.INIT();
		})
	*/
	</script>
</body>
</html>
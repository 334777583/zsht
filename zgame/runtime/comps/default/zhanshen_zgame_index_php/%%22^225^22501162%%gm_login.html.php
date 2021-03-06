<?php /* Smarty version 2.6.18, created on 2014-01-06 18:07:41
         compiled from gmtools/gm_login.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>特殊登录记录</title>
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
							<td width="95%" class="tableleft">1、GM相关操作历史记录</td>
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
					</select>
					<span>操作:</span>
					<select id="gm_stype">
						<option value="1">一键登录</option>
						<option value="2">发送邮件</option>
						<option value="3">发送公告</option>
						<option value="4">冻结</option>
						<option value="5">踢下线</option>
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
							<th>用户id</th>
							<th>用户名</th>
							<th>原因</th>
							<th>操作人</th>
							<th>操作时间</th>
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
		var user_pay = {
			INIT : function(){
				var self = this;
				
				//时间插件
				$("#startdate").datepicker();
				$("#enddate").datepicker();
				page.listen();
				
				showTitle("运营工具:GM操作记录");
				
				$("#querybtn").click(function(){
					if(validator("startdate", "enddate")){
						self.showRole();
					}
				});
				//alert(self.Num);
				self.showRole();
			},
			
			showRole : function() {
				$.ajax({
					'type' : 'POST',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/login_sign/getRole',
					dataType : 'json',
					data : {
						startdate : $("#startdate").val(),
						enddate : $("#enddate").val(),
						ip : $("#sip").val(),
						gmtype : $("#gm_stype").val()
					},
					beforeSend : function() {
						$("#dtatr_body").html("<tr><td colspan='8'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
					},
					success : function (data) {
						if(typeof(data.result) != 'undefined' && data.result != "") {
							var result = data.result;
							$("#pagehtml").show();
							var fields = ['type', 'role_name', 'reason', 'operaor', 'time'];
							page.INIT(10, result, fields, '#dtatr_body');
							$("#home_page").trigger('click');
							$("#startdate").val(data.startDate);
							$("#enddate").val(data.endDate);
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
			
			
		}
		
		$(document).ready(function(){
			user_pay.INIT();
		})
	</script>
</body>
</html>
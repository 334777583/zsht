<?php /* Smarty version 2.6.18, created on 2014-01-02 10:28:50
         compiled from money/shop_consume.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>商城消费记录</title>
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
							<td width="95%" class="tableleft">1、查询玩家在时间区间内的消费记录</td>
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
"><?php echo $this->_tpl_vars['ip']['s_name']; ?>
</option>
						<?php endforeach; endif; unset($_from); ?>
					</select>
					<select id="type" style="margin-left:50px">
						<option value="1">角色ID</option>
						<option value="2">角色名</option>
					</select>
					<input type="text" class="input1" id="key"/>
					<input type="hidden" id="roleId"/>
					<label id="fuzzylabel" style="display:none">
						<input type="checkbox" name="checkbox" id="fuzzy" style="margin-left:10px;"/><span>模糊查询</span>
					</label>
					<select id="money_type" style="margin-left:50px">
						<option value="1">元宝</option>
						<option value="2">绑定元宝</option>
						<option value="3">礼券</option>
					</select>
					<span style="margin-left:50px">购买时间:</span>
					<input type="text" class="input1" id="startdate" value="<?php echo $this->_tpl_vars['startDate']; ?>
" />至<input type="text" class="input1" id="enddate" value="<?php echo $this->_tpl_vars['endDate']; ?>
" />
					<input type="button" value="查询" id="querybtn" style="margin-left:20px"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<div id="tabs-1" class="tabitem">
				<div>
					<table  class="mytable" style="display:none">
						<thead>
							<tr>
								<th>账号</th>
								<th>角色ID</th>
								<th>角色名</th>
								<th>活动类型</th>
								<th>物品ID</th>
								<th>物品名称</th>
								<th>单价</th>
								<th>数量</th>
								<th>购买时间</th>
							</tr>
						</thead>
						<tbody id="mbody">
						</tbody>
					</table>
				</div>
				<div id="pagehtml" style="float:right;margin-right:20px"></div>
				<div id="example_length" class="dataTables_length" style="display:none">
					<label>每页显示
						<select id="menu" name="example_length" size="1" aria-controls="example">
						<option value="10">10</option>
						<option value="25" selected="selected">25</option>
						<option value="50">50</option>
						<option value="100">100</option>
						</select> 条记录
					</label>
				</div>
			</div>
			
			<div style="clear:both"></div>
		</div>
		
		<div style="height:50px">&nbsp;</div>
		
	</div>
	
	<!-- 角色ID与角色名称 -->
	<div id="form"  style="display:none">
		<div class="ajaxform">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
				<tbody>
					<tr>
						<td>
							<table class="tooltable">
								<thead>
									<tr>
										<th>角色ID</th>
										<th>角色名称</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody id="form_body">
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			<div id="pagehtml2" style="float:right;margin-right:20px"></div>
		</div>
	</div>
	
	
	
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery-ui.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/function.js" type="text/javascript"></script>	
	<script type="text/javascript">
		var shop_consume = {
			 INIT : function(){
				var self = this;
				
				//时间插件
				$("#startdate").datepicker();
				$("#enddate").datepicker();
				
				showTitle("货币数据分析:商城消费记录");
				
				//角色名才显示模糊查询
				$("#type").change(function(){
					if($("#type").val() == "2"){
						$("#fuzzy").attr("checked",'true');
						$("#fuzzylabel").show();
					}else{
						$("#fuzzy").removeAttr("checked");
						$("#roleId").val('');
						$("#fuzzylabel").hide();
					}
				});
				
				//每页显示
				$("#menu").change(function(){
					self.showTable(1);
				});
				
				//查询
				$("#querybtn").click(function(){
					$(".mytable").show();
					self.showRole();
				});
				
				//选择角色
				$(".choose").live('click',function(){
					var id = $(this).parent().prev().prev().html();
					$("#roleId").val(id);
					$("#key").val($(this).parent().prev().html());
					$("#form").dialog("close");
					self.showTable();
				})
			 },
			 
			 //table交叉样式
			 color_table : function(){
				$("#mbody tr:odd").css("background-color", "#edf2f7"); 
				$("#mbody tr:even").css("background-color","#e0f0f0"); 
			 },
			 
			 //角色名时先查询ID
			 showRole : function(page){
				var self = this;
				if(self.check("startdate","enddate")){
					var type = $("#type").val();
					var fuzzy = 1;	//0为模糊;1为精确
					var ip = $("#sip").val();
					if($("#fuzzy").is(":checked")){
						fuzzy = 0;
					}
					var key = $("#key").val();
					if('1' == 'type'){
						if('' == key){
							alert('请输入内容！');
							return;
						}
					}else if('2' == type){	//先根据角色名查询对应的ID
						$.ajax({
							type : 'post',
							url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/shopconsume/getRoleList',
							dataType : 'json',
							data : {
								key : key,
								fuzzy : fuzzy,
								ip : ip,
								curPage : page,
								startdate : $('#startdate').val(),
								enddate : $('#enddate').val()
							},
							beforeSend : function(){
								$("#form_body").html("<tr><td colspan='3'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
							},
							success : function(data){
								if(typeof(data.error) != 'undefined'){
									alert(data.error);
									return;
								}
								if(typeof(data.plays) != 'undefined'){
									var html = "";
									if(data.plays.length >0){
										for(var i in data.plays){
											html += "<tr>";
											html += "<td>"+data.plays[i]["GUID"]+"</td>";
											html += "<td>"+data.plays[i]["RoleName"]+"</td>";
											html += "<td><span class='choose'>选择</span></td>";
											html += "</tr>";
										}
										$("#form_body").html(html);
										$("#pagehtml2").html(data.pageHtml);
									}else{
										$("#pagehtml2").html("");
										$("#form_body").html("<tr><td colspan='3'>没有记录！</td></tr>");
									}
								}else{
									$("#form_body").html("<tr><td colspan='3'>没有记录！</td></tr>");
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
							error : function(){
								$("#form_body").html("<tr><td colspan='3'>没有记录！</td></tr>");
							}
						})
					}else if('1' == type){	//选择角色ID
						self.showTable(1);
					}
				}
			 },
			 
			 check : function(startdate,enddate){
				var isok = true;
				var reg = /\d{4}-\d{2}-\d{2}/;
				var start = $("#"+startdate).val();
				var end = $("#"+enddate).val();
				if(start != "" && end != ""){
					if(!reg.test(start) || !reg.test(end)){
						alert("请输入格式为YYYY-MM-DD的时间");
						isok = false;
					}else if(start>end){
						alert("结束时间要大于开始时间！");
						isok = false;
					}
				}
				return isok;

			 },
			 
			 //商城消费记录表格
			 showTable : function(page){
				var self = this;
				var type = $("#type").val()					//类型(0：账号；1：ID;2:角色名)
				var startdate  = $("#startdate").val();		//开始时间
				var enddate = $("#enddate").val();			//结束时间
				var key =	$("#key").val();				//搜索内容	
				var roleId = $("#roleId").val();			//角色ID
				if('' != roleId){
					key = roleId;
				}
				var ip = $("#sip").val();					//服务器IP
				$.ajax({
					type : 'post',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/shopconsume/search',
					dataType : 'json',
					data : {
						type : type,
						startdate : startdate,
						enddate : enddate,
						key : key,
						ip : ip,
						pageSize : $("#menu").val(),
						curPage : page,
						money_type:$('#money_type').val()
					},
					beforeSend : function(){
						$("#mbody").html("<tr><td colspan='10'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
					},
					success : function(data){
						var html = "";
						if(typeof(data.list) != "undefined" ){
							if(data.list.length > 0){
								$("#example_length").show();//显示每页
								for(var i in data.list){
									var i_dt = data.list[i]['i_dtype'];
									//alert(i_dt);
									var i_text = "";
									switch(i_dt) {
										case '0' : i_text = "普通购买";break;
										case '1' : i_text = "抢购";break;
										case '2' : i_text = "秒杀";break;
									}
									var s_id = data.list[i]['i_shopid'];
									//var s_id =s_id.substr(7,2);
									html += "<tr>";
									html += "<td>"+data.list[i]['p_account']+"</td>";
									html += "<td>"+data.list[i]['i_playid']+"</td>";
									html += "<td>"+data.list[i]['p_name']+"</td>";
									html += "<td>"+i_text+"</td>";
									html += "<td>"+s_id+"</td>";
									html += "<td>"+data.list[i]['t_name']+"</td>";
									html += "<td>"+data.list[i]['i_price']+"</td>";
									html += "<td>"+data.list[i]['cnum']+"</td>";
									html += "<td>"+data.list[i]['i_date']+"</td>";
									html += "</tr>";
								}
								$("#pagehtml").html(data.pageHtml);	
								$("#mbody").html(html);
								self.color_table();
							}else{
								$("#mbody").html("<tr><td colspan='9'>没有数据！</td></tr>");
							}
						}else{
							$("#mbody").html("<tr><td colspan='9'>没有数据！</td></tr>");
						}
					},
					error : function(){
						$("#mbody").html("<tr><td colspan='9'>没有数据！</td></tr>");
					}
				})
			 }
		}
		
		//分页ajax函数
		var pageAjax = function(page){
			shop_consume.showTable(page);
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
		
		//角色列表分页ajax函数
		var pageAjax2 = function(page){
			shop_consume.showRole(page);
		}

		//角色列表跳到相应页面 
		var go2 = function(){
			var pagenum = $("#page2").val();
			if(pagenum=='' || isNaN(pagenum) || pagenum <= 0){
				alert('请输入一个正整数！');
				$("#page2").val(1);
			}else{
				pageAjax2(pagenum);
			}
		}
		
		$(document).ready(function(){
			shop_consume.INIT();
		})
	</script>
</body>
</html>
<?php /* Smarty version 2.6.18, created on 2014-01-04 11:15:13
         compiled from money/shop_analysis_charts.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>商城消费分析</title>
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
	.table_blue2 td{
		 border-bottom : 3px solid #DDDBF2;
	}
	-->
	.btn{
		position: relative;
		overflow: hidden;
		margin-right: 4px;
		display:inline-block;
		*display:inline;
		padding:0px 10px 4px;
		font-size:14px;
		line-height:18px;
		*line-height:20px;
		#color:#fff;
		text-align:center;
		vertical-align:middle;
		cursor:pointer;
		-webkit-border-radius:4px;
		-moz-border-radius:4px;
		border-radius:4px;
	}
	.file {
		position: absolute;
		top: 0; 
		right: 0;
		margin: 0;
		border: solid transparent;
		opacity: 0;
		filter:alpha(opacity=0); 
		cursor: pointer;
	}
	</style>
</head>
<body>
	<div class="container">
		<div>
			<div  id="user-tabs">
				<a href="?pageId=1" ><span id="1" class="user-gray">数据展示</span></a>
				<a href="?pageId=2" ><span id="2">图表展示</span></a>
			</div>
			<hr/>
			
			<div>
				<table class="explain">
					<thead>
					</thead>
					<tbody style="font-family:Mingliu">
						<tr>
							<td width="5%"  class="tableleft"><b>说明：</b></td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">1、查询区间内游戏商城中<b>元宝</b>和<b>绑定元宝</b>消费的物品分析</td>
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
						<option value="3">礼券</option>
						<option value="1">元宝</option>
						<option value="2">绑定元宝</option>
					</select>
					<span style="margin-left:50px">时间:</span>
					<input type="text" class="input1" id="startdate" />至<input type="text" class="input1" id="enddate" value="<?php echo $this->_tpl_vars['endDate']; ?>
" />
					<input type="button" value="查询" id="querybtn" style="margin-left:20px"/>
					<div class="btn">
						<input type="button" value="上传道具列表" style="margin-left:20px"/>
						<input id="update_tool" type="file" name="xls" class="file">
						<!--<input type="button" value="insert" id="update_click">-->
					</div>
	
				</div>
			</div>
			<div style="clear:both"></div>
			<div>
				<table width="100%" cellpadding="0" cellspacing="0" class='table_blue2'>
					<tbody id="chart_tbody">
					</tbody>
					<!--
					<tr>
						<td>
							<h2>2013-06-26</h2>
							<div id="leftdiv" style="width: 100%; height: 500px;"></div>
						</td>
					</tr>
					-->
				</table>
			</div>
			
			<div id="dform"  style="display:none">
				<div class="ajaxform">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tbody>							
							<tr>
								<td width="10%" style="text-align:left">
								
								</td>
							</tr>						
							<tr>
								<td colspan='2'>
									<table class="tooltable">
										<thead>
											<tr>
												<th>道具ID</th>
												<th>道具名称</th>
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
			
			<!--弹出覆盖层-->
			<div class="overlay" style="display:none">
				<table style="width:220px;height:100%;margin:0 auto;">
					<tr>
						<td style="text-align:center">
							<img src='<?php echo $this->_tpl_vars['res']; ?>
/images/ajax-loader.gif'/>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	
	
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery-ui.js" type="text/javascript"></script> 
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery.form.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/amcharts.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/function.js" type="text/javascript"></script>	
	<script type="text/javascript">
		var shop_analysis_charts = {
			INIT : function(){
				var self = this;
				//时间插件
				$("#startdate").datepicker();
				$("#enddate").datepicker();
				
				showTitle("货币数据分析:商城消费分析");
				//self.StartDate();//获取开服日期
				self.show_charts();
				
				$("#querybtn").click(function(){
					if( validator("startdate", "enddate") ){
						self.show_charts();
					}
				})
				/*
				//获取开服日期
				$("#sip").change(function(){
					$("#startdate").attr('value','');
					self.StartDate();
				})
				*/
				//切换标签
				$("#user-tabs span").click(function(){
					window.location = "<?php echo $this->_tpl_vars['app']; ?>
/shopanalysis/show/pageId/"+this.id;
				})
				/*
				//更新道具列表
				$("#update_tool").wrap("<form id='myupload' action='<?php echo $this->_tpl_vars['logicApp']; ?>
/analyxls/analy' method='post' enctype='multipart/form-data'></form>");
				$("#update_tool").change(function(){
					if(confirm('确定要更新道具列表吗？')){
						$("#myupload").ajaxSubmit({
							dataType:  'json',
							data : {
								ip : $('#sip').val()
							},
							beforeSend : function(){
								$(".overlay").show();
							},
							complete : function(){
								$(".overlay").hide();
							},
							success: function(data) {
								if(data == 'success'){
									alert('更新成功！');
								}else{//返回错误信息
									alert(data);
								}
							},
							error:function(xhr){
								alert('更新失败！');
							}
						});
					}
				})*/
				
				//更新道具列表
				$("#update_tool").click(function(){
					if(confirm('确定要更新道具列表吗？')){
						$.ajax({
							type : 'post',
							//url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/analyxml/analy',
							url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/analyxls/analy',
							dataType : 'json',
							data : {
								ip : $("#sip").val(),
							},
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
								} else if(data == 'error') {
									alert('更新失败！');
								}
							},
							error : function(){
								alert('fuck更新失败！');
							}
						})
					}
				})
			},
			/*
			//获取开服日期
			 StartDate : function(){
				var ip = $("#sip").val();
				$.ajax({
					type : 'post',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/shopanalysis/getStartData',
					dataType : 'json',
					data : {
						ip : ip
					},
					success : function(data){
						$("#startdate").val(data.startDate);
					}
				})
			 },
			*/
			//显示图饼
			show_charts : function() {
				var self = this;
				$.ajax({
					type : 'POST',
					dataType : 'json',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/shopanalysis/getChartData',
					data : {
						startdate : $("#startdate").val(),
						enddate : $("#enddate").val(),
						ip : $("#sip").val(),
						type : $("#type").val()
					},
					beforeSend : function(){
						$(".overlay").show();
					},
					complete : function(){
						$(".overlay").hide();
					},
					success : function (data) {
						$("#startdate").val(data.startDate);
						var result = data.result;
						var goods_arr = data.goods_arr;
						$("#chart_tbody").html("");
						for(var i in result) {
							var tbody = "<tr>";
							tbody += "<td>";
							tbody += "<h2>"+i+"</h2>";
							tbody += "<div id='d_"+i+"' style='width: 100%; height: 800px;'></div>";
							tbody += "</td>";
							tbody += "</tr>";
							$("#chart_tbody").append(tbody);
							
							var div  = "d_"+i;
							var data = [];
							var goods = result[i];
							for(var i in goods) {
								var item = {};
								item.country = goods_arr[i];
								item.litres = goods[i];
								data.push(item);
							}
							self.showPie(data, div);
						}
						if(typeof(result.length)) {
							if(result.length == 0) {
								$("#chart_tbody").append("<div style='text-align:center'>没有记录！</div>");
							}
						}
					},
					error : function () {
						alert('error');
					}
				
				})
			
			},
			
			//显示饼状图
			showPie : function(data, div){
				// PIE pieChart
				var pieChart = new AmCharts.AmPieChart();
				pieChart.dataProvider = data;
				pieChart.titleField = "country";
				pieChart.valueField = "litres";

				// LEGEND
				var legend = new AmCharts.AmLegend();
				legend.align = "circle";
				legend.position = "right";
				legend.marginRight = 500;
				legend.switchType = "y";
				pieChart.addLegend(legend);

				// WRITE
				pieChart.write(div);
			}
		}	
		
		$(document).ready(function(){
			shop_analysis_charts.INIT();
		})
	</script>
</body>
</html>
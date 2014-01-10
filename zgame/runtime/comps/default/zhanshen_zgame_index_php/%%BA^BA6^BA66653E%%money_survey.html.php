<?php /* Smarty version 2.6.18, created on 2014-01-02 10:33:55
         compiled from money/money_survey.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>货币收支概况</title>
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
							<td width="95%" class="tableleft">1、查询区间内游戏中各种货币的收支概况图表；</td>
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
					<input type="text" class="input1" id="startdate" value="<?php echo $this->_tpl_vars['startDate']; ?>
"/>至<input type="text" class="input1" id="enddate" value="<?php echo $this->_tpl_vars['endDate']; ?>
"/>
					<input type="button" value="查询" id="querybtn" style="margin-left:20px"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<div id="tabs-1" class="tabitem">
				<div>
					<h1>铜钱收支概况</h1>
				</div>
				<div>
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table_blue2">
						<tr>
							<td align="center" colspan="10" >
								<div id="copper_div" style="width: 100%; height: 400px;"></div>
							</td>
						</tr>
						<tbody id='am_body'>
						</tbody>
					</table>
				</div>
				
			</div>
			
			<div id="tabs-2" class="tabitem">
				<div>
					<h1>绑定铜币收支概况</h1>
				</div>
				<div>
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table_blue2">
						<tr>
							<td align="center" >
								<div id="silver_div" style="width: 100%; height: 400px;"></div>
							</td>
						</tr>
					</table>
				</div>
			</div>
			
			<div id="tabs-3" class="tabitem">
				<div>
					<h1>元宝收支概况</h1>
				</div>
				<div>
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table_blue2">
						<tr>
							<td align="center" >
								<div id="gold_div" style="width: 100%; height: 400px;"></div>
							</td>
						</tr>
					</table>
				</div>
			</div>
			
			<div id="tabs-4" class="tabitem">
				<div>
					<h1>绑定元宝收支概况</h1>
				</div>
				<div>
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table_blue2">
						<tr>
							<td align="center" >
								<div id="bgold_div" style="width: 100%; height: 400px;"></div>
							</td>
						</tr>
					</table>
				</div>
			</div>
			
			<div style="clear:both"></div>
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
		var money_survey = {
			INIT : function(){
				var self = this;
				
				//时间插件
				$("#startdate").datepicker();
				$("#enddate").datepicker();
				
				showTitle("货币数据分析:货币收支概况");
				
				
				$("#querybtn").click(function(){
					if( validator("startdate", "enddate") ){
						self.getdata();
					}
				})
				
				//页面加载数据
				self.getdata();
			},
			
			//获取数据并显示货币收支概况
			getdata : function() {
				var self = this;
				$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/moneysurvey/get',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startdate : $("#startdate").val(),
						enddate : $("#enddate").val()
					},
					beforeSend : function(){
						$(".overlay").show();
						$("#am_body").html("<tr><td><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
					},
					complete : function(){
						$(".overlay").hide();
					},
					success : function(data){
						if(typeof(data.list) != 'undefined'){
							if(data.list.length > 0){
								var copper_data = [];
								var silver_data = [];
								var bgold_data = [];
								var gold_data = [];
								
								var copper_map = {};
								var silver_map = {};
								var bgold_map = {};
								var gold_map = {};
								var n = 1;
								
								var html = "";
								for(var i in data.list){
									if(data.list[i]['p_type'] == '0'){
										copper_map.date = parseDate(data.list[i]['p_date']);
										copper_map.hits = parseInt(data.list[i]['p_tong']);
										
										silver_map.date = parseDate(data.list[i]['p_date']);
										silver_map.hits = parseInt(data.list[i]['p_yin']);
										
										bgold_map.date = parseDate(data.list[i]['p_date']);
										bgold_map.hits = parseInt(data.list[i]['p_byuan']);
										
										gold_map.date = parseDate(data.list[i]['p_date']);
										gold_map.hits = parseInt(data.list[i]['p_yuan']);
										
									}else if(data.list[i]['p_type'] == '1'){
										copper_map.visits = parseInt(data.list[i]['p_tong']);
										
										silver_map.visits = parseInt(data.list[i]['p_yin']);
										
										bgold_map.visits = parseInt(data.list[i]['p_byuan']);
										
										gold_map.visits = parseInt(data.list[i]['p_yuan']);
									}
									
									
									if(i != 0){
										if(n%2 == 0) {
											copper_data.push(copper_map);
											silver_data.push(silver_map);
											bgold_data.push(bgold_map);
											gold_data.push(gold_map);
											
											copper_map = {};
											silver_map = {};
											bgold_map = {};
											gold_map = {};

										}
									}
									
									n++;
								}
								if(data.sumlist.length > 0){
									for(var q in data.sumlist){
										if(data.sumlist[q]['p_type'] == '0'){
											var addtong = data.sumlist[q]['sum_tong'];
										}else if(data.sumlist[q]['p_type'] == '1'){
											var usetong = data.sumlist[q]['sum_tong'];
										}
									}
								}else{
									addtong = 0;
									usetong = 0;
								}
								html += "<tr>";
								html += "<td width='10%'>" + "铜币产出:" + "</td>";
								html += "<td>"+ addtong +"</td>";
								html += "<td width='10%'>" + "铜币消耗:" + "</td>";
								html += "<td>"+ usetong +"</td>";
								html += "<td colspan='6' width='80%'></td>";
								html += "</tr>";
								
								$("#am_body").html(html);
								self.showChart(copper_data, "copper_div", '铜币产出', '铜币消耗');
								self.showChart(silver_data, "silver_div", '绑定铜币产出', '绑定铜币消耗');
								self.showChart(bgold_data, "bgold_div", '绑定元宝产出', '绑定元宝消耗');
								self.showChart(gold_data, "gold_div", '元宝产出', '元宝消耗');
							}else{
								self.noRecord("copper_div");
								self.noRecord("silver_div");
								self.noRecord("bgold_div");
								self.noRecord("gold_div");
							}
						}else{
							self.noRecord("copper_div");
							self.noRecord("silver_div");
							self.noRecord("bgold_div");
							self.noRecord("gold_div");
						}
					},
					error : function(){
						self.noRecord("copper_div");
						self.noRecord("silver_div");
						self.noRecord("bgold_div");
						self.noRecord("gold_div");
					}
				})
			
			},
			
			//显示走势图
			showChart : function(chartData,position,title1,title2){
				var chart;	

				// this method is called when chart is first inited as we listen for "dataUpdated" event
				function zoomChart() {
				 // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
				 chart.zoomToIndexes(0, 15);
				}

				// SERIAL CHART    
				chart = new AmCharts.AmSerialChart();
				chart.pathToImages = "<?php echo $this->_tpl_vars['res']; ?>
/images/";
				chart.zoomOutButton = {
					backgroundColor: '#000000',
					backgroundAlpha: 0.15
				};
				chart.dataProvider = chartData;
				chart.categoryField = "date";

				// listen for "dataUpdated" event (fired when chart is inited) and call zoomChart method when it happens
				chart.addListener("dataUpdated", zoomChart);

				// AXES
				// category                
				var categoryAxis = chart.categoryAxis;
				categoryAxis.parseDates = true; // as our data is date-based, we set parseDates to true
				categoryAxis.minPeriod = "DD"; // our data is daily, so we set minPeriod to DD
				categoryAxis.dashLength = 2;
				categoryAxis.gridAlpha = 0.15;
				categoryAxis.axisColor = "#DADADA";

				// first value axis (on the left)
				var valueAxis1 = new AmCharts.ValueAxis();
				valueAxis1.axisColor = "#FF6600";
				valueAxis1.axisThickness = 2;
				valueAxis1.gridAlpha = 0;
				chart.addValueAxis(valueAxis1);
				
				 // second value axis (on the right) 
                var valueAxis2 = new AmCharts.ValueAxis();
                //valueAxis2.offset = 50; // this line makes the axis to appear detached from plot area
				valueAxis2.position = "right";
                valueAxis2.gridAlpha = 0;
                valueAxis2.axisColor = "#FCD202";
                valueAxis2.axisThickness = 2;
                chart.addValueAxis(valueAxis2);

				// GRAPHS
				// first graph
				var graph1 = new AmCharts.AmGraph();
				graph1.valueAxis = valueAxis1; // we have to indicate which value axis should be used
				graph1.title = title1;
				graph1.valueField = "visits";
				graph1.bullet = "round";
				graph1.hideBulletsCount = 30;
				chart.addGraph(graph1);
				
				// second graph                
                var graph2 = new AmCharts.AmGraph();
                graph2.valueAxis = valueAxis2; // we have to indicate which value axis should be used
                graph2.title = title2;
                graph2.valueField = "hits";
                graph2.bullet = "square";
                graph2.hideBulletsCount = 30;
                chart.addGraph(graph2);

				// CURSOR
				var chartCursor = new AmCharts.ChartCursor();
				chartCursor.cursorPosition = "mouse";
				chart.addChartCursor(chartCursor);

				// SCROLLBAR
				var chartScrollbar = new AmCharts.ChartScrollbar();
				chart.addChartScrollbar(chartScrollbar);

				// LEGEND
				var legend = new AmCharts.AmLegend();
				legend.marginLeft = 110;
				chart.addLegend(legend);

				// WRITE
				chart.write(position);
			},
			
			//没有数据
			noRecord : function(div){
				$("#"+div).html("<div style='text-align:center'>没有记录！</div>");
			}	
			
		}
		
		$(document).ready(function(){
			money_survey.INIT();
			//页面加载，显示table
		})
	</script>
</body>
</html>
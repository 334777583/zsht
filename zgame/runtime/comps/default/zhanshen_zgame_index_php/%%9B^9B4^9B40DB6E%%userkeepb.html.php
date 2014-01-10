<?php /* Smarty version 2.6.18, created on 2014-01-04 12:18:15
         compiled from stickiness/userkeepb.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>保有率</title>
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
/js/amcharts.js" type="text/javascript"></script>	
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/function.js" type="text/javascript"></script> 	
	
	

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
	.toptitle{
		cursor : pointer;
	}


	#ul{
		width:520px;
		height:20px;
		margin-left: 50px;
		display: inline-block;
		margin-bottom: 0;
		margin-top: 0;
		position: relative;
		top:5px;
	}

	.li1{
		margin-left: 5px;
		width:60px;
		position: relative;
		left:-37px;
		list-style: none;
		float: left;
		line-height: 20px;
		font-size: 15px;
		background-image: url("<?php echo $this->_tpl_vars['res']; ?>
/images/01.png");
		background-repeat:no-repeat;
		background-position:center,;
		text-indent: 20px;
		
	}
		-->
	</style>
</head>
<body>
	<div id="user-tabs" style="margin-top:20px;">
	</div>
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
							<td width="95%" class="tableleft">1、<b>留存用户</b>:过去7天有过登陆行为的用户，就视为留存用户;</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">2、<b>流失用户</b>:过去7天没有过登陆行为的用户，就视为流失用户;</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">3、留存用户分布及流失用户分布分析数据;</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">4、此页面开服前7天请无视、7天后才有数据;</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">5、开服7天数据请进入“新进分析”;</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="topinfo">
				<div>
					<span>游戏区服:</span>
					<select id="sip">
						<?php $_from = $this->_tpl_vars['ipList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ip']):
?>
							<option value="<?php echo $this->_tpl_vars['ip']['s_id']; ?>
"><?php echo $this->_tpl_vars['ip']['s_name']; ?>
</option>
						<?php endforeach; endif; unset($_from); ?>
					</select>

					<span>游戏平台:</span>
					<select id="yxpt">
						
							<option value="49you">49you</option>
						
					</select>
					 <input type="text" value="2013-12-13" name="startdate" id="startdate" class="input1"/>至<input type="text" name="enddate" value="<?php echo $this->_tpl_vars['endDate']; ?>
" id="enddate" class="input1"/>
					<input type="button" value="查询" id="querybtn" style="margin-left:20px"/>
					
				</div>
			</div>	
			
			<div style="clear:both;">
				
			</div>
			
			<div>
				<div class="toptitle">
					<span>用户登录分析<span/>
				</div>
				<div id="chartdiv" style="width: 100%; height: 400px;"></div>
				<table cellpadding="0" cellspacing="0" class="table_blue2" width="100%">	
					<tr>
						<td width="100%" style="vertical-align:top">
							
							<div style="height:500px;overflow-y:auto">	
								<table class="mytable" id="lcfb_body">
								<thead>
									<tr id="head"></tr>
								</thead>
									<tbody id="tbody">
									</tbody>
								</table>
								<div style="float:right;margin-right:20px;display:none" id="pagehtml">
				<div class="pages">
					每页显示： 
				<select id="pagesize">
						<option value="10">10</option>
						<option value="50">50</option>
						<option value="100">100</option>
						<option value="200">200</option>
				</select>
					<a id="home_page" href="javascript:void(0)">首页</a>&nbsp;&nbsp;
					<a id="pre_page" href="javascript:void(0)">上一页</a>&nbsp;&nbsp;
					<a id="next_page" href="javascript:void(0)">下一页</a>&nbsp;&nbsp;
					<a id="last_page" href="javascript:void(0)">尾页</a>&nbsp;&nbsp;
					<span>第<span id="cur_page">1</span>/<span id="total_page">1</span>页&nbsp;&nbsp;</span>
					转到<input type="text" class="text" size="3"  id="page" value="1"/>
					<a id="go" class="go" href="javascript:void(0);"></a>页
					&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="导出Excel" id="excel"/>
				</div>
							</div>
						</td>
					</tr>
				</table>
				
				
				
			</div>
		
			<div style="clear:both"></div>
		</div>
		
		<div style="height:50px">&nbsp;</div>
		
	</div>
	
	
	<script type="text/javascript">
function settime(){
	$.post("<?php echo $this->_tpl_vars['logicApp']; ?>
/userkeepb/getstartTime",{'sip':$('#sip').val()},
			function(data){
				$('#startdate').val(data);
			})
}
	
	showTitle("游戏数据分析:留存分析");

	function first(data1){
		$("#home_page").bind('click', function(){
					var result = [];
					$('#cur_page').html(1);
					var k = 0;
					for(var i in data1) {
						if(k < $('#pagesize').val()) {
							result.push(data1[i])
						}else {
							break;
						}
						k++;
					}
					
					showResult(result);
					return false;
				})
	}

	function pre(data){
		$("#pre_page").bind('click', function(){
			var curpage = $('#cur_page').html();
			
			var result = [];
			var pagesize = $('#pagesize').val();
			if(curpage == 1) {	//没有上一页
				//alert('当前页是第一页！');
				return false;
			}else {
				var k = 0;
				for(var i in data) {
					if(k >= (curpage-2)*pagesize && k< (curpage-1)*pagesize) {
						k++;
						result.push(data[i]);
					}else {
						k++;
						continue;
					}
				}
				curpage --;
				$("#cur_page").html(curpage);
			}
			showResult(result);
			return false;
		})
	}

	function next(data){
		$("#next_page").bind('click', function(){
					var result = [];
					var curpage = $('#cur_page').html();
					var pagesize = $('#pagesize').val();
					var totalpage = Math.ceil(data.length/pagesize);
					if(curpage > totalpage) {	//没有下一页
						$('#cur_page').html(totalpage);
						return false;
					}else {
						var k = 0;
						for(var i in data) {
							if(k >= curpage*pagesize  && k< (curpage+1)*pagesize) {
								k++;
								result.push(data[i]);
							}else {
								k++;
								continue;
							}
						}
						curpage ++;
						$("#cur_page").html(curpage);
					}
					$('#total_page').html(totalpage);

					showResult(result);
					return false;
				})
	}

	function end(data){
		$("#last_page").bind('click', function(){
					var result = [];
					var curpage = $('#cur_page').html();
					var pagesize = $('#pagesize').val();
					var totalpage = Math.ceil(data.length/pagesize);
					
					if(curpage == totalpage) {	//没有下一页
						return false;
					}else {
						var k = 0;
						for(var i in data) {
							if(k >= (totalpage-1)*pagesize) {
								k++;
								result.push(data[i]);
							}else {
								k++;
								continue;
							}
						}
						
						$("#cur_page").html(totalpage);
					}
					showResult(result);
					return false;
				})
	}

	function gotonum(data){
		$("#go").bind('click', function(){
					var page = $("#page").val();
					var pagesize = $('#pagesize').val();
					var curpage = $('#cur_page').html();
					var totalpage = Math.ceil(data.length/pagesize);
					if(page > totalpage) {
						page = totalpage;
					}else if(isNaN(page)) {
						alert('请输入数字！');
						return false;
					}
					var result = [];
					var k = 0;
					for(var i in data) {
						if(k >= (page-1)*pagesize  && k< page*pagesize) {
							k++;
							result.push(data[i]);
						}else {
							k++;
							continue;
						}
					}
					curpage  = page;
					$("#cur_page").html(curpage);
					showResult(result);
					return false;					
				})
	}

	
			var user_keep = {
				INIT : function(){
					var self = this;
					
					$("#sip").click (function(){
						
					});
			 		//时间插件
					$("#startdate").datepicker();
					$("#enddate").datepicker();
					
					//showTitle("用户数据分析:用户登录分析");
					getResult();
					$("#querybtn").click(function(){
						
						$('#tbody').empty();
						getResult();
					});
				},
				
			 	//验证时间
				validator : function(enddate){
					var isok = true;
					var reg = /\d{4}-\d{2}-\d{2}/;
					var end = $("#"+enddate).val();
					if(end == ""){
						alert("请输入时间！");
						isok = false;
					}else if(!reg.test(end)){
						alert("请输入格式为YYYY-MM-DD的时间");
						isok = false;
					}
					return isok;
				}
			}
			user_keep.INIT();

			function setCheckBox(obj){
				obj.toggle(function(){
				obj.val('0');
				obj.css({ "background-image": "url(\"<?php echo $this->_tpl_vars['res']; ?>
/images/00.png\")", "background-repeat": "no-repeat" });
				
				},function(){
				obj.val('1');
				obj.css({ "background-image": "url(\"<?php echo $this->_tpl_vars['res']; ?>
/images/01.png\")", "background-repeat": "no-repeat" });
				});
			}

			setCheckBox($('#secondS'));
			setCheckBox($('#thrS'));
			setCheckBox($('#fourS'));
			setCheckBox($('#fiveS'));
			setCheckBox($('#sixS'));
			setCheckBox($('#sevenS'));
			setCheckBox($('#weekS'));
			setCheckBox($('#monS'));
			
			function getResult(){
				$('#tbody').empty();
				$.ajax({
					type : 'POST',
					url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/userkeepb/getImgResult",
					dataType : 'json',
					data : {
						sip:$('#sip').val(),
						yxpt:$('#yxpt').val(),
						startdate:$('#startdate').val(),
						enddate:$('#enddate').val()
						
					},
					success : function(data){
					var chart;

					
				
				// SERIAL CHART
						chart = new AmCharts.AmSerialChart();
						chart.dataProvider = data;
						chart.categoryField = "time";
						chart.startDuration = 0.5;
						chart.balloon.color = "#000000";

						// AXES
						// category
						var categoryAxis = chart.categoryAxis;
						categoryAxis.fillAlpha = 1;
						categoryAxis.fillColor = "#FAFAFA";
						categoryAxis.gridAlpha = 0;
						categoryAxis.axisAlpha = 0;
						categoryAxis.gridPosition = "start";
						categoryAxis.position = "bottom";//时间放的位置

						// value
						var valueAxis = new AmCharts.ValueAxis();
						valueAxis.title = "";
						valueAxis.dashLength = 5;
						valueAxis.axisAlpha = 0;
						valueAxis.maximum = 100;
						valueAxis.minimum = 0;
						valueAxis.integersOnly = true;
						valueAxis.gridCount = 10;
						valueAxis.reversed = false; // this line makes the value axis reversed
						chart.addValueAxis(valueAxis);

									var graph = new AmCharts.AmGraph();
					                graph.title = "次日留存率";
					                graph.valueField = "second";         
					                graph.balloonText = "次日留存率 : [[value]]";
					                graph.bullet = "round";
					                chart.addGraph(graph);

					                var graph = new AmCharts.AmGraph();
					                graph.title = "三日留存率";
					                graph.valueField = "thr";
					                graph.balloonText = "三日留存率 : [[value]]";
					                graph.bullet = "round";
					                chart.addGraph(graph);

					                var graph = new AmCharts.AmGraph();
					                graph.title = "四日留存率";
					                graph.valueField = "four";
					                graph.balloonText = "四日留存率 : [[value]]";
					                graph.bullet = "round";
					                chart.addGraph(graph);

					                var graph = new AmCharts.AmGraph();
					                graph.title = "五日留存率";
					                graph.valueField = "five";
					                graph.balloonText = "五日留存率 : [[value]]";
					                graph.bullet = "round";
					                chart.addGraph(graph);

					                var graph = new AmCharts.AmGraph();
					                graph.title = "六日留存率";
					                graph.valueField = "six";
					                graph.balloonText = "六日留存率 : [[value]]";
					                graph.bullet = "round";
					                chart.addGraph(graph);

					                var graph = new AmCharts.AmGraph();
					                graph.title = "七日留存率";
					                graph.valueField = "seven";
					                graph.balloonText = "七日留存率 : [[value]]";
					                graph.bullet = "round";
					                chart.addGraph(graph);

					                var graph = new AmCharts.AmGraph();
					                graph.title = "双周留存率";
					                graph.valueField = "week";
					                graph.balloonText = "双周留存率 : [[value]]";
					                graph.bullet = "round";
					                chart.addGraph(graph);

					                var graph = new AmCharts.AmGraph();
					                graph.title = "30日留存率";
					                graph.valueField = "week";
					                graph.balloonText = "30日留存率 : [[value]]";
					                graph.bullet = "round";
					                chart.addGraph(graph);

				var chartCursor = new AmCharts.ChartCursor();
                chartCursor.cursorPosition = "mouse";
                chartCursor.zoomable = false;
                chartCursor.cursorAlpha = 0;
                chart.addChartCursor(chartCursor); 

				//LEGEND
				var legend = new AmCharts.AmLegend();
				legend.markerType = "circle";
				chart.addLegend(legend);

				// WRITE
				chart.write("chartdiv");
			
					}
		});
		
			$.ajax({
			
					type : 'POST',
					url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/userkeepb/getResult",
					dataType : 'json',
					data : {
						sip:$('#sip').val(),
						yxpt:$('#yxpt').val(),
						startdate:$('#startdate').val(),
						enddate:$('#enddate').val()
						
					},
					/*
					beforeSend : function(){
						$("#chartdiv").html("<div style='margin-top:200px;width:100%;display:block;text-align:center'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></div>");
						$("#mbody").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					*/
					success : function(data){
						var headStr = '';
						
						$('#head').empty();
						headStr +='<th width="100px" class="dateS">日期</th>';
						headStr +='<th width="100px" class="newS">新登录账号</th>';
						if (seconddata != 1) {
							headStr +='<th width="100" class="secondS">次日留存率</th>'
						};

						if (thrdata != 1) {
							headStr +='<th width="100" class="secondS">三日留存率</th>'
						};

						if (fourdata != 1) {
							headStr +='<th width="100" class="secondS">四日留存率</th>'
						};

						if (fivedata != 1) {
							headStr +='<th width="100" class="secondS">五日留存率</th>'
						};

						if (sixdata != 1) {
							headStr +='<th width="100" class="secondS">六日留存率</th>'
						};

						if (sevendata != 1) {
							headStr +='<th width="100" class="secondS">七日留存率</th>'
						};

						if (weekdata != 1) {
							headStr +='<th width="100" class="secondS">两周留存率</th>'
						};

						if (mondata != 1) {
							headStr +='<th width="100" class="secondS">30日留存率</th>'
						};
						headStr += '</tr>';
						$('#head').append(headStr);
						if(data != 1){
							var htmlE = '';
							/*
							var listdata = data['listdata'];
							var seconddata = data['seconddata'];
							var thrdata = data['thrdata'];
							var fourdata = data['fourdata'];
							var fivedata = data['fivedata'];
							var sixdata = data['sixdata'];
							var sevendata = data['sevendata'];
							var weekdata = data['weekdata'];
							var mondata = data['mondata'];
							*/
							var listdata = data.listdata;
							var seconddata = data.seconddata;
							var thrdata = data.thrdata;
							var fourdata = data.fourdata;
							var fivedata = data.fivedata;
							var sixdata = data.sixdata;
							var sevendata = data.sevendata;
							var weekdata = data.weekdata;
							var mondata = data.mondata;
							
							var seco = data.seco;
							var thco = data.thco;
							var foco = data.foco;
							var fico = data.fico;
							var sico = data.sico;
							var sevco = data.sevco;
							var weco = data.weco;
							var moco = data.moco;
							
							for (var i = listdata.length-1; i > -1; i--) {
								var abc = listdata[i];
								
								htmlE += '<tr>';
								
									htmlE += '<td style="width:100px;" class="dateS">'+abc['c_time']+'</td>';
									htmlE += '<td style="width:100px;" class="newS">'+abc['dataC']+'</td>';

									if (seconddata != 1) {
										if (i < seconddata.length) {
										
											htmlE += '<td style="width:100px;" class="secondS">'+(seco[i]['dataC'])+' %('+seconddata[i]['dataC']+')'+'</td>';
										}else{
											htmlE += '<td style="width:100px;" class="secondS"></td>';
										}
									}else{
										$('.secondS').remove();
									}

									if (thrdata != 1) {
										if (i < thrdata.length) {
										htmlE += '<td style="width:100px;" class="thrS">'+(thco[i]['dataC'])+' %('+thrdata[i]['dataC']+')'+'</td>';
										}else{
											htmlE += '<td style="width:100px;" class="thrS"></td>';
										}
									}else{
										$('.thrS').remove();
									}
									
									if (fourdata != 1) {
										if (i < fourdata.length) {
											htmlE += '<td style="width:100px;" class="fourS">'+(foco[i]['dataC'])+' %('+fourdata[i]['dataC']+')'+'</td>';
										}else{
											htmlE += '<td style="width:100px;" class="fourS"></td>';
										}
									}else{
										$('.fourS').remove();
									}

									if (fivedata != 1) {
										if (i < fivedata.length) {
											htmlE += '<td style="width:100px;" class="fiveS">'+(fico[i]['dataC'])+' %('+fivedata[i]['dataC']+')'+'</td>';
										}else{
											htmlE += '<td style="width:100px;" class="fiveS"></td>';
										}
									}else{
										$('.fiveS').remove();
									}

									if (sixdata != 1) {
										if (i < sixdata.length) {
											htmlE += '<td style="width:100px;" class="sixS">'+(sico[i]['dataC'])+' %('+sixdata[i]['dataC']+')'+'</td>';
										}else{
											htmlE += '<td style="width:100px;" class="sixS"></td>';
										}
									}else{
										$('.sixS').remove();
									}

									if (sevendata != 1) {
										if (i < sevendata.length) {
											htmlE += '<td style="width:100px;" class="sevenS">'+(sevco[i]['dataC'])+' %('+sevendata[i]['dataC']+')'+'</td>';
										}else{
											htmlE += '<td style="width:100px;" class="sevenS"></td>';
										}
									}else{
										$('.sevenS').remove();
									}

									if (weekdata != 1) {
										if (i < weekdata.length) {
											htmlE += '<td style="width:100px;" class="weekS">'+(weco[i]['dataC'])+' %('+weekdata[i]['dataC']+')'+'</td>';
										}else{
											htmlE += '<td style="width:100px;" class="weekS"></td>';
										}
									}else{
										$('.weekS').remove();
									}

									if (mondata != 1) {
										if (i < mondata.length) {
											htmlE += '<td style="width:100px;" class="monS">'+(moco[i]['dataC'])+' %('+mondata[i]['dataC']+')'+'</td>';
										}else{
											htmlE += '<td style="width:100px;" class="monS"></td>';
										}
									}else{
										$('.monS').remove();
									}


								htmlE += '<tr/>';
							};
							$('#tbody').append(htmlE);
							$('#pagehtml').css('display','block');
						}else{
							$("#tbody").html("<div style=\"text-align:center\">没有记录！</div>");
						$("#mbody").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
					},
					error : function(){
						$("#chartdiv").html("<div style=\"text-align:center\">没有记录！</div>");
						$("#mbody").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				});


			}

			$("#excel").click(function(){
		var ip = $("#sip").val();
		var startdate = $("#startdate").val();
		var enddate = $("#enddate").val();
		window.location = "<?php echo $this->_tpl_vars['logicApp']; ?>
/userkeepb/writeExcel/ip/"+ip+"/startdate/"+startdate+"/enddate/"+enddate;
	});


	
-->
						
	</script>
</body>
</html>
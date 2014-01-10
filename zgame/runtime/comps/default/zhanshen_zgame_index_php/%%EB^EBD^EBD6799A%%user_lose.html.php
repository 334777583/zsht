<?php /* Smarty version 2.6.18, created on 2014-01-04 11:07:20
         compiled from stickiness/user_lose.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>新进用户流失等级分布</title>
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
	<div class="container">
		<div>
			<div  id="user-tabs">
				<span id="1" class="user-gray">留存分布</span>
				<span id="2" >流失分布</span>
				<span id="3" class="user-gray">任务分布</span>
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
							<td width="95%" class="tableleft">1 新进用户N日留存用户：查询当天的新进登陆用户，在第N天仍有登陆行为的用户</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">2 新进用户N日流失用户：查询当天的新进登陆用户，在第N天没有登陆行为的用户</td>
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
					<span style="margin-left: 20px">日期:</span>
					<input type="text" id="startdate" class="input1" value="<?php echo $this->_tpl_vars['startDate']; ?>
"/>
					<input type="button" value="查询" id="querybtn"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<div>
				<table  class="mytable" cellspacing="0" align="center" id="dtable">
					<thead>
						<tr>
							<th>流失等级</th>
							<th>新进用户次日流失总数</th>
							<th>百分比</th>
							<th>新进用户三日流失总数</th>
							<th>百分比</th>
							<th>新进用户四日流失总数</th>
							<th>百分比</th>
							<th>新进用户五日流失总数</th>
							<th>百分比</th>
							<th>新进用户六日流失总数</th>
							<th>百分比</th>
							<th>新进用户七日流失总数</th>
							<th>百分比</th>
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
			
			<div style="height:50px">&nbsp;</div>
			<div style="display:none" id="chart_select">
				<select id="chart_type">
					<option value="1">新进用户次日流失总数</option>
					<option value="2">新进用户三日流失总数</option>
					<option value="3">新进用户四日流失总数</option>
					<option value="4">新进用户五日流失总数</option>
					<option value="5">新进用户六日流失总数</option>
					<option value="6">新进用户七日流失总数</option>
				</select>
			</div>
			<div id="chart_div" style="width: 100%; height: 500px;display:none"></div>
		
			<div style="clear:both"></div>
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
		var user_lose = {
			INIT : function(){
				var self = this;
				
				//时间插件
				$("#startdate").datepicker();
				
				showTitle("用户数据分析:新进分析");
				self.showLoseLV();
				page.listen();	//分页事件绑定
				
				//切换标签
				$("#user-tabs span").click(function(){
					window.location = "<?php echo $this->_tpl_vars['app']; ?>
/usernew/show/pageId/"+this.id;
				})
				
				$("#querybtn").bind('click', function(){
					self.showLoseLV();
				})
				
				//选择图表类型
				$("#chart_type").change(function() {
					var val = $(this).val();
					var data = '';
					switch(val) {
						case '1' : data = $('body').data('two');break;
						case '2' : data = $('body').data('three');break;
						case '3' : data = $('body').data('four');break;
						case '4' : data = $('body').data('five');break;
						case '5' : data = $('body').data('six');break;
						case '6' : data = $('body').data('seven');break;
					}
					self.showColumn(data, "chart_div");	
				})

			},
			
			//新进用户等级分布
			showLoseLV : function() {
				var self = this;
				$("#chart_div, #chart_select").hide();
				$.ajax({
					type : 'POST',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/usernew/getLoseLV',
					dataType : 'json',
					data : {
						startdate : $("#startdate").val(),
						ip : $("#sip").val()
					},
					beforeSend : function() {
						$("#dtatr_body").html("<tr><td colspan='13'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
					},
					success : function (data) {
						if(typeof(data.result) != 'undefined' && data.result != "") {
							var result = data.result;
							
							var two = [];			
							var three = [];
							var four = [];
							var five = [];
							var six = [];
							var seven = [];
							for(var i in result) {		//组装数据，用于图表展示
								var two_item = {};
								var three_item = {};
								var four_item = {};
								var five_item = {};
								var six_item = {};
								var seven_item = {};
	
								two_item.level = result[i]['level']+"级";
								two_item.num = result[i]['two_num'];
								two.push(two_item);
								
								three_item.level = result[i]['level']+"级";
								three_item.num = result[i]['three_num'];
								three.push(three_item);
								
								four_item.level = result[i]['level']+"级";
								four_item.num = result[i]['four_num'];
								four.push(four_item);

								five_item.level = result[i]['level']+"级";
								five_item.num = result[i]['five_num'];
								five.push(five_item);
								
								six_item.level = result[i]['level']+"级";
								six_item.num = result[i]['six_num'];
								six.push(six_item);
								
								seven_item.level = result[i]['level']+"级";
								seven_item.num = result[i]['seven_num'];
								seven.push(seven_item);
							}
							
							$('body').data('two',two);
							$('body').data('three',three);
							$('body').data('four',four);
							$('body').data('five',five);
							$('body').data('six',six);
							$('body').data('seven',seven);
							
							$("#chart_div, #chart_select").show();
							self.showColumn(two, "chart_div");	
							
							
							$("#pagehtml").show();
							var fields = ['level', 'two_num', 'two_per', 'three_num', 'three_per', 'four_num', 'four_per', 'five_num', 'five_per', 'six_num', 'six_per', 'seven_num', 'seven_per'];
							page.INIT(25, result, fields, '#dtatr_body');
							$("#home_page").trigger('click');
						}else {
							$("#chart_div, #chart_select").hide();
							$("#pagehtml").hide();
							$("#dtatr_body").html("<tr><td colspan='13'>没有数据！</td></tr>");
						}
					},
					error : function () {
						$("#pagehtml").hide();
						$("#dtatr_body").html("<tr><td colspan='13'>没有数据！</td></tr>");
					}
				})
			},
			
			//显示柱状图
			showColumn : function(data, div) {
				var chart = new AmCharts.AmSerialChart();
                chart.dataProvider = data;
                chart.categoryField = "level";
                chart.startDuration = 1;

                // AXES
                // category
                var categoryAxis = chart.categoryAxis;
                categoryAxis.labelRotation = 90;
                categoryAxis.gridPosition = "start";

                // value
                // in case you don't want to change default settings of value axis,
                // you don't need to create it, as one value axis is created automatically.

                // GRAPH
                var graph = new AmCharts.AmGraph();
                graph.valueField = "num";
                graph.balloonText = "[[category]]: [[value]]";
                graph.type = "column";
                graph.lineAlpha = 0;
                graph.fillAlphas = 0.8;
                chart.addGraph(graph);

                chart.write(div);
			}

		}
		
		$(document).ready(function(){
			user_lose.INIT();
		})
	</script>
</body>
</html>
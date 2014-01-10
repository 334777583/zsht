<?php /* Smarty version 2.6.18, created on 2014-01-02 10:36:23
         compiled from stickiness/user_login.html */ ?>
<!DOCTYPE html>
<html>
<head>
<title>登录概况</title>
<meta http-equiv="Content-Type" content="text/html; chartset=utf-8" />
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
	background-color: #F7F8F9;
	font-size: 12px;
}
-->
</style>
</head>
<body>
<div>
	<div id="user-tabs"  style="margin-top:20px;">
		<span id="1">登录汇总</span>
		<span id="2" class="user-gray">在线信息</span>
		<span id="3" class="user-gray">在线时长</span>
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
						<td width="95%" class="tableleft">1、曲线图中，单独查看某个数值趋势，可在曲线图下方点击其他字段取消显示</td>
					</tr>
					<tr>
						<td width="95%" class="tableleft">2、曲线图中，默认不能显示查询的所有数据，可拖动<font color="red"><b>横向滚动条</b></font>OR点击图右上方的<font color="red"><b>"Showall"</b></font>查看所有信息</td>
					</tr>
					<tr>
						<td width="95%" class="tableleft">3、<b>创角数</b>：当天创建角色数；<b>登录数</b>：去掉重复后的帐号登录数；<b>登录总数</b>：不计重复登录的角色登录数；<b>2登</b>：活跃天数为2的角色</td>
					</tr>
					<tr>
						<td width="95%" class="tableleft">4、<b>平均在线时长</b>：当天角色平均在线时长；<b>最高在线时长</b>：当天最高在线时长；<b>平均同时在线人数</b>：平均每分钟同时在线人数；<b>最高同时在线人数</b>：当天最高在线</td>
					</tr>
				</tbody>
			</table>
		</div>
		<table width="100%"  cellpadding="0" cellspacing="0">
			<tr>
		    	<td valign="top" bgcolor="#F7F8F9">
			    	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
			 			<tr>
			 				<td>
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
			 							<input type="text" id="startdate" class="input1"/>至<input type="text" id="enddate" class="input1"/>
			 							<input type="button" value="查询" id="querybtn"/>
		 							</div>
		 						</div>
			 				</td>
			 			</tr>
			 			<tr>	
			 				<td align="center">
			 					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table_blue2">
									<tr>
										<td align="center" >
											<div id="chartdiv" style="width: 100%; height: 400px;"></div>
										</td>
									</tr>
								</table>
			 				</td>	
			 			</tr>


			 			<tr>
			 				<td>
			 					<table class="mytable" id="mytable" cellspacing="0" align="center">
			 						<thead>
										<tr>
											<th>时间</th>
											<th>创角数</th>
											<th>登录数</th>
											<th>登录总数</th>
											<th>登录IP数</th>
											<th>≥2登</th>
											<th>≥3登</th>
											<th>≥5登</th>
											<th>≥10登</th>
											<th>≥15登</th>
											<th>平均在线时长</th>
											<th>最高在线时长</th>
											<th>平均同时在线人数</th>
											<th>最高同时在线人数</th>
											<!-- <th>当前在线</th> -->
										</tr>
									</thead>
									<tbody id="mbody">
									</tbody>
								</table>
			 				</td>
			 			</tr>
			 			<tr>
			 				<td>
								<div class="exportbtn" style="display:none" id="export_div">
				 					<input type="button" value="导出Excel" id="exportbtn"/>
				 				</div>
		
								<div id="pagehtml" style="float:right;margin-right:20px"></div>
								<div id="example_length" class="dataTables_length"  style="display:none">
			 						<label>每页显示
				 						<select id="menu" name="example_length" size="1" aria-controls="example">
				 						<option value="10" selected="selected">10</option>
				 						<option value="25">25</option>
				 						<option value="50">50</option>
				 						<option value="100">100</option>
				 						</select> 条记录
				 					</label>
				 				</div>
			 				</td>
			 			</tr>
			    	</table>
		    	</td>
	  		</tr>
		</table>
	</div>

	<div title="Basic dialog" id="tabs-2" style="display:none" class="tabitem">
		<div>
			<table class="explain">
				<thead>
				</thead>
				<tbody style="font-family:Mingliu">
					<tr>
						<td width="5%"  class="tableleft"><b>说明：</b></td>
					</tr>
					<tr>
						<td width="95%" class="tableleft">1、首次进入此页面默认显示的是今天凌晨到进入页面的时间的在线情况；</td>
					</tr>
					<tr>
						<td width="95%" class="tableleft">2、输入查询日期即刻查询当日的在线趋势情况；</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="top_item topinfo" style="margin-bottom: 0px">
			<div>
				<label>
					当前服务时间:<span id="curTime"></span>
				</label>
				<label>
					<span>服务器:</span>
					<select id="rip">
					<?php $_from = $this->_tpl_vars['ipList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ip']):
?>
						<option value="<?php echo $this->_tpl_vars['ip']['s_id']; ?>
" ip="<?php echo $this->_tpl_vars['ip']['g_ip']; ?>
"><?php echo $this->_tpl_vars['ip']['s_name']; ?>
</option>
					<?php endforeach; endif; unset($_from); ?>
					</select>
				</label>
				<label>
					<span>时间:</span>
					<input type="text" id="qdate" class="input1"/>
				</label>
				<label>
					<span>时间间隔:</span>
					<select id="interval">
						<option value="1">每1分钟</option>
						<option value="2">每5分钟</option>
						<option value="3">每1小时</option>
					</select>
				</label>
				<label>
					<input type="button" id="digquary" value="查询"/>
				</label>
				<label>
					<input type="button" id="dtquary" value="实时刷新"/>
				</label>
			</div>
		</div>
		<div>
			<div id="realinfo">
				<label>在线信息统计:</label>
				<label>今日注册:<span id="regNum">0</span></label>
				<label>创号数:<span id="createNum">0</span></label>
				<label>登录总数:<span id="loginNum">0</span></label>
				<label>当前在线:<span id="curNum" >0</span></label>
			</div>
			<div class="title_font">
				<span>在线走势</span>
			</div>
			<div>
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table_blue2">
					<tr>
						<td align="center" >
							<div id="chartdiv2" style="width: 100%; height: 400px;"></div>
						</td>
					</tr>
				</table>
			</div>
			<div style="clear:both"></div>
		</div>
		<div style="clear: both;"></div>
		<div>
			<table  class="mytable" cellspacing="0" align="center" id="digtable">
				<thead>
					<tr>
						<th style="width:20%">统计时间</th>
						<th style="width:20%">实时在线人数</th>
						<th style="width:60%">注：柱状下限为0，峰值为5000具体长度根据在线人数比例显示</th>
					</tr>
				</thead>
				<tbody id="dbody">
				</tbody>
			</table>
		</div>
		<div id="pagehtml2" style="float:right;margin-right:20px"></div>
		<div id="example_length" class="dataTables_length">
			<label>每页显示
				<select id="digmenu" name="example_length" size="1" aria-controls="example">
				<option value="50" selected="selected">50</option>
				<option value="100" >100</option>
				<option value="200">200</option>
				<option value="500">500</option>
				</select> 条记录
			</label>
		</div>
	</div>
	
	<div id="tabs-3" style="display:none" class="tabitem">
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
							<td width="95%" class="tableleft">1、<b>用户每日平均在线时长</b>=当天所有用户的总在线时长/当天登陆账号数；</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">2、<b>日平均在线时长</b> = 查询时间段内，该玩家的总在线时长/查询时间段内，该玩家的登陆天数；</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div>
				<div class="topinfo">	
					<span>服务器:</span>
					<select id="tip">
					<?php $_from = $this->_tpl_vars['ipList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ip']):
?>
					<option value="<?php echo $this->_tpl_vars['ip']['s_id']; ?>
"><?php echo $this->_tpl_vars['ip']['s_name']; ?>
</option>
					<?php endforeach; endif; unset($_from); ?>
					</select>
					<span style="margin-left: 20px">日期:</span>
					<input type="text" id="tstartdate" class="input1"/>至<input type="text" id="tenddate" class="input1"/>
					<input type="button" value="查询" id="tquerybtn"/>
				</div>
			</div>
			<div class="title_font">
				<span>用户平均在线时长</span>
			</div>
			<div>
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table_blue2">
					<tr>
						<td align="center" >
							<div id="chartdiv3" style="width: 100%; height: 400px;"></div>
						</td>
					</tr>
				</table>
			</div>
			<div class="title_font">
				<span>日平均在线分布</span>
			</div>
			<div  style="border:1px solid #DDDBF2" >
				<div id="piediv" style="width: 75%; height: 400px;"></div>
			</div>
		</div>
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
	//登录汇总
	var user_login = {	
		INIT : function(){
			var self = this;
			
			//时间插件
			$("#startdate").datepicker();
			$("#enddate").datepicker();
			
			//根据条件查询
			$("#querybtn").click(function(){
				if(validator("startdate", "enddate")){
					self.getData();
				}
			});
			
			//每页显示
			$("#menu").change(function(){
				self.getdata(1);
			});
			
			//导出excel
			$("#exportbtn").click(function(){
				var ip = $("#sip").val();
				var startDate = $("#startdate").val();
				endDate = $("#enddate").val();
				window.location.href = "<?php echo $this->_tpl_vars['logicApp']; ?>
/userlogin/writeExcel/ip/"+ip+"/startDate/"+startDate+"/endDate/"+endDate;
			});
			
			//页面加载，显示图表
			self.getData();
			showTitle("用户数据分析:登录概况");
			
			
			//切换标签
			$("#user-tabs span").click(function(){
				$(".tabitem").css("display","none");
				var id  = "#tabs-"+this.id;
				if(1 == this.id){ //登录汇总
					$("#user-tabs span").attr("class","user-gray");//标签切换颜色
					$(this).removeClass("user-gray");
					showTitle("用户数据分析:登录概况");
					if($("#chartdiv").children().length>0){ //解决谷歌显示问题
						$("#chartdiv").children().first().css("width","1669px");
						$("#chartdiv").children().first().css("height","352px");
					}
				}else if(2 == this.id){ //在线信息
					$("#user-tabs span").attr("class","user-gray");//标签切换颜色
					$(this).removeClass("user-gray");
					$("#tabs-2").show();
					showTitle("用户数据分析:在线信息");
					
					var today =  curentDate();
					$("#qdate").val(today);
					user_zaxx.getRealData(today,1);
					
					if($("#chartdiv2").children().length > 0){
						$("#chartdiv2").children().first().css("width","1669px");
						$("#chartdiv2").children().first().css("height","352px");
					}
				}else if(3 == this.id){ //在线时长
					$("#user-tabs span").attr("class","user-gray");//标签切换颜色
					$(this).removeClass("user-gray");
					
					$("#tabs-3").show();
					showTitle("用户数据分析:在线时长");
					user_zxsc.showOnline();
					
					if($("#chartdiv3").children().length > 0){
						$("#chartdiv3").children().first().css("width","1669px");
						$("#chartdiv3").children().first().css("height","352px");
					}
				}
				$(id).css("display","block");
			})
		
		},

		//获取登录汇总信息
		getData : function(){
			var self = this;
			
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/userlogin/getdata',
					dataType : 'json',
					data : {
						ip : $("#sip").val(),
						startDate : $("#startdate").val(),
						endDate : $("#enddate").val(),
						pageSize : $("#menu").val(),
						time : Date.parse(new Date())
					},
					async : true,
					cache : true,
					beforeSend : function(){
						$("#chartdiv").html("<div style='margin-top:200px;width:100%;display:block;text-align:center'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></div>");
						$("#mbody").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						chartData  = [];//清除数据，防止不断叠加
						
						var list = [];
						list = data.list;
						var html = "";
						var zcs = 0;	//本页注册数总计
						var chs = 0;	//本页创号数总计
						var dls = 0;	//本页登录数总计
						var dlzs = 0;	//本页登录总数
						var dlips = 0;	//本页登录IP数
						var twod = 0;	//本页2登
						var three = 0;	//本页3登
						var fived = 0;	//本页≥5登
						var tend = 0;	//本页≥10登
						var fifteen = 0;//本页≥15登
						var pjzx = 0;	//本页平均在线
						var zgzx = 0;	//本页最高在线
						var zjts = 0;	//本页平均同时在线
						var zgts = 0;	//本页最高同时在线
						
						var zcs_sum = 0;	//注册数总计
						var chs_sum = 0;	//创号数总计
						var dls_sum = 0;	//登录数总计
						var dlzs_sum = 0;	//登录总数
						var dlips_sum = 0;	//登录IP数
						var twod_sum = 0;	//2登
						var three_sum = 0;	//3登
						var fived_sum = 0;	//≥5登
						var tend_sum = 0;	//≥10登
						var fifteen_sum = 0;//≥15登
						var pjzx_sum = 0;	//平均在线
						var zgzx_sum = 0;	//最高在线
						var zjts_sum = 0;	//平均同时在线
						var zgts_sum = 0;	//最高同时在线
						
						if(list.length >0){
							$("#export_div").show();
							$("#example_length").show();
							var chartDate = [];
							for(var i in list){
								//初始化图表数据
								var newDate = parseDate(list[i]["m_date"]);
								chartData.push({
									date: newDate,
									visits: parseInt(list[i]["m_creat"]),
									hits: parseInt(list[i]["m_login"]),
									views: parseInt(list[i]["m_sametime"]),
									newCounts:parseInt(list[i]["m_maxsametime"])
								});
								
								if(i < parseInt(data.pageSize)){
									html += "<tr>";
									html += "<td>" + list[i]["m_date"] + "</td>";
									html += "<td>" + list[i]["m_creat"] + "</td>";
									html += "<td>" + list[i]["m_login"] + "</td>";
									html += "<td>" + list[i]["m_login_sum"] + "</td>";
									html += "<td>" + list[i]["m_ip_num"] + "</td>";
									html +=	"<td>" + list[i]["l_two"] + "</td>";
									html +=	"<td>" + list[i]["l_three"] + "</td>";
									html += "<td>" + list[i]["l_five"] + "</td>";
									html += "<td>" + list[i]["l_ten"] + "</td>";
									html += "<td>" + list[i]["l_fifteen"] + "</td>";
									html += "<td>" + toformat(list[i]["m_count"]) + "</td>";
									html += "<td>" + toformat(list[i]["m_maxtime"]) + "</td>";
									html += "<td>" + list[i]["m_sametime"] + "</td>";
									html += "<td>" + list[i]["m_maxsametime"] + "</td>";
									html += "</tr>";
									
									zcs += parseInt(list[i]["m_reg"]);
									chs += parseInt(list[i]["m_creat"]);
									dls += parseInt(list[i]["m_login"]);
									dlzs += parseInt(list[i]["m_login_sum"]);
									dlips += parseInt(list[i]["m_ip_num"]);
									twod += parseInt(list[i]["l_two"]);
									three += parseInt(list[i]["l_three"]);
									fived += parseInt(list[i]["l_five"]);
									tend += parseInt(list[i]["l_ten"]);							
									fifteen += parseInt(list[i]["l_fifteen"]);							
									pjzx += parseInt(list[i]["m_count"]);
									zgzx += parseInt(list[i]["m_maxtime"]);	
									zjts += parseInt(list[i]["m_sametime"]);
									zgts += parseInt(list[i]["m_maxsametime"]);	
								}

								zcs_sum += parseInt(list[i]["m_reg"]);
								chs_sum += parseInt(list[i]["m_creat"]);
								dls_sum += parseInt(list[i]["m_login"]);
								dlzs_sum += parseInt(list[i]["m_login_sum"]);
								dlips_sum += parseInt(list[i]["m_ip_num"]);
								twod_sum += parseInt(list[i]["l_two"]);
								three_sum += parseInt(list[i]["l_three"]);
								fived_sum += parseInt(list[i]["l_five"]);
								tend_sum += parseInt(list[i]["l_ten"]);							
								fifteen_sum += parseInt(list[i]["l_fifteen"]);							
								pjzx_sum += parseInt(list[i]["m_count"]);
								zgzx_sum += parseInt(list[i]["m_maxtime"]);	
								zjts_sum += parseInt(list[i]["m_sametime"]);
								zgts_sum += parseInt(list[i]["m_maxsametime"]);			
							}
							//本页总计
							html += "<tr>";
							html += "<td>本页总计</td>";
							html += "<td>" + chs + "</td>";
							html += "<td>" + dls + "</td>";
							html += "<td>" + dlzs + "</td>";
							html += "<td>" + dlips + "</td>";
							html +=	"<td>" + twod + "</td>";
							html +=	"<td>" + three + "</td>";
							html += "<td>" + fived + "</td>";
							html += "<td>" + tend + "</td>";
							html += "<td>" + fifteen + "</td>";
							html += "<td>" + toformat(pjzx) + "</td>";
							html += "<td>" + toformat(zgzx) + "</td>";
							html += "<td>" + zjts + "</td>";
							html += "<td>" + zgts + "</td>";
							html += "</tr>";
							
							
							//总计
							html += "<tr>";
							html += "<td>总计</td>";
							html += "<td>" + chs_sum + "</td>";
							html += "<td>" + dls_sum + "</td>";
							html += "<td>" + dlzs_sum + "</td>";
							html += "<td>" + dlips_sum + "</td>";
							html +=	"<td>" + twod_sum + "</td>";
							html +=	"<td>" + three_sum + "</td>";
							html += "<td>" + fived_sum + "</td>";
							html += "<td>" + tend_sum + "</td>";
							html += "<td>" + fifteen_sum + "</td>";
							html += "<td>" + toformat(pjzx_sum) + "</td>";
							html += "<td>" + toformat(zgzx_sum) + "</td>";
							html += "<td>" + zjts_sum + "</td>";
							html += "<td>" + zgts_sum + "</td>";
							html += "</tr>";
							
							$("#mbody").html(html);
							$("#pagehtml").html(data.pageHtml);		//分页
							
							//table单双行交叉样式
							$("#mbody tr:odd").css("background-color", "#edf2f7"); 
							$("#mbody tr:even").css("background-color","#e0f0f0"); 
							self.showChart(chartData);				//显示图表
						}else{ 
							//没有记录默认为0
							var chartList = data.chartList;
							var chartData = [];
							for(var i in chartList){
								var newDate = parseDate(chartList[i]["m_date"]);
								chartData.push({
									date: newDate,
									visits: parseInt(chartList[i]["m_creat"]),
									hits: parseInt(chartList[i]["m_login"]),
									views: parseInt(chartList[i]["m_sametime"]),
									newCounts:parseInt(chartList[i]["m_maxsametime"])
								});
							}
							self.showChart(chartData);				//显示图表
							$("#mbody").html("<tr><td colspan='15'>没有记录！</td></tr>");
						}
						
						$( "#startdate").val(data.startDate);
						$("#enddate").val(data.endDate);
						
						$( "#tstartdate").val(data.startDate);
						$("#tenddate").val(data.endDate);
					},
					error : function(){
						$("#chartdiv").html("<div style=\"text-align:center\">没有记录！</div>");
						$("#mbody").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				})
		},
		
		//用于表格ajax请求数据
		getdata : function(page){
			$("#mbody").html("");
			$("#pagehtml").html("");
			$.ajax({
				type : 'get', 
				url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/userlogin/getJsonData',
				dataType : 'json',
				data :{
					ip : $("#sip").val(),
					startDate : $("#startdate").val(),
					endDate : $("#enddate").val(),
					pageSize : $("#menu").val(),
					curPage : page,
					time : Date.parse(new Date())
				},
				beforeSend : function(){
					$("#mbody").html("<tr><td colspan='15'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
				},
				success : function(data){			
					var list = [];
					var html = ""	//table表格html
					
					var zcs = 0;	//本页注册数总计
					var chs = 0;	//本页创号数总计
					var dls = 0;	//本页登录数总计
					var dlzs = 0;	//本页登录总数
					var dlips = 0;	//本页登录IP数
					var twod = 0;	//本页2登
					var three = 0;	//本页3登
					var fived = 0;	//本页≥5登
					var tend = 0;	//本页≥10登
					var fifteen = 0;//本页≥15登
					var pjzx = 0;	//本页平均在线
					var zgzx = 0;	//本页最高在线
					var zjts = 0;	//本页平均同时在线
					var zgts = 0;	//本页最高同时在线
					
					var zcs_sum = 0;	//注册数总计
					var chs_sum = 0;	//创号数总计
					var dls_sum = 0;	//登录数总计
					var dlzs_sum = 0;	//登录总数
					var dlips_sum = 0;	//登录IP数
					var twod_sum = 0;	//2登
					var three_sum = 0;	//3登
					var fived_sum = 0;	//≥5登
					var tend_sum = 0;	//≥10登
					var fifteen_sum = 0;//≥15登
					var pjzx_sum = 0;	//平均在线
					var zgzx_sum = 0;	//最高在线
					var zjts_sum = 0;	//平均同时在线
					var zgts_sum = 0;	//最高同时在线
					
					list = data.list;
					if(list.length >0){
						for(var i in data.oriList){
							zcs_sum += parseInt(data.oriList[i]["m_reg"]);
							chs_sum += parseInt(data.oriList[i]["m_creat"]);
							dls_sum += parseInt(data.oriList[i]["m_login"]);
							dlzs_sum += parseInt(data.oriList[i]["m_login_sum"]);
							dlips_sum += parseInt(data.oriList[i]["m_ip_num"]);
							twod_sum += parseInt(data.oriList[i]["l_two"]);
							three_sum += parseInt(data.oriList[i]["l_three"]);
							fived_sum += parseInt(data.oriList[i]["l_five"]);
							tend_sum += parseInt(data.oriList[i]["l_ten"]);							
							fifteen_sum += parseInt(data.oriList[i]["l_fifteen"]);							
							pjzx_sum += parseInt(data.oriList[i]["m_count"]);
							zgzx_sum += parseInt(data.oriList[i]["m_maxtime"]);	
							zjts_sum += parseInt(data.oriList[i]["m_sametime"]);
							zgts_sum += parseInt(data.oriList[i]["m_maxsametime"]);			
						}
						
						for(var i in list){
							html += "<tr>";
							html += "<td>" + list[i]["m_date"] + "</td>";
							html += "<td>" + list[i]["m_creat"] + "</td>";
							html +=	"<td>" + list[i]["m_login"] + "</td>";
							html += "<td>" + list[i]["m_login_sum"] + "</td>";
							html += "<td>" + list[i]["m_ip_num"] + "</td>";
							html +=	"<td>" + list[i]["l_two"] + "</td>";
							html +=	"<td>" + list[i]["l_three"] + "</td>";
							html += "<td>" + list[i]["l_five"] + "</td>";
							html += "<td>" + list[i]["l_ten"] + "</td>";
							html += "<td>" + list[i]["l_fifteen"] + "</td>";
							html += "<td>" + toformat(list[i]["m_count"]) + "</td>";
							html += "<td>" + toformat(list[i]["m_maxtime"]) + "</td>";
							html += "<td>" + list[i]["m_sametime"] + "</td>";
							html += "<td>" + list[i]["m_maxsametime"] + "</td>";
							html += "</tr>";
							

							chs += parseInt(list[i]["m_creat"]);
							dls += parseInt(list[i]["m_login"]);
							dlzs += parseInt(list[i]["m_login_sum"]);
							dlips += parseInt(list[i]["m_ip_num"]);
							twod += parseInt(list[i]["l_two"]);
							three += parseInt(list[i]["l_three"]);
							fived += parseInt(list[i]["l_five"]);
							tend += parseInt(list[i]["l_ten"]);							
							fifteen += parseInt(list[i]["l_fifteen"]);							
							pjzx += parseInt(list[i]["m_count"]);
							zgzx += parseInt(list[i]["m_maxtime"]);	
							zjts += parseInt(list[i]["m_sametime"]);
							zgts += parseInt(list[i]["m_maxsametime"]);	
						}
						
						//本页总计
						html += "<tr>";
						html += "<td>本页总计</td>";
						html += "<td>" + chs + "</td>";
						html += "<td>" + dls + "</td>";
						html += "<td>" + dlzs + "</td>";
						html += "<td>" + dlips + "</td>";
						html +=	"<td>" + twod + "</td>";
						html +=	"<td>" + three + "</td>";
						html += "<td>" + fived + "</td>";
						html += "<td>" + tend + "</td>";
						html += "<td>" + fifteen + "</td>";
						html += "<td>" + toformat(pjzx) + "</td>";
						html += "<td>" + toformat(zgzx) + "</td>";
						html += "<td>" + zjts + "</td>";
						html += "<td>" + zgts + "</td>";
						html += "</tr>";
						
						//总计
						html += "<tr>";
						html += "<td>总计</td>";
						html += "<td>" + chs_sum + "</td>";
						html += "<td>" + dls_sum + "</td>";
						html += "<td>" + dlzs_sum + "</td>";
						html += "<td>" + dlips_sum + "</td>";
						html +=	"<td>" + twod_sum + "</td>";
						html +=	"<td>" + three_sum + "</td>";
						html += "<td>" + fived_sum + "</td>";
						html += "<td>" + tend_sum + "</td>";
						html += "<td>" + fifteen_sum + "</td>";
						html += "<td>" + toformat(pjzx_sum) + "</td>";
						html += "<td>" + toformat(zgzx_sum) + "</td>";
						html += "<td>" + zjts_sum + "</td>";
						html += "<td>" + zgts_sum + "</td>";
						html += "</tr>";
						
						$("#mbody").html(html);
						$("#mbody tr:odd").css("background-color", "#edf2f7"); 
						$("#mbody tr:even").css("background-color","#e0f0f0"); 
						$("#pagehtml").html(data.pageHtml);
					}else{ 
						$("#mbody").html("<tr><td colspan='15'>没有记录！</td></tr>");
					}
				},
				error : function(){
					$("#mbody").html("<tr><td colspan='15'>没有记录！</td></tr>");
				}
			}); 
		},
		
	
		//amcharts相关配置
		showChart : function(chartData){
			var chart;
			
			function zoomChart() {
				chart.zoomToIndexes(0, 50);
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
			valueAxis2.position = "right"; // this line makes the axis to appear on the right
			valueAxis2.axisColor = "#FCD202";
			valueAxis2.gridAlpha = 0;
			valueAxis2.axisThickness = 2;
			chart.addValueAxis(valueAxis2);

			// third value axis (on the left, detached)
			var valueAxis3 = new AmCharts.ValueAxis();
			valueAxis3.offset = 50; // this line makes the axis to appear detached from plot area
			valueAxis3.gridAlpha = 0;
			valueAxis3.axisColor = "#B0DE09";
			valueAxis3.axisThickness = 2;
			chart.addValueAxis(valueAxis3);

			var valueAxis4 = new AmCharts.ValueAxis();
			valueAxis4.offset = 100; // this line makes the axis to appear detached from plot area
			valueAxis4.gridAlpha = 0;
			valueAxis4.axisColor = "#66CCCC";
			valueAxis4.axisThickness = 2;
			chart.addValueAxis(valueAxis4);

			// GRAPHS
			// first graph
			var graph1 = new AmCharts.AmGraph();
			graph1.valueAxis = valueAxis1; // we have to indicate which value axis should be used
			graph1.title = "创号数";
			graph1.valueField = "visits";
			graph1.bullet = "round";
			graph1.hideBulletsCount = 30;
			chart.addGraph(graph1);

			// second graph				
			var graph2 = new AmCharts.AmGraph();
			graph2.valueAxis = valueAxis2; // we have to indicate which value axis should be used
			graph2.title = "登录数";
			graph2.valueField = "hits";
			graph2.bullet = "square";
			graph2.hideBulletsCount = 30;
			chart.addGraph(graph2);

			// third graph
			var graph3 = new AmCharts.AmGraph();
			graph3.valueAxis = valueAxis3; // we have to indicate which value axis should be used
			graph3.valueField = "views";
			graph3.title = "平均同时在线";
			graph3.bullet = "triangleUp";
			graph3.hideBulletsCount = 30;
			chart.addGraph(graph3);

			//fourth graph
			var graph4 = new AmCharts.AmGraph();
			graph4.valueAxis = valueAxis4; // we have to indicate which value axis should be used
			graph4.valueField = "newCounts";
			graph4.title = "最高同时在线";
			graph4.bullet = "triangleUp";
			graph4.hideBulletsCount = 30;
			chart.addGraph(graph4);


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
			chart.write("chartdiv");
		}
	
	}
	
	var flag = true;	//标志，用于防止重复执行在线信息
	//在线信息
	var user_zaxx = {
		INIT : function(){
			var self = this;
			$("#tstartdate").datepicker();
			$("#tenddate").datepicker();
		
			//根据时间实时查询
			$("#digquary").click(function(){
				if(self.checktime()){
					self._fun_ge($("#qdate").val(),1);
					$("#realinfo").hide();
				}
			});
			
			//实时刷新
			$("#dtquary").click(function(){
				if(self.checktime()){
					var today =  curentDate();
					$("#qdate").val(today);
					self.getRealData(today,1);
					$("#realinfo").show();
				}
			});
			
			//在线信息每页显示
			$("#digmenu").change(function(){
				self._digTable($("#qdate").val(),1);
			});
			
		},
		
		checktime : function(){
			var isok = true;
			var reg = /\d{4}-\d{2}-\d{2}/;
			var end = $("#qdate").val();
			if(end == ""){
				alert("请输入时间！");
				isok = false;
			}else if(!reg.test(end)){
				alert("请输入格式为YYYY-MM-DD的时间");
				isok = false;
			}
			return isok;
		},
		
		//封装在线信息amcharts配置 
		digChartObj : function(data, minPeriod){
			var digChart;
			var zoomChart = function(){
				digChart.zoomToIndexes(0,24);
			}		
			
			// SERIAL digChart	
			digChart = new AmCharts.AmSerialChart();
			digChart.pathToImages = "<?php echo $this->_tpl_vars['res']; ?>
/images/";
			digChart.zoomOutButton = {
				backgroundColor: '#000000',
				backgroundAlpha: 0.15
			};
			
			digChart.categoryField = "date";
			digChart.dataProvider = data;

			// listen for "dataUpdated" event (fired when digChart is inited) and call zoomChart method when it happens
			digChart.addListener("dataUpdated", zoomChart);

			// AXES
			// category				
			var categoryAxis = digChart.categoryAxis;
			categoryAxis.parseDates = true; // as our data is date-based, we set parseDates to true
			categoryAxis.minPeriod = minPeriod; // our data is daily, so we set minPeriod to DD
			categoryAxis.dashLength = 2;
			categoryAxis.gridAlpha = 0.15;  
			categoryAxis.axisColor = "#DADADA";

			// first value axis (on the left)
			var valueAxis1 = new AmCharts.ValueAxis();
			valueAxis1.axisColor = "#0066CC";
			valueAxis1.axisThickness = 2;
			valueAxis1.gridAlpha = 0;
			digChart.addValueAxis(valueAxis1);

			// GRAPHS
			// first graph
			var graph1 = new AmCharts.AmGraph();
			graph1.valueAxis = valueAxis1; // we have to indicate which value axis should be used
			graph1.title = "在线走势";
			graph1.lineColor = "#0066CC";
			graph1.valueField = "visits";
			graph1.bullet = "round";
			graph1.hideBulletsCount = 30;
			digChart.addGraph(graph1);
			

			// CURSOR
			var chartCursor = new AmCharts.ChartCursor();
			chartCursor.cursorPosition = "mouse";
			//chartCursor.categoryBalloonDateFormat = "JJ:NN:SS";
			chartCursor.categoryBalloonDateFormat = "JJ:NN";
			digChart.addChartCursor(chartCursor);

			// SCROLLBAR
			var chartScrollbar = new AmCharts.ChartScrollbar();
			digChart.addChartScrollbar(chartScrollbar);

			// LEGEND
			var legend = new AmCharts.AmLegend();
			legend.marginLeft = 110;
			digChart.addLegend(legend);
			
			
			digChart.write("chartdiv2");
			
		},
		
		_fun_ge : function(endDate,page){
			var self  = this;
			var interval = $("#interval").val();
			var minPeriod = 'mm';
			if(interval == '3') {
				minPeriod = 'hh';
			}
			
			$("#dbody").html("");
			
			$.ajax({
				type : 'get',
				url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/userlogin/getCurData',
				dataType : 'json',
				data :{
					ip : $("#rip").val(),
					pageSize : $("#digmenu").val(),
					interval : interval,
					endDate : endDate,
					curPage : page
				},
				async : false,
				beforeSend : function(){
					$("#chartdiv2").html("<div style='margin-top:200px;width:100%;display:block;text-align:center'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></div>");
					$("#dbody").html("<tr><td colspan='3'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
				},
				complete : function() {
					flag = true;
				},
				success : function(data){
					var olist = [];
					var minArr = [];
					var html = "";
					if(data.olist){
						olist = data.olist;
					}
					if(data.minArr){
						minArr = data.minArr;
					}

					if(olist.length>0){
						var digData = [];//清空数组
						for(var i in olist){
							var percent = parseInt(olist[i]["h_num"])/5000*100+"%";
							html += "<tr>";
							html += "<td>" + olist[i]["h_date"] + "</td>";
							html += "<td>" + olist[i]["h_num"] + "</td>";
							html += "<td style=\"text-align:left\"><div style=\"background-color: #87cefa;height:8px;width:"+percent+"\">&nbsp;</div></td>";
							html += "</tr>";
						}
						$("#dbody").html(html);
						for(var i in minArr){
							var newDate = self.dhToDate(minArr[i]["h_date"]);
							var visits = parseInt(minArr[i]["h_num"]);
							digData.push({
								date: newDate,
								visits: visits
							}); 
						}
						$("#curNum").html(parseInt(data.curCount));	//当前在线
						self.digChartObj(digData,minPeriod);		//显示在线信息图表 
						$("#pagehtml2").html(data.pageHtml);		//分页
					}else {
						$("#chartdiv2").html("<div style='text-align:center'>没有记录！</div>");
						$("#dbody").html("<tr><td colspan='3'>没有记录！</td></tr>");
					}
				},
				error : function() {
					$("#chartdiv2").html("<div style='text-align:center'>没有记录！</div>");
					$("#dbody").html("<tr><td colspan='3'>没有记录！</td></tr>");
				}
			});
		
		},
		
		//根据日期（YYYY-MM-DD）和小时转换成Date对象
		dhToDate : function(dataString){
			var part =  new Array();
			var arr = new Array();
			var timeArr = new Array();
			part = dataString.split(" ");
			arr = part[0].split("-");
			timeArr = part[1].split(":");
			var date = new Date(Number(arr[0]), Number(arr[1]) - 1, Number(arr[2]),Number(timeArr[0]),Number(timeArr[1]),0);
			return date;
		},
		
		//实时刷新ajax函数
		getRealData : function(endDate,page){
			var self = this;
			var ip = $("#rip").find("option:selected").attr('ip');
			if(flag) {
				$.ajax({
					url :'<?php echo $this->_tpl_vars['furl']; ?>
'+'?ip='+ip,
					type:'get',
					async:	true,
					beforeSend : function(){
						$("#chartdiv2").html("<div style='margin-top:200px;width:100%;display:block;text-align:center'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></div>");
						$("#dbody").html("<tr><td colspan='3'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
						flag = false;
					},
					complete:function(){
						self._fun_ge(endDate,page);
					},
					error : function() {
						$("#chartdiv2").html("<div style='text-align:center'>没有记录！</div>");
						$("#dbody").html("<tr><td colspan='3'>没有记录！</td></tr>");
					}
				}); 
			}
			
		},
		
		//在线信息表格分页
		_digTable : function(endDate,page){
			$("#dbody").html("");
			
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/userlogin/getCurData',
					dataType : 'json',
					data :{
						ip : $("#rip").val(),
						pageSize : $("#digmenu").val(),
						interval : $("#interval").val(),
						endDate : endDate,
						curPage : page
					},
					cache : false,
					beforeSend : function(){
						$("#dbody").html("<tr><td colspan='3'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></td></tr>");
					},
					success : function(data){
						var olist = [];
						var html = "";
						if(data.olist){
							olist = data.olist;
						}
						if(olist.length>0){
							digData = [];//清空数组
							for(var i in olist){
								var percent = parseInt(olist[i]["h_num"])/5000*100+"%";
								html += "<tr>";
								html += "<td>" + olist[i]["h_date"] + "</td>";
								html += "<td>" + olist[i]["h_num"] + "</td>";
								html += "<td><div style=\"background-color: #87cefa;height:8px;width:"+percent+"\">&nbsp;</div></td>";
								html += "</tr>";
							}
							$("#dbody").html(html);
							$("#pagehtml2").html(data.pageHtml);//分页
						}else{ 
							$("#dbody").html("<tr><td colspan='3'>没有记录！</td></tr>");
						}
					},
					error : function() {
						$("#dbody").html("<tr><td colspan='3'>没有记录！</td></tr>");
					}
			});
		},

		
		//设置在线信息当前服务时间
		setCurent : function(){
			var currentTime = curentTime();
			$("#curTime").html(currentTime);
		}
	
	}

	
	//在线时长
	var user_zxsc = {
		INIT : function(){
			var self = this;
			$("#qdate").datepicker();
			
			//在线时长查询 
			$("#tquerybtn").click(function(){
				if(validator("tstartdate", "tenddate")){
					self.showOnline();
				}
			})
		},
		
		onlineShowChart : function(onlineData){
			var onlineChart;
			
			var onlinezoomChart = function() {
			// different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
				onlineChart.zoomToIndexes(0,50);
			}
			// SERIAL onlineChart	
			onlineChart = new AmCharts.AmSerialChart();
			onlineChart.pathToImages = "<?php echo $this->_tpl_vars['res']; ?>
/images/";
			onlineChart.zoomOutButton = {
				backgroundColor: '#000000',
				backgroundAlpha: 0.15
			};
			onlineChart.dataProvider = onlineData;
			onlineChart.categoryField = "date";

			// listen for "dataUpdated" event (fired when digChart is inited) and call onlinezoomChart method when it happens
			onlineChart.addListener("dataUpdated", onlinezoomChart);

			// AXES
			// category				
			var categoryAxis = onlineChart.categoryAxis;
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
			onlineChart.addValueAxis(valueAxis1);

			// GRAPHS
			// first graph
			var graph1 = new AmCharts.AmGraph();
			graph1.valueAxis = valueAxis1; // we have to indicate which value axis should be used
			graph1.title = "用户每日平均在线时长=当天所有用户的总在线时长 / 当天登录账户数";
			graph1.lineColor = "#FF6600";
			graph1.valueField = "visits";
			graph1.bullet = "round";
			graph1.hideBulletsCount = 30;
			onlineChart.addGraph(graph1);
			

			// CURSOR
			var chartCursor = new AmCharts.ChartCursor();
			chartCursor.cursorPosition = "mouse";
			//chartCursor.categoryBalloonDateFormat = "JJ:NN:SS";
			//chartCursor.categoryBalloonDateFormat = "JJ:NN";
			onlineChart.addChartCursor(chartCursor);

			// SCROLLBAR
			var chartScrollbar = new AmCharts.ChartScrollbar();
			onlineChart.addChartScrollbar(chartScrollbar);

			// LEGEND
			var legend = new AmCharts.AmLegend();
			legend.marginLeft = 110;
			onlineChart.addLegend(legend);

			// WRITE
			onlineChart.write("chartdiv3");
		},
		
		//用户每日平均在线时长 
		getDaily : function(ip,startdate,enddate){
			var self = this;
			$.ajax({
					type : 'get',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/userlogin/getDaily',
					dataType : 'json',
					data :{
						ip :ip,
						startDate : startdate,
						endDate : enddate,
						time : Date.parse(new Date())
					},
					beforeSend : function(){
						$("#chartdiv3").html("<div style='margin-top:200px;width:100%;display:block;text-align:center'><img src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif\"/></div>");
					},
					success : function(data){
						var list = [];
						list = data.list;
						var onlineData = [];
						if(list.length >0){
							for(var i in list){
								var newDate = parseDate(list[i]["m_date"]);
								var visits = parseInt(list[i]["m_count"]);

								onlineData.push({
									date: newDate,
									visits: visits
								});
							}
							self.onlineShowChart(onlineData);
						}else{ 
							var list = data.chartList;
							for(var i in list){
								var newDate = parseDate(list[i]["m_date"]);
								var visits = parseInt(list[i]["m_count"]);

								onlineData.push({
									date: newDate,
									visits: visits
								});
							}
							self.onlineShowChart(onlineData);
						}
					},
					error : function() {
						$("#chartdiv3").html("<div style='margin-top:200px;width:100%;display:block;text-align:center'>没有记录！</div>");
					}
			}); 
		},
		
		//显示饼状图
		showPie : function(pieChartData){
			// PIE pieChart
			var pieChart = new AmCharts.AmPieChart();
			pieChart.dataProvider = pieChartData;
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
			pieChart.write("piediv");
		},
		
		//日平均在线时长分布
		getDuration : function(ip,startdate,enddate){
			var self = this;
			$("#piediv").html("");
			$.get(
					'<?php echo $this->_tpl_vars['logicApp']; ?>
/userlogin/getDuration',
					{
						ip :ip,
						startDate : startdate,
						endDate : enddate,
						time : Date.parse(new Date())
					},
					function(data){
						var pieChartData = [{
							country: "0~30分钟",
							litres: data["030"]
						}, {
							country: "30~60分钟",
							litres: data["3060"]
						}, {
							country: "1~2小时",
							litres: data["12"]
						}, {
							country: "2~4小时",
							litres: data["24"]
						}, {
							country: "4~8小时",
							litres: data["48"]
						}, {
							country: "8小时以上",
							litres: data["8m"]
						}];
						//显示饼状图
						self.showPie(pieChartData);
					},
					'json'
				);	
		},
		
		//显示在线时长图表
		showOnline : function(){
			var self = this;
			var serverIp = $("#tip").val();//服务器ip
			var startdate = $("#tstartdate").val();//开始时间
			var enddate = $("#tenddate").val();//结束时间
			self.getDaily(serverIp,startdate,enddate); 
			self.getDuration(serverIp,startdate,enddate);
		}	
		
	} 


	$(document).ready(function(){
		user_login.INIT();
		user_zaxx.INIT();
		user_zxsc.INIT();			
	})
	
	setInterval(user_zaxx.setCurent,1000);
	
	//跳到相应页面 
	var go = function(){
		var pagenum = $("#page").val();
		if(pagenum=='' || isNaN(pagenum) || pagenum <= 0){
			alert('请输入一个正整数！');
			$("#page").val(1);
		}else{
			user_login.getdata(pagenum);
		}
	}
	
	//分页ajax函数
	var formAjax = function(page){
		user_login.getdata(page);
	}
	
	
	//实时刷新分页函数
	var digFormAjax = function(page){
		user_zaxx._digTable($("#qdate").val(), page);
	}
	
	//跳到相应页面 
	var digGo = function(){
		var pagenum = $("#digPage").val();
		if(pagenum=='' || isNaN(pagenum) || pagenum <= 0){
			alert('请输入一个正整数！');
			$("#digPage").val(1);
		}else{
			user_zaxx._digTable($("#qdate").val(), pagenum);
		}
	}
	
	
</script>


</body>
</html>
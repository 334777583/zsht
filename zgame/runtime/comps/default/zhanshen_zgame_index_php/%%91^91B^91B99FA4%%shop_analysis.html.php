<?php /* Smarty version 2.6.18, created on 2014-01-08 11:08:57
         compiled from money/shop_analysis.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>商城消费统计</title>
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
	-->
	</style>
</head>
<body>
	<div class="container">
		<div>
			<div  id="user-tabs">
				<a href="?pageId=1" ><span id="1">数据展示</span></a>
				<a href="?pageId=2" ><span id="2" class="user-gray">图表展示</span></a>
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
							<td width="95%" class="tableleft">1、查询区间内游戏商城中<b>礼券</b>、<b>元宝</b>和<b>绑定元宝</b>消费的物品分析</td>
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
					<input type="button" value="导出EXCEL" id="outexcel" style="margin-left:20px"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<div>
				<div>
					<table class="mytable" style="display:none">
						<thead id= 'am_thead'>
							<tr>
								
								<th>物品ID</th>
								<th>物品名称</th>
								<th colspan='2'>购买人数</th>
								<th>单价</th>
								<th colspan='2'>数量</th>
								<th colspan='2'>数量比</th>
								<th colspan='2'>总价</th>
								<th colspan='2'>总价比</th>
							</tr>
						</thead>
						<tbody id='am_body'>
						</tbody>
					</table>
				</div>
				<div id="pagehtml" style="float:right;margin-right:20px"></div>
				<div id="example_length" class="dataTables_length" style="display:none">
					<label>每页显示
						<select id="menu" name="example_length" size="1" aria-controls="example">
							<option value="10" selected="selected">10</option>
							<option value="25">25</option>
							<option value="50">50</option>
							<option value="100">100</option>
						</select> 条记录
					</label>
					<a id="home_page" href="javascript:void(0)">首页</a>&nbsp;&nbsp;
					<a id="pre_page" href="javascript:void(0)">上一页</a>&nbsp;&nbsp;
					<a id="next_page" href="javascript:void(0)">下一页</a>&nbsp;&nbsp;
					<a id="last_page" href="javascript:void(0)">尾页</a>&nbsp;&nbsp;
					<span>第<span id="cur_page">1</span>/<span id="total_page">1</span>页&nbsp;&nbsp;</span>
					
				</div>
			</div>
			
			
			<div style="margin-top:100px">
				<div>
					<table  class="mytable" style="display:none">
						<thead>
							<tr>
								<th width='15%'>日期</th>
								<th width='25%' colspan='2' >本日购买人数</th>
								<th width='25%' colspan='2'>本日购买数量</th>
								<th width='25%'colspan='2'>本日消费总额</th>
								<th width='10%'>消费占比</th>
							</tr>
						</thead>
						<tbody id='ad_body'>
						</tbody>
					</table>
				</div>
				<div id="pagehtml2" style="float:right;margin-right:20px"></div>
				<div id="example_length2" class="dataTables_length" style="display:none">
					<label>每页显示
						<select id="menu2" name="example_length" size="1" aria-controls="example">
							<option value="10" selected="selected">10</option>
							<option value="25">25</option>
							<option value="50">50</option>
							<option value="100">100</option>
						</select> 条记录
					</label>
					<a id="home_page1" href="javascript:void(0)">首页</a>&nbsp;&nbsp;
					<a id="pre_page1" href="javascript:void(0)">上一页</a>&nbsp;&nbsp;
					<a id="next_page1" href="javascript:void(0)">下一页</a>&nbsp;&nbsp;
					<a id="last_page1" href="javascript:void(0)">尾页</a>&nbsp;&nbsp;
					<span>第<span id="cur_page1">1</span>/<span id="total_page1">1</span>页&nbsp;&nbsp;</span>
					
				</div>
			</div>
			
			<div style="clear:both"></div>
		</div>
	</div>
	
	
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery-ui.js" type="text/javascript"></script> 
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/function.js" type="text/javascript"></script>	
	<script type="text/javascript">
	$("#startdate").datepicker();
	$("#enddate").datepicker();
	showTitle("货币数据分析:商城消费统计");
	var self = this;
	self.show_goods(1);
	self.show_sumgoods(1);

	$('#outexcel').click(function(){
		var ip = $("#sip").val();
		var startdate = $("#startdate").val();
		var enddate = $("#enddate").val();
		window.location = "<?php echo $this->_tpl_vars['logicApp']; ?>
/shopanalysis/writeExcel/ip/"+ip+'/type/'+$('#type').val()+"/startdate/"+startdate+"/enddate/"+enddate;
	});
	
	function color_table (table) {
		$("#"+table+" tr:odd").css("background-color", "#edf2f7"); 
		$("#"+table+" tr:odd").css("background-color","#e0f0f0"); 
	}

	function showdata1(data,total){
		$("#am_body").empty()
		var html = '';
		var pagesize = parseInt($('#menu').val());
		var page = (parseInt($('#cur_page').html())-1)*pagesize;
		for (var i = page; i < (page+pagesize); i++) {
			var data1 = data[i];
			if (data1 != undefined) {
				html += '<tr>';
				html += '<td>'+data1['t_code']+'</td>';
				html += '<td>'+data1['t_name']+'</td>';
				html += '<td colspan=\'2\'>'+data1['cid']+'</td>';
				html += '<td>'+data1['i_price']+'</td>';
				html += '<td colspan=\'2\'>'+data1['cnum']+'</td>';
				html += '<td colspan=\'2\'>'+(parseInt(data1['cnum'])/parseInt(total['cnum'])*100).toFixed(2)+' %</td>';
				html += '<td colspan=\'2\'>'+data1['c_price']+'</td>';
				html += '<td colspan=\'2\'>'+(parseInt(data1['c_price'])/parseInt(total['c_price'])*100).toFixed(2)+' %</td>';
				html += '</tr>';
			};
		};
		html += '<tr>';
		html += '<td>合计</td>';
		html += '<td colspan=\'1\'></td>';
		html += '<td colspan=\'3\'>'+total['cid']+'</td>';
		html += '<td colspan=\'4\'>'+total['cnum']+'</td>';
		html += '<td colspan=\'4\'>'+total['c_price']+'</td>';
		html += '</tr>';
		$("#am_body").append(html);
	}

	function showdata2(){
		var html = "";
		if(typeof(data.list) != "undefined" ){
			if(data.list.length > 0){
				$("#example_length2").show();//显示每页
				for(var i in data.list){
					var code = data.list[i]['s_code'];
					html += "<tr>";
					html += "<td>" + data.list[i]['s_date'] + "</td>";
					html += "<td width='10%' style='text-align:right;border-right:none;'>" + data.list[i]['s_peo'] +"</td>";
					// html += "<td width='10%' style='text-align:left;><span class='arrow'>" + color_arrow(code,0) +"</span></td>";
					html += "<td width='10%' style='text-align:right;border-right:none;'>" + data.list[i]['s_num'] +"</td>";
					// html += "<td width='10%' style='text-align:left;'><span class='arrow'>" + color_arrow(code,1) +"</span></td>";
					html += "<td width='10%' style='text-align:right;border-right:none;'>" + data.list[i]['s_total'] +"</td>";
					// html += "<td width='10%' style='text-align:left;'><span class='arrow'>" + color_arrow(code,2) +"</span></td>";
					if(parseInt(data.sumList["sum"]) != 0 && parseInt(data.list[i]['s_total']) != 0) {
						var val = parseInt(data.list[i]['s_total']) / parseInt(data.sumList["sum"]) * 100;
						html += "<td>" + float_n(val.toString(),2) + '%' + "</td>";
					}else {
						html += "<td>" + '0' + "</td>";
					}
					html += "</tr>";
				}
				html += "<tr>";
				html += "<td>" + "合计" + "</td>";
				html += "<td style='text-align:right;border-right:none;'>" + data.sumList["sum_peo"] + "</td>";
				html += "<td>&nbsp;</td>";
				html += "<td style='text-align:right;border-right:none;'>" + data.sumList["sum_price"] + "</td>";
				html += "<td>&nbsp;</td>";
				html += "<td style='text-align:right;border-right:none;'>" + data.sumList["sum"] + "</td>";
				html += "<td>&nbsp;</td>";
				html += "<td>" + '' + "</td>";
				html += "</tr>";
				
				$("#ad_body").html(html);
			}
		}
	}
	
$(".mytable").show();
	function show_goods(){
		
		$.post("<?php echo $this->_tpl_vars['logicApp']; ?>
/shopanalysis/getGoods",{type : $("#type").val(),startdate : $("#startdate").val(),enddate : $("#enddate").val(),ip : $("#sip").val(),pageSize : $('#menu').val(),type : $("#type").val()},
			function(datar){
				var data = JSON.parse(datar);
					$("#startdate").val(data['startDate']);
					var list = data['list'];
					if((list).length > 0){
						$("#example_length").show();	//显示每页
						var total = data['total_list'][0];
						var totalnum = Math.ceil(data['list'].length/$('#menu').val());
						showdata1(data['list'],total);
						$('#example_length').css('display','block');
						$('#total_page').html(totalnum);


						$('#next_page').click(function() {
                            var curPage = parseInt($('#cur_page').html())+1;
                            if (curPage <= totalnum) {
                                $('#cur_page').html(curPage);
                               showdata1(data['list'],total);
                            }else{
                                alert('已经是最后一页了！');
                            }
                        });

                        $('#pre_page').click(function() {
                            var curPage = parseInt($('#cur_page').html())-1;
                            if (curPage >= 1) {
                                $('#cur_page').html(curPage);
                               showdata1(data['list'],total);
                            }else{
                                alert('已经是第一页了！');
                            }
                        });

                        $('#home_page').click(function() {
                            $('#cur_page').html(1);
                            showdata1(data['list'],total);
                        });

                        $('#last_page').click(function() {
                            $('#cur_page').html(totalnum);
                            showdata1(data['list'],total);
                        });

                        $('#go').click(function() {
                            if ($('#num').val() < 1) {
                                $('#cur_page').html(1);
                            }else if($('#num').val() > totalnum){
                                $('#cur_page').html(totalnum);
                            }else{
                            	$('#cur_page').html($('#num').val());
                            }
                           showdata1(data['list'],total);
                        });

					}else{
						$("#am_body").html("<tr><td colspan='13'>没有数据！</td></tr>");
						$("#pagehtml").html("");
						$("#example_length").hide();
					}
				
			})
	}

	function show_sumgoods(){
		
		$.post("<?php echo $this->_tpl_vars['logicApp']; ?>
/shopanalysis/getSumGoods",{type : $("#type").val(),startdate : $("#startdate").val(),enddate : $("#enddate").val(),ip : $("#sip").val(),pageSize : $('#menu2').val(),type : $("#type").val()},
			function(data1){

				var data = JSON.parse(data1);
				var html = "";
				var list = data['list'];
				for (var i = 0; i < list.length; i++) {
					html += '<tr>';
					html += '<td>'+list[i]['time']+'</td>';
					html += "<td width='10%' style='text-align:right;border-right:none;'>"+list[i]['cid']+'</td>';
					html += "<td width='10%' style='text-align:left;><span class='arrow'><image src='<?php echo $this->_tpl_vars['res']; ?>
/images/"+list[i]['cid_s']+".png' /></span></td>";

					html += "<td width='10%' style='text-align:right;border-right:none;'>"+list[i]['cnum']+'</td>';
					html += "<td width='10%' style='text-align:left;><span class='arrow'><image src='<?php echo $this->_tpl_vars['res']; ?>
/images/"+list[i]['cnum_s']+".png' /></span></td>";

					html += "<td width='10%' style='text-align:right;border-right:none;'>"+list[i]['cprice']+'</td>';
					html += "<td width='10%' style='text-align:left;><span class='arrow'><image src='<?php echo $this->_tpl_vars['res']; ?>
/images/"+list[i]['cprice_s']+".png' /></span></td>";

					html += '<td>'+(parseInt(list[i]['cprice'])/parseInt(data['sumList'][0]["cprice"])*100).toFixed(2)+' %</td>';

					html += '</tr>';
				};

				html += "<tr>";
				html += "<td>" + "合计" + "</td>";
				html += "<td style='text-align:right;border-right:none;'>" + data['sumList'][0]["cid"] + "</td>";
				html += "<td>&nbsp;</td>";
				html += "<td style='text-align:right;border-right:none;'>" + data['sumList'][0]["cnum"] + "</td>";
				html += "<td>&nbsp;</td>";
				html += "<td style='text-align:right;border-right:none;'>" + data['sumList'][0]["cprice"] + "</td>";
				html += "<td>&nbsp;</td>";
				html += "<td>" + '' + "</td>";
				html += "</tr>";

			$("#ad_body").html(html);
			});
	}
	
	$('#querybtn').click(function(){
		show_sumgoods();
	});
$('#querybtn').click(function(){
		show_goods();
	});


	</script>
</body>
</html> 

	</script>
</body>
</html>
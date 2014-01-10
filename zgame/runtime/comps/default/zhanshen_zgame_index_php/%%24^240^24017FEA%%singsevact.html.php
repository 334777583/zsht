<?php /* Smarty version 2.6.18, created on 2013-12-29 16:06:40
         compiled from stickiness/singsevact.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>单服活跃分析</title>
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
	<script type="text/javascript">
$(function(){
	//时间插件
	$("#startdate").datepicker();
	$("#enddate").datepicker();
	showTitle("用户数据分析:单服活跃分析");
	function showResult(data){
		$('#dtatr_body').empty();
			var htmlstr = '';
			var num;
			var a = ($('#cur_page').html())-1 ;
			if(a == NaN) {
				num=0
			}else{
				num = a*10;
			}
			
			for (var i = 0; i < $('#pagesize').val(); i++) {
				var data3 = data[i];
				if(data3 != undefined){
					if (data[i]['statis'] == 1) {
						htmlstr += '<tr">';
						htmlstr += '<td style="font-size:12px">'+(i+1+num)+'</td>';
						htmlstr += '<td style="font-size:12px">'+data3['AccountId']+'</td>';
						htmlstr += '<td style="font-size:12px">'+data3['RoleName']+'</td>';
						htmlstr += '<td style="font-size:12px">'+data3['level']+'</td>';
						htmlstr += '<td style="font-size:12px">'+data3['CreateTime']+'</td>';
						htmlstr += '<td style="font-size:12px">'+data3['LoginTime']+'</td>';
						htmlstr += '</tr>';
					}else{
						htmlstr += '<tr">';
						htmlstr += '<td style="font-size:12px; background:yellow;">'+(i+1+num)+'</td>';
						htmlstr += '<td style="font-size:12px; background:yellow;">'+data3['AccountId']+'</td>';
						htmlstr += '<td style="font-size:12px; background:yellow;">'+data3['RoleName']+'</td>';
						htmlstr += '<td style="font-size:12px; background:yellow;">'+data3['level']+'</td>';
						htmlstr += '<td style="font-size:12px; background:yellow;">'+data3['CreateTime']+'</td>';
						htmlstr += '<td style="font-size:12px; background:yellow;">'+data3['LoginTime']+'</td>';
						htmlstr += '</tr>';
					}
				}
			};
			$('#dtatr_body').append(htmlstr);
			$("#pagehtml").show();
	}

//首页
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

function showData(){
	var sortval = $('#sort').val();
		$.post('<?php echo $this->_tpl_vars['logicApp']; ?>
/singsevact/SingSevAct',{sort:sortval,startdate : $("#startdate").val(),enddate : $("#enddate").val(),ip : $("#sip").val()},function(data){
			var data2 = JSON.parse(data);
			
			if (data2['result'].length > 0) {
				
			
				showResult(data2['result']);
				first(data2['result']);
				pre(data2['result']);
				next(data2['result']);
				end(data2['result']);
				gotonum(data2['result']);
				$('#pagesize').change(showData);
				$('#total_page').html(Math.ceil(data2['result'].length/$('#pagesize').val()));
			}else{
				$("#pagehtml").hide();
				$("#dtatr_body").html("<tr><td colspan='13'>没有数据！</td></tr>");
			}
		});
}
var sortval = $('#sort').val();
showData();
$('#querybtn').click(showData);

$('#fast').toggle(
	function(){
		var sortvall = 'level';
		var sortval = $('#sort').val();
		$.post('<?php echo $this->_tpl_vars['logicApp']; ?>
/singsevact/SingSevAct',{sort:sortvall,startdate : $("#startdate").val(),enddate : $("#enddate").val(),ip : $("#sip").val()},function(data){
			var data2 = JSON.parse(data);
			
			if (data2['result'].length > 0) {
				
			
				showResult(data2['result']);
				first(data2['result']);
				pre(data2['result']);
				next(data2['result']);
				end(data2['result']);
				gotonum(data2['result']);
				$('#pagesize').change(showData);
				$('#total_page').html(Math.ceil(data2['result'].length/$('#pagesize').val()));
			}else{
				$("#pagehtml").hide();
				$("#dtatr_body").html("<tr><td colspan='13'>没有数据！</td></tr>");
			}
		});
	},
	function(){
		var sortvall = 'LoginTime';
		var sortval = $('#sort').val();
		$.post('<?php echo $this->_tpl_vars['logicApp']; ?>
/singsevact/SingSevAct',{sort:sortvall,startdate : $("#startdate").val(),enddate : $("#enddate").val(),ip : $("#sip").val()},function(data){
			var data2 = JSON.parse(data);
			
			if (data2['result'].length > 0) {
				
			
				showResult(data2['result']);
				first(data2['result']);
				pre(data2['result']);
				next(data2['result']);
				end(data2['result']);
				gotonum(data2['result']);
				$('#pagesize').change(showData);
				$('#total_page').html(Math.ceil(data2['result'].length/$('#pagesize').val()));
			}else{
				$("#pagehtml").hide();
				$("#dtatr_body").html("<tr><td colspan='13'>没有数据！</td></tr>");
			}
		});
	}
)

$("#exportbtn").click(function(){
		var ip = $("#sip").val();
		var startdate = $("#startdate").val();
		var enddate = $("#enddate").val();
		window.location = "<?php echo $this->_tpl_vars['logicApp']; ?>
/singsevact/writeExcel/ip/"+ip+"/startdate/"+startdate+"/enddate/"+enddate;
	});
})
	</script>
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
			
			
			<div>
				<table class="explain">
					<thead>
					</thead>
					<tbody style="font-family:Mingliu">
						<tr>
							<td width="5%"  class="tableleft"><b>单服活跃说明：</b></td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">1.　当未选择时间时，默认时间为开服当天顺延一周的时间。</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">2.　等级限制：40级或以上玩家。</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">3.　等级按照降幂排列。</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">4.　3天或者3天以上未登陆玩家，出现底色橙色预警。</td>
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
					<span>排序:</span>
					<select id="sort">
						<option selected="selected" value="LoginTime">登录时间</option>
							<option value="level">等级</option>
					</select>
					<span>游戏平台:</span>
					<select id="yxpt">
							<option value="49you">49you</option>
					</select>
					 <input type="text" value="<?php echo $this->_tpl_vars['startDate']; ?>
" name="startdate" id="startdate" class="input1"/>至<input type="text" name="enddate" value="<?php echo $this->_tpl_vars['endDate']; ?>
" id="enddate" class="input1"/>
					<input type="button" value="查询" id="querybtn"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<div>
				<table  class="mytable" cellspacing="0" align="center" id="dtable">
					<thead>
						<tr class="jt_sort">
							<th><input type="button" id="fast" value="快捷排序" style="margin-right:10px;" />排行</th>
							<th>账号</th>
							<th>角色</th>
							<th>等级</th>
							<th>注册时间</th>
							<th>最后登录</th>
						</tr>
					</thead>
					<tbody id="dtatr_body">
					</tbody>
					<tbody id="countnum">
					</tbody>
				</table>
			</div>
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
					&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="导出Excel" id="exportbtn"/>
				</div>
			</div>
			<div style="display:none">
				<input type='hidden' id ="sort" value='1'>
			</div>
			<div style="clear:both"></div>
		</div>
	</div>
	
	

</body>
</html>
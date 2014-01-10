<?php /* Smarty version 2.6.18, created on 2014-01-02 10:00:05
         compiled from stickiness/shishi.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>实时查询</title>
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
							<td width="95%" class="tableleft">1、付费比=登陆总数/付费人数（账号去重）</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">2、ARPU=付费总额/付费人数</td>
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
" attr="<?php echo $this->_tpl_vars['ip']['g_file']; ?>
" bz = "<?php echo $this->_tpl_vars['ip']['g_yxbz']; ?>
"><?php echo $this->_tpl_vars['ip']['s_name']; ?>
</option>
					<?php endforeach; endif; unset($_from); ?>
					</select>
					&nbsp;<input type="button" value="即时查询" id="queryjs"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<div>
				<table  class="mytable" cellspacing="0" align="center" id="dtable">
					<thead>
						<tr>
							<th>类型</th>
							<th>登陆总数</th>
							<th>当前在线</th>
							<th>付费人数（账号去重）</th>
							<th>付费人数</th>
							<th>创角人数（账号去重）</th>
							<th>付费总额</th>
							<th>付费比</th>
							<th>ARPU</th>
						</tr>
					</thead>
					<tbody id="dtatr_body">
					</tbody>
				</table>
			</div>
			
			<!--<div style="float:right;margin-right:20px;display:none" id="pagehtml">
				<div class="pages">
					<a id="home_page" href="javascript:void(0)">首页</a>&nbsp;&nbsp;
					<a id="pre_page" href="javascript:void(0)">上一页</a>&nbsp;&nbsp;
					<a id="next_page" href="javascript:void(0)">下一页</a>&nbsp;&nbsp;
					<a id="last_page" href="javascript:void(0)">尾页</a>&nbsp;&nbsp;
					<span>第<span id="cur_page">1</span>/<span id="total_page">1</span>页&nbsp;&nbsp;</span>
					转到<input type="text" class="text" size="3"  id="page" value="1"/>
					<a id="go" class="go" href="javascript:void(0);"></a>页
				</div>
			</div>-->
		
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
			$("#startdate").datepicker();
				$("#enddate").datepicker();

	$('#queryjs').click(function(){
		$.post("<?php echo $this->_tpl_vars['logicApp']; ?>
/userrole/getshishi",{sip:$('#sip').val()},
		function(data){
			
			data = JSON.parse(data);
			$('#dtatr_body').empty();
			var str = '<tr><td>实时</td><td>'+data[0].login+'</td><td>'+data[0].online+'</td><td>'+data[0].cnum+'</td><td>'+data[0].num+'</td><td>'+data[0].jiaose+'</td><td>'+data[0].summoney+'</td><td>'+(parseInt(data[0].cnum)/parseInt(data[0].login)).toFixed(2)+'</td><td>'+(parseInt(data[0].summoney)/parseInt(data[0].num)).toFixed(2)+'</td></tr>'
			$('#dtatr_body').append(str);
		});
	});

showTitle("用户数据分析:实时查询");


		/*var user_pay = {
			object : <?php echo $this->_tpl_vars['ipDetail']; ?>

			INIT : function(){
				var self = this;
				
				//时间插件
				
				page.listen();
				
				
				
				$("#querybtn").click(function(){
					if(validator("startdate", "enddate")){
						self.showRole();
					}
				});
				$("#queryjs").click(function(){
					self.jishi();
				});
			},
			
			jishi : function(){
			
				var si = $("#sip").val();
				var self = this;
				var biaoshi = '';
				var ip = '';
				var domain = $("#sip option:selected").attr('attr');
				var yxbz = $("#sip option:selected").attr('bz');
				var r = Math.floor(Math.random()*10+1);
				for(var i in self.object){
					if(self.object[i].s_id == si){
						biaoshi = self.object[i].s_biaoshi;
					}
				}
				if(biaoshi && yxbz){
					$.ajax({
						types : 'get',
						url : '<?php echo $this->_tpl_vars['curl']; ?>
?ip='+domain+'&biaoshi='+biaoshi+'&pingtai='+yxbz+'&r='+r,
						dataType : 'json',
						jsonp: 'jsoncallback',
						timeout:5000,
						async:	true,
						complete : function (data) {
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
					dataType : 'jsonp',
					jsonp : 'callback',
					cache : false,
					complete : function (data){
						//console.log(data);
						//alert(data);
						//$("dtatr_body").html(
						//
						//);
					}
				});
			}
		}
		var callback = function(data){
			if(data.summoney){
				var arpu = (parseInt(data.summoney)/parseInt(data.num)).toFixed(2);
			}else{
				var arpu = 0;
			}
			if(data.cnum){
				var per = (parseInt(data.cnum)/parseInt(data.login)).toFixed(2) * 100;
			}else{
				var per = 0;
			}
			$("#dtatr_body").html(
				'<tr><td>实时</td><td>'+data.login+'</td><td>'+data.online+'</td><td>'+data.cnum+'</td><td>'+data.num+'</td><td>'+data.jiaose+'</td><td>'+data.summoney+'</td><td>'+per+'%</td><td>'+arpu+'</td></tr>'
			);
		}*/
		
	</script>
</body>
</html>
<?php /* Smarty version 2.6.18, created on 2013-12-29 16:06:37
         compiled from stickiness/singsevrak.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>单服排行</title>
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
			
			
			<div>
				<table class="explain">
					<thead>
					</thead>
					<tbody style="font-family:Mingliu">
						<tr>
							<td width="5%"  class="tableleft"><b>单服排行说明：</b></td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">1.先获取最新数据,再查询.</td>
						</tr>
						<tr>
							<td width="95%" class="tableleft">2.该功能可以对一个区的全部充值金额进行排行(根据角色).</td>
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
					<input type="button" value="查询" id="querybtn"/>
					<input type="button" value="即时更新" id="updatebtn"/>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<div>
				<table  class="mytable" cellspacing="0" align="center" id="dtable">
					<thead>
						<tr class="jt_sort">
							<th>排行</th>
							<th>账号</th>
							<th>角色</th>
							<th id='level'>等级</th>
							<th id='gold'>剩余元宝</th>
							<th id='money'>充值金额</th>
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
			<div style="float:right;margin-right:20px;display:none;" id="pagehtml">
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
			<div style="display:none">
				<input type='hidden' id ="sort" value='0'>
				<input type='hidden' id ="sort_key" value='s_money'>
				<input type='hidden' id ="key_reset" value='1'>
			</div>
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
		var SingLv = {
			INIT : function(){
				var self = this;
				
				showTitle("用户数据分析:单服排序");
				
				//切换标签
				$("#user-tabs span").click(function(){
					window.location = "<?php echo $this->_tpl_vars['app']; ?>
/singsevrak/show/pageId/"+this.id;
				})
				//提交查询 (默认充值金额倒序)
				$("#querybtn").click(function(){
					self.showSingLV(1,'RMB',1);
					//按充值金额排序
					var sortkey = 'FindKeySort("RMB")';
					$("#money").html("<a id='RMB' style='color:black' href='javascript:void(0)' onclick='"+sortkey+"'>充值金额</a>");
					//按等级排序
					var sortkey = 'FindKeySort("level")';
					$("#level").html("<a id='level' style='color:black' href='javascript:void(0)' onclick='"+sortkey+"'>等级</a>");
					//按元宝排序
					var sortkey = 'FindKeySort("Gold")';
					$("#gold").html("<a id='Gold' style='color:black' href='javascript:void(0)' onclick='"+sortkey+"'>剩余元宝</a>");
				})
				
				//更新单服排行数据
				$("#updatebtn").click(function(){
					self.UpSingRank();
				})
			},
			
			//单服排序
			/**
			 * Name : showSingLV
			 * page   页数
			 * sort_key   需要排序的字段名
			 * val	判断是升序还是倒序 0、升序；1、倒序
			**/
			showSingLV : function(page,sort_key,val) {
				var self = this;
				$.ajax({
					'type' : 'POST',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/singsevrak/SingSevRak',
					dataType : 'json',
					data : {
						startdate : $("#startdate").val(),
						ip : $("#sip").val(),
						sort_key : sort_key,
						sort : val,
						curPage : page
					},
					beforeSend : function() {
						$("#dtatr_body").html("<tr><td colspan='13'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
					},
					success : function (data) {
						if(typeof(data.result) != 'undefined' && data.result != "") {
							var result = data.result;
							//排序处理
							if(val == 0){//升序
								self.JianTou_Img(1,sort_key,"up.png",data.key_reset);
							}
							if(val == 1){//倒序
								self.JianTou_Img(0,sort_key,"down.png",data.key_reset);
							}
							var tbody = "";
							for(var i in result) {
								tbody += "<tr>";
								tbody += "<td>" + result[i]["id"] + "</td>";
								tbody += "<td>" + result[i]["account_code"] + "</td>";
								tbody += "<td>" + result[i]["name"] + "</td>";
								tbody += "<td>" + result[i]["level"] + "</td>";
								tbody += "<td>" + result[i]["gold"] + "</td>";
								tbody += "<td>" + result[i]["money"] + "</td>";
								tbody += "<td>" + result[i]["create_time"] + "</td>";
								tbody += "<td>" + result[i]["last_down_time"] + "</td>";
								tbody += "</tr>";
							}
							$("#pagehtml").show();
							$("#dtatr_body").html(tbody);
							$("#pagehtml").html(data.pageHtml);		//分页
							
							return true;
						}
						$("#pagehtml").hide();
						$("#dtatr_body").html("<tr><td colspan='13'>没有数据！</td></tr>");
						
					},
					error : function () {
						$("#pagehtml").hide();
						$("#dtatr_body").html("<tr><td colspan='13'>没有数据！</td></tr>");
					}
				})
			},
			//更新单服排行数据
			UpSingRank : function() {
				$.ajax({
					'type' : 'POST',
					url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/singsevrak/RankData',
					dataType : 'json',
					data : {
						ip : $("#sip").val()
					},
					beforeSend : function() {
						$("#dtatr_body").html("<tr><td colspan='13'><img src='<?php echo $this->_tpl_vars['res']; ?>
/images/loading.gif'/></td></tr>");
					},
					success : function (data) {
						if(typeof(data.success) != 'undefined' && data.success != "") {
							if(data.success == 1){
								alert('更新成功');
							}else{
								alert('更新失败');
								$("#dtatr_body").html("<tr><td colspan='13'>更新失败</td></tr>");
							}
							$("#dtatr_body").html("<tr><td colspan='13'>更新完成！</td></tr>");
						}else{
							alert('更新失败');
							$("#dtatr_body").html("<tr><td colspan='13'>更新失败</td></tr>");
						}
					}
				})
			},
			//清空所有箭头图标
			/**
			 * Name : JianTou_Img
			 * val   判断是升序还是倒序 0、升序；1、倒序
			 * sort_key   需要排序的字段名
			 * img	图片名称
			 * key_reset //判断是否别的字段排序 0、否；1、是
			**/
			JianTou_Img : function(val,sort_key,img,key_reset){
				$len = $(".mytable img").length;
				for(var i = 0; i < $len; i++){
					$(".jt_sort img").eq(i).remove();
				}
				var img_url = "<?php echo $this->_tpl_vars['res']; ?>
/images/"+img;
				$("<img src='"+img_url+"'>").insertAfter("#"+sort_key);
				if(key_reset){//判断是否别的字段排序
					$("#sort").val(val);//排序完成后 更改排序方式
				}
			}
		}
		//整理排序
		var FindKeySort = function (key){
			var sort = $("#sort").attr("value");
			$("#key_reset").attr("value",sort);//将之前排序方式更改
			$("#sort_key").attr("value",key);//排序的字段
			SingLv.showSingLV('',key,sort);
		}
		
		
		$(document).ready(function(){
			SingLv.INIT();
		})
		
		//跳到相应页面 
		var go = function(){
			var pagenum = $("#page").val();
			if(pagenum=='' || isNaN(pagenum) || pagenum <= 0){
				alert('请输入一个正整数！');
				$("#page").val(1);
			}else{
				var sort_key = $("#sort_key").attr("value");
				var sort = $("#sort").attr("value");
				SingLv.showSingLV(pagenum,sort_key,sort);
			}
		}
		
		//分页ajax函数
		var formAjax = function(page){
			var sort_key = $("#sort_key").attr("value");
			var sort = $("#key_reset").attr("value");
			SingLv.showSingLV(page,sort_key,sort);
		}
	</script>
</body>
</html>
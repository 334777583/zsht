<?php /* Smarty version 2.6.18, created on 2013-12-28 18:32:58
         compiled from common/homepage.html */ ?>
<!DOCTYPE html>
<html>
<head>
	<title>二部运营后台</title>
	<meta http-equiv=Content-Type content="text/html;charset=utf-8">
	<link type="image/x-icon" rel="shortcut icon" href="<?php echo $this->_tpl_vars['root']; ?>
/favicon.ico" />
	<link href="<?php echo $this->_tpl_vars['res']; ?>
/css/skin.css" rel="stylesheet" type="text/css">
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery.js" type="text/javascript"></script>
	<style>
	<!--
		body {
			font:12px Arial, Helvetica, sans-serif;
			color: #000;
			background-color: #EEF2FB;
			margin: 0px;
		}
		#container {
			width: 182px;
		}
		H1 {
			font-size: 12px;
			margin: 0px;
			width: 182px;
			height: 30px;
			line-height: 20px;		
		}
		H1 a {
			display: block;
			width: 182px;
			color: #000;
			height: 30px;
			text-decoration: none;
			moz-outline-style: none;
			background-image: url(<?php echo $this->_tpl_vars['res']; ?>
/images/menu_bgs.gif);
			background-repeat: no-repeat;
			line-height: 30px;
			text-align: center;
			margin: 0px;
			padding: 0px;
		}
	-->	
	</style>
</head>
<body>
	<div>
		<div class="header">
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		</div>
		<table class="contentBox">
			<tr>
				<td class="sidebar" valign="top">
					<div>
						<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/sidebar.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
					</div>
				</td>
				<td  class="center" valign="top">
					<div>
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="17" valign="top" background="<?php echo $this->_tpl_vars['res']; ?>
/images/mail_leftbg.gif">
									<img src="<?php echo $this->_tpl_vars['res']; ?>
/images/left-top-right.gif" width="17" height="29" />
								</td>
								<td valign="top" background="<?php echo $this->_tpl_vars['res']; ?>
/images/content-bg.gif" id="tabs">
									<h1 id="tab_info"></h1>
								<!--<div class="titlebt titlebt_active" id="tab_login">
										<span class="caption">登录概况</span><img id="login" class="closeicon" src="<?php echo $this->_tpl_vars['res']; ?>
/images/close.gif"/>
									</div> -->
								</td>
								<td width="16" valign="top" background="<?php echo $this->_tpl_vars['res']; ?>
/images/mail_rightbg.gif">
									<img src="<?php echo $this->_tpl_vars['res']; ?>
/images/nav-right-bg.gif" width="16" height="29" />
								</td>
							</tr>
							<tr>
								<td valign="middle" background="<?php echo $this->_tpl_vars['res']; ?>
/images/mail_leftbg.gif">&nbsp;</td>
								<td valign="top" bgcolor="#F7F8F9" id="mainframe">
									<!-- <div id="iframe_login"><iframe class="iframe" name="iframe" src="<?php echo $this->_tpl_vars['app']; ?>
/userlogin/show" width="100%"  marginwidth="0" marginheight="0" frameborder="0"  scrolling="no" ></iframe></div> -->
								</td>
								<td background="<?php echo $this->_tpl_vars['res']; ?>
/images/mail_rightbg.gif">&nbsp;</td>
							</tr>
							<tr>
								<td valign="bottom" background="<?php echo $this->_tpl_vars['res']; ?>
/images/mail_leftbg.gif"><img src="<?php echo $this->_tpl_vars['res']; ?>
/images/buttom_left2.gif" width="17" height="17" /></td>
								<td background="<?php echo $this->_tpl_vars['res']; ?>
/images/buttom_bgs.gif"><img src="<?php echo $this->_tpl_vars['res']; ?>
/images/buttom_bgs.gif" width="17" height="17"></td>
								<td valign="bottom" background="<?php echo $this->_tpl_vars['res']; ?>
/images/mail_rightbg.gif"><img src="<?php echo $this->_tpl_vars['res']; ?>
/images/buttom_right2.gif" width="16" height="17" /></td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</table>
	</div>
	
</body>


<script type="text/javascript"> 

var a_h = 130;

var createTag = function(){
	var w_h = $(window).height();
	var lidom = $(".sidebar").find("li").first();
	var id = lidom.attr("id");
	
	var iframeId = "iframe_"+id;
	var tabId = "tab_"+id;
	var title = lidom.attr("ref") || lidom.text();
	var url =  lidom.find("a").attr("href");

	
	var height = parseInt(w_h) - a_h;
	
	//var tabHtml = "<div id=\""+tabId+"\" class=\"titlebt titlebt_active\"><span class=\"caption\">"+title+"</span><img id=\""+id+"\" class=\"closeicon\" src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/close.png\"/></div>"
	var iframeHtml = "<div id=\""+iframeId+"\"><iframe class=\"iframe\" name=\"iframe\" src=\""+url+"\" height='"+height+"' width=\"100%\" marginwidth=\"0\" marginheight=\"0\" frameborder=\"0\"  scrolling=\"auto\"></iframe></div>"
	
	//$("#tabs").append(tabHtml);
	$("#mainframe").append(iframeHtml);
}
window.onresize = function(){
	var w_h = $(window).height();
	$(".iframe").attr('height',parseInt(w_h-a_h));
}
$(function() {
	//初始化当前默认第一个标签内容
	createTag();
	
	/*
	//关闭标签 
	$(".closeicon").live("click", function() {
		var activeid = $(".titlebt_active").find("img").attr("id");
		var id = this.id;
		var iframeId = "#iframe_"+id;
		if(activeid == id){
			if($(iframeId).nextAll().length > 0){
				$(this).parent().next().addClass("titlebt_active");
				$(iframeId).next().show();
			}else{
				$(this).parent().prev().addClass("titlebt_active");
				$(iframeId).prev().show();
			}
		}
		$(this).parent().remove();
		$(iframeId).remove();
	})
	
	//切换标签
	$(".caption").live("click",function(){
		var id = $(this).next().attr("id");
		var iframeId = "#iframe_"+id;
		$("#mainframe").children().each(function(index, dom){
			if($(dom).css("display") == "block" || $(dom).css("display") == "inline"){
				$(dom).css("display","none");
			}
		});
	 	$("#tabs").children().each(function(index, dom){
			$(dom).removeClass("titlebt_active");
		}); 
		$(this).parent().addClass("titlebt_active");
		$(iframeId).show();
	})
	
	
	//改变标签样式(深灰)
	 $(".titlebt").live("mouseenter",function(){
		 $(this).toggleClass('titlebt_hover')
	}).live("mouseleave",function(){
		 $(this).toggleClass('titlebt_hover')
	})
	*/ 	
	
	//以下是sidebar的js 
	$(".content").not($(".content:first")).hide(); 
	$(".type").click(function(){	//手风琴菜单的jquery实现
		if($(this).next().css("display") == "none"){
			//$(".content").slideUp("100");
			$(this).next().slideDown("1000");
		}else{
			$(this).next().slideUp("1000");
		}
	});
	
	//菜单选中时显示小图标 
	$(".subMenu li").hover(
		function(){
			var html =  $(this).find("a").html();
			var icon = "<img class='icon' src='<?php echo $this->_tpl_vars['res']; ?>
/images/menu_bg2.png'/>";
			var newHtml = icon+html;
			$(this).find("a").html(newHtml);	
		},
	 	function () {
			$(this).find("img").remove();
		}
	);
	
	//创建标签和iframe
	$(".subMenu li").click(function(){
		var url =  $(this).find("a").attr("href");
		$(".iframe").attr("src", url);
	
		/*var flag = true;
		var id = this.id;
		var iframeId = "iframe_"+id;
		var tabId = "tab_"+id;
		var title = $(this).attr("ref") || $(this).text();
		var url =  $(this).find("a").attr("href");
		
		var height = parseInt(w_h) - a_h;
		
		var tabHtml = "<div id=\""+tabId+"\" class=\"titlebt titlebt_active\"><span class=\"caption\">"+title+"</span><img id=\""+id+"\" class=\"closeicon\" src=\"<?php echo $this->_tpl_vars['res']; ?>
/images/close.png\"/></div>"
		var iframeHtml = "<div id=\""+iframeId+"\"><iframe class=\"iframe\" name=\"iframe\" src=\""+url+"\" height='"+height+"' width=\"100%\" marginwidth=\"0\" marginheight=\"0\" frameborder=\"0\"  scrolling=\"auto\"></iframe></div>"
		
		$("#mainframe").children().each(function(index, dome){				
			if($(dome).css("display") == "block" || $(dome).css("display") == "inline"){ //查找当前显示iframe，设为不可见
				$(dome).css("display","none");
			}
			if($(dome).attr("id") == iframeId){	//如果当前iframe存在，则显示选择iframe，不再新创建 
				flag = false;
				$("#tabs").children().each(function(index, domt){ //先去高亮
					$(domt).removeClass("titlebt_active");
				});
				$("#"+tabId).addClass("titlebt_active"); //当前选中项高亮
				$(dome).css("display","block");//显示当前iframe
			}
		});
		if(flag){
			$("#tabs").children().each(function(index, dom){ //先去高亮
				$(dom).removeClass("titlebt_active");
			});
			$("#tabs").append(tabHtml);
			$("#mainframe").append(iframeHtml);
		}*/
		return false;
	})
	

});

</script>

</html>
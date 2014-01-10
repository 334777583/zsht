<?php /* Smarty version 2.6.18, created on 2014-01-02 18:31:14
         compiled from gmtools/code_create.html */ ?>
<!DOCTYPE html>
<html>
<head>
<title>激活码生成</title>
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
.toptable label {
	margin-left : 20px;
} 
-->
</style>
</head>
<body>
	<div>
		<div id="tabs-1" class="tabitem">
			<div id="user-tabs"  style="margin-top:20px;">
				<span id="1">生成</span>
				<span id="2" class="user-gray">查询</span>
				<hr/>
			</div>

			<div class="tabitem">
				<div>
					<table class="explain">
						<thead>
						</thead>
						<tbody style="font-family:Mingliu">
							<tr>
								<td width="5%"  class="tableleft"><b>说明：</b></td>
							</tr>
							<tr>
								<td width="95%" class="tableleft">1、激活码类型分为5种，其中一种使用过则不可以再使用，不影响其他类型使用;</td>
							</tr>
							<tr>
								<td width="95%" class="tableleft">2、不限制的类型为使用过任何激活码后都可以<font color = "red"><b>重复</b></font>使用;谨慎生成<font color = "red"><b>不限制类型</b></font>激活码！</td>
							</tr>
							<tr>
								<td width="95%" class="tableleft">3、<u><b>例如</b></u>：使用了<font color = "red"><b>A类型</b></font>则不可以再使用<font color = "red"><b>A类型</b></font>，可使用其他没有使用过的类型;</td>
							</tr>
						</tbody>
					</table>
				</div>	
				<div>
					<table class="toptable">
						<thead>
						</thead>
						<tbody>
							<tr>
								<td width="5%" class="tableright">
									<span>选择服务器：</span>
								</td>
								<td width="95%" class="tableleft">
									<select id="sip">
									<?php $_from = $this->_tpl_vars['ipList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ip']):
?>
										<option value="<?php echo $this->_tpl_vars['ip']['s_id']; ?>
"><?php echo $this->_tpl_vars['ip']['s_name']; ?>
</option>
									<?php endforeach; endif; unset($_from); ?>
									</select>
								</td>
							</tr>
							<tr>
								<td width="5%" class="tableright">
									<span>激活码类型：</span>
								</td>
								<td width="95%" class="tablerleft">
									<select id="codeType">
										<option value="6">不限类型</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="7">6</option>
										<option value="8">7</option>
										<option value="9">8</option>
										<option value="10">9</option>
										<option value="11">10</option>
										<option value="12">11</option>
										<option value="13">12</option>
										<option value="14">13</option>
										<option value="15">14</option>
										<option value="16">15</option>
										<option value="17">16</option>
										<option value="18">17</option>
										<option value="19">18</option>
										<option value="20">19</option>
										<option value="21">20</option>
										<option value="22">21</option>
										<option value="23">22</option>
										<option value="24">23</option>
										<option value="25">24</option>
										<option value="26">25</option>
										<option value="27">26</option>
										<option value="28">27</option>
										<option value="29">28</option>
										<option value="30">29</option>
										<option value="31">30</option>
										<option value="32">31</option>
										<option value="33">32</option>
										<option value="34">33</option>
										<option value="35">34</option>
										<option value="36">35</option>
										<option value="37">36</option>
										<option value="38">37</option>
										<option value="39">38</option>
										<option value="40">39</option>
										<option value="41">40</option>
										<option value="42">41</option>
										<option value="43">42</option>
										<option value="44">43</option>
										<option value="45">44</option>
										<option value="46">45</option>
										<option value="47">46</option>
										<option value="48">47</option>
										<option value="49">48</option>
										<option value="50">49</option>
										<option value="51">50</option>
										<option value="52">51</option>
										<option value="53">52</option>
										<option value="54">53</option>
										<option value="55">54</option>
										<option value="56">55</option>
										<option value="57">56</option>
										<option value="58">57</option>
										<option value="59">58</option>
										<option value="60">59</option>
										<option value="61">60</option>
										<option value="62">61</option>
										<option value="63">62</option>
										<option value="64">63</option>
										<option value="65">64</option>
										<option value="66">65</option>
										<option value="67">66</option>
										<option value="68">67</option>
										<option value="69">68</option>
										<option value="70">69</option>
										<option value="71">70</option>
										<option value="72">71</option>
										<option value="73">72</option>
										<option value="74">73</option>
										<option value="75">74</option>
										<option value="76">75</option>
										<option value="77">76</option>
										<option value="78">77</option>
										<option value="79">78</option>
										<option value="80">79</option>
										<option value="81">80</option>
										<option value="82">81</option>
										<option value="83">82</option>
										<option value="84">83</option>
										<option value="85">84</option>
										<option value="86">85</option>
										<option value="87">86</option>
										<option value="88">87</option>
										<option value="89">88</option>
										<option value="90">89</option>
										<option value="91">90</option>
										<option value="92">91</option>
										<option value="93">92</option>
										<option value="94">93</option>
										<option value="95">94</option>
										<option value="96">95</option>
										<option value="97">96</option>
										<option value="98">97</option>
										<option value="99">98</option>
										<option value="100">99</option>
									</select>

									<label>
										<span>生成数量：</span>
										<input type='input' class='input1' size='6' id="num"/>
										<span><font color="red"><i><b>注</b>：生成上限为10000个！</i></font></span>
									</label>						
								</td>
							</tr>
							
							<tr>
								<td width="5%" class="tableright">
									<span>时限：</span>
								</td>
								<td width="95%" colspan="2" class="tableleft">
									<input type="radio" name="sxGroup" value="1" checked="checked"/><span>不限</span>
									<input type="radio" name="sxGroup" value="2"/><span>限时</span>
									<label id="sx_model" style="display:none">
										<span>限时至：</span>
										<input type="text" class="input1" id='sxtime' onClick="WdatePicker({mixDate:'#F{$dp.$D(\'endtime\',{s:-1})}'})" />失效
									</label>
								</td>
							</tr>
							<!--
							<tr>
								<td width="5%" class="tableright">
									<span>角色：</span>
								</td>
								<td width="95%" colspan="2" class="tableleft">
									<input type="radio" name="jsGroup" value="1" checked="checked"/><span>不限</span>
									<input type="radio" name="jsGroup" value="2"/><span>限角色</span>
									<label id="role_model" style="display:none">
										<span>角色ID：</span>
										<input type="text" class="input1" id='roleText' size="6"/>使用
									</label>
								</td>
							</tr>
							-->
							<tr>
								<td width="5%" class="tableright">
									<span>礼包：</span>
								</td>
								<td width="95%" class="tableleft labc">
									<span>礼品ID：</span>
									<span  class="input1">
										<input type="text" size="13" id="toolsId" style="border: 0 none;"/>
										<span class="mini-buttonedit-icon" id="tools_icon">&nbsp;&nbsp;&nbsp;</span>
									</span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div style="clear: both"></div>
				<div>
					<input type="button" value="确认生成" id="createCode"/>
				</div>
				<div>
					<h3>生成的激活码</h3>
					<table  class="mytable">
						<thead>
							<tr>
								<th>激活码</th>
								<th>激活类型</th>
								<th>激活码详情</th>
								<th>服务器</th>
								<th>角色ID</th>
								<th>使用时限</th>
							</tr>
						</thead>
						<tbody id="askBody">
						</tbody>
					</table>
					<div class="exportbtn" style="display:none" id="export_div">
						<input type="button" value="导出Excel" id="exportbtn"/>
					</div>
				</div>
			</div>
		<div style="clear:both"></div>
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
	
	<!-- 道具ID与道具名称 -->
	<div id="dform"  style="display:none">
		<div class="ajaxform">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
				<tbody>
					<tr>
						<td>道具ID与道具名称关系：</td>
					</tr>
					
					<tr>
						<?php if (in_array ( '00400900' , $this->_tpl_vars['code'] )): ?>
						<td style="text-align:left">
							<input type="button" value="更新道具列表" id="update_tool"/>
						</td>
						<?php else: ?>
						<td style="text-align:left">
							&nbsp;
						</td>
						<?php endif; ?>
						<!--
						<td style="text-align:right">
							<input type="text" id="searchKey"/ >
							<input type="button" value="搜索" id="toolSearch"/>
						</td>
						-->
					</tr>
					
					<tr>
						<td colspan='2'>
							<table class="tooltable">
								<thead>
									<tr>
										<th>道具ID</th>
										<th>道具名称</th>
										<th>操作</th>
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
	
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/ajaxfileupload.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/jquery-ui.js" type="text/javascript"></script> 
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/My97DatePicker/WdatePicker.js" type="text/javascript"></script>
	<script src="<?php echo $this->_tpl_vars['res']; ?>
/js/function.js" type="text/javascript"></script>
	<script type="text/javascript">
		var code = {
			INIT : function(){	
				var self = this;
				
				showTitle("运营工具:激活码生成");
				
				//时限选择
				$("input[name=sxGroup]").click(function(){
					var type = $(this).val();
					type == "2" ? $("#sx_model").show():$("#sx_model").hide();
				})
				
				//角色选择
				$("input[name=jsGroup]").click(function(){
					var type = $(this).val();
					type == "2" ? $("#role_model").show():$("#role_model").hide();
				})
				
				//切换标签
				$("#user-tabs span").click(function(){
					window.location = "<?php echo $this->_tpl_vars['app']; ?>
/code/show/pageId/"+this.id;
				})
				
				//导出excel
				$("#exportbtn").click(function(){
				
					var codes = $(".mytable").data('excel');
					$.ajax({
						type : 'post',
						url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/code/getNewExcel',
						data : {
							'codes' : codes
						},
						success:function(data){
							//if(data != '0'){
								window.location.href = "<?php echo $this->_tpl_vars['logicApp']; ?>
/code/excel/f/"+encodeURIComponent(data);
							//}
						}
					
					});
				})
				
				//确认生成
				$("#createCode").click(function(){
					var codeType = $("#codeType").val();	//激活码类型
					var codeName = $("#codeType option:checked").text();
					var num = $("#num").val();				//生成数量
					var ip = $("#sip").val();				//服务器
					var sxGroup = $("input[name=sxGroup]:checked").val();	//时限
					var jsGroup = $("input[name=jsGroup]:checked").val();	//角色
					var sxtime = $("#sxtime").val();
					var roleText = $("#roleText").val();
					var toolsId = $("#toolsId").val();				//礼包
					if(sxGroup == '1') {
						sxtime = 0;
					}
					
					if(jsGroup == '1') {
						roleText = 0;
					}
					
					if(num == ""){
						alert("请输入生成数量!");
						return false;
					}else if(isNaN(num)){
						alert("生成数量必须为数字！");
						return false;
					}else if(num > 10000){
						alert("请输入小于100000的值");
						return false;
					}else if(sxGroup == '2' && sxtime == ''){
						alert("请输入失效时间！");
						return false;
					}else if(jsGroup == '2' && roleText == ''){
						alert("请输入角色ID");
						return false;
					}else if(toolsId == ""){
						alert("请输入礼品ID！");
						return false;
					}
					
					$.ajax({
						type : 'post',
						url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/code/addCode',
						dataType : 'json',
						data : {
							'codeType' : codeType,
							'codeName' : codeName,
							'num' : num,
							'ip' : ip,
							'sxtime' : sxtime,
							'roleText' : roleText,
							'toolsId' : toolsId
						},
						beforeSend : function(){
							$(".overlay").show();
						},
						complete : function(){
							$(".overlay").hide();
						},
						success : function(data){
							if(typeof(data.error) != 'undefined'){
								alert(data.error);
							}else{
								var html = "";
								$("#export_div").show();
								for(var i in data.list){
									var re = data.list;
									switch(parseInt(data.list[i]["type"])){
										case 1 : data.list[i]["type"] = "1";break;
										case 2 : data.list[i]["type"] = "2";break;
										case 3 : data.list[i]["type"] = "3";break;
										case 4 : data.list[i]["type"] = "4";break;
										case 5 : data.list[i]["type"] = "5";break;
										case 7 : data.list[i]["type"] = "6";break;
										case 8 : data.list[i]["type"] = "7";break;
										case 9 : data.list[i]["type"] = "8";break;
										case 10 : data.list[i]["type"] = "9";break;
										case 11 : data.list[i]["type"] = "10";break;
										case 12 : data.list[i]["type"] = "11";break;
										case 13 : data.list[i]["type"] = "12";break;
										case 14 : data.list[i]["type"] = "13";break;
										case 15 : data.list[i]["type"] = "14";break;
										case 16 : data.list[i]["type"] = "15";break;
										case 17 : data.list[i]["type"] = "16";break;
										case 18 : data.list[i]["type"] = "17";break;
										case 19 : data.list[i]["type"] = "18";break;
										case 20 : data.list[i]["type"] = "19";break;
										case 21 : data.list[i]["type"] = "20";break;
										case 22 : data.list[i]["type"] = "21";break;
										case 23 : data.list[i]["type"] = "22";break;
										case 24 : data.list[i]["type"] = "23";break;
										case 25 : data.list[i]["type"] = "24";break;
										case 26 : data.list[i]["type"] = "25";break;
										case 27 : data.list[i]["type"] = "26";break;
										case 28 : data.list[i]["type"] = "27";break;
										case 29 : data.list[i]["type"] = "28";break;
										case 30 : data.list[i]["type"] = "29";break;
										case 31 : data.list[i]["type"] = "30";break;
										case 32 : data.list[i]["type"] = "31";break;
										case 33 : data.list[i]["type"] = "32";break;
										case 34 : data.list[i]["type"] = "33";break;
										case 35 : data.list[i]["type"] = "34";break;
										case 36 : data.list[i]["type"] = "35";break;
										case 37 : data.list[i]["type"] = "36";break;
										case 38 : data.list[i]["type"] = "37";break;
										case 39 : data.list[i]["type"] = "38";break;
										case 40 : data.list[i]["type"] = "39";break;
										case 41 : data.list[i]["type"] = "40";break;
										case 42 : data.list[i]["type"] = "41";break;
										case 43 : data.list[i]["type"] = "42";break;
										case 44 : data.list[i]["type"] = "43";break;
										case 45 : data.list[i]["type"] = "44";break;
										case 46 : data.list[i]["type"] = "45";break;
										case 47 : data.list[i]["type"] = "46";break;
										case 48 : data.list[i]["type"] = "47";break;
										case 49 : data.list[i]["type"] = "48";break;
										case 50 : data.list[i]["type"] = "49";break;
										case 51 : data.list[i]["type"] = "50";break;
										case 52 : data.list[i]["type"] = "51";break;
										case 53 : data.list[i]["type"] = "52";break;
										case 54 : data.list[i]["type"] = "53";break;
										case 55 : data.list[i]["type"] = "54";break;
										case 56 : data.list[i]["type"] = "55";break;
										case 57 : data.list[i]["type"] = "56";break;
										case 58 : data.list[i]["type"] = "57";break;
										case 59 : data.list[i]["type"] = "58";break;
										case 60 : data.list[i]["type"] = "59";break;
										case 61 : data.list[i]["type"] = "60";break;
										case 62 : data.list[i]["type"] = "61";break;
										case 63 : data.list[i]["type"] = "62";break;
										case 64 : data.list[i]["type"] = "63";break;
										case 65 : data.list[i]["type"] = "64";break;
										case 66 : data.list[i]["type"] = "65";break;
										case 67 : data.list[i]["type"] = "66";break;
										case 68 : data.list[i]["type"] = "67";break;
										case 69 : data.list[i]["type"] = "68";break;
										case 70 : data.list[i]["type"] = "69";break;
										case 71 : data.list[i]["type"] = "70";break;
										case 72 : data.list[i]["type"] = "71";break;
										case 73 : data.list[i]["type"] = "72";break;
										case 74 : data.list[i]["type"] = "73";break;
										case 75 : data.list[i]["type"] = "74";break;
										case 76 : data.list[i]["type"] = "75";break;
										case 77 : data.list[i]["type"] = "76";break;
										case 78 : data.list[i]["type"] = "77";break;
										case 79 : data.list[i]["type"] = "78";break;
										case 80 : data.list[i]["type"] = "79";break;
										case 81 : data.list[i]["type"] = "80";break;
										case 82 : data.list[i]["type"] = "81";break;
										case 83 : data.list[i]["type"] = "82";break;
										case 84 : data.list[i]["type"] = "83";break;
										case 85 : data.list[i]["type"] = "84";break;
										case 86 : data.list[i]["type"] = "85";break;
										case 87 : data.list[i]["type"] = "86";break;
										case 88 : data.list[i]["type"] = "87";break;
										case 89 : data.list[i]["type"] = "88";break;
										case 90 : data.list[i]["type"] = "89";break;
										case 91 : data.list[i]["type"] = "90";break;
										case 92 : data.list[i]["type"] = "91";break;
										case 93 : data.list[i]["type"] = "92";break;
										case 94 : data.list[i]["type"] = "93";break;
										case 95 : data.list[i]["type"] = "94";break;
										case 96 : data.list[i]["type"] = "95";break;
										case 97 : data.list[i]["type"] = "96";break;
										case 98 : data.list[i]["type"] = "97";break;
										case 99 : data.list[i]["type"] = "98";break;
										case 100 : data.list[i]["type"] = "99";break;
										case 6 : data.list[i]["type"] = "不限";break;
									}
									
									if(re[i]["player_id"] == '0'){
										re[i]["player_id"] = "不限";
									}
									
									if(re[i]["end_time"] == '0'){
										re[i]["end_time"] = "不限";
									}
									
									html += "<tr>";
									html += "<td>"+re[i]["id"]+"</td>";
									html += "<td>"+re[i]["type"]+"</td>";
									html += "<td>"+re[i]["item_id"]+"</td>";
									html += "<td>"+re[i]["ip"]+"</td>";
									//html += "<td>"+re[i]['used']+"</td>";
									html += "<td>"+re[i]["player_id"]+"</td>";
									//html += "<td>"+re[i]["start_time"]+"</td>";
									html += "<td>"+re[i]["end_time"]+"</td>";
									html += "</tr>";
								}
								$(".mytable").data('excel',re);
								$("#askBody").html(html);								
							}
						},
						error : function(){
							alert('数据库连接失败！');
						}
					})
					
				})
				
				//道具ID点击事件
				$("#tools_icon").click(function(){	
					self.getDetail(1);
				})
				
				//道具搜索
				$("#toolSearch").click(function(){
					self.getDetail(1);
				})
				
				//选择道具
				$(".choose").live("click",function(){
					var code = $(this).parent().parent().find(".t_code").html();	//道具ID
					var name = $(this).parent().parent().find(".t_name").html();	//道具名称
					$("#toolsId").val(code);
					$("#dform").dialog("close");
				})
				
				//更新道具列表
				$("#update_tool").click(function(){
					if(confirm('确定要更新道具列表吗？')){
						$.ajax({
							type : 'post',
							url : '<?php echo $this->_tpl_vars['logicApp']; ?>
/parsexml/parse',
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
									self.getDetail(1);
								} else if(data == 'error') {
									alert('更新失败！');
								}
							},
							error : function(){
								alert('更新失败！');
							}
						})
					}
				})
			},
			
			//获取道具ID与道具名称数据
			getDetail : function(page){
				$.ajax({
					type : "post",
					url : "<?php echo $this->_tpl_vars['logicApp']; ?>
/code/getToolDetail",
					dataType : "json",
					data : {
						ip : $("#sip").val(),
						searchKey : $("#searchKey").val(),
						pageSize : 10,
						curPage : page
					},
					success : function(data){				
						if(typeof(data.list) != 'undefined'){
							var html = "";
							//if(data.list.length >0){
							for(var i in data.list){
								html += "<tr>";
								html += "<td class='t_code'>"+data.list[i]["t_code"]+"</td>";
								html += "<td class='t_name'>"+data.list[i]["t_name"]+"</td>";
								html += "<td><span class='choose'>选择</span></td>";
								html += "</tr>";
							}
							$("#dform_body").html(html);
							$("#pagehtml2").html(data.pageHtml);
							//}else{
							//	$("#pagehtml2").html("");
							//	$("#dform_body").html("<tr><td colspan='3'>没有记录！</td></tr>");
							//}
						}else{
							$("#pagehtml2").html("");
							$("#dform_body").html("<tr><td colspan='3'>没有记录！</td></tr>");
						}
						
						$("#dform").dialog({
							height: 500,
							width: 700,
							buttons :{
								"关闭" : function(){
									$(this).dialog("close");
								}
							}
						})
					},
					error:function(){
						alert("error");
					}
				})
			}
				
		}
		
		$(document).ready(function(){
			code.INIT();
		})
		
		//分页ajax函数(道具ID与名称关系)
		var pageAjax2 = function(page){
			code.getDetail(page);
		}

		//跳到相应页面 (道具ID与名称关系)
		var go2 = function(){
			var pagenum = $("#page2").val();
			if(pagenum=='' || isNaN(pagenum) || pagenum <= 0){
				alert('请输入一个正整数！');
				$("#page").val(1);
			}else{
				pageAjax2(pagenum);
			}
		}
	</script>
</body>
</html>
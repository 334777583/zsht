<?php /* Smarty version 2.6.18, created on 2013-12-28 18:32:58
         compiled from common/sidebar.html */ ?>
<div>
	<?php if (in_array ( '00100000' , $this->_tpl_vars['user']['code'] )): ?>
		<h1 class="type"><a href="javascript:void(0)">充值相关查询</a></h1>
		<div class="content">
			<div class="topline">
				<img src="<?php echo $this->_tpl_vars['res']; ?>
/images/menu_topline.gif" width="182" height="5" />
			</div>
			<ul class="subMenu">
				<?php if (in_array ( '00100100' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="account"><a href="<?php echo $this->_tpl_vars['app']; ?>
/rechargequery/show" onclick="return false;">玩家充值查询</a></li>
				<?php endif; ?>
				<?php if (in_array ( '00100200' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="account"><a href="<?php echo $this->_tpl_vars['app']; ?>
/jishiquery/show" onclick="return false;">充值即时查询</a></li>
				<?php endif; ?>
				<?php if (in_array ( '00100300' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="accountstat"><a href="<?php echo $this->_tpl_vars['app']; ?>
/rechargestat/show" onclick="return false;">充值统计</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00100400' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="accconcom"><a href="<?php echo $this->_tpl_vars['app']; ?>
/rechargeduibi/show" onclick="return false;">充值对比</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00100500' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="accdis"><a href="<?php echo $this->_tpl_vars['app']; ?>
/rechargefengbu/show" onclick="return false;">充值分布</a></li>
				<?php endif; ?>
				<?php if (in_array ( '00100600' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="accport"><a href="<?php echo $this->_tpl_vars['app']; ?>
/rechargeport/show" onclick="return false;">充值接口</a></li>
				<?php endif; ?>
			</ul>
		</div>
	<?php endif; ?>
	
	<?php if (in_array ( '00200000' , $this->_tpl_vars['user']['code'] )): ?>
		<h1 class="type"><a href="javascript:void(0)">货币相关查询</a></h1>
		<div class="content">
			<div class="topline">
				<img src="<?php echo $this->_tpl_vars['res']; ?>
/images/menu_topline.gif" width="182" height="5" />
			</div>
			<ul class="subMenu">
				<?php if (in_array ( '00200100' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="monout"><a href="<?php echo $this->_tpl_vars['app']; ?>
/shopconsume/show" onclick="return false;">玩家商城消费记录</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00200200' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="monpay"><a href="<?php echo $this->_tpl_vars['app']; ?>
/shopanalysis/show" onclick="return false;">商城消费统计</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00200300' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="goldout"><a href="<?php echo $this->_tpl_vars['app']; ?>
/moneysurvey/show" onclick="return false;">货币收支概况</a></li>
				<?php endif; ?>
								
				<?php if (in_array ( '00200400' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="goldpay"><a href="<?php echo $this->_tpl_vars['app']; ?>
/residualvalue/show" onclick="return false;">剩余价值统计</a>	</li>
				<?php endif; ?>
			</ul>
		 </div>
	 <?php endif; ?>
	 
	 
	<?php if (in_array ( '00300000' , $this->_tpl_vars['user']['code'] )): ?>
		<h1 class="type"><a href="javascript:void(0)">玩家数据查询</a></h1>
		<div class="content">
			<div class="topline">
				<img src="<?php echo $this->_tpl_vars['res']; ?>
/images/menu_topline.gif" width="182" height="5" />
			</div>
			<ul class="subMenu">
				<?php if (in_array ( '00300100' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="infoquery"><a href="<?php echo $this->_tpl_vars['app']; ?>
/gminfo/show" onclick="return false;">用户查询</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00300200' , $this->_tpl_vars['user']['code'] )): ?><!--原留存分析-->
					<li id="keep"><a href="<?php echo $this->_tpl_vars['app']; ?>
/useronline/show" onclick="return false;">玩家在线信息</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00300300' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="sin"><a href="<?php echo $this->_tpl_vars['app']; ?>
/singsevrak/show"  onclick="return false;">玩家充值排行</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00300400' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="sin"><a href="<?php echo $this->_tpl_vars['app']; ?>
/singsevact/show"  onclick="return false;">玩家单服活跃统计</a></li>
				<?php endif; ?>
				
			</ul>
		</div>
	<?php endif; ?>

	<?php if (in_array ( '00400000' , $this->_tpl_vars['user']['code'] )): ?>
		<h1 class="type"><a href="javascript:void(0)">游戏数据统计</a></h1>
		<div class="content" >
			<div class="topline">
				<img src="<?php echo $this->_tpl_vars['res']; ?>
/images/menu_topline.gif" width="182" height="5" />
			</div>
			<ul class="subMenu">
				<?php if (in_array ( '00400101' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="new"><a href="<?php echo $this->_tpl_vars['app']; ?>
/userrole/shishi"  onclick="return false;">实时查询</a></li>
				<?php endif; ?>
				<?php if (in_array ( '00400200' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="login" ><a href="<?php echo $this->_tpl_vars['app']; ?>
/userlogin/show" onclick="return false;">登录汇总</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00400300' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="active"><a href="<?php echo $this->_tpl_vars['app']; ?>
/userkeepb/show" onclick="return false;">留存分析</a></li><!--原活跃分析-->
				<?php endif; ?>
				<!--
				<?php if (in_array ( '00400300' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="keep"><a href="<?php echo $this->_tpl_vars['app']; ?>
/userkeep/show" onclick="return false;">留存分析</a></li>
				<?php endif; ?>
				-->
				<?php if (in_array ( '00400400' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="accport"><a href="<?php echo $this->_tpl_vars['app']; ?>
/recharresult/show" onclick="return false;">渠道数据统计</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00400500' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="sin"><a href="<?php echo $this->_tpl_vars['app']; ?>
/singaction/show"  onclick="return false;">单服活动数据分析</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00400600' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="pay"><a href="<?php echo $this->_tpl_vars['app']; ?>
/userpay/show" onclick="return false;">付费分析</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00400700' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="role"><a href="<?php echo $this->_tpl_vars['app']; ?>
/userrole/show"  onclick="return false;">创角统计</a></li>
				<?php endif; ?>
				
				
			<?php if (in_array ( '00400800' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="new"><a href="<?php echo $this->_tpl_vars['app']; ?>
/usernew/show"  onclick="return false;">新进数据统计</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00400900' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="new"><a href="<?php echo $this->_tpl_vars['app']; ?>
/usercopy/show"  onclick="return false;">副本数据统计</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00401000' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="hot"><a href="<?php echo $this->_tpl_vars['app']; ?>
/gmgrow/show" onclick="return false;" target="main">玩家成长日志</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00401100' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="action"><a href="<?php echo $this->_tpl_vars['app']; ?>
/useraction/show"  onclick="return false;">玩家行为数据统计</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00401200' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="goldpay"><a href="<?php echo $this->_tpl_vars['app']; ?>
/consumeranalysis/show" onclick="return false;">用户行为消耗统计</a>	</li>
				<?php endif; ?>
				
				<?php if (in_array ( '00401300' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="level"><a href="<?php echo $this->_tpl_vars['app']; ?>
/userlevel/show"  onclick="return false;">玩家等级分布统计</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00401400' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="proppay"><a href="<?php echo $this->_tpl_vars['app']; ?>
/propanalysis/show" onclick="return false;">道具消耗统计</a>	</li>
				<?php endif; ?>
			</ul>
		</div>
	<?php endif; ?>
	
	
	
	
	 
    <?php if (in_array ( '00500000' , $this->_tpl_vars['user']['code'] )): ?>
		<h1 class="type"><a href="javascript:void(0)">GM工具</a></h1>
		<div class="content">
			<div class="topline">
				<img src="<?php echo $this->_tpl_vars['res']; ?>
/images/menu_topline.gif" width="182" height="5" />
			</div>
			<ul class="subMenu">
				
				<?php if (in_array ( '00500100' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="handle"><a href="<?php echo $this->_tpl_vars['app']; ?>
/gmoperate/show" onclick="return false;">游戏账号禁封</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00500200' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="newmag"><a href="<?php echo $this->_tpl_vars['app']; ?>
/gmnew/show" onclick="return false;">发送公告</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00500300' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="email"><a href="<?php echo $this->_tpl_vars['app']; ?>
/gmemail/show" onclick="return false;">发送邮件</a></li>
				<?php endif; ?>
				<?php if (in_array ( '00500400' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="toolsask"><a href="<?php echo $this->_tpl_vars['app']; ?>
/gmtoolsask/show" onclick="return false;">道具申请</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00500500' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="toolspass"><a href="<?php echo $this->_tpl_vars['app']; ?>
/gmtoolspass/show" onclick="return false;">道具审批</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00500600' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="code"><a href="<?php echo $this->_tpl_vars['app']; ?>
/code/show" onclick="return false;" target="main">新手卡</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00500700' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="delemail"><a href="<?php echo $this->_tpl_vars['app']; ?>
/gmemail/showdel" onclick="return false;" target="main">删除邮件</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00500800' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="double"><a href="<?php echo $this->_tpl_vars['app']; ?>
/gmdouble/show" onclick="return false;" target="main">多倍经验管理</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00500900' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="hot"><a href="<?php echo $this->_tpl_vars['app']; ?>
/gmstart/show" onclick="return false;" target="main">热启动</a></li>
				<?php endif; ?>
				
				<?php if (in_array ( '00501000' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="hot"><a href="<?php echo $this->_tpl_vars['app']; ?>
/gmlogin/show" onclick="return false;" target="main">GM操作记录</a></li>
				<?php endif; ?>
				
					<?php if (in_array ( '00501100' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="serverini"><a href="<?php echo $this->_tpl_vars['app']; ?>
/server/show" onclick="return false;">gm设置</a></li>
				<?php endif; ?>
				<?php if (in_array ( '00501200' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="gameini"><a href="<?php echo $this->_tpl_vars['app']; ?>
/game/show" onclick="return false;">开服设置</a></li>
				<?php endif; ?>	
				<?php if (in_array ( '00501300' , $this->_tpl_vars['user']['code'] )): ?>
					<li id="usermang"><a href="<?php echo $this->_tpl_vars['app']; ?>
/system/show" onclick="return false;">权限管理</a></li>
				<?php endif; ?>
				<?php if (in_array ( '00501400' , $this->_tpl_vars['user']['code'] )): ?>
				<li id="databak"><a href="<?php echo $this->_tpl_vars['app']; ?>
/data/show" onclick="return false;">数据备份</a></li>
				<?php endif; ?>
				<?php if (in_array ( '00501500' , $this->_tpl_vars['user']['code'] )): ?>
				<li id="databak"><a href="<?php echo $this->_tpl_vars['app']; ?>
/platformquery/show" onclick="return false;">平台记录</a></li>
				<?php endif; ?>
				<?php if (in_array ( '00501600' , $this->_tpl_vars['user']['code'] )): ?>
				<li id="brower"><a href="<?php echo $this->_tpl_vars['app']; ?>
/brower/show" onclick="return false;">浏览器记录</a></li>
				<?php endif; ?>
				<?php if (in_array ( '00501700' , $this->_tpl_vars['user']['code'] )): ?>
				<li id="gold"><a href="<?php echo $this->_tpl_vars['app']; ?>
/gold/show" onclick="return false;">邮件发元宝</a></li>
				<?php endif; ?>
			</ul>
		</div>
	<?php endif; ?>
	
	
	
	<?php if (empty ( $this->_tpl_vars['user']['code'] )): ?>
		<h1 class="nopage"><a href="javascript:void(0)">没有权限</a></h1>
	<?php endif; ?>
</div>
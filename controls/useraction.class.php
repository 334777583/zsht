<?php
/**
 * FileName: useraction.class.php
 * Description:行为分析
 * Author: xiaoliao
 * Date:2013-9-24 18:19:57
 * Version:1.00
 */
class useraction{
	/**
	 * 服务器IP
	 * @var string
	 */
	public $ip;
	
	
	/**
	 * 用户数据
	 * @var Array
	 */
	public $user;
	
	/**
	 * 结束时间
	 * @var string
	 */
	private $enddate;
	
	/**
	 * 开始时间
	 * @var string
	 */
	private $startdate;
	
	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00401100', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
		
		$this->ip =  get_var_value('ip') == NULL? -1 : get_var_value('ip');
		$this->startdate = get_var_value("startdate") == NULL?date("Y-m-d",strtotime("-7 day")):date("Y-m-d",strtotime(get_var_value("startdate")));
		$this->enddate =  get_var_value('enddate') == NULL? '' : get_var_value('enddate');
	
	}

	/**
	 * 获取行为分析数据
	 */
	public function getResult() {
		// print_r($_POST);
		$point = D(GNAME.$_POST['ip']);
		//show columns from table_name from database_name
		//$result = $point->fquery("SELECT * FROM {$_POST['table']} WHERE time BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		

		$result = array();
		if ($_POST['table'] == 'xitongrenwu') {
			$result = $point->fquery("SELECT SUM(xuanshangrwwc) 悬赏任务完成,SUM(xuanshangadd) 悬赏增加,SUM(qingjiaorwwc) 清剿任务完成,SUM(qingjiaoadd) 清剿增加 FROM {$_POST['table']} WHERE time BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		}elseif ($_POST['table'] == 'jhhb') {
			$result = $point->fquery("SELECT SUM(chuangdangjhtg) 闯荡江湖过关,SUM(chuangdangjhchzh) 闯荡江湖重置,SUM(gumuqytg) 古墓奇缘通过,SUM(gumuqychzh) 古墓奇缘重置,SUM(wentaowltg) 文韬武略通过,SUM(wentaowljs) 文韬武略加速,SUM(hsongchg) 护送出关,SUM(taihushz) 太湖水贼 FROM {$_POST['table']} WHERE time BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		}elseif ($_POST['table'] == 'teshewf') {
			$result = $point->fquery("SELECT SUM(shenglongdingxljq) 神龙鼎香料聚气,SUM(nxgxuanxiu) 凝香阁选秀,SUM(nxglueduo) 凝香阁掠夺,SUM(nxggunli) 凝香阁鼓励,SUM(nxgmeiren) 凝香阁美人技术升级,SUM(nxgaixin) 凝香阁爱心小店兑换,SUM(nxgjiasu) 凝香阁加速,SUM(nxgshuaxin) 凝香阁刷新,SUM(qiankunshj) 乾坤升级,SUM(xinghunshj) 星魂升级,SUM(xinfashj) 心法升级,SUM(qiyu) 奇遇,SUM(wulinzhb) 武林争霸,SUM(zhuoyuejj) 卓越进阶,SUM(zhuoyuexil) 卓越洗练 FROM {$_POST['table']} WHERE time BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		}elseif ($_POST['table'] == 'rchxw') {
			$result = $point->fquery("SELECT SUM(mianfeichm) 免费冲脉,SUM(yuanbaochm) 元宝冲脉,SUM(zuoqijinjie) 坐骑进阶,SUM(zuoqijinengjj) 坐骑技能进阶,SUM(zhuangbeiqh) 装备强化,SUM(linghunbshhc) 灵魂宝石合成,SUM(banghjx) 帮会捐献,SUM(banghuiqifu) 帮会祈福,SUM(bangzhan) 帮战,SUM(bhjinengyanfa) 帮会技能研发,SUM(baitan) 摆摊,SUM(baitangoumai) 摆摊购买,SUM(zhufuyousj) 祝福油升级,SUM(chaojizhufyjs) 超级祝福油升级,SUM(zhuizongchouren) 追踪仇人,SUM(haoyoutj) 好友添加,SUM(fenjie) 分解,SUM(maiwu) 卖物 FROM {$_POST['table']} WHERE time BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		}elseif ($_POST['table'] == 'gmxw') {
			$result = $point->fquery("SELECT SUM(chxqwjgm) 促销区玩家购买,SUM(shangchenggm) 商城购买,SUM(shangdiangm) 商店购买 FROM {$_POST['table']} WHERE time BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		}
		// print_r($result);
		$arr1 = array();

		foreach ($result[0] as $key => $value) {
			$arr1[] = "\"name\":\"".$key."\",\"num\":".$value;
			// echo $key.'<br/>'.$value.'<br/>';
			
		}
		// print_r($arr1);

		echo $str = '[{'.implode('},{', $arr1).'}]';

	}
	
	/**
	 * 删除指定日期前的表数据
	**/
	// public function delete() {
	// 	if(!in_array('00100401', $this->user['code'])){
	// 		echo 'not available!';
	// 		exit();
	// 	}
	// 	$ip = get_var_value('ip');
	// 	if($ip) {
	// 		$point = D(GNAME.$ip);
			
	// 		$result = $point -> table('action_temp') -> delete();//删除缓存数据
	// 		$list  = $point -> field('date') -> table('action_temp') -> select();		//先取出已经做缓存的日期，清空它们的原始数据
			
	// 		if($list != '') {
	// 			$date = '(';
	// 			foreach($list as $item) {
	// 				$date .= "'".$item['date'] . "',";
	// 			}
	// 			$date = rtrim($date, ',');
	// 			$date .= ')';
				
	// 			$cwltg = $point -> table('dwltg') -> where('left(d_date,10) in ' . $date) -> delete();//闯皇陵每人每天平均通关次数
	// 			$cwlcz = $point -> table('dwlcz') -> where('left(d_date,10) in ' . $date) -> delete();//闯皇陵每人每天平均重置次数
	// 			$jqtg = $point -> table('fbtz') -> where('left(f_date,10) in ' . $date ) -> delete();//剧情副本每人每天通关次数
	// 			$jqzj = $point -> table('fbgm') -> where('left(f_date,10) in ' . $date) -> delete();//剧情副本每人每天平均增加次数
	// 			$dgtg = $point -> table('fbtz') -> where('left(f_date,10) in ' . $date) -> delete();//独孤求败每人每天通关次数
	// 			$dgzj = $point -> table('fbcz') -> where('left(f_date,10) in ' . $date) -> delete();//独孤求败每人每天平均增加次数
	// 			$qm = $point -> table('zbzj') -> where('left(z_date,10) in ' . $date) -> delete();//奇门每人每天洗练次数
	// 			$dj = $point -> table('zbzj') -> where('left(z_date,10) in ' . $date) -> delete();//遁甲每人每天洗练次数
	// 			$ht = $point -> table('zbzj') -> where('left(z_date,10) in ' . $date) -> delete();//河图每人每天洗练次数
	// 			$ls = $point -> table('zbzj') -> where('left(z_date,10) in ' . $date) -> delete();//洛书每人每天洗练次数
	// 			$xl = $point -> table('zbjnxl') -> where('left(z_date,10) in ' . $date) -> delete();//套装技能每人每天洗练次数
	// 			$qy = $point -> table('qy') -> where('left(q_date,10) in ' . $date) -> delete();//每人每天奇遇次数
	// 			$du = $point -> table('sx') -> where('left(s_date,10) in ' . $date) -> delete();//每人每天上香次数
	// 			$jm = $point -> table('cm') -> where('left(c_date,10) in ' . $date) -> delete();//每人每天冲脉次数
	// 			$sjwc = $point -> table('wcrw') -> where('left(w_date,10) in ' . $date) -> delete();//每天每人完成随机日常的个数
	// 			$sjgm  = $point -> table('gmrwcs') -> where('left(g_date,10) in ' . $date) -> delete();//每人每天随机日常购买次数
	// 			$cfwc = $point -> table('wcrw') -> where('left(w_date,10) in ' . $date) -> delete();//每天每人完成重复日常的个数
	// 			$cfgm  = $point -> table('gmrwcs') -> where('left(g_date,10) in ' . $date) -> delete();//每人每天重复日常购买次数
	// 			$sh  = $point -> table('mvsh') -> where('left(m_date,10) in ' . $date) -> delete();//天上人间每人每天收获次数
	// 			$td  = $point -> table('mvtd') -> where('left(m_date,10) in ' . $date) -> delete();//天上人间每人每天推倒次数
	// 			$py  = $point -> table('mvpy') -> where('left(m_date,10) in ' . $date) -> delete();//天上人间每人每天培养次数
	// 			$sx  = $point -> table('mvdjsx') -> where('left(m_date,10) in ' . $date) -> delete();//天上人间每人每天刷新次数	
	// 		}
			
	// 		if($result){
	// 			echo json_encode("success");
	// 			exit;
	// 		}else{
	// 			echo json_encode("error");
	// 			exit;
	// 		}
	// 	} else {
	// 		echo json_encode("error");
	// 		exit;
	// 	}
	// }
	
	
}
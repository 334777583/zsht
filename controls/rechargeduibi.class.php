<?php
/**
 * FileName: rechargeduibi.class.php
 * Description:充值对比
 * Author: xiaochengcheng,tanjianchengcc@gmail.com,hjt
 * Date:2013-9-24 18:03:18
 * Version:1.03
 */
class rechargeduibi{
	/**
	 * 登录用户信息
	 */
	private $user;

	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00100400', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	/**
	 * 获取充值对比
	 */
	public function getRecords(){
		$result = array();				//各个服的充值对比数据
		$page_data = array();			//返回到页面显示的数据
		$game_db = array();				//返回到页面的服务器信息
		$db = get_var_value('db');	
		$startDate = get_var_value('startDate').' 00:00:00';
		$endDate = get_var_value('endDate').' 23:59:59';
		
		if($db) {
			foreach($db as $id) {
				$point = D(GNAME.$id);
				
				$pay_list = $point -> table('pay_detail') -> where("p_creatdate >= '" . $startDate ."' and p_creatdate <= '". $endDate ."' and p_result = 1") -> order('p_creatdate asc') -> select();
				
				$obj = D('game_info');
				$list = $obj -> table('servers') -> where('s_flag = 1') -> select();
				$server_info = array();		//以ID为键值的服务器信息
				if($list != '') {
					foreach($list as $bo) {
						$server_info[$bo['s_id']] = $bo['s_name'];
					}
				}
				
				$game_db[] = $server_info[$id];
				
				if($pay_list != '') {
					$arr_by_date = array();			//根据日期为键值组装数据
					foreach ($pay_list as $pay) {
						$chg_date = substr($pay['p_creatdate'], 0, 10);		//获取日期
						$arr_by_date[$chg_date][] = $pay;
					}
				
					
					$temp_peo = array();	//用于计算充值人数(去重)
					
					foreach ($arr_by_date as $date => $daily) {		//计算每天各个服务器的充值情况
						$czje = 0;			//充值金额
						$czrs = 0;			//充值人数
						$czcs = 0;			//充值次数
						
						$czrs_str = '';		//记录账号的字符串（用于去重）
						
						foreach ($daily as $record => $rec_val) {
							$czje += $rec_val['p_money'];
							//统计速度过慢
							/*if(!in_array($rec_val['p_acc'], $temp_peo)) {
								$czrs += 1;			//计算充值人数（根据账号）
								$temp_peo[] = $rec_val['p_acc'];
							}*/
							//账号用“,”分开 结尾处为空   用于二维降一维(去重)
							$czrs_str .= $rec_val['p_playid'].',';
							$czcs ++;
						}
						$czrs_str = rtrim($czrs_str,',');
						
						$temp_peo = explode(',',$czrs_str);//将账号写成一维数组
						$temp_peo = array_unique($temp_peo);//去重
						$czrs = count($temp_peo);//计算充值人数（根据账号）
						
						//组装 统计数据
						$result[$date][$id] = array('czje' => $czje, 'czrs' => $czrs, 'czcs' => $czcs);
					}
				}
			}
			ksort($result);
			foreach($result as $date => $item) {			//组织页面显示数据	
									
				$fields = array('czje' => '充值金额', 'czrs' => '充值人数', 'czcs' => '充值次数');
				
				foreach($fields as $key => $field) {	
					$column = array();
					$column[] = $date;
					$column[] = $field;
					$sum = 0;			//各服合计
					foreach($item as $id => $bo) {
						$sum += $bo[$key];
					}
					$column[] = $sum;
					foreach($db as $id) {
						if(isset($item[$id][$key])) {
							$column[] = $item[$id][$key];
						} else {
							$column[] = 0;
						}
					}
					$page_data[] = $column;
				}
			}
		}
		
		 //print_r($page_data);
		// exit;
		
		echo json_encode(array(
				'result' => $page_data,
				'gamedb' => $game_db
			));
	}
}
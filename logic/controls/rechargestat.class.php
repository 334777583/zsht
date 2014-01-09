<?php
/**
 * FileName: rechargestat.class.php
 * Description:充值查询
 * Author: xiaochengcheng,tanjianchengcc@gmail.com,hjt
 * Date:2013-9-5 14:54:08
 * Version:1.02
 */
class rechargestat{
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
			if(!in_array('00100300', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	/**
	 * 获取充值统计
	 */
	public function getRecords(){
		$result = array();				//各个服的充值统计数据
		$where_sql = '';//初始化  查询条件
		$end_time = '';//初始化 截止时间
		
		$type = get_var_value('type');	//1：开服到截止时间，2:区间
		$startDate = get_var_value('startDate').' 00:00:00';
		$endDate = get_var_value('endDate').' 23:59:59';
		$finshDate = get_var_value('finshDate').' 23:59:59';
		$db = get_var_value('db');
		
		if($db) {
			foreach($db as $id) {
				$point = D(GNAME.$id);
				if($point) {
					if($type == 1) {
						$where_sql = "p_creatdate <= '" . $finshDate ."' and p_result = 1";
						$end_time = $finshDate;
					} else if($type == 2) {
						$where_sql = "p_creatdate >= '" . $startDate ."' and p_creatdate <= '". $endDate ."' and p_result = 1";
						$end_time = $endDate;
					}
					$pay_list = $point -> table('pay_detail') -> where($where_sql) -> order('p_creatdate asc') -> select();
					
					$obj = D('game_info');
					$list = $obj -> table('servers') -> where('s_flag = 1') -> select();
					$server_info = array();		//以ID为键值的服务器信息
					if($list != '') {
						foreach($list as $bo) {
							$server_info[$bo['s_id']] = $bo['s_name'];
						}
					}
					
					if($pay_list != '') {
						$first_date = substr($pay_list[0]['p_creatdate'], 0, 10);		//开服日期
						
						$data = array();	
						$czts = 0;    //充值天数
						$czrs = 0;	  //充值人数
						$czje = 0;	  //充值金额
						$kfsc = 0;	  //开服首冲金额
						$csrs = 0;	  //首充人数
						$crczje = 0;  //首日充值总金额
						$rjcz = 0;	  //日均充值
						$arpu = 0;	  //ARPU（充值金额/用户数 *100%)
						
						$czts_str = '';//记录充值天数的字符串(降维处理)
						$czrs_str = '';//记录充值人数的字符串(降维处理)
						$csrs_str = '';//记录首充人数的字符串(降维处理)
						
						$temp_date = array();	//用于计算充值天数(去重)
						$temp_peo = array();	//用于计算充值人数(去重)
						$temp_cs = array();	    //用于计算首充人数(去重)
						
						$flag = true;
						
						foreach($pay_list as $pay => $pay_val) {
							$chg_date = substr($pay_val['p_creatdate'], 0, 10);		//获取日期
							if($chg_date == $first_date) {
								if($flag) {
									$kfsc += $pay_val['p_money'];		//计算开服首冲金额
									$flag = false;
								}
								$csrs_str .= $pay_val['p_playid'].',';//计算首充人数
								//$csrs ++;							 
								$crczje += $pay_val['p_money'];			 //计算首日充值总金额
							}
							$czje += $pay_val['p_money'];				 //计算充值金额
							
							//统计速度过慢
							/*if(!in_array($chg_date, $temp_date)) {	 //计算充值天数
								$czts ++;
								$temp_date[] = $chg_date;
							}
							if(!in_array($pay_val['p_acc'], $temp_peo)) {
								$czrs ++;							 //计算充值人数
								$temp_peo[] = $pay_val['p_acc'];
							}*/
							//用“,”分开 结尾处为空   用于二维降一维(去重)
							$czts_str .= $chg_date.',';
							$czrs_str .= $pay_val['p_acc'].',';
						}
						$czts_str = rtrim($czts_str,',');
						$czrs_str = rtrim($czrs_str,',');
						$csrs_str = rtrim($csrs_str,',');
						
						//计算充值天数
						$temp_date = explode(',',$czts_str);//将充值天数写成一维数组
						$temp_date = array_unique($temp_date);//去重
						$czts = count($temp_date);//计算充值天数
						
						//计算充值人数
						$temp_peo = explode(',',$czrs_str);//将充值人数写成一维数组
						$temp_peo = array_unique($temp_peo);//去重
						$czrs = count($temp_peo);//计算充值人数
						
						//计算首充人数
						$temp_cs = explode(',',$csrs_str);//将首充人数写成一维数组
						$temp_cs = array_unique($temp_cs);//去重
						$csrs = count($temp_cs);//计算首充人数
						
						
						//计算日均充值
						if($czts !== 0) {
							$rjcz = sprintf('%0.2f',$czje/$czts);
						}
						
						$sum_pay = 0;								 //所有充值总金额
						$sum_list = $point -> table('pay_detail') -> field('sum(p_money) as sum') -> where($where_sql) -> select();
						
						if($sum_list != '') {
							$sum_pay = $sum_list[0]["sum"];
						}
						$sum_peo = 0;
						$sum_peo = $point -> table('pay_detail') -> where($where_sql) -> total();	//所有充值总人数
						
						if($sum_peo != 0) {
							$arpu = sprintf('%0.2f',$sum_pay/$sum_peo);	 //计算ARPU（充值金额/用户数)
						}
						
						$result[] = array(
									'db' => $server_info[$id], 
									'first' => $first_date,
									'czts' => $czts,
									'czrs' => $czrs,
									'czje' => $czje,
									'kfsc' => $kfsc,
									'csrs' => $csrs,
									'crczje' => $crczje,
									'rjcz' => $rjcz,
									'arpu' => $arpu
								); 
					}	
				}
			}	
		}
		
		// print_r($result);
		// exit;
		echo json_encode(array(
				'result' => $result
			));
	}
}
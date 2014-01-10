<?php
/**
 * FileName: rechargestat.class.php
 * Description:充值查询
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-7-4 17:02:37
 * Version:1.00
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
		$type = get_var_value('type');	//1：开服到截止时间，2:区间
		$startDate = get_var_value('startDate');
		$endDate = get_var_value('endDate');
		$finshDate = get_var_value('finshDate');
		
		$obj = D('game_base');
		$list = $obj -> table('servers') -> where('s_flag = 1') -> select();
		if($list != '') {
			foreach($list as $item) {
				$point = D("game".$item['s_id']);
				
				if($point) {
					if($type == 1) {
						$pay_list = $point -> table('pay_detail') -> where("left(p_creatdate,10) <= '" . $finshDate ."' and p_result = 0") -> order('p_creatdate asc') -> select();
					} else if($type == 2) {
						$pay_list = $point -> table('pay_detail') -> where("left(p_creatdate,10) >= '" . $startDate ."' and left(p_creatdate,10) <= '". $endDate ."' and p_result = 0") -> order('p_creatdate asc') -> select();
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
						
						$temp_date = array();	//用于计算充值天数(去重)
						$temp_peo = array();	//用于计算充值人数(去重)
						$flag = true;		
						foreach($pay_list as $pay) {
							$chg_date = substr($pay['p_creatdate'], 0, 10);		//获取日期
							if($chg_date == $first_date) {
								if($flag) {
									$kfsc += $pay['p_money'];		//计算开服首冲金额
									$flag = false;
								}
								$csrs ++;							 //计算首充人数
								$crczje += $pay['p_money'];			 //计算首日充值总金额
							}
							$czje += $pay['p_money'];				 //计算充值金额
							if(!in_array($chg_date, $temp_date)) {	 //计算充值天数
								$czts ++;
								$temp_date[] = $chg_date;
							}
							if(!in_array($pay['p_acc'], $temp_peo)) {
								$czrs ++;							 //计算充值人数
								$temp_peo[] = $pay['p_acc'];
							}
						}
						
						if($czts !== 0) {
							$rjcz = sprintf('%0.2f',$czje/$czts) .'%';	 //计算日均充值
						}
						
						$sum_pay = 0;								 //所有充值总金额
						$sum_list = $point -> table('pay_detail') -> field('sum(p_money) as sum') -> where('p_result = 0') -> select();
						if($sum_list != '') {
							$sum_pay = $sum_list[0]["sum"];
						}
						$sum_peo = $point -> table('pay_detail') -> where('p_result = 0') -> total();	//所有充值总人数
						
						if($sum_peo != 0) {
							$arpu = sprintf('%0.2f',$sum_pay/$sum_peo) .'%';	 //计算ARPU（充值金额/用户数 *100%)
						}
						
						$result[] = array(
									'db' => $item['s_name'], 
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
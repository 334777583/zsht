<?php
/**
 * FileName: moneysurvey.class.php
 * Description:货币收支概况
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-5-8 10:35:44
 * Version:1.00
 */
class moneysurvey{
	/**
	 * 服务器IP
	 * @var string
	 */
	public $ip;
	
	/**
	 * gm接口类
	 * @var class
	 */
	public $gm;
	
	/**
	 * 用户数据
	 * @var Array
	 */
	public $user;
	

	/**
	 * 开始时间
	 * @var string
	 */
	private $startdate;
	
	/**
	 * 结束时间
	 * @var string
	 */
	private $enddate;
	
	
	  /**
	 *初始化数据
	 **/
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00200300', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
		
		$this->gm = new autogm();
		$this->ip =  get_var_value('ip') == NULL? -1 : get_var_value('ip');
		$this->startdate = get_var_value('startdate') == NULL? '': get_var_value('startdate');
		$this->enddate =  get_var_value('enddate') == NULL? '' : get_var_value('enddate');
	
	}
	
	
	
	 /**
	 * FunctionName: getGoods
	 * Description: 获取货币收支概况
	 * Author: （jan）						
	 * Date: 2013-9-5 15:58:20	
	 **/
	public function get(){
		list($ip, $port, $loginName) = autoConfig::getConfig($this->ip);
		
		$obj = D('game'.$this -> ip);
		$listsql = ' p_id in (select max(p_id) from payments group by p_date,p_type) and p_date >= "'.$this->startdate.'" and p_date <= "'.$this->enddate.'"';
		//获取最新信息并去除重复时间
		$list = $obj -> table('payments') 
					 -> where($listsql)
					 -> order('p_date asc,p_type desc')
					 -> select(); 
		$sumList = $obj -> table('payments')		//合计
						-> field('sum(p_tong) as sum_tong,p_type')
						-> where($listsql)
						-> group('p_type')
						-> select();	
		if(empty($list)){	//默认数据为0
			$list = array();
			
			$start = strtotime($this->startdate);
			$end = strtotime($this->enddate);
			
			$n = 0;					
			for($i = $start; $i <= $end; $i = $i+86400) {
				$date =  date('Y-m-d', $i);
				$list[$n]['p_date'] = $date;
				$list[$n]['p_tong'] = 0;
				$list[$n]['p_yin'] = 0;
				$list[$n]['p_byuan'] = 0;
				$list[$n]['p_yuan'] = 0;
				$list[$n]['p_type'] = 0;
				$n++;
				$list[$n]['p_date'] = $date;
				$list[$n]['p_tong'] = 0;
				$list[$n]['p_yin'] = 0;
				$list[$n]['p_byuan'] = 0;
				$list[$n]['p_yuan'] = 0;
				$list[$n]['p_type'] = 1;
				$n++;
			}
			
		}
					 
		$result = array(
				'list' => $list,	
				'sumlist' =>$sumList		
			);
		
		echo json_encode($result);
		exit;
		
	}
}
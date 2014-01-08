<?php
/**
 * FileName: shopanalysis.class.php
 * Description:商城消费分析
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-5-6 10:09:51
 * Version:1.00
 */
class shopanalysis{
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
	 * 每页显示记录数
	 * @var int
	 */
	private $pageSize = 10;
	
	/**
	 * 当前页
	 * @var int
	 */
	private $curPage = 1;
	
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
	 * 金钱类型（1：元宝；2：绑定元宝）
	 * @var int
	 */
	private $type;
	
	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00200200', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
		
		$this->gm = new autogm();
		$this->ip =  get_var_value('ip') == NULL? -1 : get_var_value('ip');
		$this->pageSize = get_var_value('pageSize') == NULL? 10: get_var_value('pageSize');
		$this->curPage =  get_var_value('curPage') == NULL? 1 : get_var_value('curPage');
		$this->startdate = get_var_value('startdate') == NULL? '': get_var_value('startdate');
		$this->enddate =  get_var_value('enddate') == NULL? '' : get_var_value('enddate');
		$this->type =  get_var_value('type') == NULL? 0 : get_var_value('type');
	}
	
	/**
	 * FunctionName: getStartData
	 * Description: 获取开服时间
	 * Author: jan	
	 * Parameter：null
	 * Return: json
	 * Date: 2013-11-4 15:49:08
	 **/
	function getStartData(){
		$obj = D('game'.$this -> ip);
		 //查询商城消费分析 的开服日期
		$listdate = $obj -> table('item') -> field('i_date') -> order('i_date asc') -> limit(0,1) -> find();
		$list_date = isset($listdate['i_date'])? date('Y-m-d',strtotime($listdate['i_date'])) : date("Y-m-d",strtotime("-7 day"));//如果表里没数据 默认7天前
		$startdate = get_var_value('startdate') == NULL? $list_date : get_var_value('startdate');
		
		echo json_encode(array('startDate'=>$this -> startdate));
	}
	/**
	 * 获取商城消费分析物品情况
	 */
	public function getGoods(){
		$obj = D(GNAME.$this -> ip);
		$total = 0;
		
		
		if ($this->enddate == $this->startdate) {	//选择一天的时候
			$list = $obj -> table('goods')
					 -> where( array('g_type' => $this->type, 'g_date >= ' => $this->startdate,'g_date <= '=> $this->enddate) )
					 ->	limit( intval(($this->curPage - 1) * $this->pageSize),intval($this->pageSize) )
					 -> select();
					 
			$sumList = $obj -> table('goods')		//合计
							-> field('sum(g_peo) as sum_peo,sum(g_price) as sum_price,sum(g_num) as sum_num, sum(g_price*g_num) as sum')
							-> where( array('g_type' => $this->type,'g_date >= ' => $this->startdate,'g_date <= '=> $this->enddate) )
							-> find();
							
			$total = $obj -> table('goods')
				 -> where( array('g_type' => $this->type,'g_date >= ' => $this->startdate,'g_date <= '=> $this->enddate) )
				 -> total();	
						 
			if(!empty($list)){
				$curDate = '';
				foreach($list as $k => $bo) {	//循环数组，与上一天数据对比
					if($bo['g_code'] != '0'){	//已经比较过，直接跳过
						continue;
					}
					
					$preDay = $obj -> table('goods')
								   -> where(array('g_type' => $this->type, 'g_ids' => $bo['g_ids'], 'g_price' => $bo['g_price'], 'g_date' => date("Y-m-d",strtotime('-1 day'.$bo['g_date']))))
								   -> select();
					
					
								   
					if(empty($preDay)){			//没有上一天记录，跳过	
						continue;
					}

					
					if($bo['g_date'] != $curDate){
						$preSum = $obj -> table('goods')		//前天合计
							   -> field('sum(g_peo) as sum_peo,sum(g_price) as sum_price,sum(g_num) as sum_num, sum(g_price*g_num) as sum')
							   -> where(array('g_type' => $this->type, 'g_date' => date("Y-m-d",strtotime('-1 day'.$bo['g_date']))))
							   -> find();
						$curDate = $bo['g_date'];
					}
								   
					$peoFlag = 0;		//购买人数比较(0:无变化；1：升；2：降)
					$numFlag = 0;		//数量
					$numComFlag = 0;	//数量比
					$sumFlag = 0;		//总价
					$sumComFlag = 0;	//总价比
					
					
					$peoS = 0;			// 购买人数数
					$numS =	0;			//数量数
					$numComS = 0;		//数量比
					$sumS = 0;			//总价数
					$sumComS = 0;		//总价比
					
					$cnumComS = 0; 		//当前日数量比
					$csumComS = 0;		//当前日总价比
					
					foreach($preDay as $day){	//多个取总数
						$peoS += $day['g_peo'];
						$numS += $day['g_num'];
						$sumS += intval($day['g_price']) * intval($day['g_num']);	
					}
					
					if($preSum['sum_num'] != 0){
						$numComs = floatval($numS) / floatval($preSum['sum_num']);
					}
					if($preSum['sum'] != 0){
						$sumComs = floatval($sumS) / floatval($preSum['sum']);
					}
					
					if($sumList['sum_num'] != 0){
						$cnumComS = floatval($bo['g_peo']) / floatval($sumList['sum_num']); 
					}
					if($sumList['sum'] != 0){	
						$csumComS = floatval(intval($bo['g_price']) * intval($bo['g_num'])) / floatval($sumList['sum']);
					}
					
					if($bo['g_peo'] > $peoS){
						$peoFlag = 1;	//升
					}else if($bo['g_peo'] < $peoS){
						$peoFlag = 2;	//降
					}
					
					if($bo['g_num'] > $numS){
						$numFlag = 1;	
					}else if($bo['g_num'] < $numS){
						$numFlag = 2;	
					}
					
					if($cnumComS > $numComS){
						$numComFlag = 1;	
					}else if($cnumComS < $numComS){
						$numComFlag = 2;	
					}
					
					if(intval($bo['g_price']) * intval($bo['g_num']) > $sumS){
						$sumFlag = 1;	
					}else if(intval($bo['g_price']) * intval($bo['g_num']) < $sumS){
						$sumFlag = 2;	
					}
					

					if($csumComS > $sumComS){
						$sumComFlag = 1;	
					}else if($csumComS < $sumComS){
						$sumComFlag = 2;	
					}
					
					
					$result = $peoFlag.$numFlag.$numComFlag.$sumFlag.$sumComFlag;
					
					$list[$k]['g_code'] = $result;
					
					$obj -> table('goods') -> where('g_id = '.$bo['g_id']) -> update(array('g_code' => $result));
				}
			}
			
		} else {	//选择时间区间
			$list = $obj -> table('goods')
						 -> field("g_date, g_price, g_ids, sum(g_peo) as g_peo, sum(g_num) as g_num")	
						 -> where(array('g_type' => $this->type, 'g_date >= ' => $this->startdate,'g_date <= '=> $this->enddate) )
						 -> group(' g_ids,g_price ')
						 ->	limit(intval(($this->curPage - 1) * $this->pageSize),intval($this->pageSize) )
						 -> select();
			
			$listAll = $obj -> table('goods')
						 -> field("'".$this->startdate.'~'.$this->enddate."' as g_date, g_price, g_ids, sum(g_peo) as g_peo, sum(g_num) as g_num")	
						 -> where(array('g_type' => $this->type, 'g_date >= ' => $this->startdate,'g_date <= '=> $this->enddate) )
						 -> group(' g_ids,g_price ')
						 -> select();
						 
			
			$sumList = array();	
			if (is_array($listAll)) {	
				$sum_peo = 0;		//购买人数总数
				$sum_num = 0;		//数量总数
				$sum = 0;			//总价总数
				foreach ($listAll as $bo) {
					$sum_peo += $bo['g_peo'];
					$sum_num+= $bo['g_num'];
					$sum +=	 $bo['g_num'] * $bo['g_price'] ;	
				}
				
				$sumList['sum_peo'] = $sum_peo;
				$sumList['sum_num'] = $sum_num;
				$sumList['sum'] = $sum;
				
				$total = count($listAll);	
			}	
					 			
		}
		
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'pageAjax','go','page');
		$pageHtml = $page->getPageHtml();
		
		$point = D("game_info");
		$goods = $point -> table('goods_detail') -> select();
		$goods_arr = array();
		foreach($goods as $val) {
			$goods_arr[$val['g_code']] = $val['g_name'];
		}
		
		$result = array(
					'list' => $list,
					'pageHtml'=> $pageHtml,
					'sumList' => $sumList,
					'good_list' => $goods_arr
				);
				
		echo json_encode($result);
		exit;		
	}
	
	
	/**
	 * FunctionName: getSumGoods
	 * Description: 获取每天商城消费情况
	 * Author: （jan）						
	 * Date: 2013-9-6 15:58:20	
	 **/
	public function getSumGoods(){
		$obj = D(GNAME.$this -> ip);
		
		$total = 0;
		$SIdSql = ' s_id in (select max(s_id) from sumgoods where s_type="'.$this->type.'"group by s_date)';//去重查询
		$TimeSql = ' and s_date >= "'.$this->startdate.'" and s_date <= "'.$this->enddate.'"';//时间查询
		$list_sql = $SIdSql.$TimeSql;
		//去除重复获取最新数据
		$list = $obj -> table('sumgoods')
					 -> where($list_sql)
					 ->	limit( intval(($this->curPage - 1) * $this->pageSize),intval($this->pageSize) )
					 -> select();
		
		$sumList = $obj -> table('sumgoods')	//合计
						-> field('sum(s_peo) as sum_peo,sum(s_num) as sum_price, sum(s_total) as sum')
					    -> where($list_sql)
						-> find();
		
		if(!empty($list)){
			foreach($list as $k => $bo) {	//循环数组，与上一天数据对比
				if($bo['s_code'] != '0'){	//已经比较过，直接跳过
					continue;
				}
				
				$preDay = $obj -> table('sumgoods')
							   -> where($SIdSql.' and s_date = "'.date("Y-m-d",strtotime('-1 day'.$bo['s_date'])).'"')
							   -> find();
				
				if(empty($preDay)){		//没有上一天记录，跳过	
					continue;
				}
				
				$peoFlag = 0;	//本日购买人数比较(0:无变化；1：升；2：降)
				$numFlag = 0;	//本日购买数量
				$sumFlag = 0;	//本日消费总额
				$sumComFlag = 0;	//消费占比

				if($bo['s_peo'] > $preDay['s_peo']){
					$peoFlag = 1;	//升
				}else if($bo['s_peo'] < $preDay['s_peo']){
					$peoFlag = 2;	//降
				}
				
				if($bo['s_num'] > $preDay['s_num']){
					$numFlag = 1;	
				}else if($bo['s_num'] < $preDay['s_num']){
					$numFlag = 2;	
				}
				
				if($bo['s_total'] > $preDay['s_total']){
					$sumFlag = 1;	
				}else if($bo['s_total'] < $preDay['s_total']){
					$sumFlag = 2;	
				}
				
				$result = $peoFlag.$numFlag.$sumFlag.$sumComFlag;
				
				$list[$k]['s_code'] = $result;
				
				$obj -> table('sumgoods') -> where('s_id = '.$bo['s_id']) -> update(array('s_code' => $result));
			}
		}else{
			$list = array();
		}
		
		if( empty($sumList) ){
			$sumList = array();
		}				
	
		$total = $obj -> table('sumgoods')
					 //-> where( array('s_type' => $this->type, 's_date >= ' => $this->startdate,'s_date <= '=> $this->enddate) )
					 -> where($list_sql)
					 -> total();	
					  
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'pageAjax2','go2','page2');
		$pageHtml = $page->getPageHtml();			 
		
		$result = array(
					'list' => $list,
					'pageHtml' => $pageHtml,
					'sumList' => $sumList 		
				);
				
		echo json_encode($result);
		exit;
		
	}
	
	/**
	 * 获取商城消费每天物品的消费数据，用作图表展示
	 */
	public function getChartData() {
		$obj = D(GNAME.$this -> ip);
		$result = array();				//返回的结果集
		//$arr_by_date = array();		//以日期为键值组装数据
		
		$list = $obj -> table('goods')
					 -> where( array('g_type' => $this->type, 'left(g_date,10) >= ' => $this->startdate,'left(g_date,10) <= '=> $this->enddate) )
					 -> order('g_date asc')
					 -> select();
					 
					 
		if($list != '') {
			foreach($list as $item) {
				if(isset($result[$item['g_date']][$item['g_ids']])) {	//存在则累加
					$result[$item['g_date']][$item['g_ids']] += $item['g_num'];
				} else {												//不存在则初始化		
					$result[$item['g_date']][$item['g_ids']] = $item['g_num'];
				}
			}
			
			
			foreach($result as $date => $item) {						//计算百分比小于1%的归类为其他
				$sum  = 0; 												//日期对应的商品总数
				foreach($item as $good) {
					$sum += $good;
				}
				if($sum != 0) {
					foreach($item as $id => $good) {
						if($good/$sum < 0.01) {
							if(isset($result[$date]['other'])) {
								$result[$date]['other'] += $good;
							} else {
								$result[$date]['other'] = $good;
							}
							unset($result[$date][$id]);
						}
					}
				}
			}
		}
		
		$point = D("game_info");					//获取物品名称
		$goods = $point -> table('goods_detail') -> select();
		$goods_arr = array();
		foreach($goods as $val) {
			$goods_arr[$val['g_code']] = $val['g_name'];
		}
		$goods_arr['other'] = '其他';
		
		echo json_encode(array(
				'result' => $result,
				'goods_arr' => $goods_arr
			));
	}

}
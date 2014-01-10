<?php
/**
 * FileName: propanalysis.class.php
 * Description:道具消耗分析
 * Author: jan
 * Date:2013-11-24 18:20:59
 * Version:1.00
 */
class propanalysis{
	/**
	 * 服务器IP
	 * @var string
	 */
	public $ip;
	
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
			if(!in_array('00401400', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
		$this->pageSize = get_var_value('pageSize') == NULL? 10: get_var_value('pageSize');
		$this->curPage =  get_var_value('curPage') == NULL? 1 : get_var_value('curPage');
		
	}
	
	/**
	 * 获取道具消耗数据
	 */
	public function getprop() {
		$ip = get_var_value('ip');
		$obj = D('game'.$ip);
		$point = D('game_base');
		$base = $point -> table('servers')->field('s_name') -> where(array('s_id'=>$ip)) -> find();
		//开服时间
		$listdate = $obj -> table('item') -> field('i_date') -> order('i_date asc') -> limit(0,1) -> find();
		$list_date = isset($listdate['i_date'])? date('Y-m-d',strtotime($listdate['i_date'])) : date("Y-m-d",strtotime("-7 day"));//如果表里没数据 默认7天前
		$startdate = get_var_value('startdate') == NULL? $list_date : get_var_value('startdate');
		$enddate = get_var_value('enddate') == NULL? date('Y-m-d'):get_var_value('enddate');
		$ipList = autoConfig::getIPS();		//获取服务器信息
		
		$wherelist = array(
							'i_date >='=>$startdate,
							'i_date <='=>$enddate
							);
		$salelist = $obj ->table('item')
					 ->field('i_shopid,sum(i_num)as sum_num,i_price,i_dtype')
					 ->where($wherelist)
					 ->group('i_price,i_shopid')
					 ->order('i_date desc')
					 ->select();
		$goods = $obj ->table('goods_detail')
					  ->select();
		$goods_arr = array();
		// foreach($goods as $val) {
			// $goods_arr[$val['gid']] = $val['name'];
		// }
		$list = array();
		foreach($salelist as $key => $value){
			$list[$key]['stype'] = '49you';
			$list[$key]['db'] = $base['s_name'];
			if($salelist[$key]['i_shopid'] = $goods[$key]['t_code']){
				$list[$key]['gname'] = $goods[$key]['t_name'];
			}
			$list[$key]['num'] = $salelist[$key]['sum_num'];
			$list[$key]['total'] = $salelist[$key]['sum_num']*$salelist[$key]['i_price'];
		}
		echo json_encode(array(/*'pageHtml'=>$pageHtml,*/'list'=>$list,'startDate'=>$startdate));
	}
	
	/**
	 * 导出excel
	 */
	public function writeExcel(){
		$ip = get_var_value('ip');
		$obj = D('game_info');
		
		$listdate = $obj -> table('prop_list') -> field('createtime') -> order('p_id asc') -> limit(0,1) -> find();
		$list_date = isset($listdate['createtime'])? date('Y-m-d',strtotime($listdate['createtime'])) : date("Y-m-d",strtotime("-7 day"));//如果表里没数据 默认7天前
		$startdate = get_var_value('startdate') == NULL? $list_date : get_var_value('startdate');
		$enddate = get_var_value('enddate') == NULL? date('Y-m-d'):get_var_value('enddate');
		
		$listsql = "select p_plat,p_sever,p_name,sum(p_num) num,sum(p_money) money from prop_list where createtime >= '".$startdate."' and createtime <='".$enddate."' group by p_name order by num desc";
		$list = $obj->fquery($listsql);
		require_once(AClass.'phpexcel/PHPExcel.php');
		
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("PHPExcel Test Document")
							 ->setSubject("PHPExcel Test Document")
							 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("Test result file");
							 
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '平台');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '游戏平台');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '道具名称');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '消耗数量');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '消耗元宝');
		
		if (is_array($list)) {
			foreach($list as $k => $item){
			
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $item["p_plat"]);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["p_sever"]);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["p_name"]);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["num"]);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["money"]);
			}	
		}	

		$objPHPExcel->getActiveSheet()->setTitle('Simple');
		$objPHPExcel->setActiveSheetIndex(0);
		$file_name = "道具消耗分析_".date('Y_m_d H_i_s');
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;

	}
	
}
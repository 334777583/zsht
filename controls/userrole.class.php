<?php
/**
 * FileName: userrole.class.php
 * Description:创角分析
 * Author: xiaochengcheng,tanjianchengcc@gmail.com,hjt
 * Date:2013-9-24 18:20:59
 * Version:1.00
 */
class userrole{
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
			if(!in_array('00400700', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	/**
	 * 获取创角分析数据
	 */
	public function getRole() {
		$ip = get_var_value('ip');
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		
		$point = D(GNAME.$ip);
		$listdate = $point -> table('createplay') 
						   -> field('c_date')
						   -> order('c_date asc')
						   -> limit(0,1)
						   -> find();
		
		$list_date = isset($listdate['c_date'])?$listdate['c_date']:date('Y-m-d',strtotime('-7 day'));
		$startdate = get_var_value('startdate') == NULL?$list_date:get_var_value('startdate');
		
		if($ip && $startdate && $enddate) {	
			$list = $point -> table('createplay') 
						   -> where('c_date >= "'.$startdate .'" and c_date <= "'.$enddate.'"') 
						   -> order('c_date desc')
						   ->select();
			if($list != '') {
				foreach($list as $k => $item) {
					if($item['c_enter'] != 0 ){
						$cjv = $item['c_csuccess']/$item['c_enter'];							//创角率
						if($cjv < 0) {															//负数设为0
							$cjv = 0;
						}
					}else {
						$cjv = 0;
					}
					if($item['c_csuccess'] != 0) {
						$load = ($item['c_csuccess']-$item['c_entergame'])/$item['c_csuccess'];	 //流失率
						if($load < 0) {
							$load = 0;
						}
					}else {
						$load = 0;
					}
					
					$list[$k]['c_cjv'] = sprintf('%0.2f',$cjv*100).'%';
					$list[$k]['c_load'] = sprintf('%0.2f',$load*100).'%';
				}
			}
			
			$json = array(
				'result' => $list,
				'startDate' => $startdate,
				'endDate' => $enddate
			);
			
			echo json_encode($json);
			exit;
		} else {
			echo '1';
		}
	}
	
	/**
	 * 导出excel
	 */
	public function writeExcel(){
		$ip = get_var_value('ip');
		$point = D(GNAME.$ip);
		
		$listdate = $point -> table('createplay') -> field('c_date') -> order('c_id asc') -> limit(0,1) -> find();
		$list_date = isset($listdate['c_date'])? date('Y-m-d',strtotime($listdate['c_date'])) : date("Y-m-d",strtotime("-7 day"));//如果表里没数据 默认7天前
		$startdate = get_var_value('startdate') == NULL? $list_date : get_var_value('startdate');
		
		$enddate = get_var_value('enddate')? date('Y-m-d'):get_var_value('endDate');
		
		$list = $point-> table("createplay")->order('c_date asc') -> where(array('c_date >='=>$startdate,"c_date <="=>$enddate))->select();
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '日期');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '平台成功跳转数');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '平台失败跳转数');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '进入创建角色页面数');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '创建角色成功数');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', '创建角色成功进入游戏数');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', '创角率');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'loading流失率');
		
		if (is_array($list)) {
			foreach($list as $k => $item){
				if($item['c_enter'] != 0 ){
					$cjv = $item['c_csuccess']/$item['c_enter'];							//创角率
					if($cjv < 0) {															//负数设为0
						$cjv = 0;
					}
			}else {
				$cjv = 0;
			}
			if($item['c_csuccess'] != 0) {
				$load = ($item['c_csuccess']-$item['c_entergame'])/$item['c_csuccess'];	 //流失率
				if($load < 0) {
					$load = 0;
				}
			}else {
				$load = 0;
			}
			$item['c_cjv'] = sprintf('%0.2f',$cjv*100).'%';
			$item['c_load'] = sprintf('%0.2f',$load*100).'%';
		
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $item["c_date"]);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["c_login_suc"]);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["c_login_fai"]);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["c_enter"]);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["c_csuccess"]);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["c_entergame"]);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["c_cjv"]);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["c_load"]);
			}	
		}	

		$objPHPExcel->getActiveSheet()->setTitle('Simple');
		$objPHPExcel->setActiveSheetIndex(0);
		$file_name = "创角分析_".date('Y_m_d H_i_s');
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;

	}
	
}
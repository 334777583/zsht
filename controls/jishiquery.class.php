<?php
/**
 * FileName: jishiquery.class.php
 * Description:即时查询
 * Author: hjt
 * Date:2013-9-2 11:02:23
 * Version:1.00
 */
class jishiquery{
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
			if(!in_array('00100200', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	/**
	 * @name: jishi
	 * @description: 即时查询
	 * @param: null
	 * @return: null
	 * @author: hjt
	 * @create: 2013-9-2 11:02:23
	**/
	public function jishi(){
		$rate = 10; 		//货币与元宝的比例
		
		$ip = get_var_value('ip');
		$startDate = get_var_value('startDate');
		$endDate = get_var_value('endDate');
		$code = get_var_value('code');
		$orderKey = get_var_value('orderKey');
		$key = get_var_value('key');
		$p_result = get_var_value('result') == NULL ? 1 : get_var_value('result');
		$pageSize = get_var_value('pageSize') == NULL ? 10 : get_var_value('pageSize');
		$curPage = get_var_value('curPage') == NULL ? 1 : get_var_value('curPage');
		
		$result = array();////统计结果
		
		if($ip) {
			global $chongzhi;

			list($sip) = autoConfig::getServer($ip);//获取服务器信息

			$obj = F($chongzhi[$sip]['db'], $chongzhi[$sip]['ip'], $chongzhi[$sip]['user'], $chongzhi[$sip]['password'], $chongzhi[$sip]['port']);
			
			$gamedb = D("game_info");
			
			$baoshi = $gamedb->table('servers')->field('s_biaoshi')->where(array('s_id'=>$ip))->find();
			if(!$obj){
				echo json_encode(array(
					'error' => '数据库连接失败！'
				));
				exit;
			}else{
				$where_sql = "";
				$isset_table = '';//判断表是否存在
				
				
				$p_result = 1;//默认只查询订单成功的
				switch($p_result){
					case 1:
						$where_sql .= "t_status = 3 and t_ser = '" .$baoshi['s_biaoshi']."'";//订单成功
						break;
					case 2:
						$where_sql .= "t_status < 3 and t_ser = '" .$baoshi['s_biaoshi']."'";//订单失败
						break;
				}
				
				if($startDate) {//开始日期
					$startDate = $startDate.' 00:00:00';
					$where_sql .= "t_creattime >= '" . $startDate . "' and ";
				}
				if($endDate) {//结束日期
					$endDate = $endDate.'23:59:59';
					$where_sql .= "t_creattime <='" . $endDate . "' and ";	
				}
				if($code && $key) {//查询类型
					switch($code ) {
						case 1 : $where_sql .= "t_account = '" . $key . "' and ";break;
						case 3 : $where_sql .= "t_uid = '" . $key . "' and ";break;
					}
				}
				if($orderKey) {//订单号
					$where_sql .= "t_order = '" . $orderKey . "' and ";
				}
				
				$where_sql = rtrim($where_sql, ' and ');
				$table = 'temp_pay';
				$isset_table = $this -> isset_table($ip,$table);
				if(!$isset_table){//判断表是否存在
					echo json_encode(array(
						'error' => '数据表不存在',
					));
					exit;
				}
				$total = $obj -> table($table) -> where($where_sql)  -> total();
				if($total > 0){//如果补单临时表 存在 就获取补单临时表数据
					$list = $obj -> table($table) 
								-> field('t_id,t_order,t_account,t_uid,t_creattime,(t_money * '.$rate.') as t_rate,t_money,t_pt')
								-> where($where_sql) 
								-> limit(intval(($curPage-1)*$pageSize),intval($pageSize)) 
								-> order('p_creatdate desc') 
								-> select();
				}else{//不存在就获取记录表数据
					$table = 'history_pay';
					$isset_table = $this -> isset_table($ip,$table);
					if(!$isset_table){//判断表是否存在
						echo json_encode(array(
							'error' => '数据表不存在',
						));
						exit;
					}
					//输出数据
					$list = $obj -> table($table) 
								-> field('t_id,t_order,t_account,t_uid,t_creattime,(t_money * '.$rate.') as t_rate,t_money,t_pt')
								-> where($where_sql) 
								-> limit(intval(($curPage-1)*$pageSize),intval($pageSize)) 
								-> order('t_creattime desc') 
								-> select();
					//excel数据		
					$excel_list =  $obj -> table($table) 
								-> field('t_id,t_order,t_account,t_uid,t_creattime,(t_money * '.$rate.') as t_rate,t_money,t_pt')
								-> where($where_sql) 
								-> order('t_creattime desc') 
								-> select();
					
					$total = $obj -> table($table) -> where($where_sql)  -> total();
				}
			}
			
			$filename = '';
			if(isset($list) && $total > 0){
				//封装数据
				$result = $list;
				$excel = $excel_list;//excel 数据
				$excel_count = count($excel);
				
				$excel_array = array();
				for($i = 0 ;$i < $excel_count ; $i++){				
					$excel_array = $excel;
					if(($i % 1000) == 0){
						//写入缓存目录
						$tmpfname = tempnam('/tmp','ASDFGHJKEWRTYUI');
						$handle = fopen($tmpfname, "w");
						fwrite($handle, json_encode($excel_array));
						$excel_array = $excel;
					}
				}
				$filename = base64_encode($tmpfname);
				fclose($handle);
			}
			
			$page = new autoAjaxPage($pageSize, $curPage, $total, "formAjax", "go","page");
			$pageHtml = $page->getPageHtml();
			
			echo json_encode(array(
					'result' => $result,
					'pageHtml' => $pageHtml,
					'filename' => $filename
				));
		}else {
			echo '1';
		}
	}
	
	/**
	 * @name: isset_table
	 * @description: 判断表是否存在
	 * @param: $ip	 服务器id
	 * @param: $table 表名
	 * @return: boolean
	 * @author: hjt
	 * @create: 2013-9-9 10:53:59
	**/
	private function isset_table($ip,$table){
		global $chongzhi;
		// print_r($chongzhi);
		$isset_table = '';
		list($sip) = autoConfig::getServer($ip);//获取服务器信息
		// print_r($chongzhi[$sip]);
		$obj = F($chongzhi[$sip]['db'], $chongzhi[$sip]['ip'], $chongzhi[$sip]['user'], $chongzhi[$sip]['password'], $chongzhi[$sip]['port']);
		
		if(!$obj){//连接失败
			return false;
		}else{
			$isset_table = $obj -> rquery('select count(*) from '.$table);
			if($isset_table){//存在
				return true;
			}else{//不存在
				return false;
			}
		}
	}
	
	/**
	 * @name: excel
	 * @description: 导出excel
	 * @param: null
	 * @return: http1.1
	 * @author: hjt
	 * @create: 2013-9-2 11:02:23
	**/
	public function excel(){
		$f = base64_decode($_GET['f']);
		if(!is_file($f)){
			echo 'error';
			exit();
		}
		
		$list = json_decode(file_get_contents($f),true);
		
		if(!empty($list)){

			require_once(AClass.'phpexcel/PHPExcel.php');
			
			$objPHPExcel = new PHPExcel();
			
			$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
								 ->setLastModifiedBy("Maarten Balliauw")
								 ->setTitle("PHPExcel Test Document")
								 ->setSubject("PHPExcel Test Document")
								 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
								 ->setKeywords("office PHPExcel php")
								 ->setCategory("Test result file");
								 
			$objPHPExcel->setActiveSheetIndex(0);	
			$objPHPExcel->getActiveSheet()->setCellValue('A1', '订单号'); 
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '账号');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', '角色ID');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', '充值时间');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '充值获得元宝');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '货币');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '充值渠道');
			
			$DataType = PHPExcel_Cell_DataType::TYPE_STRING;//科学型 改成字符串型
			
			if (is_array($list)) {
				foreach($list as $k => $item){
					//$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $item["t_order"]); //订单号
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["t_order"],$DataType); //订单号
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["t_account"]);//账号
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["t_uid"]);//角色ID
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["t_creattime"]);//充值时间
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["t_rate"]);//充值获得元宝
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["t_money"]);//货币
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["t_pt"]);//充值渠道
				}	
			}	

			$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "即时查询_".date('Y_m_d H_i_s');
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		exit;

		}
	}
}
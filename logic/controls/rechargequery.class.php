<?php
/**
 * FileName: rechargequery.class.php
 * Description:充值查询(添加excel导出功能)(添加订单状态查询)
 * Author: xiaochengcheng,tanjianchengcc@gmail.com,hjt
 * Date:2013年9月12日14:50:29
 * Version:1.03
 */
class rechargequery{
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
			if(!in_array('00100100', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	/**
	 * 获取充值记录
	 */
	public function getRecords(){
		$rate = 10; 		//货币与元宝的比例
		
		$ip = get_var_value('ip');
		$startDate = get_var_value('startDate');
		$endDate = get_var_value('endDate');
		$code = get_var_value('code');
		$orderKey = get_var_value('orderKey');
		$key = get_var_value('key');
		$p_result = get_var_value('result') == NULL ? 1 : get_var_value('result');//订单状态
		$pageSize = get_var_value('pageSize') == NULL ? 10 : get_var_value('pageSize');
		$curPage = get_var_value('curPage') == NULL ? 1 : get_var_value('curPage');
		
		
		if($ip) {
			$obj = D(GNAME.$ip);
			
			$where_sql = "";
			
			switch($p_result){
				case 1:
					$where_sql .= "p_result = 1 and ";//订单成功
					break;
				case 2:
					$where_sql .= "p_result != 1 and ";//订单失败
					break;
			}
			
			if($startDate) {
				$where_sql .= "left(p_creatdate,10) >= '" . $startDate . "' and ";
			}
			if($endDate) {
				$where_sql .= "left(p_creatdate,10) <='" . $endDate . "' and ";	
			}
			if($code && $key) {
				switch($code ) {
					case 1 : $where_sql .= "p_acc = '" . $key . "' and ";break;
					case 3 : $where_sql .= "p_playid = '" . $key . "' and ";break;
				}
			}
			if($orderKey) {
				$where_sql .= "p_order = '" . $orderKey . "' and ";
			}
			
			$where_sql = rtrim($where_sql, ' and ');
			//$ListSql = 'select u.u_id,u.u_user,p.member_name,u.u_ip from y_user u inner join y_user_permission p on u.u_allow = p.id';
			// $usql = "select p.p_id,p.p_order,p.p_acc,p.p_playid,p.p_creatdate,(p.p_money * '".$rate."') as p_rate,p.p_money,p.p_pt,o.o_user,o.o_userid from pay_detail p inner join online_sec o on p.p_playid = o.o_userid where ".$where_sql." order by p.p_creatdate desc limit " .intval(($curPage-1)*$pageSize).",".intval($pageSize);
			// echo $usql;
			//输出数据
			//$list = $obj ->fquery($usql);
			$list = $obj -> table('pay_detail') 
						 -> field('p_id,p_order,p_acc,p_playid,p_creatdate,(p_money * '.$rate.') as p_rate,p_money,p_pt')
						 -> where($where_sql) 
						 -> limit(intval(($curPage-1)*$pageSize),intval($pageSize)) 
						 -> order('p_creatdate desc') 
						 -> select();
			$name = $obj ->table('online_sec')
						 ->field('o_userid,o_user')
						 ->select();
			//excel 数据
			$excel_list = $obj -> table('pay_detail') 
						 -> field('p_id,p_order,p_acc,p_playid,p_creatdate,(p_money * '.$rate.') as p_rate,p_money,p_pt')
						 -> where($where_sql) 
						 -> order('p_creatdate desc') 
						 -> select();
			
			$total = $obj -> table('pay_detail') -> where($where_sql)  -> total();
			
			$filename = '';
			if(isset($list) && count($list) > 0){
				//封装数据
				$result = $list;//输出数据
				foreach($result as $key => $value){
					if($result[$key]['p_playid'] = $name[$key]['o_userid']){
					$result[$key]['user'] = $name[$key]['o_user'];
					}
				}
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
					//'name' => $name,
					'filename' => $filename,
					'pageHtml' => $pageHtml
				));
		}else {
			echo '1';
		}
	}
	
	/**
	* FunctionName: getd
	* Description: 付费分析
	* Author: Kim
	* Date:2013-8-16 11:25:16
	*/
	public function getd(){
		$ip = get_var_value('ip');
		$endDate = get_var_value('enddate');
		$startdate = get_var_value('startdate');
		if(!$ip || !$endDate) {
			die('error');
		}//2013-08-14 10:01:15
		
		$str = 'p_creatdate <"'.$endDate.' 23:59:59" AND p_creatdate >"'.$startdate.' 00:00:00"';
		$point = D(GNAME.$ip);
		// $big_arr  = $point -> table('pay_detail') -> where('p_result=0 AND '.$str) -> select();
		// if(count($big_arr) > 1){
			// $sql = 'SELECT * from (SELECT count(p_id) as b from pay_detail where '.$str.' GROUP BY p_playid) as a where a.b>3';
			// $arr_num = $point -> fquery($sql);
			// if(count($arr_num) > 1){
				// $num = count($arr_num);	//活跃用户数
			// }else{
				// $num = 0;
			// }
			// $count_num = 0;	//付费用户
			// $sum = 0 ; //金额总数
			// foreach($big_arr as $key => $val){
				// $count_num++;
				// $sum += $val['p_money'];
			// }
		// }
		
		$big_arr  = $point -> table('pay_detail') -> where('p_result=0 AND '.$str) -> select();
		if(count($big_arr) > 1){
			$count_num = 0;	//付费总人数
			$sum = 0 ; //金额总数
			$arr = array();
			foreach($big_arr as $key => $val){
				if(!in_array($val['p_playid'], $arr)){
					$arr[] = $val['p_playid'];
				}
				$count_num++;
				$sum += $val['p_money'];
			}
			$arpu = round($sum / $count_num ,2); 
			echo json_encode(array('num'=>$count_num , 'qnum' =>count($arr) ,'sum'=>$sum , 'arpu'=>$arpu));
			unset($arr);
		}else{
			echo '0';
		}
	}
	
	/**
	 * @name: excel
	 * @description: 导出excel
	 * @param: null
	 * @return: http1.1
	 * @author: hjt
	 * @create: 2013-9-4 15:02:23
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
					//$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $item["p_order"]); //订单号
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["p_order"],$DataType); //订单号
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["p_acc"]);//账号
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["p_playid"]);//角色ID
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["p_creatdate"]);//充值时间
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["p_rate"]);//充值获得元宝
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["p_money"]);//货币
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["p_pt"]);//充值渠道
				}	
			}

			$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "充值查询_".date('Y_m_d H_i_s');
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
			exit;

		}
	}
}
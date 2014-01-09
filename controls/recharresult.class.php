<?php
/**
 * FileName: recharResult.class.php
 * Description:渠道
 * Author: xiaoliao
 * Date:2013-9-5 14:54:08
 * Version:1.02
 */
class recharresult{
	/**
	 * 登录用户信息
	 */
	private $user;
	private $ip;
	private $date;

	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00400400', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
		$this->ip =  get_var_value("ip") == NULL?-1:get_var_value("ip");
		$this->date = get_var_value("startdate") == NULL?date("Y-m-d",strtotime("-14 day")):date("Y-m-d",strtotime(get_var_value("startdate")));

	}
	
	/**
	 * 获取充值统计
	 */

	private function readlOG($sip){
		error_reporting(0);
		global $t_conf,$chongzhi;
		$sc = '';
		if ($sip == 4) {
			$sc = 'S1';
		}else if($sip == 10){
			$sc = 'S2';
		}elseif($sip == 11){
			$sc = 'S3';
		}
		
		$startDate = strtotime($_POST['startDate']);
		$endDate = strtotime($_POST['endDate'])+(3600*24);
		$point = F($t_conf['qd1']['db'], $t_conf['qd1']['ip'], $t_conf['qd1']['user'], $t_conf['qd1']['password'], $t_conf['qd1']['port']);
		
		list($sip) = autoConfig::getServer($sip);//获取服务器信息
		
		$obj = F($chongzhi[$sip]['db'], $chongzhi[$sip]['ip'], $chongzhi[$sip]['user'], $chongzhi[$sip]['password'], $chongzhi[$sip]['port']);
		
		$point->fquery("DELETE FROM tmp_chongzhi");
		
		$result2 =  $point->fquery("SELECT subtype,name FROM account_data_test1");

		$arr_name = array();	//获取用户表称
		foreach ($result2 as $key => $value) {
			$arr_name[] = $value['name'];
		}
		$result1 = $obj->fquery("SELECT t_uid,t_money,t_account,t_status,t_acctime FROM history_pay WHERE t_ser='{$sc}' AND t_acctime BETWEEN {$startDate} AND {$endDate}");
		
		$result3 = array();

		foreach ($result2 as $key => $value) {
			foreach ($result1 as $k => $v) {
				if ($result2[$key]['name'] == $result1[$k]['t_account']) {
					$v['subtype'] = $result2[$key]['subtype'];
					$result3[] = $v;
				}
			}
		}

		$mysql = "INSERT INTO tmp_chongzhi(subtype,money,name,t_status,time) VALUES";
		$myarr = array();
		
		foreach ($result3 as $key => $value) {
			$myarr[] ="({$value['subtype']},{$value['t_money']},{$value['t_account']},{$value['t_status']},{$value['t_acctime']})";
		}
		
		$mysqlstr = $mysql.implode(',', $myarr);
		
		$point->fquery($mysqlstr);
	}


	public function getstartTime(){
		$obj1 = D(GNAME.$_POST['sip']);
		$listdate = $obj1 -> table("user") -> field('u_date') -> order('u_date asc')-> limit(0,1) ->find();
		echo substr($listdate['u_date'], 0,-9);
	}

	public function getResult(){
		$sip = $_POST['ip'];
		$this->readlOG($sip);
		error_reporting(0);

		$obj1 = D(GNAME.$this->ip);
		$sc = '';
		if ($sip == 4) {
			$sc = 'S1';
		}else if($sip == 10){
			$sc = 'S2';
		}elseif($sip == 11){
			$sc = 'S3';
		}
		//查出最初开服时间
		$listdate = $obj1 -> table("user") -> field('u_date') -> order('u_date asc')-> limit(0,1) ->find();
		$list_date = isset($listdate['u_date'])?date('Y-m-d',strtotime($listdate['u_date'])):date("Y-m-d",strtotime("-14 day"));//如果没数据时间默认14天前

		$this -> date = get_var_value("startdate") == NULL?$list_date:date("Y-m-d",strtotime(get_var_value("startdate")));

		$start = strtotime($this->date) + 86400;
		$end = strtotime($this->date) + 14*86400;
		
		$startdate = date('Y-m-d', $start);					//第二天算起
		$enddate = date('Y-m-d', $end);						//第十四天

		global $t_conf,$chongzhi;
		$startDate = strtotime($_POST['startDate']);
		$endDate = strtotime($_POST['endDate']);
		$point = F($t_conf['qd1']['db'], $t_conf['qd1']['ip'], $t_conf['qd1']['user'], $t_conf['qd1']['password'], $t_conf['qd1']['port']);

		list($sip) = autoConfig::getServer($sip);//获取服务器信息
		$obj = F($chongzhi[$sip]['db'], $chongzhi[$sip]['ip'], $chongzhi[$sip]['user'], $chongzhi[$sip]['password'], $chongzhi[$sip]['port']);
		
		$time1 = strtotime('2013-12-17');
		$time2 = strtotime('2013-12-18');
		$result1 = $obj->fquery("SELECT t_account, t_money FROM history_pay WHERE t_ser = '{$sc}' AND t_status>2 AND t_acctime > '{$time1}' AND t_acctime <= '{$time2}'");
		$result2 =  $point->fquery("SELECT subtype,name FROM account_data_test1");
		$result3 = array();
		$point->fquery("DELETE FROM firstresult");
		foreach ($result1 as $key => $value) {
			foreach ($result2 as $k => $v) {
				if ($value['t_account'] == $v['name']) {
					$value['subtype'] = $v['subtype'];
					$result3[] = $value;
				}
			}
		}
		$mysql1 = "INSERT INTO firstresult(name,subtype,money) VALUES";

		$ruku_arr = array();
		foreach ($result3 as $key => $value) {
			$ruku_arr []= "('{$value['t_account']}',{$value['subtype']},{$value['t_money']})";
		}

		$mysql = $mysql1.implode(',', $ruku_arr);
		$point->fquery($mysql);

		$create_num = $point->fquery("SELECT COUNT(id) cid,subtype FROM account_data_test1  GROUP BY subtype ORDER BY subtype ASC");

		$first_result = $point->fquery("SELECT COUNT(id) cid, SUM(money) cmoney,subtype FROM firstresult GROUP BY subtype");

		$total_result = $point->fquery("SELECT SUM(money) cmoney , COUNT(id) cid,time, subtype FROM tmp_chongzhi WHERE t_status>2 GROUP BY subtype ORDER BY subtype ASC");
		$Big_result = $point->fquery("SELECT SUM(money) cmoney , COUNT(id) cid,time, subtype FROM tmp_chongzhi GROUP BY subtype ORDER BY subtype ASC");
		
		
		$str = '';

		foreach ($create_num as $key => $value) {
				$str .= '<tr>';
				$str .= '<td>'.$value['subtype'].'</td>';

				if (!empty($first_result)) {
					if ($first_result[$key]['subtype'] == $value['subtype']) {
						$str .= '<td>'.$first_result[$key]['cmoney'].'</td>';
					}else{
						$str .= '<td> 0 </td>';
					}

					if ($first_result[$key]['subtype'] == $value['subtype']) {
						$str .= '<td>'.$first_result[$key]['cid'].'</td>';
					}else{
						$str .= '<td> 0 </td>';
					}

				}else{
					$str .= '<td> 0 </td><td> 0 </td>';
				}

				$str .= '<td>'.$value['cid'].'</td>';

				if (!empty($total_result)) {
					if ($total_result[$key]['subtype'] == $value['subtype']) {
						$str .= '<td>'.$total_result[$key]['cmoney'].'</td>';
					}else{
						$str .= '<td> 0 </td>';
					}
					if ($total_result[$key]['subtype'] == $value['subtype']) {
						$str .= '<td>'.$total_result[$key]['cid'].'</td>';
					}else{
						$str .= '<td> 0 </td>';
					}
				}else{
					$str .= '<td> 0 </td><td> 0 </td>';
				}

				if (!empty($Big_result)) {
					if ($Big_result[$key]['subtype'] == $value['subtype']) {
						$str .= '<td>'.round($total_result[$key]['cmoney']/$Big_result[$key]['cid'],2).'</td>';
					}else{
						$str .= '<td> 0 </td>';
					}

					if ($Big_result[$key]['subtype'] == $value['subtype']) {
						$str .= '<td>'.round(($total_result[$key]['cid']/$value['cid'])*100,2).' %</td>';
					}else{
						$str .= '<td> 0 </td>';
					}
				}else{
					$str .= '<td> 0 </td><td> 0 </td>';
				}
				

				$str .='</tr><br/>';
		}

			echo json_encode(array('str'=>$str,'date'=>$this->date));
	}

	

	public function writeExcel(){
		$sip = $_GET['ip'];
		$this->readlOG($sip);
		error_reporting(0);

		$obj1 = D(GNAME.$this->ip);
		//查出最初开服时间
		$listdate = $obj1 -> table("user") -> field('u_date') -> order('u_date asc')-> limit(0,1) ->find();
		$list_date = isset($listdate['u_date'])?date('Y-m-d',strtotime($listdate['u_date'])):date("Y-m-d",strtotime("-14 day"));//如果没数据时间默认14天前

		$this ->date = get_var_value("startdate") == NULL?$list_date:date("Y-m-d",strtotime(get_var_value("startdate")));

		$start = strtotime($this->date) + 86400;
		$end = strtotime($this->date) + 14*86400;
		
		$startdate = date('Y-m-d', $start);					//第二天算起
		$enddate = date('Y-m-d', $end);						//第十四天

		global $t_conf,$chongzhi;
		$startDate = strtotime($_GET['startDate']);
		$endDate = strtotime($_GET['endDate']);
		$point = F($t_conf['qd1']['db'], $t_conf['qd1']['ip'], $t_conf['qd1']['user'], $t_conf['qd1']['password'], $t_conf['qd1']['port']);
		
		list($sip) = autoConfig::getServer($sip);//获取服务器信息
		$obj = F($chongzhi[$sip]['db'], $chongzhi[$sip]['ip'], $chongzhi[$sip]['user'], $chongzhi[$sip]['password'], $chongzhi[$sip]['port']);
		$time1 = strtotime($this ->date);
		$time2 = strtotime($_GET['startDate'])+3600*23;
		$result1 = $obj->fquery("SELECT t_account, t_money FROM history_pay WHERE t_status>2 AND t_acctime > '{$time1}' AND t_acctime <= '{$time2}'");
		$result2 =  $point->fquery("SELECT subtype,name FROM account_data_test1");
		$result3 = array();
		$point->fquery("DELETE FROM firstresult");
		foreach ($result1 as $key => $value) {
			foreach ($result2 as $k => $v) {
				if ($value['t_account'] == $v['name']) {
					$value['subtype'] = $v['subtype'];
					$result3[] = $value;
				}
			}
		}
		$mysql1 = "INSERT INTO firstresult(name,subtype,money) VALUES";

		$ruku_arr = array();
		foreach ($result3 as $key => $value) {
			$ruku_arr []= "('{$value['t_account']}',{$value['subtype']},{$value['t_money']})";
		}

		$mysql = $mysql1.implode(',', $ruku_arr);
		$point->fquery($mysql);

		$create_num = $point->fquery("SELECT COUNT(id) cid FROM account_data_test1  GROUP BY subtype ORDER BY subtype ASC");
		
		$first_result = $point->fquery("SELECT COUNT(id) cid, SUM(money) cmoney,subtype FROM firstresult GROUP BY subtype");

		$total_result = $point->fquery("SELECT SUM(money) cmoney , COUNT(id) cid,time, subtype FROM tmp_chongzhi WHERE t_status>2 GROUP BY subtype ORDER BY subtype ASC");
		$Big_result = $point->fquery("SELECT SUM(money) cmoney , COUNT(id) cid,time, subtype FROM tmp_chongzhi GROUP BY subtype ORDER BY subtype ASC");
		
		
		//重组数组
		$new_first = array();
		foreach ($first_result as $key => $value) {
			$new_first[$value['subtype']] = $value;
		}

		// print_r($new_first);
		// die;
			$big_arr = array();
			foreach ($Big_result as $key => $value) {
				$new_first[] = $value['subtype'];
			}
			$big_num = max($big_arr);

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
		//$objPHPExcel->getActiveSheet()->mergeCells('A1:A2', '时间');
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '渠道ID');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '首日充值金额');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '首日充值人数');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '创建数');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '充值金额');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', '充值人数');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'ARPU');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', '付费率');
		
		//$DataType = PHPExcel_Cell_DataType::TYPE_STRING;//科学型 改成字符串型

		// $str .= '<td>'.$new_first[0]['cmoney'].'</td>';
		// 			$str .= '<td>'.$new_first[0]['cid'].'</td>';
		// 			$str .= '<td>'.$create_num[0]['cid'].'</td>';
		// 			$str .= '<td>'.$total_result[0]['cmoney'].'</td>';
		// 			$str .= '<td>'.$total_result[0]['cid'].'</td>';
		// 			$str .= '<td>'.round($total_result[0]['cmoney']/$Big_result[0]['cid'],2).'</td>';
		// 			$str .= '<td>'.round(($total_result[0]['cid']/$create_num[0]['cid'])*100,2).' %</td>';

		if ($big_num == 0) {
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setCellValue('A2','0');
			$objPHPExcel->getActiveSheet()->setCellValue('B2', $new_first[0]['cmoney']);
			$objPHPExcel->getActiveSheet()->setCellValue('C2', $new_first[0]['cid']);
			$objPHPExcel->getActiveSheet()->setCellValue('D2', $create_num[0]['cid']);
			$objPHPExcel->getActiveSheet()->setCellValue('E2', $total_result[0]['cmoney']);
			$objPHPExcel->getActiveSheet()->setCellValue('F2', $total_result[0]["cid"]);
			$objPHPExcel->getActiveSheet()->setCellValue('G2', round($total_result[0]['cmoney']/$Big_result[0]['cid'],2));
			$objPHPExcel->getActiveSheet()->setCellValue('H2', round(($total_result[0]['cid']/$create_num[0]['cid'])*100,2));
		}else{
			for ($i=0; $i < $big_num; $i++) { 
			$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.($i+2), ($i+1));
				$objPHPExcel->getActiveSheet()->setCellValue('B'.($i+2), $new_first[$i]['cmoney']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.($i+2), $new_first[$i]['cid']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.($i+2), $create_num[$i]['cid']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.($i+2), $total_result[$i]['cmoney']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.($i+2), $total_result[$i]["cid"]);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.($i+2), round($total_result[$i]['cmoney']/$Big_result[$i]['cid'],2));
				$objPHPExcel->getActiveSheet()->setCellValue('H'.($i+2), round(($total_result[$i]['cid']/$create_num[$i]['cid'])*100,2));
			}
		}

		

		/*if (is_array($total_result)) {
			foreach($total_result as $k => $item){
				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $total_result[$k]["subtype"]);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $first_result[$k]["cmoney"]);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $first_result[$k]["cid"]);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $create_num[$k]["cid"]);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $total_result[$k]["cmoney"]);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $total_result[$k]["cid"]);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), (sprintf("%.2f",$total_result[$k]["cmoney"]/$Big_result[$k]["cid"])));
				$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), (sprintf("%.2f",($total_result[$k]["cmoney"]/$Big_result[$k]['cmoney'])*100).' %'));
			}
		}	*/

		$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "渠道分析_".date('Y_m_d H_i_s');
			
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
			header('Cache-Control: max-age=0');


			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		exit;
		//}

	}
}
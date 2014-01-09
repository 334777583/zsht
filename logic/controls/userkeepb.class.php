<?php
/**
 * FileName: userkeep.class.php
 * Description:留存分析
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-5-18 14:20:29
 * Version:1.00
 */
class userkeepb{
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
	 * 时间
	 * @var string
	 */
	private $enddate;
	
	
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
		
		
		$this->ip =  get_var_value('ip') == NULL? -1 : get_var_value('ip');
		$this->enddate =  get_var_value('enddate') == NULL? '' : get_var_value('enddate');
	}
	
	
	/**
	 * user_temp缓存表取数据
	 */
	private function getRecordByTemp(&$loseIds, &$keepIds, $date, $point) {
		$point -> table('user_temp') -> where('u_expire < "'.date("Y-m-d").'"') -> delete();	//检查过期的，先清除(默认三天后)
	
		$keep_temp = $point -> table('user_temp') -> where('u_type = 0 and u_date = "'.$date.'"') -> select();
		$lose_temp = $point -> table('user_temp') -> where('u_type = 1 and u_date = "'.$date.'"') -> select();
		if($keep_temp != '') {
			foreach($keep_temp as $keep) {
				$keepIds[] = array('o_id' => $keep['u_oid'],'userid' => $keep['u_userid']);
			}	
		}
		if($lose_temp != '') {
			foreach($lose_temp as $lose) {
				$loseIds[] = array('o_id' => $lose['u_oid'],'userid' => $lose['u_userid']);
			}	
		}
	}

	public function getstartTime(){
		$obj1 = D(GNAME.$_POST['sip']);
		$listdate = $obj1 -> table("user") -> field('u_date') -> order('u_date asc')-> limit(0,1) ->find();
		echo substr($listdate['u_date'], 0,-9);
	}
	
	public function getResult(){ //获取所有统计的数据
		
		$point = D(GNAME.$_POST['sip']);
		//查询当前日期
		$listdata = $point->fquery("SELECT COUNT(o_id) dataC,o_date  FROM online_sec WHERE o_date BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		//查询第二天统计数据
		
			$secondS = date("Y-m-d",strtotime($_POST['startdate'])+86400);
			$secondE = date("Y-m-d",strtotime($_POST['enddate'])+86400);
			$seconddata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$secondS}' AND '{$secondE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		// print_r($seconddata);
		// die;

		//查询第三天数据统计
		
			$thrS = date("Y-m-d",strtotime($_POST['startdate'])+86400*2);
			$thrE = date("Y-m-d",strtotime($_POST['enddate'])+86400*2);
			$thrdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$thrS}' AND '{$thrE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询第四天数据统计
		
			$fourS = date("Y-m-d",strtotime($_POST['startdate'])+86400*3);
			$fourE = date("Y-m-d",strtotime($_POST['enddate'])+86400*3);
			$fourdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$fourS}' AND '{$fourE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询第五天数据统计
		
			$fiveS = date("Y-m-d",strtotime($_POST['startdate'])+86400*4);
			$fiveE = date("Y-m-d",strtotime($_POST['enddate'])+86400*4);
			$fivedata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$fiveS}' AND '{$fiveE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		
		
		

		//查询第六天数据统计
		
			$sixS = date("Y-m-d",strtotime($_POST['startdate'])+86400*5);
			$sixE = date("Y-m-d",strtotime($_POST['enddate'])+86400*5);
			$sixdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$sixS}' AND '{$sixE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询第七天数据统计
		
			$sevenS = date("Y-m-d",strtotime($_POST['startdate'])+86400*6);
			$sevenE = date("Y-m-d",strtotime($_POST['enddate'])+86400*6);
			$sevendata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$sevenS}' AND '{$sevenE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		
		
		//查询双周数据统计
		
			$weekS = date("Y-m-d",strtotime($_POST['startdate'])+86400*13);
			$weekE = date("Y-m-d",strtotime($_POST['enddate'])+86400*13);
			$weekdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$weekS}' AND '{$weekE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询30天保留数据统计
		
			$monS = date("Y-m-d",strtotime($_POST['startdate'])+86400*29);
			$monE = date("Y-m-d",strtotime($_POST['enddate'])+86400*29);
			$mondata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$monS}' AND '{$monE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		$return_Arr = array('listdata'=>$listdata,'seconddata'=>$seconddata,'thrdata'=>$thrdata,'fourdata'=>$fourdata,'fivedata'=>$fivedata,'sixdata'=>$sixdata,'sevendata'=>$sevendata,'weekdata'=>$weekdata,'mondata'=>$mondata);

			if (!empty($listdata)) {
				 ECHO json_encode($return_Arr);
			}else{
				echo 1;
			}
		 

	}

	public function getImgResult(){ //获取所有统计的图表数据
		
		$point = D(GNAME.$_POST['sip']);
		//查询当前日期
		$listdata = $point->fquery("SELECT COUNT(o_id) dataC,date_format(o_date,'%Y-%m-%d') Cdate  FROM online_sec WHERE o_date BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		//查询第二天统计数据
		
			$secondS = date("Y-m-d",strtotime($_POST['startdate'])+86400);
			$secondE = date("Y-m-d",strtotime($_POST['enddate'])+86400);
			$seconddata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$secondS}' AND '{$secondE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询第三天数据统计
		
			$thrS = date("Y-m-d",strtotime($_POST['startdate'])+86400*2);
			$thrE = date("Y-m-d",strtotime($_POST['enddate'])+86400*2);
			$thrdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$thrS}' AND '{$thrE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询第四天数据统计
		
			$fourS = date("Y-m-d",strtotime($_POST['startdate'])+86400*3);
			$fourE = date("Y-m-d",strtotime($_POST['enddate'])+86400*3);
			$fourdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$fourS}' AND '{$fourE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询第五天数据统计
		
			$fiveS = date("Y-m-d",strtotime($_POST['startdate'])+86400*4);
			$fiveE = date("Y-m-d",strtotime($_POST['enddate'])+86400*4);
			$fivedata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$fiveS}' AND '{$fiveE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		
		
		

		//查询第六天数据统计
		
			$sixS = date("Y-m-d",strtotime($_POST['startdate'])+86400*5);
			$sixE = date("Y-m-d",strtotime($_POST['enddate'])+86400*5);
			$sixdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$sixS}' AND '{$sixE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询第七天数据统计
		
			$sevenS = date("Y-m-d",strtotime($_POST['startdate'])+86400*6);
			$sevenE = date("Y-m-d",strtotime($_POST['enddate'])+86400*6);
			$sevendata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$sevenS}' AND '{$sevenE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		
		
		//查询双周数据统计
		
			$weekS = date("Y-m-d",strtotime($_POST['startdate'])+86400*13);
			$weekE = date("Y-m-d",strtotime($_POST['enddate'])+86400*13);
			$weekdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$weekS}' AND '{$weekE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询30天保留数据统计
		
			$monS = date("Y-m-d",strtotime($_POST['startdate'])+86400*29);
			$monE = date("Y-m-d",strtotime($_POST['enddate'])+86400*29);
			$mondata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$monS}' AND '{$monE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		$data_Arr = array();
		$str_arr = array();
		$string_arr = array();
		error_reporting(4);

		
			for ($i=0; $i <count($listdata); $i++) { 
				$str_arr[$i][] = '"time":"'.$listdata[$i]['Cdate'].'"';
				if(!empty($seconddata)){
					if ($i < count($seconddata)) {
						$str_arr[$i][] = '"second":'.$seconddata[$i]['dataC'];
					}
				}

				if(!empty($thrdata)){
					if ($i < count($thrdata)) {
						$str_arr[$i][] = '"thr":'.$thrdata[$i]['dataC'];
					}
				}

				if(!empty($fourdata)){
					if ($i < count($fourdata)) {
						$str_arr[$i][] = '"four":'.$fourdata[$i]['dataC'];
					}
				}

				if(!empty($fivedata)){
					if ($i < count($fivedata)) {
						$str_arr[$i][] = '"five":'.$fivedata[$i]['dataC'];
					}
				}

				if(!empty($sixdata)){
					if ($i < count($sixdata)) {
						$str_arr[$i][] = '"six":'.$sixdata[$i]['dataC'];
					}
				}

				if(!empty($sevendata)){
					if ($i < count($sevendata)) {
						$str_arr[$i][] = '"seven":'.$sevendata[$i]['dataC'];
					}
				}

				if(!empty($weekdata)){
					if ($i < count($weekdata)) {
						$str_arr[$i][] = '"week":'.$weekdata[$i]['dataC'];
					}
				}

				if(!empty($mondata)){
					if ($i < count($mondata)) {
						$str_arr[$i][] = '"mon":'.$mondata[$i]['dataC'];
					}
				}
				$string_arr[] = implode(',', $str_arr[$i]);
			}
			echo $str3 = "[{".implode('},{', $string_arr).'}]';
		
	}

	public function writeExcel(){
		
    	$point = D(GNAME.$_GET['ip']);
		//查询当前日期
		$listdata = $point->fquery("SELECT COUNT(o_id) dataC,o_date  FROM online_sec WHERE o_date BETWEEN '{$_GET['startdate']}' AND '{$_GET['enddate']}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		
		//查询第二天统计数据
		
			$secondS = date("Y-m-d",strtotime($_GET['startdate'])+86400);
			$secondE = date("Y-m-d",strtotime($_GET['enddate'])+86400);
			$seconddata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$secondS}' AND '{$secondE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询第三天数据统计
		
			$thrS = date("Y-m-d",strtotime($_GET['startdate'])+86400*2);
			$thrE = date("Y-m-d",strtotime($_GET['enddate'])+86400*2);
			$thrdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$thrS}' AND '{$thrE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询第四天数据统计
		
			$fourS = date("Y-m-d",strtotime($_GET['startdate'])+86400*3);
			$fourE = date("Y-m-d",strtotime($_GET['enddate'])+86400*3);
			$fourdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$fourS}' AND '{$fourE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询第五天数据统计
		
			$fiveS = date("Y-m-d",strtotime($_GET['startdate'])+86400*4);
			$fiveE = date("Y-m-d",strtotime($_GET['enddate'])+86400*4);
			$fivedata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$fiveS}' AND '{$fiveE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		
		
		

		//查询第六天数据统计
		
			$sixS = date("Y-m-d",strtotime($_GET['startdate'])+86400*5);
			$sixE = date("Y-m-d",strtotime($_GET['enddate'])+86400*5);
			$sixdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$sixS}' AND '{$sixE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询第七天数据统计
		
			$sevenS = date("Y-m-d",strtotime($_GET['startdate'])+86400*6);
			$sevenE = date("Y-m-d",strtotime($_GET['enddate'])+86400*6);
			$sevendata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$sevenS}' AND '{$sevenE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		
		
		//查询双周数据统计
		
			$weekS = date("Y-m-d",strtotime($_GET['startdate'])+86400*13);
			$weekE = date("Y-m-d",strtotime($_GET['enddate'])+86400*13);
			$weekdata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$weekS}' AND '{$weekE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

		//查询30天保留数据统计
		
			$monS = date("Y-m-d",strtotime($_GET['startdate'])+86400*29);
			$monE = date("Y-m-d",strtotime($_GET['enddate'])+86400*29);
			$mondata = $point->fquery("SELECT COUNT(o_id) dataC  FROM online_sec WHERE o_date BETWEEN '{$monS}' AND '{$monE}' GROUP BY date_format(o_date,'%Y-%m-%d')");
		

	
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '时间');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '新登陆账号');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '次日留存率');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '三日留存率');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '四日留存率');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', '五日留存率');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', '六日留存率');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', '七日留存率');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', '双周日留存率');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', '30日留存率');
		
		//$DataType = PHPExcel_Cell_DataType::TYPE_STRING;//科学型 改成字符串型
		
		if (is_array($listdata)) {
			foreach($listdata as $k => $item){
				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $item['o_date']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item['dataC']);
				if ($seconddata != 1) {
					if($k < count($seconddata)){
						
						$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), round($seconddata[$k]['dataC']/$item['dataC']*100,2).' %('.$seconddata[$k]['dataC'].')');
					}
				}

				if ($thrdata != 1) {
					if($k < count($thrdata)){
						$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), round($thrdata[$k]['dataC']/$item['dataC']*100,2).' %('.$thrdata[$k]['dataC'].')');
					}
				}

				if ($fourdata != 1) {
					if($k < count($fourdata)){
						$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), round($fourdata[$k]['dataC']/$item['dataC']*100,2).' %('.$fourdata[$k]['dataC'].')');
					}
				}


				if ($fivedata != 1) {
					if($k < count($fivedata)){
						$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), round($fivedata[$k]['dataC']/$item['dataC']*100,2).' %('.$fivedata[$k]['dataC'].')');
					}
				}
				if ($sixdata != 1) {
					if($k < count($sixdata)){
						$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), round($sixdata[$k]['dataC']/$item['dataC']*100,2).' %('.$sixdata[$k]['dataC'].')');
					}
				}
				if ($sevendata != 1) {
					if($k < count($sevendata)){
						$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), round($sevendata[$k]['dataC']/$item['dataC']*100,2).' %('.$sevendata[$k]['dataC'].')');
					}
				}
				if ($weekdata != 1) {
					if($k < count($weekdata)){
						$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), round($weekdata[$k]['dataC']/$item['dataC']*100,2).' %('.$weekdata[$k]['dataC'].')');
					}
				}

				if (!empty($mondata)) {
					if($k < count($mondata)){
						$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), round($mondata[$k]['dataC']/$item['dataC']*100,2).' %('.$mondata[$k]['dataC'].')');
					}
				}

			}
		}	

		$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "留存分析_".date('Y_m_d H_i_s');
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
			header('Cache-Control: max-age=0');


			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		exit;
	}
}
<?php
/**
 * FileName: residualValue.class.php
 * Description:剩余价值分析
 * Author: xiaoliao
 * Date:2013-11-26 14:10:29
 * Version:1.00
 */
class residualvalue{
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
		
		
		$this->sip =  get_var_value('sip') == NULL? -1 : get_var_value('sip');
		$this->startdate =  get_var_value('startdate') == NULL? '' : get_var_value('startdate');
		$this->enddate =  get_var_value('enddate') == NULL? '' : get_var_value('enddate');
	}
	
	
	
	public function getResult() {
		$point = D(GNAME.$this->sip);
		date_default_timezone_set('Asia/Chongqing'); //设置每天晚上23:55进行插库操作！
		//if (date("H:i:s") < '00:00:00' && date("H:i:s") > '00:01:00') {
			//$this->LogInsertMysql($point);
		//}
		
		
		$result = $point->fquery("SELECT SUM(addcoins) addcoinss,SUM(costcoins) costcoinss,SUM(addgold) addgolds,SUM(costgold) costgolds,SUM(addbindgold) addbindgolds,SUM(costbindgold) costbindgolds,time FROM money_exp WHERE time BETWEEN '{$this->startdate}' AND '{$this->enddate}' GROUP BY time");
		
		
		echo json_encode($result);
	}


	public function LogInsertMysql(){
		$point = D(GNAME.$this->sip);
		$file = file_get_contents(TPATH.'/dzs_49you_s001a/money_exp/money_exp.log.'.date('Y-m-d',strtotime("-1 day")));
		//$file = file_get_contents(TPATH.'/money_exp.log.'.date('Y-m-d',strtotime("-6 day")));
		 error_reporting(4);

		 //先清空数据库
			/*$clear_res = $point -> rquery('delete from money_exp');
			if(!$clear_res){//清空失败
				echo json_encode(array('success' => 0));
				exit;
			}*/

		$a = array();
		$arr = explode("\n",$file);
		$a=$b=$c=$d = $mysql_arr =$mysql_arr1 = array();

		foreach ($arr as $key => $value) {
			if (strpos($value, 'moneyAdd') > -1) {
				$b[] = $value;
			}elseif (strpos($value, 'moneyReduce') > -1) {
				$c[] = $value;
			}
		}

		//正值入库
		for ($i=0; $i < count($b) ; $i++) { 
			$a = explode('-[', $b[$i]);
			for ($k=0; $k < count($a) ; $k++) { 
				if ($k == 0) {
					$mysql_arr[$i]['time'] = substr($a[$k], 1,19);
				}

				if ($k == 1) {
					$mysql_arr[$i]['account'] = substr($a[$k], strpos($a[$k], 'account')+8,-1);
				}

				if ($k == 2) {
					$mysql_arr[$i]['player'] = substr($a[$k], strpos($a[$k], 'player')+7,-1);
				}

				if ($k == 3) {
					$mysql_arr[$i]['addCoins'] = substr($a[$k], strpos($a[$k], 'add coins')+10,-1);
				}

				if ($k == 4) {
					$mysql_arr[$i]['addBindGold'] = substr($a[$k], strpos($a[$k], 'add bindCoins')+14,-1);
				}

				if ($k == 6) {
					$mysql_arr[$i]['addGold'] = substr($a[$k], strpos($a[$k], 'add gold')+9,-1);
				}
			}
		}
		$ins_data = "INSERT INTO money_exp(account,player,addCoins,addGold,addBindGold,time) VALUES";
		$num = 800;
			for($j = 0 ;$j < count($mysql_arr) ; $j++){
				
				$ins_data .= "({$mysql_arr[$j]['account']},{$mysql_arr[$j]['player']},{$mysql_arr[$j]['addCoins']},{$mysql_arr[$j]['addGold']},{$mysql_arr[$j]['addBindGold']},'{$mysql_arr[$j]['time']}'),";
				
				if(($j % $num) == 0){
					$ins_data = rtrim($ins_data, ',');
					$ins_data .= ';';
					$ins_str = $point -> rquery($ins_data);
					if(!$ins_str){//添加失败
						echo json_encode(array('success' => 0));
						exit;
					}
					$ins_data = "insert into money_exp(account,player,addCoins,addGold,addBindGold,time) values ";
				}
			}
			$ins_data = rtrim($ins_data, ',');
			$ins_data .= ';';
			$ins_str = $point -> rquery($ins_data);
			

		for ($i=0; $i < count($c) ; $i++) { 
			$d = explode('-[', $c[$i]);

			for ($k=0; $k < count($d) ; $k++) { 
				if ($k == 0) {
					$mysql_arr1[$i]['time'] = substr($d[$k], 1,19);
				}

				if ($k == 1) {
					$mysql_arr1[$i]['account'] = substr($d[$k], strpos($d[$k], 'account')+8,-1);
				}

				if ($k == 2) {
					$mysql_arr1[$i]['player'] = substr($d[$k], strpos($d[$k], 'player')+7,-1);
				}

				if ($k == 5) {
					$mysql_arr1[$i]['costBindGold'] = substr($d[$k], strpos($d[$k], 'reduce bindGold')+16,-1);
				}

				if ($k == 3) {
					$mysql_arr1[$i]['costCoins'] = substr($d[$k], strpos($d[$k], 'reduce coins')+13,-1);
				}

				if ($k == 6) {
					$mysql_arr1[$i]['costGold'] = substr($d[$k], strpos($d[$k], 'reduce gold')+12,-1);
				}
			}
		}
			$mysql1 = "INSERT INTO money_exp(account,player,costCoins,costGold,costBindGold,time) VALUES";
			for($k = 0 ;$k < count($mysql_arr1) ; $k++){
				
				$mysql1 .= "({$mysql_arr1[$k]['account']},{$mysql_arr1[$k]['player']},{$mysql_arr1[$k]['costCoins']},{$mysql_arr1[$k]['costGold']},{$mysql_arr1[$k]['costBindGold']},'{$mysql_arr1[$k]['time']}'),";
				
				if(($k % $num) == 0){
					$mysql1 = rtrim($mysql1, ',');
					$mysql1 .= ';';
					$ins_str = $point -> rquery($mysql1);
					if(!$ins_str){//添加失败
						echo json_encode(array('success' => 0));
						exit;
					}
					$mysql1 = "insert into money_exp(account,player,costCoins,costGold,costBindGold,time) values ";
				}
			}
			$mysql1 = rtrim($mysql1, ',');
			$mysql1 .= ';';
			echo $mysql1;
			$ins_str = $point -> rquery($mysql1);

			
				echo 1;
			
		
	}

	public function writeExcel(){
		$point = D(GNAME.$_GET['ip']);
		
		$result = $point->fquery("SELECT SUM(addCoins) addCoinsS,SUM(costCoins) costCoinsS,SUM(addGold) addGoldS,SUM(costGold) costGoldS,SUM(addBindGold) addBindGoldS,SUM(costBindGold) costBindGoldS,time FROM money_exp WHERE time BETWEEN '{$_GET['startdate']}' AND '{$_GET['enddate']}' GROUP BY time ORDER BY time DESC");
	
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
		$objPHPExcel->getActiveSheet()->mergeCells('A1:A2', '时间');
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '时间');
		$objPHPExcel->getActiveSheet()->mergeCells('B1:D1', '元宝');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '元宝');
		$objPHPExcel->getActiveSheet()->mergeCells('E1:G1', '绑定元宝');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '绑定元宝');
		//$objPHPExcel->getActiveSheet()->mergeCells('J1:M1', '任务');
		//$objPHPExcel->getActiveSheet()->setCellValue('J1', '任务');
		$objPHPExcel->getActiveSheet()->mergeCells('H1:J1', '铜钱');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', '铜钱');
		
		$objPHPExcel->getActiveSheet()->setCellValue('B2', '总产出');
		$objPHPExcel->getActiveSheet()->setCellValue('C2', '总消耗');
		$objPHPExcel->getActiveSheet()->setCellValue('D2', '总剩余');
		$objPHPExcel->getActiveSheet()->setCellValue('E2', '总产出');
		$objPHPExcel->getActiveSheet()->setCellValue('F2', '总消耗');
		$objPHPExcel->getActiveSheet()->setCellValue('G2', '总剩余');
		$objPHPExcel->getActiveSheet()->setCellValue('H2', '总产出');
		$objPHPExcel->getActiveSheet()->setCellValue('I2', '总消耗');
		$objPHPExcel->getActiveSheet()->setCellValue('J2', '总剩余');
		
		//$DataType = PHPExcel_Cell_DataType::TYPE_STRING;//科学型 改成字符串型
		
		if (is_array($result)) {
			foreach($result as $k => $item){
				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+3), $item["time"]);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+3), $item["addCoinsS"]);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+3), $item["costCoinsS"]);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+3), $item["addCoinsS"]-$item["costCoinsS"]);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+3), $item["addGoldS"]);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+3), $item["costGoldS"]);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+3), $item["addGoldS"]-$item["costGoldS"]);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+3), $item["addBindGoldS"]);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+3), $item["costBindGoldS"]);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+3), $item["addBindGoldS"]-$item["costBindGoldS"]);
			}	
		}	

		$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "剩余价值分析_".date('Y_m_d H_i_s');
			
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
			header('Cache-Control: max-age=0');


			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		exit;
		//}

	}
	
}
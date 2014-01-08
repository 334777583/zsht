<?php
/**
 * FileName: singSevact.class.php
 * Description:单服活跃
 * Author: xiaoliao
 * Date:2013-11-27 09:10:34
 * Version:1.00
 */
class singsevact{
	/**
	 * 用户数据
	 * @var Array
	 */
	public $user;

	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00300400', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	
	/**
	 * 单服活跃用户等级排行查询
	 */
	public function SingSevAct() {
		$ip = get_var_value('ip');
		
		if($ip) {
			$data = array();			//返回给页面的结果（经过组装后）
			
			if(true) {	
				global $t_conf;
				
				list($sip) = autoConfig::getConfig($ip);
				$point = F($t_conf[$sip]['db'], $t_conf[$sip]['ip'], $t_conf[$sip]['user'], $t_conf[$sip]['password'], $t_conf[$sip]['port']);
				
				if(!$point){
					echo json_encode(array(
						'error' => '数据库连接失败！'
					));
					exit;
				}else{
					$Sing_Field = 'account_code,name,level,create_time,last_down_time';
					
					//筛选玩家数据
					$StartTime = strtotime('-3 day') * 1000;
					$EndTime = strtotime('+0 day') * 1000;
					
					//游戏角色信息
					//$SingResult = $point -> table('t_player') -> field($Sing_Field) ->where('level > 35') ->order('level DESC') -> select();	
					$SingResult = $point ->fquery("SELECT {$Sing_Field} FROM t_player WHERE level > 39 ORDER BY level DESC , last_down_time DESC");	
				}
				if(!empty($SingResult)){//组装数据
					
					foreach($SingResult as $key => $value){
						//初始化
						$downline_time = '';
						$create_time = '';
						$last_down_time = '';
						
						if (strtotime(date('Y-m-d'))-round($SingResult[$key]['last_down_time']/1000) > 3600*24*3) {
							$SingResult[$key]['statis'] = 2;
						}else{
							$SingResult[$key]['statis'] = 1;
						}
						//当时间不存在的时候 默认为今天
						$create_time = $this -> DateFormat($SingResult[$key]['create_time']);
						$last_down_time = $this -> DateFormat($SingResult[$key]['last_down_time']);
						

						//组装数据
						$SingResult[$key]['create_time'] = $create_time;//注册时间
						$SingResult[$key]['last_down_time'] = $last_down_time;//最后下线时间

						
						
						// //离线时间
						// $down_time = $this -> DateFormat($SingResult[$key]['last_down_time'],1);
						// $downline_time = $this -> ShowTime($down_time);
						// $data[$key]['downline_time'] = $downline_time;
					}
					// $data = $SingResult;
				}
				
				
				echo json_encode(array('result' => $SingResult));
				exit;
			} else {
				echo '1';
			}
		}
	}


	public function writeExcel(){
		$ip = get_var_value('ip');
		
		global $t_conf;
				
				list($sip) = autoConfig::getConfig($ip);
				$point = F($t_conf[$sip]['db'], $t_conf[$sip]['ip'], $t_conf[$sip]['user'], $t_conf[$sip]['password'], $t_conf[$sip]['port']);
				
				if(!$point){
					echo json_encode(array(
						'error' => '数据库连接失败！'
					));
					exit;
				}else{
					$Sing_Field = 'account_code,name,level,create_time,last_down_time';
					
					
					
					
					//游戏角色信息
					//$SingResult = $point -> table('t_player') -> field($Sing_Field) ->where('level > 35') ->order('level DESC') -> select();	
					$SingResult = $point ->fquery("SELECT {$Sing_Field} FROM t_player WHERE level > 39 ORDER BY level DESC");	
				}
	
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '排行');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '账号');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '角色');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '等级');
		
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '注册时间');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', '最后登录');
		
		//$DataType = PHPExcel_Cell_DataType::TYPE_STRING;//科学型 改成字符串型
		
		if (is_array($SingResult)) {
			foreach($SingResult as $k => $item){
				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $k+1);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["account_code"]);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["account_code"]);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["level"]);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), date('Y-m-d H:i:s',$item["create_time"]));
				$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), date('Y-m-d H:i:s',$item["last_down_time"]));
			}	
		}	

		$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "单服活跃分析_".date('Y_m_d H_i_s');
			
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
			header('Cache-Control: max-age=0');


			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		exit;
		//}

	}
	
	/**
	 * FunctionName: RankData
	 * Description: 即时更新单服排名数据
	 * Author: hjt	
	 * Parameter：null
	 * Return: boolen   
	 * Date: 2013-9-6 15:26:24
	 */
	public function RankData(){
		$mon_str = '';				//保存有充值的用户id字符串（用于二维降一维）
		$ins_data = '';				//插入的数据
		$sing_str = '';				//保存所有用户id字符串（用于二维降一维）
		$Mkey_len = 0;				//记录有充值的用户id数组长度（防止数组出现空值）
		$Skey_len = 0;				//记录所有用户id数组长度（防止数组出现空值）
		
		$is_money = array();		//有充值的用户id
		$sing_arr = array();		//所有用户id
		$no_money = array();		//没充值的用户id
		$money_array = array();		//所有用户充值结果（经过重组）
		
		$ip = get_var_value('ip');
		
		global $t_conf;
				
		list($sip) = autoConfig::getConfig($ip);//获取服务器信息

		$point = F($t_conf[$sip]['db'], $t_conf[$sip]['ip'], $t_conf[$sip]['user'], $t_conf[$sip]['password'], $t_conf[$sip]['port']);
		
		if(!$point){
			echo json_encode(array(
				'error' => '数据库连接失败！'
			));
			exit;
		}else{
			$Obj = D(GNAME.$ip);
			
			$Sing_Field = 'player_id,account_code,name,level,gold,create_time,last_down_time';
			//游戏角色信息
			$SingResult = $point -> table('t_player') -> field($Sing_Field)-> select();
			//充值金额
			$MonResult = $Obj -> table('pay_detail') -> field('p_playid,sum(p_money) as p_money') -> where('p_result = 1') -> group('p_playid') -> select();
			if($MonResult < 1 && $SingResult < 1){//没记录不更新
				echo json_encode(array('success' => 0));
				exit;
			}
			//将已充值的用户写成字符串（用于二维降一维）
			$Mkey_len = count($MonResult);
			if($Mkey_len > 0){
				foreach($MonResult as $key => $value) {
					$mon_str .= $value['p_playid'].',';
				}
			}
			$mon_str = rtrim($mon_str,',');
			//将所有用户写成字符串（用于二维降一维）
			$Skey_len = count($SingResult);
			foreach($SingResult as $key => $val) {
				$sing_str .= $val['player_id'].',';
			}
			$sing_str = rtrim($sing_str,',');
			
			//分离未充值的用户
			$is_money = explode(',',$mon_str);
			$sing_arr = explode(',',$sing_str);
			$no_money = array_diff($sing_arr,$is_money);
			//已充值的用户
			if($Mkey_len > 0){
				foreach($is_money as $key => $value){
					$money_array[$value] = $MonResult[$key]['p_money'];
				}
			}
			//未充值的用户默认为0
			foreach($no_money as $key => $value){
				$money_array[$value] = 0;
			}
			ksort($money_array);//重新排序 
			
			//将所有用户充值金额一起组装
			foreach($SingResult as $key => $val){
				$SingResult[$key]['money'] = $money_array[$sing_arr[$key]];
			}
			
			//先清空数据库
			$clear_res = $Obj -> rquery('delete from sing_rank');
			if(!$clear_res){//清空失败
				echo json_encode(array('success' => 0));
				exit;
			}
			
			//整理需要插入数据
			$Singcount = count($SingResult);
			$num = 800;
			$time = date('Y-m-d H:i:s');	
			$ins_data = "insert into sing_rank(s_acc,s_name,s_level,s_gold,s_money,s_create_time,s_last_down_time,s_ins_time) values ";

			//每次插入800条数据
			for($i = 0 ;$i < $Singcount ; $i++){
				$account_code = $SingResult[$i]['account_code'];//账号
				$name = $SingResult[$i]['name'];//角色
				$level = $SingResult[$i]['level'];//等级
				$gold = $SingResult[$i]['gold'];//元宝
				$money = $SingResult[$i]['money'];//充值金额
				$create_time = $this->DateFormat($SingResult[$i]['create_time']);//注册时间
				$last_down_time = $this->DateFormat($SingResult[$i]['last_down_time']);//最后登录时间
				
				$ins_data .= "('" . $account_code . "','" . $name . "','" . $level . "','" . $gold . "','" . $money . "','" . $create_time . "','" . $last_down_time ."','" . $time ."'),";
				
				if(($i % $num) == 0){
					$ins_data = rtrim($ins_data, ',');
					$ins_data .= ';';
					$ins_str = $Obj -> rquery($ins_data);
					if(!$ins_str){//添加失败
						echo json_encode(array('success' => 0));
						exit;
					}
					$ins_data = "insert into sing_rank(s_acc,s_name,s_level,s_gold,s_money,s_create_time,s_last_down_time,s_ins_time) values ";
				}
			}
			$ins_data = rtrim($ins_data, ',');
			$ins_data .= ';';
			$ins_str = $Obj -> rquery($ins_data);
			if(!$ins_str){//添加失败
				echo json_encode(array('success' => 0));
				exit;
			}
			 echo json_encode(array('success' => 1));//返回插入成功
			 exit;
		}
	}
	
	/**
	 * FunctionName: DateFormat
	 * Description: 时间戳转换(格式为 Y-m-d H:i:s)显示
	 * Author: hjt	
	 * Parameter：
	 * $Time 时间戳(单位：毫秒 或者 秒 也可以)
	 * $Type 显示模式(0、直接转换；1、直接显示) 默认直接转换
	 * Return: String				
	 * Date: 2013-8-30 17:10:19
	 **/
	private function DateFormat($Time,$Type = 0){
		//初始化
		$result = '';
		
		$Length = strlen($Time);
		if($Length > 10){//单位为毫秒时候 转成秒
			$Time = ceil($Time / 1000);
		}
		
		if($Type == 1){//直接显示数据
			$result = $Time;
			return $result;
		}
		//转换时间
		if($Time != 0 && $Time != ''){//当时间存在直接转换 否则默认为今天
			$result = date('Y-m-d H:i:s',$Time);
		}else{
			$result = date('Y-m-d H:i:s');
		}
		return $result;
	}
	
	
	/**
	 * FunctionName: ShowTime
	 * Description: 自动算出离线时间
	 * Author: hjt	
	 * Parameter：type $Time 最后下线时间
	 * Return: string   离线时间
	 * Date: 2013-8-30 10:43:19
	 */
	private function ShowTime($Time){
		//初始化
		$show_hour = '';
		$show_day = '';
		$result = '';
		
		$EndTime = strtotime('+0 day');//为今天的时间
		$StartTime = $Time;
		$DownLineTime = $EndTime - $StartTime;
		$result = $DownLineTime;
		/*$show_day = floor($DownLineTime / 3600 / 24);//天
		$show_hour = floor($DownLineTime / 3600 % 24);//小时
		
		if($show_hour == 0){//当离线小时小于1个小时  显示0.X小时
			$show_hour = round(floor($DownLineTime /60 %60)/60,1);
		}
		if($show_day > 0){//当离线大于1天 显示天数
			$result = $show_day.'天'.$show_hour.'小时';
		}else{//否则  只显示小时
			$result = $show_hour.'小时';
		}*/
		return $result;
	}
}
<?php
/**
 * FileName: singAction.class.php
 * Description:活动数据分析
 * Author: xiaoliao
 * Date:2013-11-28 09:30:33
 * Version:1.00
 */
class singaction{
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
			if(!in_array('00400500', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	
	/**
	 * 单服活跃用户等级排行查询
	 */
	public function getResult() {
		$ip = get_var_value('ip');
		$point = D(GNAME.$ip);

		if ($_POST['actiontype'] == 1) {
			$result = $point->fquery("SELECT zuoqiFive,zuoqiSix,zuoqiSeven,date FROM active WHERE date BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		}elseif ($_POST['actiontype'] == 2) {
			$result = $point->fquery("SELECT quanshenzhyFive,quanshenzhySix,quanshenzhyEight,date FROM active WHERE date BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		}elseif ($_POST['actiontype'] == 3) {
			$result = $point->fquery("SELECT quanshenzhbSix,quanshenzhbEight,quanshenzhbTwe,date FROM active WHERE date BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		}elseif ($_POST['actiontype'] == 4) {
			$result = $point->fquery("SELECT danbi500,danbi1000,danbi2000,danbi5000,danbi10000,date FROM active WHERE date BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		}elseif ($_POST['actiontype'] == 5) {
			$result = $point->fquery("SELECT cost500,cost1000,cost2000,cost5000,cost10000,date FROM active WHERE date BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		}elseif ($_POST['actiontype'] == 6) {
			$result = $point->fquery("SELECT zhqzhekFive,zhqzhekSix,zhqzhekSeven,zhqzhekNine,date FROM active WHERE date BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		}elseif ($_POST['actiontype'] == 7) {
			$result = $point->fquery("SELECT zhuySixzheBig,zhuySixzheA,zhuySixzheB,zhuySixzheC,zhuySixzheD,zhuySixzheE,zhuyNinezheBig,zhuyNinezheA,zhuyNinezheB,zhuyNinezheC,zhuyNinezheD,zhuyNinezheE,date FROM active WHERE date BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		}elseif ($_POST['actiontype'] == 8) {
			$result = $point->fquery("SELECT zhenyuanzheSeven,zhenyuanzheSix,zhenyuanzheFive,zhenyuanzheNine,date FROM active WHERE date BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		}elseif ($_POST['actiontype'] == 9) {
			$result = $point->fquery("SELECT zhuanbeideng89,zhuanbeideng99,zhuanbeideng109,zhuanbeideng129,zhuanbeideng139,zhuanbeideng149,zhuanbeideng86,zhuanbeideng96,zhuanbeideng106,zhuanbeideng116,zhuanbeideng126,zhuanbeideng136,zhuanbeideng146,zhuanbeideng119,date FROM active WHERE date BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		}else{
			$result = $point->fquery("SELECT * FROM active WHERE date BETWEEN '{$_POST['startdate']}' AND '{$_POST['enddate']}'");
		}

		if ($ip == 1) {
			$game = '内网14';
		}else if ($ip == 3) {
			$game = '内网13';
		}else if ($ip == 4) {
			$game = '版署服';
		}else if ($ip == 5) {
			$game = 'text';
		}else if ($ip == 6) {
			$game = '外网测试';
		}else{
			$game = '14服分支';
		}

		echo json_encode(array('result'=>$result,'actiontype'=>$_POST['actiontype'],'game'=>$game));

	}


	public function writeExcel(){
		$ip = get_var_value('ip');
		$point = D(GNAME.$ip);
		$result = $point->fquery("SELECT * FROM active WHERE date BETWEEN '{$_GET['startdate']}' AND '{$_GET['enddate']}'");
		
		if ($ip == 1) {
			$game = '内网14';
		}else if ($ip == 3) {
			$game = '内网13';
		}else if ($ip == 4) {
			$game = '版署服';
		}else if ($ip == 5) {
			$game = 'text';
		}else if ($ip == 6) {
			$game = '外网测试';
		}else{
			$game = '14服分支';
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
		$objPHPExcel->getActiveSheet()->mergeCells('A1:A2', '时间');
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '平台');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '游戏区服');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '时间');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '坐骑进阶至5阶');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '坐骑进阶至6阶');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', '坐骑进阶至7阶');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', '全身卓越进阶到5阶');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', '全身卓越进阶到6阶');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', '全身卓越进阶到8阶');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', '全身装备强化到6阶');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', '全身装备强化到8阶');
		$objPHPExcel->getActiveSheet()->setCellValue('L1', '全身装备强化到12阶');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', '单笔充值500元宝');
		$objPHPExcel->getActiveSheet()->setCellValue('N1', '单笔充值1000元宝');
		$objPHPExcel->getActiveSheet()->setCellValue('O1', '单笔充值2000元宝');
		$objPHPExcel->getActiveSheet()->setCellValue('P1', '单笔充值5000元宝');
		$objPHPExcel->getActiveSheet()->setCellValue('Q1', '单笔充值10000元宝');
		$objPHPExcel->getActiveSheet()->setCellValue('R1', '累计消费500元宝');
		$objPHPExcel->getActiveSheet()->setCellValue('S1', '累计消费1000元宝');
		$objPHPExcel->getActiveSheet()->setCellValue('T1', '累计消费2000元宝');
		$objPHPExcel->getActiveSheet()->setCellValue('U1', '累计消费5000元宝');
		$objPHPExcel->getActiveSheet()->setCellValue('V1', '累计消费10000元宝');
		$objPHPExcel->getActiveSheet()->setCellValue('W1', '坐骑进阶5折');
		$objPHPExcel->getActiveSheet()->setCellValue('X1', '坐骑进阶6折');
		$objPHPExcel->getActiveSheet()->setCellValue('Y1', '坐骑进阶7折');
		$objPHPExcel->getActiveSheet()->setCellValue('Z1', '坐骑进阶9折');
		$objPHPExcel->getActiveSheet()->setCellValue('AA1', '卓越6折声望大礼包');
		$objPHPExcel->getActiveSheet()->setCellValue('AB1', '卓越6折材料礼包A');
		$objPHPExcel->getActiveSheet()->setCellValue('AC1', '卓越6折材料礼包B');
		$objPHPExcel->getActiveSheet()->setCellValue('AD1', '卓越6折材料礼包C');
		$objPHPExcel->getActiveSheet()->setCellValue('AE1', '卓越6折材料礼包D');
		$objPHPExcel->getActiveSheet()->setCellValue('AF1', '卓越6折材料礼包E');
		$objPHPExcel->getActiveSheet()->setCellValue('AG1', '卓越9折声望大礼包');
		$objPHPExcel->getActiveSheet()->setCellValue('AH1', '卓越9折材料礼包A');
		$objPHPExcel->getActiveSheet()->setCellValue('AI1', '卓越9折材料礼包B');
		$objPHPExcel->getActiveSheet()->setCellValue('AJ1', '卓越9折材料礼包C');
		$objPHPExcel->getActiveSheet()->setCellValue('AK1', '卓越9折材料礼包D');
		$objPHPExcel->getActiveSheet()->setCellValue('AL1', '卓越9折材料礼包E');
		$objPHPExcel->getActiveSheet()->setCellValue('AM1', '真元7折礼包');
		$objPHPExcel->getActiveSheet()->setCellValue('AN1', '真元6折大礼包');
		$objPHPExcel->getActiveSheet()->setCellValue('AO1', '真元5折优惠大礼包');
		$objPHPExcel->getActiveSheet()->setCellValue('AP1', '真元9折优惠大礼包');
		$objPHPExcel->getActiveSheet()->setCellValue('AQ1', '装备8等级强化9折');
		$objPHPExcel->getActiveSheet()->setCellValue('AR1', '装备9等级强化9折');
		$objPHPExcel->getActiveSheet()->setCellValue('AS1', '装备10等级强化9折');
		$objPHPExcel->getActiveSheet()->setCellValue('AT1', '装备12等级强化9折');
		$objPHPExcel->getActiveSheet()->setCellValue('AU1', '装备13等级强化9折');
		$objPHPExcel->getActiveSheet()->setCellValue('AV1', '装备14等级强化9折');
		$objPHPExcel->getActiveSheet()->setCellValue('AW1', '装备8等级强化6折大礼包');
		$objPHPExcel->getActiveSheet()->setCellValue('AX1', '装备9等级强化6折大礼包');
		$objPHPExcel->getActiveSheet()->setCellValue('AY1', '装备10等级强化6折大礼包');
		$objPHPExcel->getActiveSheet()->setCellValue('AZ1', '装备11等级强化9折');
		$objPHPExcel->getActiveSheet()->setCellValue('BA1', '装备11等级强化6折大礼包');
		$objPHPExcel->getActiveSheet()->setCellValue('BB1', '装备12等级强化6折大礼包');
		$objPHPExcel->getActiveSheet()->setCellValue('BC1', '装备13等级强化6折大礼包');
		$objPHPExcel->getActiveSheet()->setCellValue('BD1', '装备14等级强化6折大礼包');
		//$DataType = PHPExcel_Cell_DataType::TYPE_STRING;//科学型 改成字符串型
		
		if (is_array($result)) {
			foreach($result as $k => $item){
				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $item['yxpt']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item['gameXfu']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item['date']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item['zuoqiFive']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item['zuoqiSix']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item['zuoqiSeven']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item['quanshenzhyFive']);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item['quanshenzhySix']);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item['quanshenzhyEight']);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item['quanshenzhbSix']);
				$objPHPExcel->getActiveSheet()->setCellValue('K'.($k+2), $item['quanshenzhbEight']);
				$objPHPExcel->getActiveSheet()->setCellValue('L'.($k+2), $item['quanshenzhbTwe']);
				$objPHPExcel->getActiveSheet()->setCellValue('M'.($k+2), $item['danbi500']);
				$objPHPExcel->getActiveSheet()->setCellValue('N'.($k+2), $item['danbi1000']);
				$objPHPExcel->getActiveSheet()->setCellValue('O'.($k+2), $item['danbi2000']);
				$objPHPExcel->getActiveSheet()->setCellValue('P'.($k+2), $item['danbi5000']);
				$objPHPExcel->getActiveSheet()->setCellValue('Q'.($k+2), $item['danbi10000']);
				$objPHPExcel->getActiveSheet()->setCellValue('R'.($k+2), $item['cost500']);
				$objPHPExcel->getActiveSheet()->setCellValue('S'.($k+2), $item['cost1000']);
				$objPHPExcel->getActiveSheet()->setCellValue('T'.($k+2), $item['cost2000']);
				$objPHPExcel->getActiveSheet()->setCellValue('U'.($k+2), $item['cost5000']);
				$objPHPExcel->getActiveSheet()->setCellValue('V'.($k+2), $item['cost5000']);
				$objPHPExcel->getActiveSheet()->setCellValue('W'.($k+2), $item['cost10000']);
				$objPHPExcel->getActiveSheet()->setCellValue('X'.($k+2), $item['zhqzhekFive']);
				$objPHPExcel->getActiveSheet()->setCellValue('Y'.($k+2), $item['zhqzhekSix']);
				$objPHPExcel->getActiveSheet()->setCellValue('Z'.($k+2), $item['zhqzhekSeven']);
				$objPHPExcel->getActiveSheet()->setCellValue('AA'.($k+2), $item['zhqzhekNine']);
				$objPHPExcel->getActiveSheet()->setCellValue('AB'.($k+2), $item['zhuySixzheBig']);
				$objPHPExcel->getActiveSheet()->setCellValue('AC'.($k+2), $item['zhuySixzheA']);
				$objPHPExcel->getActiveSheet()->setCellValue('AD'.($k+2), $item['zhuySixzheB']);
				$objPHPExcel->getActiveSheet()->setCellValue('AE'.($k+2), $item['zhuySixzheC']);
				$objPHPExcel->getActiveSheet()->setCellValue('AF'.($k+2), $item['zhuySixzheD']);
				$objPHPExcel->getActiveSheet()->setCellValue('AG'.($k+2), $item['zhuySixzheE']);
				$objPHPExcel->getActiveSheet()->setCellValue('AH'.($k+2), $item['zhuyNinezheBig']);
				$objPHPExcel->getActiveSheet()->setCellValue('AI'.($k+2), $item['zhuyNinezheA']);
				$objPHPExcel->getActiveSheet()->setCellValue('AJ'.($k+2), $item['zhuyNinezheB']);
				$objPHPExcel->getActiveSheet()->setCellValue('AK'.($k+2), $item['zhuyNinezheC']);
				$objPHPExcel->getActiveSheet()->setCellValue('AL'.($k+2), $item['zhuyNinezheD']);
				$objPHPExcel->getActiveSheet()->setCellValue('AM'.($k+2), $item['zhuyNinezheE']);
				$objPHPExcel->getActiveSheet()->setCellValue('AN'.($k+2), $item['zhenyuanzheSeven']);
				$objPHPExcel->getActiveSheet()->setCellValue('AO'.($k+2), $item['zhenyuanzheSix']);
				$objPHPExcel->getActiveSheet()->setCellValue('AP'.($k+2), $item['zhenyuanzheFive']);
				$objPHPExcel->getActiveSheet()->setCellValue('AQ'.($k+2), $item['zhenyuanzheNine']);
				$objPHPExcel->getActiveSheet()->setCellValue('AR'.($k+2), $item['zhuanbeideng89']);
				$objPHPExcel->getActiveSheet()->setCellValue('AS'.($k+2), $item['zhuanbeideng99']);
				$objPHPExcel->getActiveSheet()->setCellValue('AT'.($k+2), $item['zhuanbeideng109']);
				$objPHPExcel->getActiveSheet()->setCellValue('AU'.($k+2), $item['zhuanbeideng129']);
				$objPHPExcel->getActiveSheet()->setCellValue('AV'.($k+2), $item['zhuanbeideng139']);
				$objPHPExcel->getActiveSheet()->setCellValue('AW'.($k+2), $item['zhuanbeideng149']);
				$objPHPExcel->getActiveSheet()->setCellValue('AX'.($k+2), $item['zhuanbeideng86']);
				$objPHPExcel->getActiveSheet()->setCellValue('AY'.($k+2), $item['zhuanbeideng96']);
				$objPHPExcel->getActiveSheet()->setCellValue('AZ'.($k+2), $item['zhuanbeideng106']);
				$objPHPExcel->getActiveSheet()->setCellValue('BA'.($k+2), $item['zhuanbeideng119']);
				$objPHPExcel->getActiveSheet()->setCellValue('BB'.($k+2), $item['zhuanbeideng116']);
				$objPHPExcel->getActiveSheet()->setCellValue('BC'.($k+2), $item['zhuanbeideng126']);
				$objPHPExcel->getActiveSheet()->setCellValue('BD'.($k+2), $item['zhuanbeideng146']);
			}	
		}	

		$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "单服活动数据分析_".date('Y_m_d H_i_s');
			
			
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
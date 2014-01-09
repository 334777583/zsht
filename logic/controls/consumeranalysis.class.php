<?php
/**
 * FileName: consumerAnalysis.class.php
 * Description:用户行为消耗分析
 * Author: BestWell
 * Date:2013-11-13
 * Version:1.00
 */
class consumeranalysis{


	/**
	 * 登录用户信息
	 */
	private $user;

	/**
	 * 初始化数据
	 */
	

	public function getResult(){
		//require $ServicePath.'/yanfa_1/logic/class/autoAjaxPage.class.php';
		$point = D(GNAME.$_POST['sip']);
		
		
		$size = isset($_POST['page_num'])&& !empty($_POST['page_num']) ? $_POST['page_num'] : 10;
		$wheresql = '';
		if(isset($_POST['yxpt']) || isset($_POST['wjlx']) || isset($_POST['ybxz']) || isset($_POST['qufu'])){ //判断是否有POST传值
			$wheresql = $this->getPost();
		}
		//$C = $chongZhi->query("SELECT  COUNT(*) as count FROM chongzhi ".$wheresql,'select');
		
		//$curPage = (isset($_POST['curPage']) && !empty($_POST['curPage'])) ? $_POST['curPage'] :1;
		//$page = new autoAjaxPage($size,$curPage,$C[0]['count'],"getResult","digGo","pagehtml");
		
		
		$result = $point->fquery("SELECT *  FROM chongzhi ".$wheresql);
		
		//判断数据类型并输出
		$returnArr = array();
		if(is_array($result) && !empty($result)){
			foreach ($result as $key => $value) {
				$returnArr[$key]['time'] = $value['time'];
				$returnArr[$key]['type'] = $value['type'];
				$returnArr[$key]['yxpt'] = $value['yxpt'];
				//  = $value['yxqf'];
				if ($_POST['sip'] == 1) {
					$returnArr[$key]['yxqf'] = '内网14';
				}else if ($_POST['sip'] == 3) {
					$returnArr[$key]['yxqf'] = '内网13';
				}else if ($_POST['sip'] == 4) {
					$returnArr[$key]['yxqf'] = '版署服';
				}else if ($_POST['sip'] == 5) {
					$returnArr[$key]['yxqf'] = 'text';
				}else if ($_POST['sip'] == 6) {
					$returnArr[$key]['yxqf'] = '外网测试';
				}else{
					$returnArr[$key]['yxqf'] = '14服分支';
				}
				if ($_POST['type'] == 1) {
					$returnArr[$key]['yuanbaochongmai'] = $value['yuanbaochongmai'];
					$returnArr[$key]['chdjhchzhizhifuben'] = $value['chdjhchzhizhifuben'];
					$returnArr[$key]['chdjhqingchushaodangCD'] = $value['chdjhqingchushaodangCD'];
					$returnArr[$key]['gumqychzhifuben'] = $value['gumqychzhifuben'];
					$returnArr[$key]['gumqyshaodangCD'] = $value['gumqyshaodangCD'];
					$returnArr[$key]['ningxgqcshlqCD'] = $value['ningxgqcshlqCD'];
					$returnArr[$key]['lxjysblq'] = $value['lxjysblq'];
					$returnArr[$key]['banhuijx'] = $value['banhuijx'];
					$returnArr[$key]['qhdjwmcc'] = $value['qhdjwmcc'];
					$returnArr[$key]['fengding'] = $value['fengding'];
					$returnArr[$key]['taohuazhen'] = $value['taohuazhen'];
					$returnArr[$key]['xunbao'] = $value['xunbao'];
					$returnArr[$key]['baixiang'] = $value['baixiang'];
					$returnArr[$key]['qianzhuan'] = $value['qianzhuan'];
				}elseif($_POST['type'] == 2){
					$returnArr[$key]['shangchangshop'] = $value['shangchangshop'];
					$returnArr[$key]['taohuazhen'] = $value['taohuazhen'];
				}else{
					$returnArr[$key]['shangchangshop'] = $value['shangchangshop'];
					$returnArr[$key]['qiyu'] = $value['qiyu'];
					$returnArr[$key]['bhjx'] = $value['bhjx'];
					$returnArr[$key]['qitxh'] = $value['qitxh'];
					$returnArr[$key]['meizhouzb'] = $value['meizhouzb'];
					$returnArr[$key]['qiangkun'] = $value['qiangkun'];
					$returnArr[$key]['xinghun'] = $value['xinghun'];
					$returnArr[$key]['qianghua'] = $value['qianghua'];
					$returnArr[$key]['xinfa'] = $value['xinfa'];
				}
			}
			$str = json_encode($returnArr);
			echo $str;
			}else{
				echo 1;
			}
		
		

		
	}

	public function writeExcel(){
		$point = D(GNAME.$_GET['ip']);
		
		$result = $point->fquery("SELECT *  FROM chongzhi WHERE time BETWEEN '{$_GET['startdate']}' AND '{$_GET['enddate']}'");

		if ($_GET['ip'] == 1) {
			$game = '内网14';
		}else if ($_GET['ip'] == 3) {
			$game = '内网13';
		}else if ($_GET['ip'] == 4) {
			$game = '版署服';
		}else if ($_GET['ip'] == 5) {
			$game = 'text';
		}else if ($_GET['ip'] == 6) {
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
		//$objPHPExcel->getActiveSheet()->mergeCells('A1:A2', '时间');
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '平台');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '游戏区服');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '日期');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '元宝冲脉');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '闯荡江湖重置副本');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', '闯荡江湖清除扫荡CD');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', '古墓奇缘重置副本');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', '古墓奇缘清除扫荡CD');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', '凝香阁清除收获冷却CD');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', '离线经验三倍领取');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', '帮会捐献元宝');
		$objPHPExcel->getActiveSheet()->setCellValue('L1', '强化等级捐献完美传承');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', '凤鼎练兵采集凤血玄铁');
		$objPHPExcel->getActiveSheet()->setCellValue('N1', '桃花阵夺宝会向上一层传送');
		$objPHPExcel->getActiveSheet()->setCellValue('O1', '寻宝');
		$objPHPExcel->getActiveSheet()->setCellValue('P1', '拜仙');
		$objPHPExcel->getActiveSheet()->setCellValue('Q1', '钱庄');
		$objPHPExcel->getActiveSheet()->setCellValue('R1', '商城购买');
		$objPHPExcel->getActiveSheet()->setCellValue('S1', '奇遇');
		$objPHPExcel->getActiveSheet()->setCellValue('T1', '帮会捐献');
		$objPHPExcel->getActiveSheet()->setCellValue('U1', '其他消耗（包括坐骑升阶、招式升级、美人招聘等）');
		$objPHPExcel->getActiveSheet()->setCellValue('V1', '每周珍宝');
		$objPHPExcel->getActiveSheet()->setCellValue('W1', '乾坤');
		$objPHPExcel->getActiveSheet()->setCellValue('X1', '星魂');
		$objPHPExcel->getActiveSheet()->setCellValue('Y1', '强化');
		$objPHPExcel->getActiveSheet()->setCellValue('Z1', '心法');
		
		//$DataType = PHPExcel_Cell_DataType::TYPE_STRING;//科学型 改成字符串型
		
		if (is_array($result)) {
			foreach($result as $k => $item){
				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $item["yxpt"]);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $game);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["time"]);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["yuanbaochongmai"]);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["chdjhchzhizhifuben"]);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["chdjhqingchushaodangCD"]);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["gumqychzhifuben"]);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["gumqyshaodangCD"]);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item["ningxgqcshlqCD"]);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item["lxjysblq"]);
				$objPHPExcel->getActiveSheet()->setCellValue('K'.($k+2), $item["banhuijx"]);
				$objPHPExcel->getActiveSheet()->setCellValue('L'.($k+2), $item["qhdjwmcc"]);
				$objPHPExcel->getActiveSheet()->setCellValue('M'.($k+2), $item["fengding"]);
				$objPHPExcel->getActiveSheet()->setCellValue('N'.($k+2), $item["taohuazhen"]);
				$objPHPExcel->getActiveSheet()->setCellValue('O'.($k+2), $item["xunbao"]);
				$objPHPExcel->getActiveSheet()->setCellValue('P'.($k+2), $item["baixiang"]);
				$objPHPExcel->getActiveSheet()->setCellValue('Q'.($k+2), $item["qianzhuan"]);
				$objPHPExcel->getActiveSheet()->setCellValue('R'.($k+2), $item["shangchangshop"]);
				//$objPHPExcel->getActiveSheet()->setCellValue('S'.($k+2), $item["banghuijuanxian"]);
				$objPHPExcel->getActiveSheet()->setCellValue('S'.($k+2), $item["qiyu"]);
				$objPHPExcel->getActiveSheet()->setCellValue('T'.($k+2), $item["bhjx"]);
				$objPHPExcel->getActiveSheet()->setCellValue('U'.($k+2), $item["qitxh"]);
				$objPHPExcel->getActiveSheet()->setCellValue('V'.($k+2), $item["meizhouzb"]);
				$objPHPExcel->getActiveSheet()->setCellValue('W'.($k+2), $item["qiangkun"]);
				$objPHPExcel->getActiveSheet()->setCellValue('X'.($k+2), $item["xinghun"]);
				$objPHPExcel->getActiveSheet()->setCellValue('Y'.($k+2), $item["qianghua"]);
				$objPHPExcel->getActiveSheet()->setCellValue('Z'.($k+2), $item["xinfa"]);
			}
		}	

		$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "用户行为消耗分析_".date('Y_m_d H_i_s');
			
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
			header('Cache-Control: max-age=0');


			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		exit;
		//}

	}

	//返回货币类型
	public function getType(){
		echo $_POST['type'];
	}

	public function getPlayer(){
		if ($_POST['type'] == 1) {
			echo $_POST['wjlx'].'玩家元宝消费占比';
		}elseif($_POST['type'] == 2){
			echo $_POST['wjlx'].'玩家绑定元宝消费占比';
		}else{
			echo $_POST['wjlx'].'玩家铜钱消费占比';
		}
	}

	//统计数据显示图片
	public function showImg(){
		$point = D(GNAME.$_POST['sip']);
		
			$wheresql = $this->getPost();
		
		
		$total_result = array();
		if ($_POST['type'] == 1) {
			$total_result = $point->fquery("SELECT SUM(yuanbaochongmai) as 元宝冲脉,SUM(chdjhchzhizhifuben) as 闯荡江湖重置副本,SUM(chdjhqingchushaodangCD) as 闯荡江湖清除扫荡CD,SUM(gumqychzhifuben) as 古墓奇缘重置副本,SUM(gumqyshaodangCD) as 古墓奇缘清除扫荡CD,SUM(ningxgqcshlqCD) as 凝香阁清除收获冷却CD,SUM(lxjysblq) as 离线经验三倍领取,SUM(banhuijx) as 帮会捐献元宝,SUM(qhdjwmcc) as 强化等级捐献完美传承,SUM(fengding) as 凤鼎练兵采集凤血玄铁,SUM(taohuazhen) as 桃花阵夺宝会向上一层传送,SUM(xunbao) as 寻宝,SUM(baixiang) as 拜仙,SUM(qianzhuan) as 钱庄 FROM chongzhi ".$wheresql,'select');
		}elseif($_POST['type'] == 2){
			$total_result = $point->fquery("SELECT SUM(shangchangshop) as 商城购买,SUM(taohuazhen) as 桃花阵夺宝会向上一层传送 FROM chongzhi ".$wheresql,'select');
		}else{
			$total_result = $point->fquery("SELECT SUM(shangchangshop) as 商城购买,SUM(qiyu) as 奇遇,SUM(qianghua) as 强化,SUM(xinfa) as 心法,SUM(bhjx) as 帮会贡献,SUM(meizhouzb) as 每周珍宝,SUM(qiangkun) as 乾坤,SUM(xinghun) as 星魂 FROM chongzhi ".$wheresql,'select');
		}
		
		foreach ($total_result[0] as $key => $value) {
			if ($total_result[0][$key] == '') {
				$total_result[0][$key] = 0;
			}
		};

		// print_r($total_result[0]);
		$total_num = array_sum($total_result[0]);

		$arr = array();
		foreach ($total_result[0] as $key => $value) {
			$arr[] = '{"name":"'.$key.'","num":'.$value.'}';
		}
		if (!empty($arr)) {
			$str = implode(',', $arr);
			$str2 = '['.$str.']';
			echo $str2;
		}else{
			echo 1;
		}
		
	}

	//获取POST提交的数据
	private function getPost(){
		$where = array();

			
			if(isset($_POST['wjlx']) && !empty($_POST['wjlx'])) {
				if($_POST['wjlx'] == '大R'){
					$where[] = "shangchangshop >= 5000";
				}elseif($_POST['wjlx'] == '中R'){
					$where[] = "shangchangshop < 5000 AND shangchangshop >= 500";
				}else{
					$where[] = "shangchangshop < 500 AND shangchangshop >= 1";
				}
			}
		
			if(isset($_POST['type']) && !empty($_POST['type'])){
				$where[] = "type =".$_POST['type'];
			}

			if(isset($_POST['startdate']) && !empty($_POST['startdate']) && isset($_POST['enddate']) && !empty($_POST['enddate'])){
				
				 $where[] = "time BETWEEN '".$_POST['startdate']."' AND '".$_POST['enddate']."'";
			}
			
			$wheresql = "WHERE ".implode(' AND ',$where);
			return $wheresql;
	}

	
}
?>
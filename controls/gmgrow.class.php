<?php
/**
 * FileName: gmgrow.class.php
 * Description:用户成长日志页面
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-3-28 上午11:36:42
 * Version:1.00
 */
class gmgrow{
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
	 * 服务器IP
	 * @var string
	 */
	private $ip;
	
	/**
	 * 开始时间
	 * @var string
	 */
	private $startdate;
	
	/**
	 * 结束时间
	 * @var string
	 */
	private $enddate;
	
	/**
	 * 检索模式（0：账号；1：昵称；2：ID）
	 * @var int
	 */
	private $type;
	
	/**
	 * 查询内容
	 * @var string;
	 */
	private $text;
	
	/**
	 * 是否模糊查询（0：是；1：否）
	 * @var int
	 */
	private $fuzzy;
	
	/**
	 * gm接口类
	 * @var class
	 */
	public $gm;
	
	/**
	 * 用户信息
	 */
	public $user;
	
	/**
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo "not available!";
			exit();
		}else{
			if(!in_array("00401000", $this->user["code"])){
				echo "not available!";
				exit();
			}
		}
		$this->pageSize = get_var_value("pageSize") == NULL?2:get_var_value("pageSize");
		$this->curPage =  get_var_value("curPage") == NULL?1:get_var_value("curPage");
		$this->ip =  get_var_value("ip") == NULL?-1:get_var_value("ip");
		$this->type =  get_var_value("type") == NULL?0:get_var_value("type");
		$this->text =  get_var_value("text") == NULL?"":get_var_value("text");
		$this->fuzzy =  get_var_value("fuzzy") == NULL?0:get_var_value("fuzzy");
		$this->gm = new autogm();
		$this->startdate = get_var_value("startDate") == NULL?date("Y-m-d",strtotime("-7 day")):get_var_value("startDate");
		$this->enddate = get_var_value("endDate") == NULL?date("Y-m-d"):get_var_value("endDate");
	}
	
	/**
	 * 获取角色概况信息
	 */
	public function getrole(){
		$obj = D('game_info');
		$num = get_var_value('num');
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$listime = $obj ->table('role_list')->field('creattime')->order('creattime asc')->limit(0,1)->find();
		$this->startdate = get_var_value("startDate") == NULL?$listime['creattime']:get_var_value("startDate");
		
		$rolelist = $obj-> table("role_list") ->where(array("creattime >="=>$this->startdate,"creattime <="=>$this->enddate))->limit(0,$time)-> select();
		
		$filename = '';
			if(isset($list) && count($list) > 0){
				//封装数据
				//$result = $list;//输出数据
				$excel = $rolelist;//excel 数据
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
			
		echo json_encode(array('list'=>$rolelist,'startDate'=>$this->startdate,'filename' => $filename,'endDate'=>$this->enddate));
		exit;
	}
	
	/**
	 * 获取战斗属性信息
	 */
	public function getbattle(){
		$obj = D('game_info');
		$num = get_var_value('num');
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$listime = $obj ->table('better_list')->field('createtime')->order('createtime asc')->limit(0,1)->find();
		$this->startdate = get_var_value("startDate") == NULL?$listime['createtime']:get_var_value("startDate");
		
		// $list = $obj -> table('role_list')-> select();
		$rolelist = $obj-> table("better_list") ->where(array("createtime >="=>$this->startdate,"createtime <="=>$this->enddate))->limit(0,$time)-> select();
		
		echo json_encode(array('list'=>$rolelist,'startDate'=>$this->startdate,'endDate'=>$this->enddate));
		exit;
		
	}
	
	
	/**
	 * 获取装备信息
	 */
	public function getequip(){
		$ip = get_var_value('ip');
		$obj = D('troh_game');
		$curPage = get_var_value('curPage') == NULL ? 1 : get_var_value('curPage');//分页
		$pageSize = 2;
		$start_limit = intval(($curPage-1)*$pageSize);
		$end_limit = intval($pageSize);
		$ListSql = 'select t.last_down_time time,t.account_code code,t.player_id id,t.level,t.name,p.value from t_player t inner join t_container_equ p on t.player_id = p.player_id ORDER BY t.level desc limit '.$start_limit.','.$end_limit;
		$GetList = $obj->fquery($ListSql);
		foreach($GetList as $key => $value){
			$GetList[$key]['value']=json_decode($GetList[$key]['value'],true);
			$list[$key]['code'] = $GetList[$key]['code'];
			$list[$key]['id'] = $GetList[$key]['id'];
			$list[$key]['time'] = date("Y-m-d",$GetList[$key]['time']);
			$list[$key]['name'] = $GetList[$key]['name'];
			$list[$key]['level'] = $GetList[$key]['level'];
			for($k=1;$k<=11;$k++){
				if(is_array($GetList[$key]['value']['container'][$k])){
					$list[$key]['info'][$k]['protoId'] = $GetList[$key]['value']['container'][$k]['protoId'];
					$list[$key]['info'][$k]['enLv'] = $GetList[$key]['value']['container'][$k]['enLv'];
					$list[$key]['info'][$k]['suitLv'] = $GetList[$key]['value']['container'][$k]['itEqAt']['suitLv'];
					$list[$key]['info'][$k]['kfmax'] = $GetList[$key]['value']['container'][$k]['itEqAt']['baseEqAt']['kfAtmax'];	//最大外功攻击
					$list[$key]['info'][$k]['kfmin'] = $GetList[$key]['value']['container'][$k]['itEqAt']['baseEqAt']['kfAtMin'];	//最小外功攻击
					$list[$key]['info'][$k]['dgmax'] = $GetList[$key]['value']['container'][$k]['itEqAt']['baseEqAt']['dgAtMax'];	//最大内功攻击
					$list[$key]['info'][$k]['dgmin'] = $GetList[$key]['value']['container'][$k]['itEqAt']['baseEqAt']['dgAtMin'];	//最小内功攻击
					$list[$key]['info'][$k]['fpmin'] = $GetList[$key]['value']['container'][$k]['itEqAt']['baseEqAt']['fpAtMin'];	//最小暗器攻击
					$list[$key]['info'][$k]['fpmax'] = $GetList[$key]['value']['container'][$k]['itEqAt']['baseEqAt']['fpAtMax'];	//最大暗器攻击
					$list[$key]['info'][$k]['kfdf'] = $GetList[$key]['value']['container'][$k]['itEqAt']['baseEqAt']['kfDf'];		//外功防御
					$list[$key]['info'][$k]['dgdf'] = $GetList[$key]['value']['container'][$k]['itEqAt']['baseEqAt']['dgDf'];		//内功防御
					$list[$key]['info'][$k]['hpmax'] = $GetList[$key]['value']['container'][$k]['itEqAt']['baseEqAt']['hpMax'];	//生命
					$list[$key]['info'][$k]['mpmax'] = $GetList[$key]['value']['container'][$k]['itEqAt']['baseEqAt']['mpMax'];	//内力
				}
			}
		}
		
		// $total = $obj -> table('t_container_equ')
					 // -> where( array('s_type' => $this->type, 's_date >= ' => $this->startdate,'s_date <= '=> $this->enddate) )
					 // -> total();	
		$total = 500;			  
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'pageAjax2','go2','page2');
		
		$pageHtml = $page->getPageHtml();
		
		echo json_encode(array('list'=>$list,'startDate'=>$this->startdate,'endDate'=>$this->enddate,'pageHtml'=>$pageHtml));
		exit;
	}
	
	/**
	 * 获取心法信息
	 */
	public function getheart(){
		$obj = D('game_info');
		$num = get_var_value('num');
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		//limit ".intval($page->getOff()).",".intval($this->pageSize)
		// $list = $obj -> table('role_list')-> select();
		$listime = $obj ->table('heart_list')->field('creattime')->order('creattime asc')->limit(0,1)->find();
		$this->startdate = get_var_value("startDate") == NULL?$listime['creattime']:get_var_value("startDate");
		//$total = $obj -> table('heart_list')
					// -> where( array('s_type' => $this->type, 's_date >= ' => $this->startdate,'s_date <= '=> $this->enddate) )
					 // -> where($list_sql)
					// -> total();	
		
		//$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'pageAjax2','go2','page2');
		
		$rolelist = $obj-> table("heart_list") ->where(array("creattime >="=>$this->startdate,"creattime <="=>$this->enddate))-> select();
					  
		//$pageHtml = $page->getPageHtml();			 
		
		echo json_encode(array('list'=>$rolelist,'startDate'=>$this->startdate,'endDate'=>$this->enddate,));
		exit;
		
	}
	
	/**
	 * 获取坐骑、乾坤、星魂、帮会技能信息
	 */
	public function getskill(){
		$obj = D('game_info');
		$num = get_var_value('num');
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$total = $obj->table('prop_list')->group('p_name,p_sever')->total();
		$ipList = autoConfig::getIPS();		//获取服务器信息
		
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'pageAjax','go','page');
		$pageHtml = $page->getPageHtml();
		
		$listime = $obj ->table('skill_list')->field('createtime')->order('createtime asc')->limit(0,1)->find();
		$this->startdate = get_var_value("startDate") == NULL?$listime['createtime']:get_var_value("startDate");
		// $list = $obj -> table('role_list')-> select();
		$rolelist = $obj-> table("skill_list") ->where(array("createtime >="=>$this->startdate,"createtime <="=>$this->enddate))->limit(0,$time)-> select();
		echo json_encode(array('list'=>$rolelist,'startDate'=>$this->startdate,'endDate'=>$this->enddate));
		exit;
		
	}
	
	/**
	 * 导出角色概况excel
	 */
	public function taskexcel(){
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		$obj = D('game_info');
		$num = get_var_value('ran');
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$list = $obj-> table("role_list") ->where(array("creattime >="=>$this->startdate,"creattime <="=>$this->enddate))->limit(0,$time)-> select();
		
		
		if(!empty($list)){

			// require_once(AClass.'phpexcel/PHPExcel.php');
			
			// $objPHPExcel = new PHPExcel();
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
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '账号');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', '角色id');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', '角色名称');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '职业');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '性别');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '角色等级');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', 'vip');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', '声望');
			$objPHPExcel->getActiveSheet()->setCellValue('J1', '成就点数');
			$objPHPExcel->getActiveSheet()->setCellValue('K1', '称号');
			$objPHPExcel->getActiveSheet()->setCellValue('L1', '古墓奇缘进度');
			$objPHPExcel->getActiveSheet()->setCellValue('M1', '名动江湖进度');
			$objPHPExcel->getActiveSheet()->setCellValue('N1', '武林名宿进度');
			$objPHPExcel->getActiveSheet()->setCellValue('O1', '帮派');
			$objPHPExcel->getActiveSheet()->setCellValue('P1', '击杀世界boss');
			$objPHPExcel->getActiveSheet()->setCellValue('Q1', '帮战次数');
			$objPHPExcel->getActiveSheet()->setCellValue('R1', '悬赏任务完成次数');
			$objPHPExcel->getActiveSheet()->setCellValue('S1', '清剿任务完成次数');
			$objPHPExcel->getActiveSheet()->setCellValue('T1', '冲脉次数');
			$objPHPExcel->getActiveSheet()->setCellValue('U1', '押镖次数');
			$objPHPExcel->getActiveSheet()->setCellValue('V1', '修炼时间');
			$objPHPExcel->getActiveSheet()->setCellValue('W1', '暗金美人');
			$objPHPExcel->getActiveSheet()->setCellValue('X1', '好友数量');
			$objPHPExcel->getActiveSheet()->setCellValue('Y1', '罪恶值');
			$objPHPExcel->getActiveSheet()->setCellValue('Z1', '击杀玩家');
			$objPHPExcel->getActiveSheet()->setCellValue('AA1', '被击杀');
			
			if (is_array($list)) {
				foreach($list as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["creattime"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["r_code"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["u_id"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["r_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["r_job"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["r_sex"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["r_group"]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["r_vip"]);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item["r_pre"]);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item["r_achi"]);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.($k+2), $item["u_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.($k+2), $item["gmqy"]);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.($k+2), $item["mdjh"]);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.($k+2), $item["wlms"]);
					$objPHPExcel->getActiveSheet()->setCellValue('O'.($k+2), $item["team"]);
					$objPHPExcel->getActiveSheet()->setCellValue('P'.($k+2), $item["wboss"]);
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.($k+2), $item["bz"]);
					$objPHPExcel->getActiveSheet()->setCellValue('R'.($k+2), $item["xsrw"]);
					$objPHPExcel->getActiveSheet()->setCellValue('S'.($k+2), $item["qjrw"]);
					$objPHPExcel->getActiveSheet()->setCellValue('T'.($k+2), $item["cmnum"]);
					$objPHPExcel->getActiveSheet()->setCellValue('U'.($k+2), $item["yb"]);
					$objPHPExcel->getActiveSheet()->setCellValue('V'.($k+2), $item["xl"]);
					$objPHPExcel->getActiveSheet()->setCellValue('W'.($k+2), $item["ajmr"]);
					$objPHPExcel->getActiveSheet()->setCellValue('X'.($k+2), $item["gfnum"]);
					$objPHPExcel->getActiveSheet()->setCellValue('Y'.($k+2), $item["sin"]);
					$objPHPExcel->getActiveSheet()->setCellValue('Z'.($k+2), $item["r_kill"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AA'.($k+2), $item["die"]);
				}	
			}

		$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "角色概况_".$startdate;
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		exit;
		
		}

	}
	
	/**
	 * 导出战斗属性excel
	 */
	public function battleexcel(){
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		$obj = D('game_info');
		$num = get_var_value('ran');
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$list = $obj-> table("better_list") ->where(array("createtime >="=>$this->startdate,"createtime <="=>$this->enddate,"u_level>="=>40))->limit(0,$time)->order('u_level desc')-> select();
		if(!empty($list)){

			require_once(AClass.'phpexcel/PHPExcel.php');
			
			$objPHPExcel = new PHPExcel();
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
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '账号');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', '角色id');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', '角色名称');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '战力');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '最小外功攻击');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '最大外功攻击');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', '外功防御');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', '最小内功攻击');
			$objPHPExcel->getActiveSheet()->setCellValue('J1', '最大内功攻击');
			$objPHPExcel->getActiveSheet()->setCellValue('K1', '内功防御');
			$objPHPExcel->getActiveSheet()->setCellValue('L1', '最小暗器攻击');
			$objPHPExcel->getActiveSheet()->setCellValue('M1', '最大暗器攻击');
			$objPHPExcel->getActiveSheet()->setCellValue('N1', '生命');
			$objPHPExcel->getActiveSheet()->setCellValue('O1', '内力');
			$objPHPExcel->getActiveSheet()->setCellValue('P1', '筋骨');
			$objPHPExcel->getActiveSheet()->setCellValue('Q1', '体魄');
			$objPHPExcel->getActiveSheet()->setCellValue('R1', '悟性');
			$objPHPExcel->getActiveSheet()->setCellValue('S1', '身法');
			$objPHPExcel->getActiveSheet()->setCellValue('T1', '属性点');
			$objPHPExcel->getActiveSheet()->setCellValue('U1', '幸运');
			$objPHPExcel->getActiveSheet()->setCellValue('V1', '掉宝加成');
			$objPHPExcel->getActiveSheet()->setCellValue('W1', '经验加成');
			$objPHPExcel->getActiveSheet()->setCellValue('X1', '掉金加成');
			$objPHPExcel->getActiveSheet()->setCellValue('Y1', '移动速度');
			$objPHPExcel->getActiveSheet()->setCellValue('Z1', '生命恢复');
			$objPHPExcel->getActiveSheet()->setCellValue('AA1', '内力恢复');
			$objPHPExcel->getActiveSheet()->setCellValue('AB1', '命中等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AC1', '闪避等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AD1', '格挡等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AE1', '破格等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AF1', '暴击等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AG1', '抗暴等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AH1', '暴击伤害');
			$objPHPExcel->getActiveSheet()->setCellValue('AI1', '侵蚀抗性');
			$objPHPExcel->getActiveSheet()->setCellValue('AJ1', '裂伤抗性');
			$objPHPExcel->getActiveSheet()->setCellValue('AK1', '破甲抗性');
			$objPHPExcel->getActiveSheet()->setCellValue('AL1', '致盲抗性');
			$objPHPExcel->getActiveSheet()->setCellValue('AM1', '虚弱抗性');
			$objPHPExcel->getActiveSheet()->setCellValue('AN1', '沉默抗性');
			$objPHPExcel->getActiveSheet()->setCellValue('AO1', '眩晕抗性');
			$objPHPExcel->getActiveSheet()->setCellValue('AP1', '侵蚀');
			$objPHPExcel->getActiveSheet()->setCellValue('AQ1', '裂伤');
			$objPHPExcel->getActiveSheet()->setCellValue('AR1', '破甲');
			$objPHPExcel->getActiveSheet()->setCellValue('AS1', '致盲');
			$objPHPExcel->getActiveSheet()->setCellValue('AT1', '虚弱');
			$objPHPExcel->getActiveSheet()->setCellValue('AU1', '沉默');
			$objPHPExcel->getActiveSheet()->setCellValue('AV1', '眩晕');
			$objPHPExcel->getActiveSheet()->setCellValue('AW1', '装备战力');
			$objPHPExcel->getActiveSheet()->setCellValue('AX1', '基础属性');
			$objPHPExcel->getActiveSheet()->setCellValue('AY1', '强化属性');
			$objPHPExcel->getActiveSheet()->setCellValue('AZ1', '卓越属性');
			$objPHPExcel->getActiveSheet()->setCellValue('BA1', '追加属性');
			$objPHPExcel->getActiveSheet()->setCellValue('BB1', '心法战力');
			$objPHPExcel->getActiveSheet()->setCellValue('BC1', '骑乘坐骑');
			$objPHPExcel->getActiveSheet()->setCellValue('BD1', '星魂战力');
			$objPHPExcel->getActiveSheet()->setCellValue('BE1', '神兵战力');
			$objPHPExcel->getActiveSheet()->setCellValue('BF1', '其他战力');
			$objPHPExcel->getActiveSheet()->setCellValue('BG1', '升级战力');
			$objPHPExcel->getActiveSheet()->setCellValue('BH1', '经脉战力');
			$objPHPExcel->getActiveSheet()->setCellValue('BI1', '乾坤战力');
			$objPHPExcel->getActiveSheet()->setCellValue('BJ1', '帮会战力');
			$objPHPExcel->getActiveSheet()->setCellValue('BK1', '称号战力');
			$objPHPExcel->getActiveSheet()->setCellValue('BL1', 'vip战力');
			
			if (is_array($list)) {
				foreach($list as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["createtime"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["b_code"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["u_id"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["u_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["b_power"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["wgmin"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["wgmax"]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["wgfy"]);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item["ngmin"]);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item["ngmax"]);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.($k+2), $item["ngfy"]);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.($k+2), $item["aqmin"]);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.($k+2), $item["aqmax"]);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.($k+2), $item["life"]);
					$objPHPExcel->getActiveSheet()->setCellValue('O'.($k+2), $item["nl"]);
					$objPHPExcel->getActiveSheet()->setCellValue('P'.($k+2), $item["jg"]);
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.($k+2), $item["tp"]);
					$objPHPExcel->getActiveSheet()->setCellValue('R'.($k+2), $item["wx"]);
					$objPHPExcel->getActiveSheet()->setCellValue('S'.($k+2), $item["sf"]);
					$objPHPExcel->getActiveSheet()->setCellValue('T'.($k+2), $item["attribute"]);
					$objPHPExcel->getActiveSheet()->setCellValue('U'.($k+2), $item["luck"]);
					$objPHPExcel->getActiveSheet()->setCellValue('V'.($k+2), $item["db"]);
					$objPHPExcel->getActiveSheet()->setCellValue('W'.($k+2), $item["exp"]);
					$objPHPExcel->getActiveSheet()->setCellValue('X'.($k+2), $item["dj"]);
					$objPHPExcel->getActiveSheet()->setCellValue('Y'.($k+2), $item["speed"]);
					$objPHPExcel->getActiveSheet()->setCellValue('Z'.($k+2), $item["life_re"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AA'.($k+2), $item["nl_re"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AB'.($k+2), $item["hit"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AC'.($k+2), $item["dodge"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AD'.($k+2), $item["dg"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AE'.($k+2), $item["pg"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AF'.($k+2), $item["bj"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AG'.($k+2), $item["kb"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AH'.($k+2), $item["bjsh"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AI'.($k+2), $item["qskx"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AJ'.($k+2), $item["lskx"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AK'.($k+2), $item["pjkx"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AL'.($k+2), $item["zmkx"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AM'.($k+2), $item["xrkx"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AN'.($k+2), $item["cmkx"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AO'.($k+2), $item["xykx"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AP'.($k+2), $item["qs"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AQ'.($k+2), $item["ls"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AR'.($k+2), $item["pj"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AS'.($k+2), $item["zm"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AT'.($k+2), $item["weak"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AU'.($k+2), $item["slien"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AV'.($k+2), $item["xy"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AW'.($k+2), $item["zbzl"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AX'.($k+2), $item["jcsx"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AY'.($k+2), $item["qhsx"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AZ'.($k+2), $item["zysx"]);
					$objPHPExcel->getActiveSheet()->setCellValue('BA'.($k+2), $item["zjsx"]);
					$objPHPExcel->getActiveSheet()->setCellValue('BB'.($k+2), $item["xfzl"]);
					$objPHPExcel->getActiveSheet()->setCellValue('BC'.($k+2), $item["jczq"]);
					$objPHPExcel->getActiveSheet()->setCellValue('BD'.($k+2), $item["xhzl"]);
					$objPHPExcel->getActiveSheet()->setCellValue('BE'.($k+2), $item["sbzl"]);
					$objPHPExcel->getActiveSheet()->setCellValue('BF'.($k+2), $item["qtzl"]);
					$objPHPExcel->getActiveSheet()->setCellValue('BG'.($k+2), $item["sjzl"]);
					$objPHPExcel->getActiveSheet()->setCellValue('BH'.($k+2), $item["jmzl"]);
					$objPHPExcel->getActiveSheet()->setCellValue('BI'.($k+2), $item["qkzl"]);
					$objPHPExcel->getActiveSheet()->setCellValue('BJ'.($k+2), $item["bhzl"]);
					$objPHPExcel->getActiveSheet()->setCellValue('BK'.($k+2), $item["chzl"]);
					$objPHPExcel->getActiveSheet()->setCellValue('BL'.($k+2), $item["vipzl"]);
				}	
			}

			$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "战斗属性_".$startdate;
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		exit;
		
		}

	}
	
	/**
	 * 导出装备excel
	 */
	public function equipexcel(){
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		$obj = D('game_info');
		$num = get_var_value('ran');
		
		$ListSql = 'select t.last_down_time time,t.account_code code,t.player_id id,t.level,t.name,p.value from t_player t inner join t_container_equ p on t.player_id = p.player_id ORDER BY t.level desc limit 0,'.$num;
		$GetList = $obj->fquery($ListSql);
		foreach($GetList as $key => $value){
			$GetList[$key]['value']=json_decode($GetList[$key]['value'],true);
			$list[$key]['code'] = $GetList[$key]['code'];
			$list[$key]['id'] = $GetList[$key]['id'];
			$list[$key]['time'] = date("Y-m_d",$GetList[$key]['time']);
			$list[$key]['name'] = $GetList[$key]['name'];
			$list[$key]['level'] = $GetList[$key]['level'];
			for($k=1;$k<=11;$k++){
				if(is_array($GetList[$key]['value']['container'][$k])){
					$list[$key][$k]['protoId'] = $GetList[$key]['value']['container'][$k]['protoId'];
					$list[$key][$k]['enLv'] = $GetList[$key]['value']['container'][$k]['enLv'];
					$list[$key][$k]['suitLv'] = $GetList[$key]['value']['container'][$k]['itEqAt']['suitLv'];
					$list[$key][$k]['kfmax'] = $GetList[$key]['value']['container'][$k]['itEqAt']['baseEqAt']['kfAtmax'];	//最大外功攻击
					$list[$key][$k]['kfmin'] = $GetList[$key]['value']['container'][$k]['itEqAt']['baseEqAt']['kfAtMin'];	//最小外功攻击
					$list[$key][$k]['dgmax'] = $GetList[$key]['value']['container'][$k]['itEqAt']['baseEqAt']['dgAtMax'];	//最大内功攻击
					$list[$key][$k]['dgmin'] = $GetList[$key]['value']['container'][$k]['itEqAt']['baseEqAt']['dgAtMin'];	//最小内功攻击
					$list[$key][$k]['fpmin'] = $GetList[$key]['value']['container'][$k]['itEqAt']['baseEqAt']['fpAtMin'];	//最小暗器攻击
					$list[$key][$k]['fpmax'] = $GetList[$key]['value']['container'][$k]['itEqAt']['baseEqAt']['fpAtMax'];	//最大暗器攻击
					$list[$key][$k]['kfdf'] = $GetList[$key]['value']['container'][$k]['itEqAt']['baseEqAt']['kfDf'];		//外功防御
					$list[$key][$k]['dgdf'] = $GetList[$key]['value']['container'][$k]['itEqAt']['baseEqAt']['dgDf'];		//内功防御
					$list[$key][$k]['hpmax'] = $GetList[$key]['value']['container'][$k]['itEqAt']['baseEqAt']['hpMax'];	//生命
					$list[$key][$k]['mpmax'] = $GetList[$key]['value']['container'][$k]['itEqAt']['baseEqAt']['mpMax'];	//内力
				}
			}
		}
		
		$list = json_decode(file_get_contents($f),true);
		if(!empty($list)){

			require_once(AClass.'phpexcel/PHPExcel.php');
			
			$objPHPExcel = new PHPExcel();
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
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '账号');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', '角色id');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', '角色名称');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '装备id');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '装备部位');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '装备颜色');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', '装备等级');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', '强化等级');
			$objPHPExcel->getActiveSheet()->setCellValue('J1', '卓越等级');
			$objPHPExcel->getActiveSheet()->setCellValue('K1', '基础最小外功攻击');
			$objPHPExcel->getActiveSheet()->setCellValue('L1', '基础最大外功攻击');
			$objPHPExcel->getActiveSheet()->setCellValue('M1', '基础外功防御');
			$objPHPExcel->getActiveSheet()->setCellValue('N1', '基础最小内功攻击');
			$objPHPExcel->getActiveSheet()->setCellValue('O1', '基础最大内功攻击');
			$objPHPExcel->getActiveSheet()->setCellValue('P1', '基础内功防御');
			$objPHPExcel->getActiveSheet()->setCellValue('Q1', '基础最小暗器攻击');
			$objPHPExcel->getActiveSheet()->setCellValue('R1', '基础最大暗器攻击');
			$objPHPExcel->getActiveSheet()->setCellValue('S1', '基础生命');
			$objPHPExcel->getActiveSheet()->setCellValue('T1', '基础内力');
			$objPHPExcel->getActiveSheet()->setCellValue('U1', '追加属性1');
			$objPHPExcel->getActiveSheet()->setCellValue('V1', '追加颜色1');
			$objPHPExcel->getActiveSheet()->setCellValue('W1', '追加属性值1');
			$objPHPExcel->getActiveSheet()->setCellValue('X1', '追加属性2');
			$objPHPExcel->getActiveSheet()->setCellValue('Y1', '追加颜色2');
			$objPHPExcel->getActiveSheet()->setCellValue('Z1', '追加属性值2');
			$objPHPExcel->getActiveSheet()->setCellValue('AA1', '追加属性3');
			$objPHPExcel->getActiveSheet()->setCellValue('AB1', '追加颜色3');
			$objPHPExcel->getActiveSheet()->setCellValue('AC1', '追加属性值3');
			$objPHPExcel->getActiveSheet()->setCellValue('AD1', '追加属性4');
			$objPHPExcel->getActiveSheet()->setCellValue('AE1', '追加颜色4');
			$objPHPExcel->getActiveSheet()->setCellValue('AF1', '追加属性值4');
			$objPHPExcel->getActiveSheet()->setCellValue('AG1', '卓越心法1_名称');
			$objPHPExcel->getActiveSheet()->setCellValue('AH1', '卓越心法2_名称');
			$objPHPExcel->getActiveSheet()->setCellValue('AI1', '卓越心法3_名称');
			$objPHPExcel->getActiveSheet()->setCellValue('AJ1', '卓越心法4_名称');
			$objPHPExcel->getActiveSheet()->setCellValue('AK1', '卓越心法5_名称');
			
			if (is_array($list)) {
				foreach($list as $k => $item){
					foreach($item as $ke => $it){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["level"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["two_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["two_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["three_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["three_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["four_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["four_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["five_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item["five_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item["six_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.($k+2), $item["six_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.($k+2), $item["seven_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('O'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('P'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('R'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('S'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('T'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('U'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('V'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('W'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('X'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('Y'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('Z'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AA'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AB'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AC'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AD'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AE'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AF'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AG'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AH'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AI'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AJ'.($k+2), $item["seven_per"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AK'.($k+2), $item["seven_per"]);
				}	
			}	
			

			$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "装备_".$startdate;
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
			exit;
			}
		}

	}
	
	/**
	 * 导出坐骑、乾坤、星魂、帮会技能eexcel
	 */
	public function skillexcel(){
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		$obj = D('game_info');
		$num = get_var_value('ran');
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$list = $obj-> table("skill_list") ->where(array("createtime >="=>$this->startdate,"createtime <="=>$this->enddate))->limit(0,$time)-> select();
		if(!empty($list)){

			require_once(AClass.'phpexcel/PHPExcel.php');
			
			$objPHPExcel = new PHPExcel();
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
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '账号');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', '角色id');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', '角色名称');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '坐骑等阶');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '显示等阶');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '神兵等阶');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', '装备毒1');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', '装备毒2');
			$objPHPExcel->getActiveSheet()->setCellValue('J1', '装备毒3');
			$objPHPExcel->getActiveSheet()->setCellValue('K1', '星魂总等级');
			$objPHPExcel->getActiveSheet()->setCellValue('L1', '帮会技能总等级');
			$objPHPExcel->getActiveSheet()->setCellValue('M1', '坐骑技能总等级');
			$objPHPExcel->getActiveSheet()->setCellValue('N1', '坐骑技能1等级');
			$objPHPExcel->getActiveSheet()->setCellValue('O1', '坐骑技能2等级');
			$objPHPExcel->getActiveSheet()->setCellValue('P1', '坐骑技能3等级');
			$objPHPExcel->getActiveSheet()->setCellValue('Q1', '坐骑技能4等级');
			$objPHPExcel->getActiveSheet()->setCellValue('R1', '坐骑技能5等级');
			$objPHPExcel->getActiveSheet()->setCellValue('S1', '坐骑技能6等级');
			$objPHPExcel->getActiveSheet()->setCellValue('T1', '坐骑技能7等级');
			$objPHPExcel->getActiveSheet()->setCellValue('U1', '坐骑技能8等级');
			$objPHPExcel->getActiveSheet()->setCellValue('V1', '坐骑技能9等级');
			$objPHPExcel->getActiveSheet()->setCellValue('W1', '坐骑技能10等级');
			$objPHPExcel->getActiveSheet()->setCellValue('X1', '坐骑技能11等级');
			$objPHPExcel->getActiveSheet()->setCellValue('Y1', '坐骑技能12等级');
			$objPHPExcel->getActiveSheet()->setCellValue('Z1', '星魂武曲等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AA1', '星魂紫薇等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AB1', '星魂贪狼等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AC1', '星魂巨门等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AD1', '星魂左辅等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AE1', '星魂右弼等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AF1', '星魂禄存等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AG1', '星魂破军等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AH1', '星魂文曲等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AI1', '帮会养生等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AJ1', '帮会强身术等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AK1', '帮会运气术等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AL1', '帮会暗杀术等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AM1', '帮会灵心术等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AN1', '帮会坚韧术等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AO1', '帮会兴奋术等级');
			$objPHPExcel->getActiveSheet()->setCellValue('AP1', '帮会精准等级');
			
			if (is_array($list)) {
				foreach($list as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["createtime"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["u_code"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["u_id"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["u_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["zjdj"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["xsdj"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["sbdj"]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["zbd1"]);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item["zbd2"]);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item["zbd3"]);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.($k+2), $item["xhdj"]);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.($k+2), $item["bhjn"]);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.($k+2), $item["zjjn"]);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.($k+2), $item["zjjn1"]);
					$objPHPExcel->getActiveSheet()->setCellValue('O'.($k+2), $item["zjjn2"]);
					$objPHPExcel->getActiveSheet()->setCellValue('P'.($k+2), $item["zjjn3"]);
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.($k+2), $item["zjjn4"]);
					$objPHPExcel->getActiveSheet()->setCellValue('R'.($k+2), $item["zjjn5"]);
					$objPHPExcel->getActiveSheet()->setCellValue('S'.($k+2), $item["zjjn6"]);
					$objPHPExcel->getActiveSheet()->setCellValue('T'.($k+2), $item["zjjn7"]);
					$objPHPExcel->getActiveSheet()->setCellValue('U'.($k+2), $item["zjjn8"]);
					$objPHPExcel->getActiveSheet()->setCellValue('V'.($k+2), $item["zjjn9"]);
					$objPHPExcel->getActiveSheet()->setCellValue('W'.($k+2), $item["zjjn10"]);
					$objPHPExcel->getActiveSheet()->setCellValue('X'.($k+2), $item["zjjn11"]);
					$objPHPExcel->getActiveSheet()->setCellValue('Y'.($k+2), $item["zjjn12"]);
					$objPHPExcel->getActiveSheet()->setCellValue('Z'.($k+2), $item["xhwq"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AA'.($k+2), $item["xhzw"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AB'.($k+2), $item["xhtl"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AC'.($k+2), $item["xhjm"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AD'.($k+2), $item["xhzf"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AE'.($k+2), $item["xhyb"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AF'.($k+2), $item["xhlc"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AG'.($k+2), $item["xhpj"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AH'.($k+2), $item["xhwqdj"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AI'.($k+2), $item["bhys"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AJ'.($k+2), $item["bhqs"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AK'.($k+2), $item["bhyq"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AL'.($k+2), $item["bhas"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AM'.($k+2), $item["bhjr"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AN'.($k+2), $item["bhlx"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AO'.($k+2), $item["bhxf"]);
					$objPHPExcel->getActiveSheet()->setCellValue('AP'.($k+2), $item["bhjz"]);
				}	
			

				$objPHPExcel->getActiveSheet()->setTitle('Simple');

				$objPHPExcel->setActiveSheetIndex(0);
				$file_name = "技能_".$startdate;
				
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save('php://output');
				exit;
			}
		}

	}
	
	/**
	 * 导出心法xcel
	 */
	public function heartexcel(){
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		$obj = D('game_info');
		$num = get_var_value('ran');
		if($num == 1){
			$time = 10;
		}elseif($num == 2){
			$time = 50;
		}elseif($num == 3){
			$time = 100;
		}else{
			$time = 500;
		}
		$list = $obj-> table("heart_list") ->where(array("creattime >="=>$this->startdate,"creattime <="=>$this->enddate))->limit(0,$time)-> select();
		if(!empty($list)){

			require_once(AClass.'phpexcel/PHPExcel.php');
			
			$objPHPExcel = new PHPExcel();
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
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '账号');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', '角色id');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', '角色名称');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '心法名称');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '基础等级');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '卓越等级');
			
			if (is_array($list)) {
				foreach($list as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["creattime"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["h_code"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["u_id"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["u_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["h_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["base"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["excell"]);
				}

				$objPHPExcel->getActiveSheet()->setTitle('Simple');

				$objPHPExcel->setActiveSheetIndex(0);
				$file_name = "心法_".$startdate;
				
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save('php://output');
				exit;
			
			}

		}
	}
	
}
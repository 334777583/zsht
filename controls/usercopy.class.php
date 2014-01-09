<?php
/**
 * FileName: usercopy.class.php
 * Description:副本页面
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-3-28 上午11:36:42
 * Version:1.00
 */
class usercopy{
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
			if(!in_array("00400900", $this->user["code"])){
				echo "not available!";
				exit();
			}
		}
		$this->pageSize = get_var_value("pageSize") == NULL?10:get_var_value("pageSize");
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
	 * 古墓奇缘信息
	 */
	public function getromance(){
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
		$rolelist = $obj-> table("copy_list")->field('u_code,u_id,u_name,c_job,u_group,c_name,copy_id,c_min,c_die,c_date') ->where(array("c_date >="=>$this->startdate,"c_date <="=>$this->enddate,'copy_id'=>2))->limit(0,$time)->order('u_group desc')-> select();
		echo json_encode(array('list'=>$rolelist,'startDate'=>$this->startdate,'endDate'=>$this->enddate));
		exit;
	}
	
	/**
	 * 获取闯荡江湖信息
	 */
	public function getwander(){
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
		// $list = $obj -> table('role_list')-> select();
		$rolelist = $obj-> table("copy_list")->field('u_code,u_id,u_name,c_job,u_group,c_name,copy_id,c_min,c_die,c_date') ->where(array("c_date >="=>$this->startdate,"c_date <="=>$this->enddate,'copy_id'=>1))->limit(0,$time)->order('u_group desc')-> select();
		
		echo json_encode(array('list'=>$rolelist,'startDate'=>$this->startdate,'endDate'=>$this->enddate));
		exit;
		
	}
	
	/**
	 * 武林名宿信息
	 */
	public function getlegend(){
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
		$rolelist = $obj-> table("copy_list")->field('u_code,u_id,u_name,c_job,u_group,c_name,copy_id,c_min,c_die,c_date') ->where(array("c_date >="=>$this->startdate,"c_date <="=>$this->enddate,'copy_id'=>8))->limit(0,$time)->order('u_group desc')-> select();
		
		echo json_encode(array('list'=>$rolelist,'startDate'=>$this->startdate,'endDate'=>$this->enddate));
		exit;
	}
	
	/**
	 * 护送出关信息
	 */
	public function getescort(){
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
		// $list = $obj -> table('role_list')-> select();
		$rolelist = $obj-> table("copy_list")->field('u_code,u_id,u_name,c_job,u_group,c_name,difficul,c_date,xq_level,xq_hp,c_time,t_kill,t_time,die_num,t_name1,t_name2,t_name3') ->where(array("c_date >="=>$this->startdate,"c_date <="=>$this->enddate,'copy_id'=>6))->limit(0,$time)->order('u_group desc')-> select();
		echo json_encode(array('list'=>$rolelist,'startDate'=>$this->startdate,'endDate'=>$this->enddate));
		exit;
		
	}
	
	/**
	 * 名动江湖信息
	 */
	public function getmdong(){
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
		// $list = $obj -> table('role_list')-> select();
		$rolelist = $obj-> table("copy_list")->field('u_code,u_id,u_name,c_job,u_group,c_name,b_num,b_time,b_kill,die_num,t_name1,t_name2,t_name3,c_date') ->where(array("c_date >="=>$this->startdate,"c_date <="=>$this->enddate,'copy_id'=>5))->limit(0,$time)->order('u_group desc')-> select();
		echo json_encode(array('list'=>$rolelist,'startDate'=>$this->startdate,'endDate'=>$this->enddate));
		exit;
		
	}
	/**
	 * 太湖水贼信息
	 */
	public function gettaihu(){
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
		// $list = $obj -> table('role_list')-> select();
		$rolelist = $obj-> table("copy_list")->field('u_code,u_id,u_name,c_job,u_group,c_name,b_num,b_time,b_kill,die_num,t_name1,t_name2,t_name3,c_date') ->where(array("c_date >="=>$this->startdate,"c_date <="=>$this->enddate,'copy_id'=>4))->limit(0,$time)->order('u_group desc')-> select();
		echo json_encode(array('list'=>$rolelist,'startDate'=>$this->startdate,'endDate'=>$this->enddate));
		exit;
		
	}
	/**
	 * 帮会boss信息
	 */
	public function getgang(){
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
		// $list = $obj -> table('role_list')-> select();
		$rolelist = $obj-> table("copy_list")->field('c_name,boss_hp,c_time,join_num,pk_die,boss_kill,c_date') ->where(array("c_date >="=>$this->startdate,"c_date <="=>$this->enddate,'copy_id'=>9))->limit(0,$time)->order('u_group desc')-> select();
		echo json_encode(array('list'=>$rolelist,'startDate'=>$this->startdate,'endDate'=>$this->enddate));
		exit;
		
	}
	/**
	 * 世界boss信息
	 */
	public function getworld(){
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
		// $list = $obj -> table('role_list')-> select();
		$rolelist = $obj-> table("copy_list")->field('c_name,boss_name,map,refresh,boss_hp,b_time,c_date') ->where(array("c_date >="=>$this->startdate,"c_date <="=>$this->enddate,'copy_id'=>10))->limit(0,$time)->order('u_group desc')-> select();
		echo json_encode(array('list'=>$rolelist,'startDate'=>$this->startdate,'endDate'=>$this->enddate));
		exit;
		
	}
	
	/**
	 * 导出闯荡江湖excel
	 */
	public function wanderExcel(){
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		
		$obj = D('game_info');
		$ip = get_var_value('ip');
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
		$list = $obj-> table("copy_list")->field('u_code,u_id,u_name,c_job,u_group,c_name,copy_id,c_min,c_die,c_date') ->where(array("c_date >="=>$this->startdate,"c_date <="=>$this->enddate,'copy_id'=>1))->limit(0,$time)->order('u_group desc')-> select();
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
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '职业');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '角色等级');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '副本类型');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', '副本id');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', '历史最短通关时间');
			$objPHPExcel->getActiveSheet()->setCellValue('J1', '历史最高死亡次数');
			
			if (is_array($list)) {
				foreach($list as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["c_date"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["u_code"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["u_id"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["u_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["c_job"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["u_group"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["c_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["copy_id"]);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item["c_min"]);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item["c_die"]);
				}	
			}

		$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "闯荡江湖_".$startdate;
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		exit;
		
		}

	}
	
	/**
	 * 导出古墓奇缘excel
	 */
	public function romanceExcel(){
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		
		$obj = D('game_info');
		$ip = get_var_value('ip');
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
		$list = $obj-> table("copy_list")->field('u_code,u_id,u_name,c_job,u_group,c_name,copy_id,c_min,c_die,c_date') ->where(array("c_date >="=>$this->startdate,"c_date <="=>$this->enddate,'copy_id'=>2))->limit(0,$time)->order('u_group desc')-> select();
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
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '职业');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '角色等级');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '副本类型');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', '副本id');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', '历史最短通关时间');
			$objPHPExcel->getActiveSheet()->setCellValue('J1', '历史最高死亡次数');
			
			if (is_array($list)) {
				foreach($list as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["c_date"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["u_code"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["u_id"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["u_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["c_job"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["u_group"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["c_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["copy_id"]);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item["c_min"]);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item["c_die"]);
				}	
			}

		$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "古墓奇缘_".$startdate;
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		exit;
		
		}

	}
	
	/**
	 * 导出武林名宿excel
	 */
	public function legendExcel(){
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		
		$obj = D('game_info');
		$ip = get_var_value('ip');
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
		$list = $obj-> table("copy_list")->field('u_code,u_id,u_name,c_job,u_group,c_name,copy_id,c_min,c_die,c_date') ->where(array("c_date >="=>$this->startdate,"c_date <="=>$this->enddate,'copy_id'=>8))->limit(0,$time)->order('u_group desc')-> select();
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
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '职业');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '角色等级');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '副本类型');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', '副本id');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', '历史最短通关时间');
			$objPHPExcel->getActiveSheet()->setCellValue('J1', '历史最高死亡次数');
			
			if (is_array($list)) {
				foreach($list as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["c_date"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["u_code"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["u_id"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["u_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["c_job"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["u_group"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["c_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["copy_id"]);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item["c_min"]);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item["c_die"]);
				}	
			

			$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);
			$file_name = "武林名宿_".$startdate;
			
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
	 * 导出护送出关excel
	 */
	public function escortExcel(){
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		
		$obj = D('game_info');
		$ip = get_var_value('ip');
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
		$list = $obj-> table("copy_list")->field('u_code,u_id,u_name,c_job,u_group,c_name,difficul,c_date,xq_level,xq_hp,c_time,t_kill,t_time,die_num,t_name1,t_name2,t_name3') ->where(array("c_date >="=>$this->startdate,"c_date <="=>$this->enddate,'copy_id'=>6))->limit(0,$time)->order('u_group desc')-> select();
		
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
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '职业');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '角色等级');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '副本类型');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', '副本难度');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', '小昭等级');
			$objPHPExcel->getActiveSheet()->setCellValue('J1', '小昭剩余血量');
			$objPHPExcel->getActiveSheet()->setCellValue('K1', '副本耗时（秒）');
			$objPHPExcel->getActiveSheet()->setCellValue('L1', '队伍击杀怪物数量');
			$objPHPExcel->getActiveSheet()->setCellValue('M1', '击杀所有怪物时间（秒）');
			$objPHPExcel->getActiveSheet()->setCellValue('N1', '死亡次数');
			$objPHPExcel->getActiveSheet()->setCellValue('O1', '队友1名称');
			$objPHPExcel->getActiveSheet()->setCellValue('P1', '队友2名称');
			$objPHPExcel->getActiveSheet()->setCellValue('Q1', '队友3名称');
			
			if (is_array($list)) {
				foreach($list as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["c_date"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["u_code"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["u_id"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["u_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["c_job"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["u_group"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["c_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["difficul"]);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item["xq_level"]);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item["xq_hp"]);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.($k+2), $item["c_time"]);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.($k+2), $item["t_kill"]);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.($k+2), $item["t_time"]);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.($k+2), $item["die_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('O'.($k+2), $item["t_name1"]);
					$objPHPExcel->getActiveSheet()->setCellValue('P'.($k+2), $item["t_name2"]);
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.($k+2), $item["t_name3"]);
				}	

				$objPHPExcel->getActiveSheet()->setTitle('Simple');

				$objPHPExcel->setActiveSheetIndex(0);
				$file_name = "护送出关_".$startdate;
				
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
	 * 导出名动江湖excel
	 */
	public function mdongExcel(){
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
		$list = $obj-> table("copy_list")->field('u_code,u_id,u_name,c_job,u_group,c_name,b_num,b_time,b_kill,die_num,t_name1,t_name2,t_name3,c_date') ->where(array("c_date >="=>$this->startdate,"c_date <="=>$this->enddate,'copy_id'=>5))->limit(0,$time)->order('u_group desc')-> select();
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
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '职业');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '角色等级');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '副本类型');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', '波数');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', '本波耗时（秒）');
			$objPHPExcel->getActiveSheet()->setCellValue('J1', '本波队伍杀怪物数量');
			$objPHPExcel->getActiveSheet()->setCellValue('K1', '死亡次数');
			$objPHPExcel->getActiveSheet()->setCellValue('L1', '队友1名称');
			$objPHPExcel->getActiveSheet()->setCellValue('M1', '队友2名称');
			$objPHPExcel->getActiveSheet()->setCellValue('N1', '队友3名称');
			
			if (is_array($list)) {
				foreach($list as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["c_date"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["u_code"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["u_id"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["u_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["c_job"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["u_group"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["c_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["b_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item["b_time"]);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item["b_kill"]);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.($k+2), $item["die_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.($k+2), $item["t_name1"]);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.($k+2), $item["t_name2"]);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.($k+2), $item["t_name3"]);
				}	
			

				$objPHPExcel->getActiveSheet()->setTitle('Simple');

				$objPHPExcel->setActiveSheetIndex(0);
				$file_name = "名动江湖_".$startdate;
				
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
	 * 导出太湖水贼excel
	 */
	public function taihuExcel(){
		$startdate = get_var_value('startdate');
		$enddate = get_var_value('enddate');
		
		$obj = D('game_info');
		$ip = get_var_value('ip');
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
		$list = $obj-> table("copy_list")->field('u_code,u_id,u_name,c_job,u_group,c_name,b_num,b_time,b_kill,die_num,t_name1,t_name2,t_name3,c_date') ->where(array("c_date >="=>$this->startdate,"c_date <="=>$this->enddate,'copy_id'=>4))->limit(0,$time)->order('u_group desc')-> select();
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
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '职业');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '角色等级');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '副本类型');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', '副本难度');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', '小昭等级');
			$objPHPExcel->getActiveSheet()->setCellValue('J1', '小昭剩余血量');
			$objPHPExcel->getActiveSheet()->setCellValue('K1', '副本耗时（秒）');
			$objPHPExcel->getActiveSheet()->setCellValue('L1', '队伍击杀怪物数量');
			$objPHPExcel->getActiveSheet()->setCellValue('M1', '击杀所有怪物时间（秒）');
			$objPHPExcel->getActiveSheet()->setCellValue('N1', '死亡次数');
			$objPHPExcel->getActiveSheet()->setCellValue('O1', '队友1名称');
			$objPHPExcel->getActiveSheet()->setCellValue('P1', '队友2名称');
			$objPHPExcel->getActiveSheet()->setCellValue('Q1', '队友3名称');
			
			if (is_array($list)) {
				foreach($list as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["c_date"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["u_code"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["u_id"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["u_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["c_job"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["u_group"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["c_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["b_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), $item["b_time"]);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.($k+2), $item["b_kill"]);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.($k+2), $item["die_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.($k+2), $item["t_name1"]);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.($k+2), $item["t_name2"]);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.($k+2), $item["t_name3"]);
				}	
			

				$objPHPExcel->getActiveSheet()->setTitle('Simple');

				$objPHPExcel->setActiveSheetIndex(0);
				$file_name = "太湖水贼_".$startdate;
				
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
	 * 导出帮会boss excel
	 */
	public function gangExcel(){
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
		$list = $obj-> table("copy_list")->field('c_name,boss_hp,c_time,join_num,pk_die,boss_kill,c_date') ->where(array("c_date >="=>$this->startdate,"c_date <="=>$this->enddate,'copy_id'=>9))->limit(0,$time)->order('u_group desc')-> select();
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
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '副本类型');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', 'boss剩余血量');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', '副本耗时（秒）');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '参与人数');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'PK死亡次数');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '被BOSS击杀次数');
			
			if (is_array($list)) {
				foreach($list as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["c_date"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["c_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["boss_hp"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["c_time"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["join_num"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["pk_die"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["boss_kill"]);
				}	
			

				$objPHPExcel->getActiveSheet()->setTitle('Simple');

				$objPHPExcel->setActiveSheetIndex(0);
				$file_name = "帮会BOSS_".$startdate;
				
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
	 * 导出世界boss excel
	 */
	public function worldExcel(){
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
		$list = $obj-> table("copy_list")->field('c_name,boss_name,map,refresh,boss_hp,b_time,c_date') ->where(array("c_date >="=>$this->startdate,"c_date <="=>$this->enddate,'copy_id'=>10))->limit(0,$time)->order('u_group desc')-> select();
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
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '副本类型');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', 'BOSS名称');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', '地图线路');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '刷新时间');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '刷新BOSS初始血量/总血量');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '击杀耗时（秒）');
			
			if (is_array($list)) {
				foreach($list as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.($k+2),$item["c_date"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["c_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["boss_name"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["map"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["refresh"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["boss_hp"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["b_time"]);
				}
			

				$objPHPExcel->getActiveSheet()->setTitle('Simple');

				$objPHPExcel->setActiveSheetIndex(0);
				$file_name = "世界BOSS_".$startdate;
				
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
<?php
/**
 * FileName: code.class.php
 * Description: 新手卡
 * Author: xiaochengcheng
 * Date: 2013-5-13 15:59:00
 * Version: 1.00
 **/
class code{
	/**
	 * 服务器IP
	 * @var string
	 */
	private $ip;
	
	/**
	 * 激活码类型(1:A;2:B;3:C;4:D;5:E;6:F)
	 * @var int
	 */
	private $codeType;
	
	/**
	 * 激活码数量
	 * @var int
	 */
	private $num;
	
	
	/**
	 * 时限
	 * @var int
	 */
	private $sxtime;
	
	/**
	 * 角色ID（0无绑定）
	 * @var int
	 */
	private $roleText;
	
	/**
	 * 礼品ID
	 * @var int
	 */
	private $toolsId;
	
	/**
	 * 激活码
	 * @var string
	 */
	private $code;
	
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
	 * 开始时间
	 * @var String
	 */
	private $startDate;
	
	/**
	 * 结束时间
	 * @var String
	 */
	private $endDate;


	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00500600', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
		$this->codeType = get_var_value('codeType') == NULL?0:get_var_value('codeType');
		$this->num =  get_var_value('num') == NULL?1:get_var_value('num');
		$this->ip =  get_var_value('ip') == NULL?-1:get_var_value('ip');
		$this->sxtime =  get_var_value('sxtime') == NULL?-1:get_var_value('sxtime');
		$this->roleText =  get_var_value('roleText') == NULL?'':get_var_value('roleText');
		$this->toolsId =  get_var_value('toolsId') == NULL?-1:get_var_value('toolsId');
		$this->code =  get_var_value('code') == NULL?'':get_var_value('code');
		$this->pageSize = get_var_value('pageSize') == NULL? 10: get_var_value('pageSize');
		$this->curPage =  get_var_value('curPage') == NULL? 1 : get_var_value('curPage');
		$this->startDate = get_var_value('startDate') == NULL? '' : get_var_value('startDate');
		$this->endDate = get_var_value('endDate') == NULL? '' : get_var_value('endDate');
		$this->searchKey =  get_var_value('searchKey') == NULL? '' : get_var_value('searchKey');
	}
	
	/**
	 * 生成激活码
	 */
	private function create($num) {
		$type_name = get_var_value('codeName');
		
		if($this->codeType == '6') {	//不限类型
			$type_name = '6';	
		}
	
		$key = 'xinshouka';
		
		$time = microtime(true);
		
		$code_arr = array();
		
		for($i = 0; $i<$num; $i++) {
			$code = md5($key.$time.$i);
			$first = strtoupper(substr($code, 0, 5));
			$second = substr($code, 5, 4);
			$code_arr[] = $type_name.$first.$second;
			//$code_arr[] = strtoupper(substr(md5($key.$time.$i), 0, 10));
		}
		
		return $code_arr;
	}

	
	/**
	 * 添加记录到游戏新手卡表
	 */
	public function addCode(){
		global $t_conf;
		$obj = D('game_info');
		$result =  $obj -> table('gamedb') -> where(array('g_flag'=>1, 'g_id' => $this->ip)) -> find();
		
		$ip = $result['g_ip'];
		$point = F($t_conf[$ip]['db'], $t_conf[$ip]['ip'], $t_conf[$ip]['user'], $t_conf[$ip]['password'], $t_conf[$ip]['port']);
		
		if(!$point){
			echo json_encode(array(
				'error' => '数据库连接失败！'
			));
			exit;
		}
		
		$code_arr = $this->create($this->num);
		$list = array(); 	//激活码详细信息
		
		
		$sql_data = '';
		$i=1;
		foreach($code_arr as $k => $code){
			$sql_data .= "('" . $code . "','" . $this->codeType . "','" . $this->roleText . "','" . $this->toolsId . "','" . time().'000' . "','" . strtotime($this->sxtime).'000' ."'),";
			
			if($i%1000 == 0 || $i == count($code_arr)) {
				$sql_data = rtrim($sql_data, ',');
				$sql_data .= ';';
				$sql = 'insert into t_new_hand_card(id,type,player_id,item_id,start_time,end_time) values ' . $sql_data;
				$f = $point->rquery($sql);
				$sql_data = "";
			}
			$i++;
			
			$list[] = array(
				'id' => $code,
				'type' => $this->codeType,
				'player_id' => $this->roleText,
				'ip' => $result['g_name'],
				'item_id' => $this->toolsId,
				'start_time' => date('Y-m-d H:i:s'),
				'used' =>  '未使用',
				'end_time'	=> $this->sxtime
			);
		}
		$filename = '';
		if(isset($list) && count($list) > 0){
			$tmpfname = tempnam('/tmp','ASDFGHJKEWRTYUI');
			$handle = fopen($tmpfname, "w");
			fwrite($handle, json_encode($list));
			fclose($handle);
			$filename = base64_encode($tmpfname);
		}
		echo json_encode(array(
				'list' => $list,
				'codes' => implode(',', $code_arr),
				'filename' =>$filename
			));
		exit;
	}
	
	/**
	 * 获取激活码信息
	 */
	public function getCode(){
		global $t_conf;
		$obj = D('game_info');
		$cstatus = get_var_value('cstatus');
		$ctype = get_var_value('ctype');
		$result =  $obj -> table('gamedb') -> where(array('g_flag'=>1, 'g_id' => $this->ip)) -> find();
		
		$ip = $result['g_ip'];
		$point = F($t_conf[$ip]['db'], $t_conf[$ip]['ip'], $t_conf[$ip]['user'], $t_conf[$ip]['password'], $t_conf[$ip]['port']);
		
		$where = '';
		
		if($cstatus != '') {
			$where .= 'used = '.$cstatus.' and ';
		}
		if($ctype) {
			$where .= 'type = '.$ctype.' and ';
		}
		
		if(!empty($this->code)){
			$where .= 'id like "%'.$this->code.'%" and ';
		}
		if(!empty($this->roleText)){
			$where .= '(player_id like "%'.$this->roleText.'%" or user_id like "%' . $this->roleText .'%") and ';
		}
		if(!empty($this->startDate) && !empty($this->endDate)){
			$where .= 'start_time >= '.strtotime($this->startDate).'000'.' and ';
			$where .= 'start_time < '.(strtotime($this->endDate)+86400).'000'.' and ';
		}
		
		if(empty($where)){
			$list = $point -> table('t_new_hand_card') -> limit(intval(($this->curPage-1)*$this->pageSize),intval($this->pageSize)) -> order('start_time desc') -> select();
			$total = $point -> table('t_new_hand_card') -> total();
		}else{
			$where = rtrim($where, ' and ');
			$list = $point -> table('t_new_hand_card') -> where($where)-> limit(intval(($this->curPage-1)*$this->pageSize),intval($this->pageSize)) -> order('start_time desc') -> select();
			$total = $point -> table('t_new_hand_card') -> where($where) -> total();
		}
		
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'pageAjax','go','page');
		$pageHtml = $page->getPageHtml();
		echo json_encode(array(
			'list' => $list,
			'sname' => $result['g_name'],
			'pageHtml'=>$pageHtml
		));
		exit;
	}
	
	/**
	 * @name: getNewExcel
	 * @description: 生成json数据
	 * @param: null
	 * @return: 1
	 * @author: xiaochengcheng
	 * @create: 2013-5-14 12:11:54
	**/
	// public function getNewExcel(){
		// $codes = get_var_value('codes');
		// if($codes){
			// $tmpfname = tempnam('/tmp','ASDFGHJKEWRTYUI');
			// $handle = fopen($tmpfname, "w");
			// fwrite($handle, json_encode($codes));
			// fclose($handle);
			// echo base64_encode($tmpfname);
		// }else{
			// echo 0;
		// }
	// }
	
	
	
	/**
	 * @name: excel
	 * @description: 导出excel
	 * @param: null
	 * @return: http1.1
	 * @author: xiaochengcheng
	 * @create: 2013-5-14 12:11:54
	**/
	public function excel(){
		$f = base64_decode($_GET['f']);
		if(!is_file($f)){
			echo 'error';
			exit();
		}
		// $handle = fopen($f, "r");
		// $list = json_decode(fread($handle, filesize ($f)),true);
		// fclose($handle);
		$list = json_decode(file_get_contents($f),true);
		// echo $f;
		// exit;
		// unlink($f);
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
			$objPHPExcel->getActiveSheet()->setCellValue('A1', '激活码'); 
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '激活类型');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', '激活码详情');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', '服务器');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', '激活码状态');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', '角色ID');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', '生成时间');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', '使用时限');
			
			if (is_array($list)) {
				foreach($list as $k => $item){
					$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $item["id"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $item["type"]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $item["item_id"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $item["ip"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $item["used"]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $item["player_id"]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $item["start_time"]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), $item["end_time"]);
				}	
			}	

			$objPHPExcel->getActiveSheet()->setTitle('Simple');

			$objPHPExcel->setActiveSheetIndex(0);

			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="激活码.xlsx"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		exit;

		}
	}
	
	
	/**
	 * 道具ID与道具名称关系
	 */
	public function getToolDetail(){
		$obj = D(GNAME.$this->ip);
		$list = array();			//道具ID与道具名称列表
		$total = 0;					//记录总数
		
		$key = get_var_value('searchKey');
		
		if(!$key){
			$total = $obj->table('tools_detail') -> where('t_type = "GIFT"') ->total();
		}else{
			$total = $obj->table('tools_detail')-> where(array('t_name like' => '%'.$key.'%', 't_type' => 'GIFT'))->total();
		}
		
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'pageAjax2','go2','page2');
		$pageHtml = $page->getPageHtml();
		
		if(!$key){
			$resource = $obj->table('tools_detail') -> where('t_type = "GIFT"') -> limit(intval($page->getOff()),intval($this->pageSize))->select();
		}else{
			$resource = $obj->table('tools_detail') -> where('(t_name like "%' . $key . '%" or t_code like "%' . $key . '%") and t_type = "GIFT"')->limit(intval($page->getOff()),intval($this->pageSize))->select();
		}
		
		if(is_array($resource)){
			$list =  $resource;
		}
		$result = array(
				'list' => $list,
				'pageHtml'=>$pageHtml
		);
		echo json_encode($result);
		exit;
	}
	
	/**
	 *  根据查询条件导出数据到Txt文本
	*/
	public function writeTxt() {
		ini_set('memory_limit', -1);
		set_time_limit(0);
		
		global $t_conf;
		$cstatus = get_var_value('cstatus');
		$ctype = get_var_value('ctype');
		
		$obj = D('game_info');
		$result =  $obj -> table('gamedb') -> where(array('g_flag'=>1, 'g_id' => $this->ip)) -> find();
		
		$ip = $result['g_ip'];
		$name = $result['g_name'];
		$point = F($t_conf[$ip]['db'], $t_conf[$ip]['ip'], $t_conf[$ip]['user'], $t_conf[$ip]['password'], $t_conf[$ip]['port']);
		
		$where = '';
		
		if($cstatus || $cstatus == 0) {
			$where .= 'used = '.$cstatus.' and ';
		}
		if($ctype) {
			$where .= 'type = '.$ctype.' and ';
		}
		if(!empty($this->code)){
			$where .= 'id like "%'.$this->code.'%" and ';
		}
		if(!empty($this->roleText)){
			$where .= '(player_id like "%'.$this->roleText.'%" or user_id like "%' . $this->roleText .'%") and ';
		}
		if(!empty($this->startDate) && !empty($this->endDate)){
			$where .= 'start_time >= '.strtotime($this->startDate).'000'.' and ';
			$where .= 'start_time < '.(strtotime($this->endDate)+86400).'000'.' and ';
		}
		
		if(empty($where)){
			$list = $point -> table('t_new_hand_card') -> order('start_time desc') -> select();
		}else{
			$where = rtrim($where, ' and ');
			$list = $point -> table('t_new_hand_card') -> where($where) -> order('start_time desc') -> select();
		}
		
		if($list != '') {
			$str = "";
			//$str = "激活码\t激活码类型\t激活码详情\t服务器\t激活码状态\t限制角色\t使用角色\t角色等级\t生成时间\t使用时限\t使用时间\r\n";	//输出到txt的文本
			foreach($list as $k => $item){
				switch($item["type"]){
					case 1 : $list[$k]["type"] = "A";break;
					case 2 : $list[$k]["type"] = "B";break;
					case 3 : $list[$k]["type"] = "C";break;
					case 4 : $list[$k]["type"] = "D";break;
					case 5 : $list[$k]["type"] = "E";break;
					case 7 : $list[$k]["type"] = "F";break;
					case 8 : $list[$k]["type"] = "G";break;
					case 9 : $list[$k]["type"] = "H";break;
					case 10 : $list[$k]["type"] = "I";break;
					case 11 : $list[$k]["type"] = "J";break;
					case 12 : $list[$k]["type"] = "K";break;
					case 13 : $list[$k]["type"] = "L";break;
					case 14 : $list[$k]["type"] = "M";break;
					case 15 : $list[$k]["type"] = "N";break;
					case 16 : $list[$k]["type"] = "O";break;
					case 17 : $list[$k]["type"] = "P";break;
					case 18 : $list[$k]["type"] = "Q";break;
					case 19 : $list[$k]["type"] = "R";break;
					case 20 : $list[$k]["type"] = "S";break;
					case 21 : $list[$k]["type"] = "T";break;
					case 22 : $list[$k]["type"] = "U";break;
					case 23 : $list[$k]["type"] = "V";break;
					case 24 : $list[$k]["type"] = "W";break;
					case 25 : $list[$k]["type"] = "X";break;
					case 26 : $list[$k]["type"] = "Y";break;
					case 27 : $list[$k]["type"] = "Z";break;
					case 6 : $list[$k]["type"] = "不限";break;
				}
				
				
				switch($item["used"]){
					case 0 : $list[$k]["used"] = "未使用";break;
					case 1 : $list[$k]["used"] = "已使用";break;
				}
					
				if($item["player_id"] == '0'){
					$list[$k]["player_id"] = "不限";
				}
				
				if($item["end_time"] == '0'){
					$list[$k]["end_time"] = "不限";
				}else{
					$list[$k]["end_time"] = $this->curentByTime($item["end_time"]);
				}
				
				if($item["used_time"] != '0'){
					$list[$k]["used_time"] = $this->curentByTime($item["used_time"]);
				}
				
				if($item["start_time"] != '0'){
					$list[$k]["start_time"] = $this->curentByTime($item["start_time"]);
				}
				
				if($item["user_id"] == '0'){
					$list[$k]["user_id"] = "未使用";
				}
				
				$str .= $list[$k]["id"] ."\t";
				$str .= $list[$k]["type"] ."\t";
				$str .= $list[$k]["item_id"] ."\t";
				$str .= $name ."\t";
				$str .= $list[$k]["used"] ."\t";
				$str .= $list[$k]["player_id"] ."\t";
				$str .= $list[$k]["user_id"] ."\t";
				$str .= $list[$k]["user_level"] ."\t";
				$str .= $list[$k]["start_time"] ."\t";
				$str .= $list[$k]["end_time"] ."\t";
				$str .= $list[$k]["used_time"] ."\r\n";
			}
			
			header( "Pragma: public" );
			header( "Expires: 0" );
			header( 'Content-Encoding: none' );
			header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
			header( "Cache-Control: public" );
			header( "Content-type: application/octet-stream\n" );
			header( "Content-Description: File Transfer" );
			header( "Content-Disposition: attachment; filename=code.txt");
			header( "Content-Transfer-Encoding: binary" );
			@ob_clean();
			flush();
			echo $str;
			exit;
		}
	}
	
	/**
	 *  查询导出全部数据excel
	 */
	public function writeExcel(){
		ini_set('memory_limit', -1);
		set_time_limit(0);
		global $t_conf;
		$obj = D('game_info');
		$result =  $obj -> table('gamedb') -> where(array('g_flag'=>1, 'g_id' => $this->ip)) -> find();
		
		$ip = $result['g_ip'];
		$name = $result['g_name'];
		$point = F($t_conf[$ip]['db'], $t_conf[$ip]['ip'], $t_conf[$ip]['user'], $t_conf[$ip]['password'], $t_conf[$ip]['port']);
		
		$list = $point -> table('t_new_hand_card') -> order('start_time desc') -> select();
	
		require_once(AClass.'phpexcel/PHPExcel.php');
		
		// $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;  
		// $cacheSettings = array( ' memoryCacheSize '  => '50MB'  
                      // ); 
		// PHPExcel_Settings::setCacheStorageMethod($cacheMethod,$cacheSettings);  
		
				
		// $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
	 
					  
		// PHPExcel_Settings::setCacheStorageMethod ( $cacheMethod, $cacheSettings );
		
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("PHPExcel Test Document")
							 ->setSubject("PHPExcel Test Document")
							 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("Test result file");
							 
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '激活码');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '激活码类型');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '激活码详情');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '服务器');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '激活码状态');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', '限制角色');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', '使用角色');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', '角色等级');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', '生成时间');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', '使用时限');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', '使用时间');

		
		$obj = $objPHPExcel->getActiveSheet();

		if (is_array($list)) {
			foreach($list as $k => $item){
				switch($item["type"]){
					case 1 : $list[$k]["type"] = "A";break;
					case 2 : $list[$k]["type"] = "B";break;
					case 3 : $list[$k]["type"] = "C";break;
					case 4 : $list[$k]["type"] = "D";break;
					case 5 : $list[$k]["type"] = "E";break;
					case 7 : $list[$k]["type"] = "F";break;
					case 8 : $list[$k]["type"] = "G";break;
					case 9 : $list[$k]["type"] = "H";break;
					case 10 : $list[$k]["type"] = "I";break;
					case 11 : $list[$k]["type"] = "J";break;
					case 12 : $list[$k]["type"] = "K";break;
					case 13 : $list[$k]["type"] = "L";break;
					case 14 : $list[$k]["type"] = "M";break;
					case 15 : $list[$k]["type"] = "N";break;
					case 16 : $list[$k]["type"] = "O";break;
					case 17 : $list[$k]["type"] = "P";break;
					case 18 : $list[$k]["type"] = "Q";break;
					case 19 : $list[$k]["type"] = "R";break;
					case 20 : $list[$k]["type"] = "S";break;
					case 21 : $list[$k]["type"] = "T";break;
					case 22 : $list[$k]["type"] = "U";break;
					case 23 : $list[$k]["type"] = "V";break;
					case 24 : $list[$k]["type"] = "W";break;
					case 25 : $list[$k]["type"] = "X";break;
					case 26 : $list[$k]["type"] = "Y";break;
					case 27 : $list[$k]["type"] = "Z";break;
					case 6 : $list[$k]["type"] = "不限";break;
				}
				
				
				switch($item["used"]){
					case 0 : $list[$k]["used"] = "未使用";break;
					case 1 : $list[$k]["used"] = "已使用";break;
				}
					
				if($item["player_id"] == '0'){
					$list[$k]["player_id"] = "不限";
				}
				
				if($item["end_time"] == '0'){
					$list[$k]["end_time"] = "不限";
				}else{
					$list[$k]["end_time"] = $this->curentByTime($item["end_time"]);
				}
				
				if($item["used_time"] != '0'){
					$list[$k]["used_time"] = $this->curentByTime($item["used_time"]);
				}
				
				if($item["start_time"] != '0'){
					$list[$k]["start_time"] = $this->curentByTime($item["start_time"]);
				}
				
				if($item["user_id"] == '0'){
					$list[$k]["user_id"] = "未使用";
				}
			
			
			
			
				$obj->setCellValue('A'.($k+2), $item["id"])
					->setCellValue('B'.($k+2), $item["type"])
					->setCellValue('C'.($k+2), $item["item_id"])
					->setCellValue('D'.($k+2), $name)
					->setCellValue('E'.($k+2), $item["used"])
					->setCellValue('F'.($k+2), $item["player_id"])
					->setCellValue('G'.($k+2), $item["user_id"])
					->setCellValue('H'.($k+2), $item["user_level"])
					->setCellValue('I'.($k+2), $item["start_time"])
					->setCellValue('J'.($k+2), $item["end_time"])
					->setCellValue('K'.($k+2), $item["used_time"]);	
			}	
		}	

		$objPHPExcel->getActiveSheet()->setTitle('Simple');

		$objPHPExcel->setActiveSheetIndex(0);

		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="激活码.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;

	}
	
	private function curentByTime($time) {
		$time = substr($time, 0, 10);
		return date('Y-m-d H:i:s', $time);
	}

}
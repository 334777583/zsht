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
		$obj = D('game_base');
		$result =  $obj -> table('gamedb') -> where(array('g_flag'=>1, 'g_id' => $this->ip)) -> find();
		
		$point = F($t_conf['zs']['db'], $t_conf['zs']['ip'], $t_conf['zs']['user'], $t_conf['zs']['password'], $t_conf['zs']['port']);
		if(!$point){
			echo json_encode(array(
				'error' => '数据库连接失败！'
			));
			exit;
		}
		// $con = mysql_connect("192.168.0.64:3306", "phptest", "123456",true);//二部
			// mysql_select_db("game",$con);//
			// mysql_query("SET NAMES 'utf8'",$con);
			// $res = mysql_query("insert into activity_gift(sn_code,pack_type,PlayerGUID,gift_item_id,end_time) value('0','0','0','0','12311')");
		
			// if(!$res){
				// echo "失败";
			// }else{
				// echo "成功";
			// }
			// mysql_close($con);
		$code_arr = $this->create($this->num);
		$list = array(); 	//激活码详细信息
		
		
		$sql_data = '';
		$i=1;
		foreach($code_arr as $k => $code){
			
			$sql_data .= "('" . $code . "','" . $this->codeType . "','" . $this->roleText . "','" . $this->toolsId . "','"  . strtotime($this->sxtime)."','".$this->ip."'),";
			
			if($i%1000 == 0 || $i == count($code_arr)) {
				$sql_data = rtrim($sql_data, ',');
				$sql_data .= ';';
				
				$sql = 'insert into activity_gift(sn_code,pack_type,PlayerGUID,gift_item_id,end_time,server_id) values ' . $sql_data;
				$f = $point->rquery($sql);
				//$res = mysql_query($sql);
				$sql_data = "";
			}
			$i++;
			
			$list[] = array(
				'id' => $code,
				'type' => $this->codeType,
				'player_id' => $this->roleText,
				'ip' => $result['g_name'],
				'item_id' => $this->toolsId,
				'end_time'	=> $this->sxtime
			);
		}
		
		echo json_encode(array(
				'list' => $list,
				'codes' => implode(',', $code_arr)
			));
		exit;
	}
	
	/**
	 * 获取激活码信息
	 */
	public function getCode(){
		//global $t_conf;
		$obj = D('game_base');
		$ip = get_var_value('ip');
		$ctype = get_var_value('ctype');
		$result =  $obj -> table('gamedb') -> where(array('g_flag'=>1, 'g_id' => $this->ip)) -> find();
		$where = '';
		//$point =D('game');
		global $t_conf;
		$point = F($t_conf['zs']['db'], $t_conf['zs']['ip'], $t_conf['zs']['user'], $t_conf['zs']['password'], $t_conf['zs']['port']);
		
		if($ctype) {
			$where .= 'pack_type = '.$ctype.' and ';
		}
		
		if(!empty($this->code)){
			$where .= 'sn_code like "%'.$this->code.'%" and ';
		}
		if(!empty($this->startDate) && !empty($this->endDate)){
			$where .= 'end_time >= '.strtotime($this->startDate).'000'.' and ';
			$where .= 'end_time < '.(strtotime($this->endDate)+86400).'000'.' and ';
		}
		
		if(empty($where)){
			$st = $point -> table('activity_gift')->/*field('id,gift_item_id,pack_type,sn_code,playerguid,server_id,end_time') ->*/ limit(intval(($this->curPage-1)*$this->pageSize),intval($this->pageSize)) -> order('end_time desc') -> select();
			$total = $point -> table('activity_gift') -> total();
		}else{
			$where = rtrim($where, ' and ');
			$st = $point -> table('activity_gift')->field('id,gift_item_id,pack_type,sn_code,PlayerGUID,server_id,end_time,') -> where($where)-> limit(intval(($this->curPage-1)*$this->pageSize),intval($this->pageSize)) -> order('end_time desc') -> select();
			$total = $point -> table('activity_gift') -> where($where) -> total();
		}
		$list = array();
		foreach($st as $key => $value){
			$list[$key]['id'] = $st[$key]['id'];
            $list[$key]['gift_item_id'] = $st[$key]['gift_item_id'];
            $list[$key]['pack_type'] = $st[$key]['pack_type'];
            $list[$key]['sn_code'] = $st[$key]['sn_code'];
            $list[$key]['end_time'] = $st[$key]['end_time'];
            $list[$key]['server_id'] = $st[$key]['server_id'];
            $list[$key]['playerguid'] = $st[$key]['playerguid'];
			if($st[$key]['playerguid'] > 0){
				$list[$key]['use'] = '已使用';
			}else{
				$list[$key]['use'] = '未使用';
			}
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
	public function getNewExcel(){
		$codes = get_var_value('codes');
		if($codes){
			$tmpfname = tempnam('/tmp','ASDFGHJKEWRTYUI');
			$handle = fopen($tmpfname, "w");
			fwrite($handle, json_encode($codes));
			fclose($handle);
			echo urlencode($tmpfname);
		}else{
			echo 0;
		}
	}
	
	
	
	/**
	 * @name: excel
	 * @description: 导出excel
	 * @param: null
	 * @return: http1.1
	 * @author: xiaochengcheng
	 * @create: 2013-5-14 12:11:54
	**/
	public function excel(){
		$f = urldecode(get_var_value('f'));
		$handle = fopen($f, "r");
		$list = json_decode(fread($handle, filesize ($f)),true);
		fclose($handle);
		// $list = json_decode(file_get_contents($f),true);
		unlink($f);
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
		$obj = D('game'.$this->ip);
		$list = array();			//道具ID与道具名称列表
		$total = 0;					//记录总数
		$key = get_var_value('searchKey');
		
		// if(!$key){
			// $total = $obj->table('tools_detail') -> where('t_type = "GIFT"') ->total();
		// }else{
			// $total = $obj->table('tools_detail')-> where(array('t_name like' => '%'.$key.'%', 't_type' => 'GIFT'))->total();
		// }
		$file = "/data0/yanfa/php_home/zhanshen/game/item/item_use.json";
		//fopen
		if (file_exists($file)) {
			$get_str = '';//获取道具日志文件内容	
			clearstatcache();
			$fs = fopen($file,'r');
			while(!feof($fs)){
					$get_str .= fgets($fs);
				}
			$str = json_decode($get_str,true);
			$resource = '';
			foreach($str as $k => $value){
				$resource[$k]['t_code']= $k;
				$resource[$k]['t_name']= $value['name'];
			}
			$total = count($list);
			$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'pageAjax2','go2','page2');
			$pageHtml = $page->getPageHtml();
			
			// if(!$key){
				// $resource = $obj->table('tools_detail') -> where('t_type = "GIFT"') -> limit(intval($page->getOff()),intval($this->pageSize))->select();
			// }else{
				// $resource = $obj->table('tools_detail') -> where('(t_name like "%' . $key . '%" or t_code like "%' . $key . '%") and t_type = "GIFT"')->limit(intval($page->getOff()),intval($this->pageSize))->select();
			// }
			
			if(is_array($resource)){
				$list =  $resource;
			}
			$result = array(
					'list' => $list,
					'pageHtml'=>$pageHtml
			);
			echo json_encode($result);
			exit;
		}else{
			echo "文件不存在";
			exit;
		}
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
		
		$obj = D('game_base');
		$result =  $obj -> table('gamedb') -> where(array('g_flag'=>1, 'g_id' => $this->ip)) -> find();
		
		$ip = $result['g_ip'];
		$name = $result['g_name'];
		$point = F($t_conf[$ip]['db'], $t_conf[$ip]['ip'], $t_conf[$ip]['user'], $t_conf[$ip]['password']);
		
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
			$list = $point -> table('activity_gift') -> order('start_time desc') -> select();
		}else{
			$where = rtrim($where, ' and ');
			$list = $point -> table('activity_gift') -> where($where) -> order('start_time desc') -> select();
		}
		
		if($list != '') {
			$str = "";
			//$str = "激活码\t激活码类型\t激活码详情\t服务器\t激活码状态\t限制角色\t使用角色\t角色等级\t生成时间\t使用时限\t使用时间\r\n";	//输出到txt的文本
			foreach($list as $k => $item){
				for($i = 1;$i<100;$i++){
					switch($item["type"]){
						// if( $i == 6){
							// case 6 : $list[$k]["type"] = "不限";break;
						// }else{
							// case $i : $list[$k]["type"] = $i;break;
						// }
						
						case 1 : $list[$k]["type"] = "1";break;
						case 2 : $list[$k]["type"] = "2";break;
						case 3 : $list[$k]["type"] = "3";break;
						case 4 : $list[$k]["type"] = "4";break;
						case 5 : $list[$k]["type"] = "5";break;
						case 7 : $list[$k]["type"] = "7";break;
						case 8 : $list[$k]["type"] = "8";break;
						case 9 : $list[$k]["type"] = "9";break;
						case 10 : $list[$k]["type"] = "10";break;
						case 11 : $list[$k]["type"] = "11";break;
						case 12 : $list[$k]["type"] = "12";break;
						case 13 : $list[$k]["type"] = "13";break;
						case 14 : $list[$k]["type"] = "14";break;
						case 15 : $list[$k]["type"] = "15";break;
						case 16 : $list[$k]["type"] = "16";break;
						case 17 : $list[$k]["type"] = "17";break;
						case 18 : $list[$k]["type"] = "18";break;
						case 19 : $list[$k]["type"] = "19";break;
						case 20 : $list[$k]["type"] = "20";break;
						case 21 : $list[$k]["type"] = "21";break;
						case 22 : $list[$k]["type"] = "22";break;
						case 23 : $list[$k]["type"] = "23";break;
						case 24 : $list[$k]["type"] = "24";break;
						case 25 : $list[$k]["type"] = "25";break;
						case 26 : $list[$k]["type"] = "26";break;
						case 27 : $list[$k]["type"] = "27";break;
						case 6 : $list[$k]["type"] = "不限";break;
						
					}
				
				}
				// switch($item["used"]){
					// case 0 : $list[$k]["used"] = "未使用";break;
					// case 1 : $list[$k]["used"] = "已使用";break;
				// }
					
				if($item["player_id"] == '0'){
					$list[$k]["player_id"] = "不限";
				}
				
				if($item["end_time"] == '0'){
					$list[$k]["end_time"] = "不限";
				}else{
					$list[$k]["end_time"] = $this->curentByTime($item["end_time"]);
				}
				
				// if($item["used_time"] != '0'){
					// $list[$k]["used_time"] = $this->curentByTime($item["used_time"]);
				// }
				
				// if($item["start_time"] != '0'){
					// $list[$k]["start_time"] = $this->curentByTime($item["start_time"]);
				// }
				
				// if($item["user_id"] == '0'){
					// $list[$k]["user_id"] = "未使用";
				// }
				
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
		$obj = D('game_base');
		$result =  $obj -> table('gamedb') -> where(array('g_flag'=>1, 'g_id' => $this->ip)) -> find();
		
		$ip = $result['g_ip'];
		$name = $result['g_name'];
		$point = F($t_conf[$ip]['db'], $t_conf[$ip]['ip'], $t_conf[$ip]['user'], $t_conf[$ip]['password']);
		
		$list = $point -> table('activity_gift') -> order('start_time desc') -> select();
	
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
					case 1 : $list[$k]["type"] = "1";break;
					case 2 : $list[$k]["type"] = "2";break;
					case 3 : $list[$k]["type"] = "3";break;
					case 4 : $list[$k]["type"] = "4";break;
					case 5 : $list[$k]["type"] = "5";break;
					case 7 : $list[$k]["type"] = "6";break;
					case 8 : $list[$k]["type"] = "7";break;
					case 9 : $list[$k]["type"] = "8";break;
					case 10 : $list[$k]["type"] = "9";break;
					case 11 : $list[$k]["type"] = "10";break;
					case 12 : $list[$k]["type"] = "11";break;
					case 13 : $list[$k]["type"] = "12";break;
					case 14 : $list[$k]["type"] = "13";break;
					case 15 : $list[$k]["type"] = "14";break;
					case 16 : $list[$k]["type"] = "15";break;
					case 17 : $list[$k]["type"] = "16";break;
					case 18 : $list[$k]["type"] = "17";break;
					case 19 : $list[$k]["type"] = "18";break;
					case 20 : $list[$k]["type"] = "19";break;
					case 21 : $list[$k]["type"] = "20";break;
					case 22 : $list[$k]["type"] = "21";break;
					case 23 : $list[$k]["type"] = "22";break;
					case 24 : $list[$k]["type"] = "23";break;
					case 25 : $list[$k]["type"] = "24";break;
					case 26 : $list[$k]["type"] = "25";break;
					case 27 : $list[$k]["type"] = "26";break;
					case 28 : $list[$k]["type"] = "27";break;
					case 29 : $list[$k]["type"] = "28";break;
					case 30 : $list[$k]["type"] = "29";break;
					case 31 : $list[$k]["type"] = "30";break;
					case 32 : $list[$k]["type"] = "31";break;
					case 33 : $list[$k]["type"] = "32";break;
					case 34 : $list[$k]["type"] = "33";break;
					case 35 : $list[$k]["type"] = "34";break;
					case 36 : $list[$k]["type"] = "35";break;
					case 37 : $list[$k]["type"] = "36";break;
					case 38 : $list[$k]["type"] = "37";break;
					case 39 : $list[$k]["type"] = "38";break;
					case 40 : $list[$k]["type"] = "39";break;
					case 41 : $list[$k]["type"] = "40";break;
					case 42 : $list[$k]["type"] = "41";break;
					case 43 : $list[$k]["type"] = "42";break;
					case 44 : $list[$k]["type"] = "43";break;
					case 45 : $list[$k]["type"] = "44";break;
					case 46 : $list[$k]["type"] = "45";break;
					case 47 : $list[$k]["type"] = "46";break;
					case 48 : $list[$k]["type"] = "47";break;
					case 49 : $list[$k]["type"] = "48";break;
					case 50 : $list[$k]["type"] = "49";break;
					case 51 : $list[$k]["type"] = "50";break;
					case 52 : $list[$k]["type"] = "51";break;
					case 53 : $list[$k]["type"] = "52";break;
					case 54 : $list[$k]["type"] = "53";break;
					case 55 : $list[$k]["type"] = "54";break;
					case 56 : $list[$k]["type"] = "55";break;
					case 57 : $list[$k]["type"] = "56";break;
					case 58 : $list[$k]["type"] = "57";break;
					case 59 : $list[$k]["type"] = "58";break;
					case 60 : $list[$k]["type"] = "59";break;
					case 61 : $list[$k]["type"] = "60";break;
					case 62 : $list[$k]["type"] = "61";break;
					case 63 : $list[$k]["type"] = "62";break;
					case 64 : $list[$k]["type"] = "63";break;
					case 65 : $list[$k]["type"] = "64";break;
					case 66 : $list[$k]["type"] = "65";break;
					case 67 : $list[$k]["type"] = "66";break;
					case 68 : $list[$k]["type"] = "67";break;
					case 69 : $list[$k]["type"] = "68";break;
					case 70 : $list[$k]["type"] = "69";break;
					case 71 : $list[$k]["type"] = "70";break;
					case 72 : $list[$k]["type"] = "71";break;
					case 73 : $list[$k]["type"] = "72";break;
					case 74 : $list[$k]["type"] = "73";break;
					case 75 : $list[$k]["type"] = "74";break;
					case 76 : $list[$k]["type"] = "75";break;
					case 77 : $list[$k]["type"] = "76";break;
					case 78 : $list[$k]["type"] = "77";break;
					case 79 : $list[$k]["type"] = "78";break;
					case 80 : $list[$k]["type"] = "79";break;
					case 81 : $list[$k]["type"] = "80";break;
					case 82 : $list[$k]["type"] = "81";break;
					case 83 : $list[$k]["type"] = "82";break;
					case 84 : $list[$k]["type"] = "83";break;
					case 85 : $list[$k]["type"] = "84";break;
					case 86 : $list[$k]["type"] = "85";break;
					case 87 : $list[$k]["type"] = "86";break;
					case 88 : $list[$k]["type"] = "87";break;
					case 89 : $list[$k]["type"] = "88";break;
					case 90 : $list[$k]["type"] = "89";break;
					case 91 : $list[$k]["type"] = "90";break;
					case 92 : $list[$k]["type"] = "91";break;
					case 93 : $list[$k]["type"] = "92";break;
					case 94 : $list[$k]["type"] = "93";break;
					case 95 : $list[$k]["type"] = "94";break;
					case 96 : $list[$k]["type"] = "95";break;
					case 97 : $list[$k]["type"] = "96";break;
					case 98 : $list[$k]["type"] = "97";break;
					case 99 : $list[$k]["type"] = "98";break;
					case 100 : $list[$k]["type"] = "99";break;
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
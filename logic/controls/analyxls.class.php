<?php
/**
 * FileName: analyxls.class.php
 * Description:解析xls道具ID数据
 * Author: jan,hjt
 * Date:2013-9-23 9:53:13
 * Version:1.02
 */
class analyxls{

	/**
	 * 将excel数据导入数据表
	 */
	public function analy(){
		ini_set('upload_max_filesize','10M');
		ini_set('post_max_size','10M');
		ini_set('memory_limit','1024M');
		set_time_limit(1000);
		$id = get_var_value('ip');
		if(!$id) {return ;}
		
		$obj = D("game_base");
		$ip = "";
		$game = $obj->table("gamedb")->where(array("g_id"=>$id,"g_flag" => 1))->find();
		if($game) {
			if(isset($game['g_domain']) && $game['g_domain'] != ''){
				$ip = $game['g_domain'];
			}else{
				$ip = $game['g_ip'];
			}
		}
		/*
		$filename = $_FILES['xls']['name'];
		$filesize = $_FILES['xls']['size'];
		
		if ($filename != "") {
			$size = 10 * 1024 * 1024;//10M
			if ($filesize > $size) {
				echo json_encode('excel文件大小不能超过10M');
				exit;
			}
			$type = strstr($filename, '.');
			
			if ($type != ".xls" && $type != ".xlsx") {
				echo json_encode('excel文件格式必须是xls或者xlsx！');
				exit;
			}
		}else{
			echo json_encode('上传失败');
			exit;
		}
		
		$path = $_FILES['xls']['tmp_name'];
		$basepath = TPATH.'/brophp/public/uploads/';
		$filepath = $basepath.'goods_detail'.$type;//更新道具列表路径
		if(!move_uploaded_file($path,$filepath)){
			echo json_encode('上传失败');
			exit;
		}
		*/
		$tool_string = '';			//道具列表
		//$basepath = TPATH.'brophp/public/uploads/';
		//$filepath = TPATH.'goods_detail.xls';//更新道具列表路径
		// $filepath = TPATH .'/'.$ip.'/'.'goods_detail.xlsx';
		$filepath = TPATH .'goods_detail.xlsx';
		if(file_exists($filepath)) {
			$xml = $this -> ReadExcel($filepath);
			if($xml) {
				foreach($xml as $item => $val) {
					//$tid = $val[''];				//ID
					$name = $val['name'];			//道具名称
					$code = $val['uid'];		//道具ID
					$type1 = $val['type1'];
					$type2 = $val['type2'];
					$type3 = $val['type3'];
					$tool_string .= "('" . $code . "','" . $name . "','".$type1."','".$type2."','".$type3."'),";
					}
				if($tool_string != '') {
					$tool_string = rtrim($tool_string, ',');
					$tool_string .= ';';
					if($id) {
						$status = $this->update($tool_string, $id);
						if($status) {
							//unlink($filepath);//上传完毕  清除文件
							echo json_encode('success');
							exit;
						} else {
							echo json_encode('fail');
							exit;
						}	
					}else {
						echo json_encode('fail');
						exit;
					}
				}
			}
		} else {
			echo json_encode('File is not find!');
			exit;
		}
	}
	
	/*
	/**
	 * FunctionName: ReadExcel
	 * Description: 读取excel
	 * @param excel文件
	 * Author: hjt	
	 * Return array
	 * Date: 2013-9-23 10:48:28
	 **/
	private function ReadExcel($path){
	
		require_once (AClass.'phpexcel/PHPExcel.php');
		
		$extend = pathinfo($path);
		$extend = strtolower($extend["extension"]);
		
		if($extend  == 'xls'){
			$objPHPExcel = PHPExcel_IOFactory::createReader('Excel5');//2007版本以下excel
		}else if($extend  == 'xlsx'){
			$objPHPExcel = PHPExcel_IOFactory::createReader('Excel2007');//2007版本excel
		}
		
		$PHPExcel = $objPHPExcel->load($path);
		$sheet = $PHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow(); // 取得总行数
		
		//循环读取excel文件
		for($j = 4;$j <= $highestRow; $j++){
			$result[$j]['id'] = $PHPExcel->getActiveSheet()->getCell("A".$j)->getValue();//获取道具id
			$result[$j]['uid'] = $PHPExcel->getActiveSheet()->getCell("B".$j)->getValue();//获取道具名
			$result[$j]['name'] = $PHPExcel->getActiveSheet()->getCell("C".$j)->getValue();//获取道具id
			// $result[$j]['money'] = $PHPExcel->getActiveSheet()->getCell("D".$j)->getValue();//获取道具名
			// $result[$j]['time'] = $PHPExcel->getActiveSheet()->getCell("E".$j)->getValue();//获取道具id
			// $result[$j]['g_id'] = $PHPExcel->getActiveSheet()->getCell("F".$j)->getValue();//获取道具名
			// $result[$j]['g_name'] = $PHPExcel->getActiveSheet()->getCell("G".$j)->getValue();//获取道具id
			$result[$j]['type1'] = $PHPExcel->getActiveSheet()->getCell("H".$j)->getValue();//获取道具名
			$result[$j]['type2'] = $PHPExcel->getActiveSheet()->getCell("I".$j)->getValue();//获取道具id
			$result[$j]['type3'] = $PHPExcel->getActiveSheet()->getCell("K".$j)->getValue();//获取道具id
		}
		return $result;
		exit;
	}
	
	/**
	 * FunctionName: update
	 * Description: 更新道具列表
	 * @param 更新的数据
	 * @param 服务器id
	 * Author: （jan）						
	 * Date: 2013-9-10 15:58:20	
	 **/
	private function update($data, $id){
		// $db = D('game_base');
		$db = D('game'.$id);
		$sql = 'delete from tools_detail;'; 
		$f = $db->rquery($sql);
		if(!$f) return false;
		$sql = 'insert into tools_detail(t_code,t_name,t_type1,t_type2,t_type3) values ' . $data;
		$f = $db->rquery($sql);
		if(!$f) 
			return false;
		else	
			return true;
	}
	
	
	
}
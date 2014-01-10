<?php
/**
 * FileName: analyxml.class.php
 * Description:解析xls道具ID数据
 * Author: jan,hjt
 * Date:2013-9-23 9:53:13
 * Version:1.02
 */
class analyxml{

	/**
	 * 将excel数据导入数据表
	 */
	public function analy(){
		
		ini_set('upload_max_filesize','10M');
		ini_set('post_max_size','10M');
		
		$id = get_var_value('ip');
		if(!$id) {return ;}
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
		
		$tool_string = '';			//道具列表
		
		if(file_exists($filepath)) {
			$xml = $this -> ReadExcel($filepath);
			if($xml) {
				foreach($xml as $item => $val) {
					$tid = $item;				//ID
					$name = $val['name'];			//��������
					$code = $val['item_id'];		//����ID
					$tool_string .= "('" . $tid . "','" . $code . "','" . $name . "'),";
				}
				
				if($tool_string != '') {
					$tool_string = rtrim($tool_string, ',');
					$tool_string .= ';';
					
					if($id) {
						$status = $this->update($tool_string, $id);
						if($status) {
							unlink($filepath);//上传完毕  清除文件
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
		for($j = 1;$j <= $highestRow; $j++){
			$result[$j]['item_id'] = $PHPExcel->getActiveSheet()->getCell("A".$j)->getValue();//��ȡ����id
			$result[$j]['name'] = $PHPExcel->getActiveSheet()->getCell("B".$j)->getValue();//��ȡ������
		}
		return $result;
		exit;
	}
	
	/**
	 * FunctionName: update
	 * Description: 更新道具列表
	 * @param 更新的数据
	 * @param 服务器id
	 * Author: jan,hjt						
	 * Date: 2013-9-16 11:12:24
	 **/
	private function update($data, $id){
	$db = D('game'.$id);
		$sql = 'delete from goods_detail;'; 
		$f = $db->rquery($sql);
		if(!$f) return false;
		$sql = 'insert into goods_detail(g_id,g_code,g_name) values ' . $data;
		$f = $db->rquery($sql);
		if(!$f) 
			return false;
		else	
			return true;
	}
	
	
	
	
}
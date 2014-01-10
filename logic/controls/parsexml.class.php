<?php
/**
 * FileName: parsexml.class.php
 * Description:解析json道具ID数据
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-5-22 15:35:53
 * Version:1.00
 */
class parsexml{

	/**
	 * 解析json
	 */
	public function parse(){
		//$path = "E:\itemnew.xml".'1922'.'/'.'itemnew.xml';
		$id = get_var_value('ip');
		$file_name = 'item.json';
		
		if(!$id) {return ;}
		
		$obj = D("game_base");
		$ip = "";
		$game = $obj->table("gamedb")->where(array("g_id"=>$id,"g_flag" => 1))->find();
		
		if($game) {
			$ip = $game['g_ip'];
		}
		$path = TPATH .'/'.$ip.'/' . $file_name;
		
		$tool_string = '';			//道具列表
		
		if(file_exists($path)) {
			//$xml = simplexml_load_file($path);
			$json = file_get_contents($path);
			if($json) {
				$data = json_decode($json, true);
				foreach($data as $item) {
					$tid = $item['ID'];					//道具ID
					$name = $item['name'];				//道具名称
					$type1 = $item['type1'];			//类型1
					$type2 = $item['type2'];			//类型2
					$type3 = $item['type3'];			//类型3
					$tool_string .= "('" . $tid . "','" . $name . "','" . $type1 . "','" . $type2 . "','" . $type3 ."'),";
				}
				
				if($tool_string != '') {
					$tool_string = rtrim($tool_string, ',');
					$tool_string .= ';';
					
					if($id) {
						$status = $this->update($tool_string, $id);
						if($status) {
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
			exit('File is not find!');
		}
	}
	
	/**
	 * 更新道具列表
	 * @param 更新的数据
	 * @param 服务器id
	 */
	private function update($data, $id){
		$db = D('game' . $id);
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
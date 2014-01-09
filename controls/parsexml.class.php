<?php
/**
 * FileName: parsexml.class.php
 * Description:解析xml道具ID数据
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-5-22 15:35:53
 * Version:1.00
 */
class parsexml{

	/**
	 * 解析xml
	 */
	public function parse(){
		//$path = "E:\itemnew.xml".'1922'.'/'.'itemnew.xml';
		$id = get_var_value('ip');
		
		if(!$id) {return ;}
		
		$obj = D("game_info");
		$ip = "";
		$game = $obj->table("gamedb")->where(array("g_id"=>$id,"g_flag" => 1))->find();
		
		if($game) {
			if(isset($game['g_domain']) && $game['g_domain'] != ''){
				$ip = $game['g_domain'];
			}else{
				$ip = $game['g_ip'];
			}
		}
		$path = TPATH .'/'.$ip.'/'.'itemnew.xml';
		// $path = 'D:\wamp\www\game\itemnew.xml';
		$tool_string = '';			//道具列表
		
		if(file_exists($path)) {
			$xml = simplexml_load_file($path);
			if($xml) {
				foreach($xml as $item) {
					$tid = $item['item_id'];		//道具ID
					$name = $item['name'];			//道具名称
					$type = $item['type'];			//穿戴部位
					$color = $item['color'];		//品质
					$prof =	$item['prof_demand'];	//职业需求
					$sex = $item['sex_demand'];		//性别
					$level = $item['lv_demand'];	//等级需求
					$tool_string .= "('" . $tid . "','" . $name . "','" . $type . "','" . $color . "','" . $prof . "','" . $sex . "','" . $level ."'),";
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
		$db = D(GNAME. $id);
		$sql = 'delete from tools_detail;'; 
		$f = $db->rquery($sql);
		if(!$f) return false;
		$sql = 'insert into tools_detail(t_code,t_name,t_type,t_color,t_prof,t_sex,t_level) values ' . $data;
		$f = $db->rquery($sql);
		if(!$f) 
			return false;
		else	
			return true;
	}
	
	
	
	
}
<?php
/**
 * FileName: analyxml.class.php
 * Description:解析xml道具ID数据
 * Author: jan
 * Date:2013-9-10 15:35:53
 * Version:1.00
 */
class analyxml{

	/**
	 * 解析xml
	 */
	public function analy(){
		//$path = "E:\itemnew.xml".'1922'.'/'.'goods_detail.xml';
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
		
		$path = TPATH .'/'.$ip.'/'.'goods_detail.xml';
		// $path = TPATH .'/'.$ip.'/'.'itemnew.xml';
		
		//$path = 'D:\wamp\www\game\goods_detail.xml';
		$tool_string = '';			//道具列表
		if(file_exists($path)) {
			$xml = simplexml_load_file($path);
			if($xml) {
				foreach($xml as $item) {
					// foreach($item as $value){
						// print_R($value);
					// }
					 print_R($item);
					$tid = $item[''];				//ID
					$name = $item['name'];			//道具名称
					$code = $item['item_id'];		//道具ID
					$tool_string .= "('" . $tid . "','" . $code . "','" . $name . "'),";
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
	 * FunctionName: update
	 * Description: 更新道具列表
	 * @param 更新的数据
	 * @param 服务器id
	 * Author: （jan）						
	 * Date: 2013-9-10 15:58:20	
	 **/
	private function update($data, $id){
		$db = D('game_info');
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
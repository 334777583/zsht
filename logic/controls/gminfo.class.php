<?php
/**
 * FileName: gminfo.class.php
 * Description:用户信息查询页面
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-3-28 上午11:36:42
 * Version:1.00
 */
class gminfo{
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
			if(!in_array("00300100", $this->user["code"])){
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
	}
	
	/**
	 * ajax请求用户基本信息数据
	 */
	public function get(){ 
		global $gm_db;
		$sex_type = array('0' => '无性别', '1' => '男', '2' => '女');			
		$career_type = array('0' => '无职业', '1' => '战士', '2' => '剑客', '3' => '谋士');
		
		list($ip, $gid, $sid) = autoConfig::getConfig($this->ip);
		$gameDb = D('game'.$gid);
		$point = F($gm_db['db'], $gm_db['ip'], $gm_db['user'], $gm_db['password']);
		$plays = array();					//玩家信息
		
		if($this -> text == '') {
			$list = $point -> table('player_table') -> field('sex,carrer,guid,accountid,rolename,level,createtime,logintime') -> limit(intval(($this->curPage-1)*$this->pageSize),intval($this->pageSize)) -> where('serverid ='.$sid) -> select();
			$total = $point -> table('player_table') -> where('serverid ='.$sid) -> total();
		} else {
			$where_field = '';
			switch($this->type) {
				case 0 : 
					$where_field .= 'accountid like "%' . $this->text . '%"';
					break;
				case 1 :
					$where_field .=	'guid like "%' . $this->text . '%"';
					break;
				case 2 :
					$where_field .= 'rolename like "%' . $this->text . '%"';
					break;
			}
			
			$where_field .= ' and serverid ='.$sid;
		
			$list = $point -> table('player_table') -> field('sex,carrer,guid,accountid,rolename,level,createtime,logintime') -> where($where_field) -> limit(intval(($this->curPage-1)*$this->pageSize),intval($this->pageSize)) -> select();
			$total = $point -> table('player_table') -> where($where_field) -> total();

		}
		
		if($list != '') {
			foreach($list as $item) {	//组织返回页面的plays信息
				$play = array();		//保存一个玩家的信息
				$play['sex'] = $sex_type[$item['sex']];
				$play['profession'] = $career_type[$item['carrer']];
				$play['id'] = $item['guid'];
				$play['accountCode'] = $item['accountid'];
				$play['name'] = $item['rolename'];
				$play['level'] = $item['level'];
				$play['createTime'] = date('Y-m-d H:i:s',$item['createtime']);
				$item['logintime'] == 0 ?	$play['lastTime'] = '' : $play['lastTime'] = date('Y-m-d H:i:s',$item['logintime']);
				$play['lastIp'] = '';
				$play['sumSec'] = 0;
				$plays[] = $play;
			}
		}
		
		
		foreach($plays as $k => $play){			//获取玩家最近在线时间，最近登录IP，总在线时长
				$lastIp = '';					//最近登录IP
				$sumSec = 0	;					//总在线时长
				
				$lt = $gameDb -> table('detail_login') -> where('d_userid ='.$play['id']) -> order('d_date desc') -> find();
				if($lt != '') {					
					$ss = $gameDb -> table('online_sec') -> field('sum(o_second) as sum') ->where('o_userid ='.$play['id']) -> find(); 
					
					if (isset($lt['d_ip'])) {
						$lastIp = $lt['d_ip'];
						$o = new autoipsearchdat();
						$area = $o->findIp($lastIp);
						if($area) {
							$lastIp .= '(' . $area . ')';	
						}
					}
					
					if (isset($ss['sum'])) {
						$sumSec = $ss['sum'];
					}
				}
				
				$plays[$k]['lastIp'] = $lastIp;
				$plays[$k]['sumSec'] = $sumSec;
			}
		
		$page = new autoAjaxPage($this->pageSize, $this->curPage, $total, "formAjax","go","page");
		$pageHtml = $page->getPageHtml();
		$result = array(
				'pageHtml'=>$pageHtml,
				'plays'=> $plays
		);
		echo json_encode($result);
		exit;
	 
	}  
	
	/**
	 * 获取玩家详细信息
	*/
	public function getDetailInfo(){
		global $gm_db;
		$point = F($gm_db['db'], $gm_db['ip'], $gm_db['user'], $gm_db['password']);
		
		$gid = get_var_value('gid');	//玩家id
		$play = array();				//玩家详细信息
		$equips = array();				//玩家装备信息
		$sex_type = array('0' => '无性别', '1' => '男', '2' => '女');			
		$career_type = array('0' => '无职业', '1' => '战士', '2' => '剑客', '3' => '谋士');
		
		if($gid) {
			$bo = $point -> table('player_table') -> where('guid ='.$gid) -> find();
			if($bo != '') {
			
				$play['accountCode'] = $bo['accountid'];
				$play['id']	= $gid;
				$play['name'] = $bo['rolename'];
				$play['sex'] = $sex_type[$bo['sex']];
				$play['level'] = $bo['level'];
				$play['viplevel'] = $bo['viplevel'];
				$play['profession'] = $career_type[$bo['carrer']];
				$play['guildid'] = $bo['guildid'];
				$play['attack'] = '暂无';
				$play['mapId'] = $bo['lastsceneid'];
				$play['recharge'] =	0;
				$play['gold'] =	$bo['gold'];
				$play['bindGold'] =	$bo['bindgold'];
				$play['coin'] =	$bo['coin'];
				$play['bindcoin'] =	$bo['bindcoin'];
				$play['createTime'] = date('Y-m-d H:i:s', $bo['createtime']);
				$bo['logintime'] == 0 ?	$play['lastOnTime'] = '' : $play['lastOnTime'] = date('Y-m-d H:i:s',$bo['logintime']);
				$play['isOnline'] = '暂无';
				$play['accountState'] = '暂无';

			}
		}
		
		$result = array(
					'player' =>  $play
			);
		echo json_encode($result);
		exit;
	}	
	
	/**
	 * 获取背包信息
	 */
	public function getBagInfo() {
		global $gm_db;
		$point = F($gm_db['db'], $gm_db['ip'], $gm_db['user'], $gm_db['password']);
		
		list($ip, $id, $sid) = autoConfig::getConfig($this->ip);
		$gameDb = D('game'.$id);
		
		$gid = get_var_value('gid');	//玩家id
		$bag = array();
		$tool_map = array();			//道具列表
		if($gid) {
			$list = $point -> table('player_table') -> where('guid=' . $gid) -> find();
			
			$tool_list = $gameDb -> table('tools_detail') -> select();
			
			if($list != '') {
				$data = $list['packagedata'];
				$bag = $this->packdate($data);
			}
			
			if($tool_list != '') {
				foreach($tool_list as $tool) {
					$tool_map[$tool['t_code']] = $tool['t_name'];
				}
			}
		}
		$result = array(
					'bag' =>  $bag,
					'tool' => $tool_map
			);
		echo json_encode($result);
		exit;
	}

	/**
	 * 解析背包二进制数据
	 */
	public function packdate($data){
		if(strlen($data) <= 0){
			return false;
		}
		$A = array();
		$T = unpack("v2", substr( $data, 0, 4 ) );		
		$Sum = $T[2]; //总物品数
		$offset = 4;
		for( $i = 0; $i < $Sum; $i++ ){
			$array = unpack("v", substr( $data, $offset, strlen($data) -  $offset) );  
			$offset += 2;
			$A[$i]['CellId'] = $array[1];
			
			$array = unpack("I3", substr( $data, $offset, strlen($data) -  $offset)  );  
			$offset += 12;
			$A[$i]['ItemId'] = $array[2];
			$A[$i]['ItemCount'] = $array[3];
			
			$array = unpack("c", substr( $data, $offset, strlen($data) -  $offset)  ); 
			$offset += 1;
			$A[$i]['bind'] = $array[1];
			
			$array = unpack("c", substr( $data, $offset, strlen($data) -  $offset)  );  
			$offset += 1;
			$offset += 8 * $array[1];
			
			$array = unpack("c", substr( $data, $offset, strlen($data) -  $offset)  );  
			$offset += 1;
			$offset += 8 * $array[1];
		}
		return $A;
	}	
	
}
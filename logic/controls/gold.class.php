<?php
/**
 * FileName: gold.class.php
 * Description:邮件发送元宝
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-4-1 下午4:35:38
 * Version:1.00
 */
class gold{
	/**
	 * 角色名（多个以逗号分隔）
	 * @var string
	 */
	public $rolename;
	
	/**
	 * 发送原因
	 * @var string
	 */
	public $reason;
	
	/**
	 * 服务器IP
	 * @var string
	 */
	public $ip;
	
	/**
	 * 标题
	 * @var string
	 */
	public $title;
	
	
	/**
	 * 信件内容
	 * @var string
	 */
	public $content;
	
	/**
	 * gm接口类
	 * @var class
	 */
	public $gm;
	
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
	 * 元宝
	 * @var int
	 */
	private $gold;
	
	/**
	 * 绑定元宝
	 * @var int
	 */
	private $bgold;
	
	/**
	 * 银币
	 * @var int
	 */
	private $silver;
	
	/**
	 * 铜币
	 * @var int
	 */
	private $copper;
	
	/**
	 * 道具列表
	 * @var array
	 */
	private $toolList = array();
	
	/**
	 * 道具申请记录ID
	 * @var int
	 */
	private $id;
	
	/**
	 * 发送类型(1:角色名；2：全服)
	 * @var int
	 */
	private $srole;
	
	/**
	 * 用户数据
	 * @var array
	 */
	private $user;
	
	/**
	 * 初始化数据
	 */
	
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo "not available!";
			exit();
		}else{
			if(!in_array("00501700", $this->user["code"])){
				echo "not available!";
				exit();
			}
		}
		$this->gm = new autogm();
		$this->id =  get_var_value('id') == NULL? -1 : get_var_value('id');
		$this->ip =  get_var_value('ip') == NULL? -1 : get_var_value('ip');
		$this->reason = get_var_value('reason') == NULL? '': get_var_value('reason');
		$this->rolename = get_var_value('rolename') == NULL? '' : get_var_value('rolename');
		$this->title = get_var_value('title') == NULL? '' : get_var_value('title');
		$this->content = get_var_value('content') == NULL? '': $_POST['content'];
		$this->gold = get_var_value('gold') == NULL? 0: get_var_value('gold');
		$this->bgold = get_var_value('bgold') == NULL? 0: get_var_value('bgold');
		$this->silver = get_var_value('silver') == NULL? 0: get_var_value('silver');
		$this->copper = get_var_value('copper') == NULL? 0: get_var_value('copper');
		$this->toolList = get_var_value('toolList') == NULL? array(): get_var_value('toolList');
		$this->pageSize = get_var_value('pageSize') == NULL? 10: get_var_value('pageSize');
		$this->curPage =  get_var_value('curPage') == NULL? 1 : get_var_value('curPage');
		$this->srole =  get_var_value('srole') == NULL? 0 : get_var_value('srole');
	}
	
	
	/**
	 * 道具申请
	 */
	public function toolsAsk(){
		$obj = D("game_info");
		$server = $obj->table("servers")->where(array("s_id"=>$this->ip))->find();
		if(isset($server["s_ip"])){
			$ip = $server["s_ip"];
		}
		if(isset($server["s_port"])){
			$port = $server["s_port"];
		}
		if(isset($server["s_name"])){
			$s_name = $server["s_name"];
		}
		if(isset($this->user["username"])){
			$loginName = $this->user["username"];
		}
	
		if($this->id == 0){
			echo json_encode("13241964165");
			exit;
		}
		$name = get_var_value('rolename');
		$info = array();
		$monArr = array(
					'1' => 0,
					//'2' => 0,
					'3'	=> $this->gold,
					'4' => $this->bgold
				);
		$info['receiverName'] = $name;
		$info['sender'] = "系统管理员";
		$info['title'] = "活动奖励";
		$info['content'] = "";
		$info['items'] =  array();
		$info['moneys'] = $monArr;
		
		$operaor = $this->user['username'];
		$time = date('Y-m-d H:i:s');
		//写日志
		$strs = '';
		$rul = TPATH."/get_gold/list.txt";
		$fs = fopen($rul,'r');
		while(!feof($fs)){
				$strs .= fgets($fs);
			}
		$test = '时间:"'.$time.'" 操作人:"'.$operaor.'" 元宝:"'.$info['moneys'][3].'" 绑定元宝:"'.$info['moneys'][4].'" 角色名:"'.$name.'" 服务器:"'.$s_name.'"'."\r\n";
		$stre = $strs.$test;
		$fs = fopen($rul,'w+');
		$res = fwrite($fs,$stre);
		fclose($fs);
		
		$callReasult = $this->gm->gm3001($info,$ip,$port,$loginName);
		if($callReasult == "error"){
			sleep(1);
			$callReasult = $this->gm->gm3001($info,$ip,$port,$loginName);	
			if($callReasult == "error"){
				echo json_encode('error');
				exit;
			}
		}
		if($callReasult == "error"){
			echo json_encode('error');
			exit;
		}
				
		$arr = explode('|',$callReasult);
		if(isset($arr[1])){
			$resultList = json_decode($arr[1],true);
			if(isset($resultList) && isset($resultList['code'])){
				if($resultList['code'] == 0){
					echo json_encode('success');	//2:已通过并发送成功
				}elseif($resultList['code'] == 1){
					print_R($arr);	//4:已通过但发送失败
				}
			}
		}
	}
	
}
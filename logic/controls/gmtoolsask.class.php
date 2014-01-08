<?php
/**
 * FileName: gmtoolsask.class.php
 * Description:用户管理工具(GM)-道具申请
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-4-1 下午4:35:38
 * Version:1.00
 */
class gmtoolsask{
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
	 * 用户数据
	 * @var Array
	 */
	public $user;
	
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
	 * 初始化数据
	 */
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo "not available!";
			exit();
		}else{
			if(!in_array("00500400", $this->user["code"])){
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
		$status = 0;

		$obj = D('game_info');
		$ids = array();				//保存返回的插入id
		if(empty($this->title)){
			$this->title = '系统邮件';
		}
		
		if('2' == $this->srole){	//单人发邮件发送
			$this->rolename = '全服';
		}
		
		if(intval($this->gold) > 100000000 || intval($this->bgold) > 100000000 || intval($this->silver) > 100000000 || intval($this->copper) > 100000000){
			$result = array('error' => '最大值为100000000');
			echo json_encode($result);
			exit;
		}
		
		//新加根据等级发送
		//start
		$minLv = get_var_value('minLv');
		$maxLv = get_var_value('maxLv');
		$emailTime = get_var_value('emailTime');
		$day = get_var_value('day');	//时间类型（1：天；2：周；3：时）
		
		if($emailTime != 0) {
			switch($day) {
				case 1 : $emailTime = $emailTime * 24 * 60 * 60 * 1000;break;
				case 2 : $emailTime = $emailTime * 7 * 24 * 60 * 60 * 1000;break;
				case 3 : $emailTime = $emailTime * 60 * 60 * 1000;break;
			}
		}
		//end
		
		
		if(!empty($this->rolename)){ //角色名不为空
			$rolename = explode(';',$this->rolename);
			
			//过滤特殊字符
			$search = array(
				"'<script[^>]*?>.*?</script>'si",
				"/\s/",
				"/\?/",
				"/\%/",
				"/\&/",
				"/\=/"
			);
			$replace = array("");
			
			$this->title = trim(addslashes(nl2br(stripslashes(preg_replace($search, $replace, $this->title)))));
			$this->title = htmlspecialchars($this->title);
			
			$this->content = trim(addslashes(nl2br(stripslashes(preg_replace($search, $replace, $this->content)))));
			$this->content = htmlspecialchars($this->content);
			
			foreach($rolename as $name){
				$id = $obj->table('tools_ask')->insert(array(
						't_role_name' => $name,
						't_ip' => $this->ip,
						't_reason'=> $this->reason,
						't_title'=> $this->title,
						't_content' => $this->content,
						't_gold'=> $this->gold,
						't_bgold' => $this->bgold,
						't_silver' => $this->silver,
						't_copper' => $this->copper,
						't_operaor' => $this->user['username'],
						't_inserttime' => date("Y-m-d H:i:s"),
						't_status' => $status,
						't_minlv' => $minLv,
						't_maxlv' => $maxLv,
						't_endtime' => $emailTime
				));
				if($id != false){
					$ids[] = $id;
					foreach($this->toolList as $tool){
						if(!isset($tool['toolId'])){
							$tool['toolId'] = 0;
						}
						if(!isset($tool['toolName'])){
							$tool['toolName'] = "";
						}
						if(!isset($tool['toolNum'])){
							$tool['toolNum'] = 0;
						}
						if(!isset($tool['bstatus'])){
							$tool['bstatus'] = 0;
						}
						if(!isset($tool['jstatus'])){
							$tool['jstatus'] = 0;
						}
						if(!isset($tool['t_level'])){
							$tool['t_level'] = 0;
						}
						if(!isset($tool['addLevel'])){
							$tool['addLevel'] = 0;
						}
						
						$obj->table("tools_list")->insert(array(
							't_ta_id' => $id,	
							't_tid' => $tool['toolId'],
							't_name' => $tool['toolName'],
							't_num' => $tool['toolNum'],
							't_bstatus' => $tool['bstatus'],
							't_jstatus' => $tool['jstatus'],
							't_level' => $tool['level'],
							't_add' => $tool['addLevel'],
							't_inserttime' => date("Y-m-d H:i:s")						 			
						)); 
					}
				}
			}
			$result = array('ids'=>implode(',',$ids));
			echo json_encode($result);
			exit;
		}else{  //角色名为空
			$result = array('error' => '请输入角色名！');
			echo json_encode($result);
			exit;
		}
	}
	
	/**
	 * 道具申请操作表格
	 */
	public function getAskTable(){
		$total = 0;		//记录总数
		$obj = D('game_info');
		$total = $obj->table('tools_ask')-> where('t_status != -1 and t_ip = ' . $this->ip)->total();
		$ipList = autoConfig::getIPS();		//获取服务器信息
		
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'pageAjax','go','page');
		$pageHtml = $page->getPageHtml();
		$list = $obj->table('tools_ask')-> where('t_status != -1 and t_ip = ' . $this->ip)->order('t_status asc,t_id desc')->limit(intval($page->getOff()),intval($this->pageSize))->select();
		
		foreach($list as $k => $v){
			$list[$k]['t_content'] = htmlspecialchars_decode($v['t_content']);
		}
		$result = array(
				'list'=>$list,
				'pageHtml'=>$pageHtml,
				'ipList' => $ipList
		);

		echo json_encode($result);
		exit;
	}
	
	/**
	 * 金钱与道具详情
	 */
	public function getDetail(){
		$obj = D('game_info');
		$moneyList = array();		//金钱列表
		$toolList = array();		//道具列表
		$bo = $obj->table('tools_ask')->field('t_gold,t_bgold,t_silver,t_copper,t_minlv,t_maxlv,t_endtime,t_inserttime')->where('t_id = '. $this->id)->find();
		$list = $obj->table('tools_list')->where('t_ta_id = '.$this->id)->select();
		if(is_array($list)){
			$toolList = $list;
		}
		if(is_array($bo)){
			$moneyList = $bo;
			if($moneyList['t_endtime'] != 0) {
				$moneyList['t_endtime'] = date('Y-m-d H:i:s', strtotime($moneyList['t_inserttime']) + $moneyList['t_endtime']/1000); //转化成格式日期输出
			}
		}
		$result = array(
				'moneyList' => $moneyList,
				'toolList' => $toolList 
		);
		echo json_encode($result);
		exit;
	}
	
	/**
	 * 道具ID与道具名称关系
	 */
	public function getToolDetail(){
		$obj = D(GNAME.$this->ip);
		$list = array();	//道具ID与道具名称列表
		$total = 0;			//记录总数
		
		
		$type = get_var_value('type');
		$color =  get_var_value('color');
		$prof = get_var_value('prof');
		$sex = get_var_value('sex');
		$level = get_var_value('level');
		$searchKey = get_var_value('searchKey');
		
		$where_sql  = '';
		
		if($type) {
			$where_sql .= 't_type = "' . $type  . '" and ';
		}
		
		if($color) {
			$where_sql .= 't_color = "' . $color . '" and ';
		}
		
		if($prof) {
			$where_sql .= 't_prof = "' . $prof . '" and ';
		}
		
		if($sex) {
			$where_sql .= 't_sex = "' . $sex . '" and ';
		}
		
		if($level) {
			$where_sql .= 't_level = "' . $level . '" and ';
		}
		
		if($searchKey) {
			if(is_numeric($searchKey)) {
				$where_sql .= 't_code like "%'.$searchKey.'%"';
			}else {
				$where_sql .= 't_name like "%'.$searchKey.'%"';
			}
		}
		
		$where_sql = rtrim($where_sql, ' and ');
		
		
		
		if(!$where_sql){
			$total = $obj->table('tools_detail')-> total();
		}else{
			$total = $obj->table('tools_detail')->where($where_sql)->total();
		}
		
		$page = new autoAjaxPage($this->pageSize,$this->curPage,$total,'pageAjax2','go2','page2');
		$pageHtml = $page->getPageHtml();
		
		if(!$where_sql){
			$resource = $obj->table('tools_detail')->limit(intval($page->getOff()),intval($this->pageSize))->select();
		}else{
			$resource = $obj->table('tools_detail')
							->where($where_sql)
							->limit(intval($page->getOff()),intval($this->pageSize))
							->select();
		}
		
		if(is_array($resource)){
			$list =  $resource;
		}
		
		
		$type_list = $obj -> table('tools_detail') -> field('distinct t_type') -> select();								//类型
		$color_list = $obj -> table('tools_detail') -> field('distinct t_color') -> select();							//品质
		$level_list = $obj -> table('tools_detail') -> field('distinct t_level') -> order('t_level asc') -> select();	//等级
		
		$result = array(
				'list' => $list,
				'type' => $type_list,
				'color' => $color_list,
				'level' => $level_list,
				'pageHtml'=>$pageHtml
		);
		echo json_encode($result);
		exit;
	}
	
	
	/**
	 * 取消申请
	 */
	public function cancleAsk(){
		$id = get_var_value("id");
		if($id) {
			$obj = D("game_info");
			$state = $obj->table('tools_ask')->where(array("t_id"=>$id))->update(array(
				"t_status" => -1
			));
			if($state !=  false){
				echo json_encode("success");
				exit;
			}else{
				echo json_encode("error");
				exit;
			}
		} else {
			echo '1';
		}
	}
}
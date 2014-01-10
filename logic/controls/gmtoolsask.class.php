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
		$status = 1;

		$obj = D('game_base');
		$ids = array();				//保存返回的插入id
		if(empty($this->title)){
			$this->title = '系统邮件';
		}
		
		if('2' == $this->srole){	//单人发邮件发送
			$this->rolename = '全服';
		}
		
		if(intval($this->gold) > 100000000 || intval($this->copper) > 100000000){
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
			
			$search = array("'<script[^>]*?>.*?</script>'si");	//过滤特殊字符
			$replace = array("");
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
						
						$obj->table("tools_list")->insert(array(
							't_ta_id' => $id,	
							't_tid' => $tool['toolId'],
							't_name' => $tool['toolName'],
							't_num' => $tool['toolNum'],
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
		$obj = D('game_base');
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
		$obj = D('game_base');
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
		$obj = D('game'.$this->ip);
		$list = array();	//道具ID与道具名称列表
		$total = 0;			//记录总数
		
		
		$type1_map = array(
						'1' => '道具',
						'2' => '装备',
						'3' => '宝石',
						'4' => '材料',
						'5' => '其他'
					);
					
		$type2_map = array(
						'1' => array(
							'1' => '消耗品类',
							'2' => '传送道具',
							'3' => '喇叭',
							'4' => '资源类',
							'7' => '战斗辅助',
							'8' => '行囊',
							'9' => '藏宝图',
							'10' => '任务类',
							'11' => '召唤类',
							'12' => '武将技能书',
							'13' => '武将配饰',
							'14' => '幻化坐骑',
							'15' => 'VIP卡',
							'16' => '武将卡',
							'17' => '礼包'
						),
						
						'2' => array(
							'1' => '头盔',
							'2' => '衣服',
							'3' => '护手',
							'4' => '腰带',
							'5' => '裤子',
							'6' => '鞋子',
							'7' => '武器',
							'8' => '戒指',
							'9' => '项链',
							'10' => '时装衣服',
							'11' => '时装武器',
						),
						
						
						'3' => array(
							'1' => '普通宝石',
							'2' => '特性宝石'
						),
						
						'4' => array(
							'1' => '装备强化材料',
							'2' => '装备洗炼材料',
							'3' => '宝石材料',
							'4' => '装备材料',
							'5' => '装备合成材料',
							'6' => '任务材料',
							'7' => '命格材料',
							'8' => '刷任务材料',
							'9' => '任命官职材料',
							'10' => '坐骑材料',
							'11' => '神兵材料',
							'12' => '武将材料',
							'13' => '寻访道具',
							'14' => '碎片类材料'
						)
						
					);			
		
		$type3_map = array(
						'1' => array(
							'1' => array(
								'1' => '角色瞬回HP',
								'2' => '角色瞬回MP',
								'3' => '角色持续HP',
								'4' => '角色持续MP',
								'5' => '角色储蓄包HP',
								'6' => '角色储蓄包MP',
								'7' => '武将持续HP',
								'8' => '武将储蓄包HP',
								'9' => '属性BUFF',
								'10' => '属性DEBUFF',
								'11' => '资源BUFF'
							),
							'2' => array(
								'1' => '行军令',
								'2' => '回城卷',
								'3' => '英豪令',
								'4' => '帮主令',
								'5' => '国家令'
							),
							'3' => array(
								'1' => '服务器喇叭',
								'2' => '跨服喇叭',
								'3' => '走马灯喇叭'
							),
							'4' => array(
								'1' => '铜币',
								'2' => '绑定铜币',
								'3' => '元宝',
								'4' => '绑定元宝',
								'5' => '礼券',
								'6' => '经验',
								'9' => '灵魄'
							),
							'7' => array(
								'1' => '小强丸'
							),
							'8' => array(
								'1' => '扩容背包'
							),
							'9' => array(
								'1' => '世界宝藏',
								'2' => '国家宝藏',
								'3' => '副本宝藏',
								'4' => '普通宝藏',
								'5' => '世界藏宝图碎片',
								'6' => '国家藏宝图碎片',
								'7' => '副本藏宝图碎片'
							),
							'10' => array(
								'1' => '委托任务',
								'2' => '生成任务'
							),
							'11' => array(
								'1' => '召唤NPC',
								'2' => '召唤monster'
							),
							'12' => array(
								'1' => '撕裂',
								'2' => '嗜血',
								'3' => '反击',
								'4' => '连击',
								'5' => '反震',
								'6' => '噬灵',
								'7' => '通灵',
								'8' => '灭魂',
								'9' => '丧胆',
								'10' => '破甲',
								'11' => '散神',
								'12' => '蛮击',
								'13' => '活力',
								'14' => '焕神',
								'15' => '培元',
								'16' => '神力',
								'17' => '迅捷',
								'18' => '强体',
								'19' => '明智',
								'20' => '聚神'
							),
							'13' => array(
								'1' => '玉佩',
								'2' => '明珠',
								'3' => '护符',
								'4' => '令牌',
								'5' => '宝镜'
							),
							'14' => array(
								'1' => '战狼',
								'2' => '虬龙',
								'3' => '麒麟'
							),
							'15' => array(
								'1' => '体验卡（30分钟）',
								'2' => '1天卡',
								'3' => '周卡',
								'4' => '月卡',
								'5' => '半年卡'
							),
							'16' => '武将卡',
							'17' => array(
								'1' => '一般随机礼包',
								'2' => '特殊随机礼包'
							)
						),
						
						'2' => array(
							'1' => '头盔',
							'2' => '衣服',
							'3' => '护手',
							'4' => '腰带',
							'5' => '裤子',
							'6' => '鞋子',
							'7' => '武器',
							'8' => '戒指',
							'9' => '项链',
							'10' => '时装衣服',
							'11' => '时装武器'
						),
						
						
						'3' => array(
							'1' => array(
								'1' => '力量',
								'2' => '敏捷',
								'3' => '体质',
								'4' => '智力',
								'5' => '精神'
							),
							'2' => array(
								'1' => '生命',
								'2' => '法力',
								'3' => '物攻',
								'4' => '物防',
								'5' => '法攻',
								'6' => '法防',
								'7' => '命中',
								'8' => '闪避',
								'9' => '暴击',
								'10' => '免爆'
							)
						),
						
						'4' => array(
							'1' => array(
								'1' => '装备强化石1',
								'2' => '装备强化石2',
								'3' => '装备强化石3',
								'4' => '装备强化石4',
								'5' => '装备强化石5',
								'6' => '装备强化石6',
								'7' => '装备强化石7',
								'8' => '装备强化石8',
								'9' => '装备强化石9',
								'10' => '装备强化石10',
								'11' => '装备强化石11',
								'12' => '装备强化石12',
								'21' => '1级强化幸运符',
								'22' => '2级强化幸运符',
								'23' => '3级强化幸运符',
								'24' => '4级强化幸运符',
								'25' => '5级强化幸运符',
								'26' => '6级强化幸运符',
								'27' => '7级强化幸运符',
								'28' => '8级强化幸运符',
								'29' => '9级强化幸运符',
								'30' => '10级强化幸运符',
								'31' => '11级强化幸运符',
								'32' => '12级强化幸运符'					
							),
							'2' => array(
								'1' => '属性洗炼石1',
								'2' => '属性洗炼石2',
								'3' => '属性洗炼石3',
								'4' => '属性洗炼石4',
								'5' => '洗炼锁'
							),
							'3' => array(
								'1' => '合成保护符',
								'2' => '1级纯炼砂',
								'3' => '2级纯炼砂',
								'4' => '3级纯炼砂',
								'5' => '4级纯炼砂',
								'6' => '5级纯炼砂',
								'7' => '6级纯炼砂',
								'8' => '7级纯炼砂',
								'9' => '8级纯炼砂'
							),
							'4' => array(
								'1' => '1、2、3、4级灵珠',
								'2' => '精炼水晶',
								'3' => '精炼灵石',
								'4' => '熔炼符'
							),
							'5' => array(
								'1' => '火云碎片',
								'2' => '天星碎片'
							),
							'6' => array(
								'1' => '任务所需道具'
							),
							'7' => array(
								'1' => '占星石',
								'2' => '七星灯'
							),
							'8' => array(
								'1' => '更改品质',
								'2' => '不更改品质'
							),
							'9' => array(
								'1' => '任命皇后',
								'2' => '任命亲卫',
								'3' => '元帅任命官职材料',
								'4' => '丞相任命官职材料'
							),
							'10' => array(
								'1' => '坐骑进阶丹',
								'2' => '坐骑破魂丹',
								'3' => '孟婆汤',
								'4' => '坐骑灵魄',
							
							),
							'11' =>  array(
								'1' => '天灵丹',
								'2' => '五行丹'
							),
							'12' => array(
								'1' => '武将资质丹',
								'3' => '武将经验丹',
								'4' => '武将转生丹',
								'5' => '武将成长丹',
								'6' => '技能符文',
								'7' => '技能封印符',
								'8' => '忘魂丹',
								'9' => '技能魂石',
								'10' => '星晶',
								'11' => '重铸玄铁',
								'12' => '武将继承丹'
							),
							'13' => array(
								'1' => '寻贤令',
								'2' => '寻访令'
							),
							'14' => array(
								'1' => '装备原生碎片',
								'2' => '精炼水晶碎片',
								'3' => '精炼灵石碎片',
								'4' => '灵珠碎片',
								'5' => '宝石原生碎片',
								'6' => '普通宝石碎片',
								'7' => '特性宝石碎片',
								'8' => '武将原生碎片',
								'9' => '技能魂石碎片',
								'10' => '寻贤令碎片',
								'11' => '寻访令碎片',
								'12' => '坐骑原生碎片',
								'13' => '占星石碎片',
								'14' => '七星灯碎片'
							)
						)
						
					);			
		
		
	
		$type1 = get_var_value('type1');
		$type2 = get_var_value('type1');
		$type3 = get_var_value('type1');
		$searchKey = get_var_value('searchKey');
		
		$where_sql  = '(t_type1 != -1 and t_type2 != -1 and t_type3 != -1) and ';	//-1为无用道具
		
		if($type1) {
			$where_sql .= 't_type1 = "' . $type1  . '" and ';
		}
		
		if($type2) {
			$where_sql .= 't_type2 = "' . $type2 . '" and ';
		}
		
		if($type3) {
			$where_sql .= 't_type3 = "' . $type3 . '" and ';
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
		
		$result = array(
				'list' => $list,
				'type1_map' => $type1_map,
				'type2_map' => $type2_map,
				'type3_map' => $type3_map,
				'pageHtml' => $pageHtml
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
			$obj = D("game_base");
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
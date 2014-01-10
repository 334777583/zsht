<?php
class game{
	/**
	 * 主键id
	 * @var int
	 */
	private $id;
	
	/**
	 * 名字
	 * @var string
	 */
	private $name;
	
	/**
	 * 服务器ip
	 * @var char
	 */
	private $ip;
	
	/**
	 * 服务器ip
	 * @var char
	 */
	private $domain;
	
	/**
	 * 端口
	 * @var int
	 */
	private $port;
	
	/**
	 * 用户信息
	 * @var array
	 */
	private $user;
	
	
	/**
	 * 初始化数据
	 */
	public function init(){
		$userobj = D("sysuser");
		if($this->user = $userobj->isLogin()){
			if(!in_array("00501200", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
		$this->name = get_var_value("name") == NULL?"":get_var_value("name");
		$this->ip =  get_var_value("ip") == NULL?"":get_var_value("ip");
		$this->id = get_var_value("id") == NULL?0:get_var_value("id");
		$this->port = get_var_value("port") == NULL?"":get_var_value("port");
		$this->domain = get_var_value("domain") == NULL?null:get_var_value("domain");
	}
	
	
	
	/**
	 * 显示服务器管理页面
	 */
	public function show(){
		$gamedb = D("gamedb");
		$plist = $gamedb->where(array("g_flag"=>1))->select();
		$this->assign("plist",$plist);
		$this->display("system/game_db");
	}
	
	/**
	 * 根据id获取服务器信息
	 */
	public function getById(){
		$server = D("gamedb");
		$bo = $server->where(array("g_id"=>$this->id))->find();
		echo json_encode($bo);
		exit;		
	}
	
	/**
	 * 保存服务器数据
	 */
	public function save(){
		if($this->id == 0){
			echo json_encode("error");
			exit;
		}
		$gamedb = D("gamedb");
		$state = $gamedb->where(array("g_id"=>$this->id))->update(array(
				"g_name"=>$this->name,
				"g_domain" => $this->domain,
				"g_ip"=>$this->ip,
				"g_port"=>$this->port,
				"g_inserttime" => date("Y-m-d H:i:s")
		));
		if($state !=  false){
			echo json_encode("success");
			exit;
		}
		echo json_encode("error");
	}
	
	/**
	 * 删除
	 */
	public  function delete(){
		if($this->id == 0){
			echo json_encode("error");
			exit;
		}
		$gamedb = D("gamedb");
		$state = $gamedb -> where(array("g_id"=>$this->id)) -> update(array("g_flag"=> 2));
		if($state != false){
		
			echo json_encode("success");
			exit;
		}
		echo json_encode("error");
		exit;
	}
	
	/**
	 * 添加
	 */
	public function add(){
		$gamedb = D("gamedb");
		$state = $gamedb->insert(array(
				"g_name"=>$this->name,
				"g_ip"=>$this->ip,
				"g_port"=>$this->port,
				"g_domain"=>$this->domain,
				"g_inserttime" => date("Y-m-d H:i:s")
		
		));
		if($state !=  false) {
			$servers = D("servers");
			$servers->insert(array(
					"s_name"=>$this->name,
					"s_ip"=>$this->ip,
					"s_gid"=>$state,
					"s_port"=>'20101',
					"s_domain"=>$this->domain,
					"s_inserttime" => date("Y-m-d H:i:s")
			
			));
			
			$call = $this -> createdb($state);
			if($call == false){
				echo json_encode("没有权限创建表！");
				exit;
			}
			
			echo json_encode("success");
			exit;
		}
		echo json_encode("error");
		exit;
	}
	
	/**
	 * 自动创建游戏db
	 */
	public function createdb($id) {
		$gamedb = D('gamedb');
		$newdb = 'game' . $id;
		$sql = 'CREATE DATABASE ' . $newdb . ' COLLATE=utf8_unicode_ci;';
		$re = $gamedb -> query_nothing($sql);
		
		if(!$re){
			return false;
		}
		
		$sql = 'use '.$newdb;
		$gamedb -> query_nothing($sql);
		
		$sql = 'set names utf8';
		$gamedb -> query_nothing($sql);
		
		$sql ="CREATE TABLE `detail_login` (
				`d_id`  int(11) NOT NULL AUTO_INCREMENT ,
				`d_user`  char(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名' ,
				`d_ip`  char(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'IP' ,
				`d_userid`  int(11) NOT NULL COMMENT '角色ID' ,
				`d_date`  datetime NOT NULL COMMENT '登陆的时间' ,
				PRIMARY KEY (`d_id`),
				INDEX `d_user` (`d_user`) USING BTREE ,
				INDEX `d_ip` (`d_ip`) USING BTREE 
				)
				ENGINE=MyISAM
				DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci
				COMMENT='用户登陆具体信息'
				AUTO_INCREMENT=0";
		$gamedb -> query_nothing($sql);
		
		$sql ="CREATE TABLE `goods` (
			  `g_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `g_ids` int(13) NOT NULL COMMENT '物品的ID',
			  `g_peo` int(5) NOT NULL COMMENT '购买人数',
			  `g_price` int(7) NOT NULL COMMENT '单价',
			  `g_num` int(6) NOT NULL COMMENT '数量',
			  `g_code` char(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '标记各项箭头方向(0:无；1：升；2：降)',
			  `g_type` tinyint(1) NOT NULL COMMENT '类型 0-元宝 1-绑定元宝',
			  `g_date` date NOT NULL COMMENT '所属日期',
			  `g_time` datetime NOT NULL COMMENT '分析日期',
			  PRIMARY KEY (`g_id`),
			  KEY `g_date` (`g_date`) USING BTREE,
			  KEY `g_ids` (`g_ids`) USING BTREE
			) ENGINE=MyISAM AUTO_INCREMENT=272 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		$gamedb -> query_nothing($sql);
		
		
		$sql ="CREATE TABLE `history_num` (
				`h_id`  int(11) NOT NULL AUTO_INCREMENT ,
				`h_date`  datetime NOT NULL ,
				`h_num`  int(7) NOT NULL COMMENT '当前在线角色数量' ,
				PRIMARY KEY (`h_id`),
				INDEX `h_date` (`h_date`) USING BTREE ,
				INDEX `h_nums` (`h_num`) USING BTREE 
				)
				ENGINE=MyISAM
				DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
				COMMENT='历史当前 在线人数'
				AUTO_INCREMENT=0";
		$gamedb -> query_nothing($sql);
		
		$sql ="CREATE TABLE `double_week` (
			  `d_id` int(11) NOT NULL AUTO_INCREMENT,
			  `d_new_man` int(5) NOT NULL DEFAULT '0' COMMENT '新注册数',
			  `d_old_man` int(5) NOT NULL DEFAULT '0' COMMENT '老用户登陆数',
			  `d_date` date NOT NULL COMMENT '当天日期',
			  `d_datetime` datetime NOT NULL COMMENT '入库日期',
			  PRIMARY KEY (`d_id`),
			  KEY `d_date` (`d_date`) USING BTREE
			) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		$gamedb -> query_nothing($sql);
		
		$sql ="CREATE TABLE `ip` (
				`i_id`  int(11) NOT NULL AUTO_INCREMENT ,
				`i_ip`  char(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'IP ' ,
				`i_date`  datetime NOT NULL ,
				PRIMARY KEY (`i_id`),
				UNIQUE INDEX `ip` (`i_ip`) USING BTREE ,
				INDEX `i_date` (`i_date`) USING BTREE 
				)
				ENGINE=MyISAM
				DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
				COMMENT='登陆历史IP表'
				AUTO_INCREMENT=0";
		$gamedb -> query_nothing($sql);	

		$sql ="CREATE TABLE `main_login` (
			  `m_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增',
			  `m_reg` int(7) NOT NULL COMMENT '注册数',
			  `m_creat` int(7) NOT NULL COMMENT '创号数',
			  `m_login` int(7) NOT NULL COMMENT '登陆数(过滤重复)',
			  `m_newlogin` int(5) NOT NULL COMMENT '新增',
			  `m_newip` int(5) NOT NULL COMMENT '新增IP数',
			  `m_login_sum` int(7) NOT NULL COMMENT '登陆总数登陆数(不过滤重复)',
			  `m_ip_num` int(7) NOT NULL COMMENT '登陆IP数(去重复)',
			  `m_two` int(7) NOT NULL COMMENT '2登陆(查询历史记录)',
			  `m_five` int(7) NOT NULL COMMENT '大于5登陆',
			  `m_ten` int(6) NOT NULL COMMENT '大于10登',
			  `m_count` int(5) NOT NULL COMMENT '平均在线时长',
			  `m_maxtime` int(5) NOT NULL COMMENT '最高在线时长',
			  `m_sametime` int(5) NOT NULL COMMENT '平均同时在线 = 所有在线人数 / 分钟',
			  `m_maxsametime` int(5) NOT NULL COMMENT '最高同时在线',
			  `m_curr` int(5) NOT NULL COMMENT '当前在线',
			  `m_date` date NOT NULL COMMENT '数据时间',
			  `m_insertdate` datetime DEFAULT NULL COMMENT '更新时间',
			  `m_service` int(3) NOT NULL COMMENT '服务器数 1 2 3 4 5....',
			  PRIMARY KEY (`m_id`),
			  KEY `m_date` (`m_date`) USING BTREE,
			  KEY `m_service` (`m_service`) USING BTREE
			) ENGINE=MyISAM AUTO_INCREMENT=137 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户登陆概况'";
		$gamedb -> query_nothing($sql);	

		$sql ="CREATE TABLE `online_sec` (
			  `o_id` int(11) NOT NULL AUTO_INCREMENT,
			  `o_user` char(25) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
			  `o_second` int(10) NOT NULL COMMENT '时长 单位秒',
			  `o_userid` int(11) NOT NULL COMMENT '角色ID',
			  `o_level` smallint(3) unsigned NOT NULL COMMENT '等级',
			  `o_task` int(8) unsigned NOT NULL COMMENT '任务ID',
			  `o_date` datetime NOT NULL COMMENT '登陆的时间',
			  PRIMARY KEY (`o_id`),
			  KEY `d_user` (`o_user`) USING BTREE,
			  KEY `d_second` (`o_second`) USING BTREE
			) ENGINE=MyISAM AUTO_INCREMENT=4889 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户在线时长表'";
		$gamedb -> query_nothing($sql);	

		$sql ="CREATE TABLE `payments` (
				  `p_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				  `p_tong` bigint(20) unsigned NOT NULL COMMENT '铜币',
				  `p_yin` bigint(20) unsigned NOT NULL COMMENT '银子',
				  `p_byuan` bigint(20) NOT NULL COMMENT '绑定元宝',
				  `p_yuan` bigint(20) NOT NULL COMMENT '元宝',
				  `p_type` tinyint(1) NOT NULL COMMENT '类型 0 增加 1减少',
				  `p_date` date NOT NULL COMMENT '所属日期',
				  `p_time` datetime NOT NULL COMMENT '分析日期',
				  PRIMARY KEY (`p_id`),
				  KEY `p_date` (`p_date`) USING BTREE
				) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		$gamedb -> query_nothing($sql);
		
		$sql ="CREATE TABLE `role` (
			  `r_roleid` int(11) NOT NULL COMMENT '角色ID',
			  `r_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色名',
			  `r_updatetime` datetime NOT NULL COMMENT '更新时间',
			  PRIMARY KEY (`r_roleid`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		$gamedb -> query_nothing($sql);	

		$sql ="CREATE TABLE `sumgoods` (
			  `s_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `s_peo` mediumint(7) NOT NULL COMMENT '购买人数',
			  `s_num` int(7) NOT NULL COMMENT '购买数量',
			  `s_total` int(10) NOT NULL COMMENT '消费总额',
			  `s_code` char(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '标记各项箭头方向(0:无；1：升；2：降)',
			  `s_type` tinyint(1) NOT NULL COMMENT '类型 0-元宝 1-绑定元宝',
			  `s_date` date NOT NULL COMMENT '所属日期',
			  `s_time` datetime NOT NULL COMMENT '统计日期',
			  PRIMARY KEY (`s_id`),
			  KEY `s_date` (`s_date`) USING BTREE
			) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		$gamedb -> query_nothing($sql);	

		$sql ="CREATE TABLE `user` (
			  `u_id` int(11) NOT NULL AUTO_INCREMENT,
			  `u_username` char(25) NOT NULL COMMENT '用户名',
			  `u_date` datetime NOT NULL COMMENT '时间',
			  PRIMARY KEY (`u_id`),
			  UNIQUE KEY `u_username` (`u_username`) USING HASH,
			  KEY `u_date` (`u_date`) USING BTREE
			) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='历史登陆用户账户表'";
		$gamedb -> query_nothing($sql);
		

		$sql ="CREATE TABLE `dead` (
			  `d_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `d_playid` int(11) unsigned NOT NULL COMMENT '角色ID',
			  `d_level` int(7) unsigned NOT NULL COMMENT '等级',
			  `d_type` int(7) unsigned NOT NULL COMMENT '类型',
			  `d_date` datetime NOT NULL COMMENT '记录日期',
			  `d_time` date NOT NULL COMMENT '创建日期',
			  PRIMARY KEY (`d_id`)
			) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		$gamedb -> query_nothing($sql);
		
		
		$sql="CREATE TABLE `action_temp` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `date` date NOT NULL,
			  `cwltg` int(11) NOT NULL,
			  `cwlcz` int(11) NOT NULL,
			  `jqtg` int(11) NOT NULL,
			  `jqzj` int(11) NOT NULL,
			  `dgtg` int(11) NOT NULL,
			  `dgzj` int(11) NOT NULL,
			  `qm` int(11) NOT NULL,
			  `dj` int(11) NOT NULL,
			  `ht` int(11) NOT NULL,
			  `ls` int(11) NOT NULL,
			  `xl` int(11) NOT NULL,
			  `qy` int(11) NOT NULL,
			  `du` int(11) NOT NULL,
			  `jm` int(11) NOT NULL,
			  `sjwc` int(11) NOT NULL,
			  `sjgm` int(11) NOT NULL,
			  `cfwc` int(11) NOT NULL,
			  `cfgm` int(11) NOT NULL,
			  `sh` int(11) NOT NULL,
			  `td` int(11) NOT NULL,
			  `py` int(11) NOT NULL,
			  `sx` int(11) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='行为分析缓存表'";
		$gamedb -> query_nothing($sql);
		
		
		$sql="CREATE TABLE `cm` (
			  `c_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `c_playid` int(13) NOT NULL COMMENT '角色ID',
			  `c_jingli` int(11) unsigned NOT NULL COMMENT '消耗精力值',
			  `c_yinzi` int(11) NOT NULL COMMENT '消耗银子',
			  `c_yuanbao` int(11) NOT NULL COMMENT '消耗元宝',
			  `c_jingyan` int(11) NOT NULL COMMENT '获得经验',
			  `c_shuxing` int(11) NOT NULL COMMENT '获取属性值',
			  `c_result` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '结果',
			  `c_date` datetime NOT NULL COMMENT '生成时间',
			  `c_time` date NOT NULL COMMENT '入库时间',
			  PRIMARY KEY (`c_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='冲脉'";
		$gamedb -> query_nothing($sql);
		
		
		
		$sql="CREATE TABLE `dwlcz` (
		  `d_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `d_playid` int(13) NOT NULL COMMENT '角色ID',
		  `d_huobi` int(1) NOT NULL COMMENT '消耗货币类型',
		  `d_chongzhi` int(5) unsigned NOT NULL COMMENT '今日已重置次数',
		  `d_date` datetime NOT NULL COMMENT '生成时间',
		  `d_time` date NOT NULL COMMENT '入库时间',
		  PRIMARY KEY (`d_id`)
		) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='盗王陵重置'";
		$gamedb -> query_nothing($sql);
		
		
		
		$sql="CREATE TABLE `dwltg` (
			  `d_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `d_playid` int(13) NOT NULL COMMENT '角色ID',
			  `d_fuben` int(10) NOT NULL COMMENT '副本原型',
			  `d_tongguan` int(5) unsigned NOT NULL COMMENT '通关次数',
			  `d_date` datetime NOT NULL COMMENT '生成时间',
			  `d_time` date NOT NULL COMMENT '入库时间',
			  PRIMARY KEY (`d_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='盗王陵通关'";
		$gamedb -> query_nothing($sql);
		
		
		$sql="CREATE TABLE `tools_detail` (
			  `t_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
			  `t_code` int(11) NOT NULL COMMENT '道具ID',
			  `t_name` varchar(255) NOT NULL COMMENT '道具名称',
			  `t_type1` int(5) NOT NULL COMMENT '道具类型',
			  `t_type2` int(5) NOT NULL COMMENT '道具品质',
			  `t_type3` int(5) NOT NULL COMMENT '职业限制',
			  PRIMARY KEY (`t_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
		$gamedb -> query_nothing($sql);
		
		
		$sql="CREATE TABLE `fbcz` (
			  `f_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `f_playid` int(13) NOT NULL COMMENT '角色ID',
			  `f_hb` int(1) unsigned NOT NULL COMMENT '货币消费类型',
			  `f_fb` int(5) NOT NULL COMMENT '副本类型',
			  `f_yuanxin` int(5) NOT NULL COMMENT '副本原型',
			  `f_date` datetime NOT NULL COMMENT '生成时间',
			  `f_time` date NOT NULL COMMENT '入库时间',
			  PRIMARY KEY (`f_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='副本重置'";
		$gamedb -> query_nothing($sql);
		
		
		$sql="CREATE TABLE `fbgm` (
			  `f_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `f_account` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '账号',
			  `f_playid` int(13) NOT NULL COMMENT '角色ID',
			  `f_huobi` int(1) NOT NULL COMMENT '货币消费类型',
			  `f_leixing` int(5) unsigned NOT NULL COMMENT '副本类型：剧情副本',
			  `f_yuanxin` int(5) NOT NULL COMMENT '副本原型',
			  `f_date` datetime NOT NULL COMMENT '生成时间',
			  `f_time` date NOT NULL COMMENT '入库时间',
			  PRIMARY KEY (`f_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='副本次数购买'";
		$gamedb -> query_nothing($sql);
		
		$sql="CREATE TABLE `fbtz` (
			  `f_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `f_playid` int(13) NOT NULL COMMENT '角色ID',
			  `f_fblx` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '副本类型',
			  `f_status` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '状态',
			  `f_haoshi` int(11) NOT NULL COMMENT '耗时（毫秒）',
			  `f_yuanxin` int(5) NOT NULL COMMENT '副本原型',
			  `f_date` datetime NOT NULL COMMENT '生成时间',
			  `f_time` date NOT NULL COMMENT '入库时间',
			  PRIMARY KEY (`f_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='副本挑战'";
		$gamedb -> query_nothing($sql);
		
		$sql="CREATE TABLE `gmrwcs` (
			  `g_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `g_rw` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '任务类型(0：random，1：repeat）',
			  `g_playid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
			  `g_hfjq` int(11) NOT NULL COMMENT '花费金钱',
			  `g_jqlx` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '花费金钱类型',
			  `g_date` datetime NOT NULL COMMENT '生成时间',
			  `g_time` date NOT NULL COMMENT '入库时间',
			  PRIMARY KEY (`g_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='购买任务次数'";
		$gamedb -> query_nothing($sql);
		
		
		$sql="CREATE TABLE `mvdjsx` (
			  `m_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `m_playid` int(13) NOT NULL COMMENT '角色ID',
			  `m_zssl` int(11) NOT NULL COMMENT '展示数量',
			  `m_mlz` int(11) NOT NULL COMMENT '产出魅力值',
			  `m_date` datetime NOT NULL COMMENT '生成时间',
			  `m_time` date NOT NULL COMMENT '入库时间',
			  PRIMARY KEY (`m_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='美女道具刷新'";
		$gamedb -> query_nothing($sql);
		
		$sql="CREATE TABLE `mvpy` (
			  `m_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `m_playid` int(13) NOT NULL COMMENT '角色ID',
			  `m_tianfu` int(11) NOT NULL COMMENT '天赋',
			  `m_jnid` int(11) NOT NULL COMMENT '培养的技能ID',
			  `m_mlz` int(11) NOT NULL COMMENT '消耗的魅力值',
			  `m_date` datetime NOT NULL COMMENT '生成时间',
			  `m_time` date NOT NULL COMMENT '入库时间',
			  PRIMARY KEY (`m_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='美女培养'";
		$gamedb -> query_nothing($sql);
		
		
		$sql="CREATE TABLE `mvsh` (
			  `m_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `m_playid` int(13) NOT NULL COMMENT '角色ID',
			  `m_mvid` int(11) NOT NULL COMMENT '美女ID',
			  `m_haogan` int(11) NOT NULL COMMENT '获得好感值',
			  `m_date` datetime NOT NULL COMMENT '生成时间',
			  `m_time` date NOT NULL COMMENT '入库时间',
			  PRIMARY KEY (`m_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='美女收获'";
		$gamedb -> query_nothing($sql);
		
		
		$sql="CREATE TABLE `mvtd` (
			  `m_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `m_playid` int(13) NOT NULL COMMENT '角色ID',
			  `m_mvid` int(11) NOT NULL COMMENT '美女ID',
			  `m_shijian` int(11) NOT NULL COMMENT '减少美女的工作时间（毫秒）',
			  `m_date` datetime NOT NULL COMMENT '生成时间',
			  `m_time` date NOT NULL COMMENT '入库时间',
			  PRIMARY KEY (`m_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='美女推倒'";
		$gamedb -> query_nothing($sql);
		
		
		$sql="CREATE TABLE `qy` (
			  `q_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `q_playid` int(13) NOT NULL COMMENT '角色ID',
			  `q_leixing` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '奇遇消耗的货币类型',
			  `q_qydd` int(5) NOT NULL COMMENT '当前奇遇地点',
			  `q_qyddzs` int(11) NOT NULL COMMENT '已激活的奇遇地点总数',
			  `q_date` datetime NOT NULL COMMENT '生成时间',
			  `q_time` date NOT NULL COMMENT '入库时间',
			  PRIMARY KEY (`q_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='奇遇'";
		$gamedb -> query_nothing($sql);
		
		$sql="CREATE TABLE `sx` (
			  `s_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `s_playid` int(13) NOT NULL COMMENT '角色ID',
			  `s_leixing` int(1) unsigned NOT NULL COMMENT '香料类型',
			  `s_date` datetime NOT NULL COMMENT '生成时间',
			  `s_time` date NOT NULL COMMENT '入库时间',
			  PRIMARY KEY (`s_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='上香'";
		$gamedb -> query_nothing($sql);
		
		
		$sql="CREATE TABLE `wcrw` (
			  `w_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `w_rw` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '任务类型',
			  `w_playid` int(11) NOT NULL COMMENT '角色ID',
			  `w_wc` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '完成类型',
			  `w_rwid` int(11) NOT NULL COMMENT '任务ID',
			  `w_date` datetime NOT NULL COMMENT '生成时间',
			  `w_time` date NOT NULL COMMENT '入库时间',
			  PRIMARY KEY (`w_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=379 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='完成任务'";
		$gamedb -> query_nothing($sql);
		
		
		$sql="CREATE TABLE `zbcd` (
			  `z_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `z_playid` int(13) NOT NULL COMMENT '角色ID',
			  `z_zbid` int(11) NOT NULL COMMENT '装备ID',
			  `z_zbdj` int(5) NOT NULL COMMENT '装备等级',
			  `z_bw` int(5) NOT NULL COMMENT '装备部位',
			  `z_zjdj` int(5) NOT NULL COMMENT '追加等级',
			  `z_qhdj` int(5) NOT NULL COMMENT '强化等级',
			  `z_jngs` int(5) NOT NULL COMMENT '套装技能个数',
			  `z_date` datetime NOT NULL COMMENT '生成时间',
			  `z_time` date NOT NULL COMMENT '入库时间',
			  PRIMARY KEY (`z_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='装备穿戴'";
		$gamedb -> query_nothing($sql);
		
		
		$sql="CREATE TABLE `zbhc` (
			  `z_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `z_playid` int(13) NOT NULL COMMENT '角色ID',
			  `z_zbid` int(11) NOT NULL COMMENT '追加装备ID',
			  `z_tzdj` int(5) NOT NULL COMMENT '套装等阶',
			  `z_zbjn` int(5) NOT NULL COMMENT '装备技能个数',
			  `z_bw` int(5) NOT NULL COMMENT '装备部位',
			  `z_date` datetime NOT NULL COMMENT '生成时间',
			  `z_time` date NOT NULL COMMENT '入库时间',
			  PRIMARY KEY (`z_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='套装合成'";
		$gamedb -> query_nothing($sql);
		
		$sql="CREATE TABLE `zbjnxl` (
			  `z_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `z_playid` int(13) NOT NULL COMMENT '角色ID',
			  `z_djid` int(11) NOT NULL COMMENT '洗练消耗的道具ID',
			  `z_djsl` int(11) NOT NULL COMMENT '消耗道具数量',
			  `z_bw` int(5) NOT NULL COMMENT '装备部位',
			  `z_date` datetime NOT NULL COMMENT '生成时间',
			  `z_time` date NOT NULL COMMENT '入库时间',
			  PRIMARY KEY (`z_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='套装技能洗练'";
		$gamedb -> query_nothing($sql);
		
		$sql="CREATE TABLE `zbxl` (
			  `z_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `z_playid` int(13) NOT NULL COMMENT '角色ID',
			  `z_xldj` int(11) NOT NULL COMMENT '洗练等级',
			  `z_zbid` int(11) NOT NULL COMMENT '洗练装备ID',
			  `z_bw` int(5) NOT NULL COMMENT '装备部位',
			  `z_date` datetime NOT NULL COMMENT '生成时间',
			  `z_time` date NOT NULL COMMENT '入库时间',
			  PRIMARY KEY (`z_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='追加功能洗练'";
		$gamedb -> query_nothing($sql);
		
		
		$sql="CREATE TABLE `zbzj` (
			  `z_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `z_playid` int(13) NOT NULL COMMENT '角色ID',
			  `z_zbid` int(11) NOT NULL COMMENT '追加装备ID',
			  `z_zbdj` int(5) NOT NULL COMMENT '追加等级',
			  `z_bw` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '装备部位',
			  `z_date` datetime NOT NULL COMMENT '生成时间',
			  `z_time` date NOT NULL COMMENT '入库时间',
			  PRIMARY KEY (`z_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='装备追加'";
		$gamedb -> query_nothing($sql);
		
		$sql="CREATE TABLE `zbqh` (
			  `z_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `z_playid` int(13) NOT NULL COMMENT '角色ID',
			  `z_zbid` int(11) NOT NULL COMMENT '强化装备ID',
			  `z_zbdj` int(5) NOT NULL COMMENT '强化等级',
			  `z_bw` int(5) NOT NULL COMMENT '装备部位',
			  `z_date` datetime NOT NULL COMMENT '生成时间',
			  `z_time` date NOT NULL COMMENT '入库时间',
			  PRIMARY KEY (`z_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='装备强化'";
		$gamedb -> query_nothing($sql);
		
		$sql = "CREATE TABLE `createplay` (
			  `c_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `c_enter` mediumint(7) unsigned NOT NULL COMMENT '进入创建角色页面数',
			  `c_csuccess` mediumint(7) unsigned NOT NULL COMMENT '创建角色成功数',
			  `c_entergame` mediumint(7) unsigned NOT NULL COMMENT '创建角色后成功进入游戏数',
			  `c_login_suc` mediumint(7) unsigned NOT NULL COMMENT '跳转成功数',
			  `c_login_fai` mediumint(7) unsigned NOT NULL COMMENT '跳转失败数',
			  `c_date` date NOT NULL COMMENT '所属日期',
			  PRIMARY KEY (`c_id`),
			  UNIQUE KEY `c_date` (`c_date`) USING HASH
			) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		$gamedb -> query_nothing($sql);	

		$sql = "CREATE TABLE `ptlogin` (
			  `p_id` int(13) unsigned NOT NULL AUTO_INCREMENT,
			  `p_account` int(13) NOT NULL COMMENT '账号',
			  `p_ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'IP',
			  `p_ser` varchar(3) COLLATE utf8_unicode_ci NOT NULL COMMENT '服数',
			  `p_decript` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
			  `p_time` datetime NOT NULL COMMENT '日期',
			  `p_date` date NOT NULL COMMENT '生成日期',
			  PRIMARY KEY (`p_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='平台具体登陆状况'";
		$gamedb -> query_nothing($sql);

		$sql = "CREATE TABLE `login_temp` (
			  `l_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `l_date` date NOT NULL,
			  `l_two` int(7) NOT NULL,
			  `l_three` int(7) NOT NULL,
			  `l_five` int(7) NOT NULL,
			  `l_ten` int(6) NOT NULL,
			  `l_fifteen` int(6) NOT NULL,
			  `l_inserttime` datetime NOT NULL,
			  PRIMARY KEY (`l_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='登录概况2登，5登，10登等缓存表'";
		$gamedb -> query_nothing($sql);
		
		
		$sql = "CREATE TABLE `brower` (
			  `b_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `b_ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户IP',
			  `b_username` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '账号',
			  `b_serverid` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '服数',
			  `b_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '流量器类型',
			  `b_version` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '浏览器版本',
			  `b_os` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '操作系统',
			  `b_date` date NOT NULL COMMENT '生成时间',
			  `b_inserttime` datetime NOT NULL COMMENT '插入时间',
			  PRIMARY KEY (`b_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		$gamedb -> query_nothing($sql);
		
		
		$sql = "CREATE TABLE `user_temp` (
				  `u_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `u_date` date NOT NULL COMMENT '查询时间',
				  `u_oid` int(11) NOT NULL COMMENT 'online自增ID',
				  `u_userid` int(11) NOT NULL COMMENT '用户ID',
				  `u_type` tinyint(1) NOT NULL COMMENT '类型（0：留存用户；1：流失用户）',
				  `u_expire` date NOT NULL COMMENT '过期日期',
				  PRIMARY KEY (`u_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户留存用户和流失用户的缓存表'";
		$gamedb -> query_nothing($sql);
		
		$sql = "CREATE TABLE `pay_detail` (
				  `p_id` int(13) unsigned NOT NULL AUTO_INCREMENT,
				  `p_result` tinyint(1) NOT NULL COMMENT '结果集 0成功1java服务器失败2角色不存在-1缺少参数-2超时-3限制IP-4找不到服务器-5加密错误-7error补单-8页面空白补单',
				  `p_ser` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '服务器',
				  `p_pt` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '平台',
				  `p_acc` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '玩家账户',
				  `p_playid` int(13) unsigned NOT NULL COMMENT '玩家ID',
				  `p_order` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '订单号',
				  `p_money` int(8) NOT NULL COMMENT '充值金额（RMB）',
				  `p_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '渠道',
				  `p_acctime` int(13) NOT NULL COMMENT '订单生成时间',
				  `p_reason` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '原因',
				  `p_creatdate` datetime NOT NULL COMMENT '生成日期',
				  `p_insertdate` date NOT NULL COMMENT '插入日期',
				  PRIMARY KEY (`p_id`),
				  KEY `p_result` (`p_result`) USING BTREE
				) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		$gamedb -> query_nothing($sql);
		
		$sql = "CREATE TABLE `creat_success` (
				  `c_id` int(10) NOT NULL AUTO_INCREMENT,
				  `c_playid` int(10) DEFAULT NULL,
				  `c_time` datetime DEFAULT NULL,
				  PRIMARY KEY (`c_id`)
				) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='创角成功'";
		$gamedb -> query_nothing($sql);
		
		$sql = "CREATE TABLE `creat_success_copy` (
				  `c_id` int(10) NOT NULL AUTO_INCREMENT,
				  `c_playid` int(10) DEFAULT NULL,
				  `c_time` datetime DEFAULT NULL,
				  PRIMARY KEY (`c_id`)
				) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='创角并成功登录'";
		$gamedb -> query_nothing($sql);

		for($i=0;$i<15;$i++){
		$sql ="CREATE TABLE `item{$i}` (
				`i_id`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
				`i_account`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '账号' ,
				`i_playid`  int(13) NOT NULL COMMENT '角色ID' ,
				`i_shopid`  int(5) NOT NULL COMMENT '商品ID' ,
				`i_price`  int(5) NOT NULL COMMENT '单价' ,
				`i_type`  smallint(2) NOT NULL COMMENT '价格类型 1元宝 2 绑定元宝' ,
				`i_num`  tinyint(3) NOT NULL DEFAULT 0 COMMENT '购买数量' ,
				`i_dtype`  smallint(1) NOT NULL DEFAULT 0 COMMENT '活动类型 0-普通购买1抢购 2秒杀' ,
				`i_date`  datetime NOT NULL COMMENT '所属日期' ,
				`i_time`  datetime NOT NULL COMMENT '分析日期' ,
				PRIMARY KEY (`i_id`),
				INDEX `i_playid` (`i_playid`) USING BTREE 
				)
				ENGINE=MyISAM
				DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci
				COMMENT='商城消费记录'
				AUTO_INCREMENT=0";
		$gamedb -> query_nothing($sql);
		
		}
		
	
		return true;
	
	}
	
}
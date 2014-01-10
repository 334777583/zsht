<?php
/**
 * FileName: system.class.php
 * Description:用户管理权限
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-3-21 下午2:45:14
 * Version:1.00
 */
class system{
	/**
	 * 用户数据
	 * @var array
	 */
	private $user;
	
	/**
	 * 当前页
	 * @var int
	 */
	private $curPage = 1;
	
	
	/**
	 * 每页显示记录数
	 * @var int
	 */
	private $pageSize = 50;
	
	/**
	 * 用户id
	 * @var int
	 */
	private $id;
	
	/**
	 * 启用标记
	 * @var int
	 */
	private $flag;
	
	/**
	 * 错误信息
	 * @var string
	 */
	private $msg;
	
	/**
	 * 权限编号(多个以逗号分隔)
	 * @var string
	 */
	private $codeString;
	
	/**
	 * 用户ID(多个以逗号分隔)
	 * @var string
	 */
	private $userString;
	
	/**
	 * 页面切换
	 * @var int
	 */
	private $pageId;
	
	public function init(){
		$userobj = D("sysuser");
		if($this->user = $userobj->isLogin()){
			if(!in_array("00501300", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		} 
		$this->pageSize = get_var_value("pageSize") == NULL?50:get_var_value("pageSize");
		$this->curPage =  get_var_value("curPage") == NULL?1:get_var_value("curPage");
		$this->id = get_var_value("id") == NULL?0:get_var_value("id");
		$this->flag = get_var_value("flag") == NULL?-1:get_var_value("flag");
		$this->codeString = get_var_value("codeString") == NULL?"":get_var_value("codeString");
		$this->userString = get_var_value("userString") == NULL?"":get_var_value("userString");
		$this->pageId = get_var_value("pageId") == NULL ? '1' : get_var_value("pageId");
	}
	
	/**
	 * 显示管理权限页面
	 */
	public function show(){
		if($this->pageId == '1') {
			$this->display("system/user_info");
		} else if($this->pageId == '2') {
			$guser = array();//用户组和用户关系
			$gcode = array();//用户组和权限关系
			$codelist =  array();//模块与子模块关系
			$groups = D("groups");
			$glist = $groups->select();
			$code = D("code_func");
			$modellist = $code->query("select * from code_func where cf_flag = 0 and cf_code LIKE '%00000'","select");
			$sysuser = D("sysuser");
			$gc = D("group_code");
			foreach($glist as $bo){
				$ulist = $sysuser->where(array("u_flag"=>0,"g_id"=>$bo["g_id"]))->select();
				$guser[$bo["g_id"]] = $ulist;
				$clist = $gc->field("cf_code")->where(array("g_id"=>$bo["g_id"]))->select();
				$tempArr = array();
				foreach($clist as $c){
					$tempArr[] = $c["cf_code"]; 
				}
				$gcode[$bo["g_id"]] = $tempArr;
			}
			foreach ($modellist as $model){
				$cid = substr($model["cf_code"], 0, 3);
				$sql = "select * from code_func where cf_flag = 0 and cf_code LIKE '".$cid."%' and cf_code not like '%00000'";
				$list = $code->query($sql,"select");
				$codelist[$model["cf_code"]] = $list;
			}
			$this->assign(array("guser"=>$guser,"gcode"=>$gcode,"codelist"=>$codelist,"modellist"=>$modellist,"tab"=>2,"groups"=>$glist));
			$this->display("system/user_role");
		}else if($this->pageId == '3') {
			$this->display("system/user_model");
		}
		
	}
	
	/**
	 * ajax获取用户数据
	 */
	public function get(){
		$sysuser = D("sysuser");
		$total = $sysuser->total();
		$page = new AjaxPage($this->pageSize,$this->curPage,$total,"getdata");
		$pageHtml = $page->getPageHtml();
		//$userList = $sysuser->limit(intval($page->getOff()),intval($this->pageSize))->select();
		$off = $page->getOff();
		$sql = "select * from sysuser s left join groups g on s.g_id = g.g_id limit " . $off .",".intval($this->pageSize);
		$userList = $sysuser->query($sql,"select");
		$com = array(
				"userList"=>$userList,
				"pageHtml" => $pageHtml
		);
		echo json_encode($com);
		exit;
	}
	
	/**
	 * ajax更新用户数据（停用，启用）
	 */
	public function edit(){
		if($this->id == 0 || $this->flag == -1){ //数据异常，操作失败
			echo json_encode("error");
			exit;
		}
		$sysuser = D("sysuser");
		$state = $sysuser->where(array("u_id"=>$this->id))->update(array("u_flag"=>$this->flag));
		if($state != false){
			echo json_encode("success");
			exit;
		}
		echo json_encode("error");
		exit;
	}
	
	/**
	 * ajax删除用户数据
	 */
	public function delete(){
		if($this->id == 0){
			echo json_encode("error");
			exit;
		}
		$sysuser = D("sysuser");
		$state = $sysuser->where(array("u_id"=>$this->id))->delete();
		if($state != false){
			echo json_encode("success");
			exit;
		}
		echo json_encode("error");
		exit;
	}
	
	/**
	 * 获取用户组列表
	 */
	public function getGroupList(){
		$groups = D("groups");
		$list = $groups->where(array("g_flag"=>0))->select();
		echo json_encode($list);
		exit;
	}
	
	/**
	 * 根据用户id获取信息
	 */
	public function getById(){
		$sysuser = D("sysuser");
		$bo = $sysuser->where(array("u_id"=>$this->id))->find();
		$groups = D("groups");
		$list = $groups->where(array("g_flag"=>0))->select();
		$com = array(
				'userbo' => $bo,
				'list' => $list		
			);
		echo json_encode($com);
		exit;
	}
	
	/**
	 * 更新用户基本信息
	 */
	public function save(){
		if($this->id == 0){
			echo json_encode("error");
			exit;
		}
		$sysuser = D("sysuser");
		try{
			$username = get_var_value("username");
			$password = get_var_value("password");
			$gid = get_var_value("gid");
			$realname = get_var_value("realname");
			$phone = get_var_value("phone");
			$email = get_var_value("email");
			$time = date("Y-m-d H:i:s");
			$sysuser->beginTransaction();
			$state = $sysuser->where(array("u_id"=>$this->id))->update(array(
					"u_name"=>$username,
					"u_password"=>md5($password . "!@#@#"),
					"g_id"=>$gid,
					"u_realname"=>$realname,
					"u_phone" => $phone,
					"u_email" => $email,
					"u_updatetime" => $time
			));
			$sysuser->commit();
			if($state ==  false){
				$sysuser->rollback();
			}else{
				echo json_encode("success");
				exit;
			}
			echo json_encode("error");
			exit;
		}catch (Exception $e){
			$sysuser->rollback();
		}
	}
	
	/**
	 * 添加用户信息
	 */
	public function add(){
		$sysuser = D("sysuser");
		$username = get_var_value("username");
		$password = get_var_value("password");
		$gid = get_var_value("gid");
		$realname = get_var_value("realname");
		$phone = get_var_value("phone");
		$email = get_var_value("email");
		$time = date("Y-m-d H:i:s");
		$state = $sysuser->insert(array(
				"u_name"=>$username,
				"u_password"=>md5($password . "!@#@#"),
				"g_id"=>$gid,
				"u_realname"=>$realname,
				"u_phone" => $phone,
				"u_email" => $email,
				"u_createtime" =>$time,
				"u_updatetime" => $time
				
		));
		if(!$state ==  false){
			echo json_encode("success");
			exit;
		}
		echo json_encode("error");
		exit;
	}
	
	
	/**
	 * 角色设置模块（角色停用，启用）
	 */
	public function editRole(){
		if($this->id == 0 || $this->flag == -1){ //数据异常，操作失败
			echo json_encode("error");
			exit;
		}
		$groups = D("groups");
		$state = $groups->where(array("g_id"=>$this->id))->update(array("g_flag"=>$this->flag));
		if($state != false){
			$sysuser = D("sysuser");
			@$userstate = $sysuser->where(array("g_id"=>$this->id))->update(array("u_flag"=>$this->flag));
			echo json_encode("success");
			exit;
		}
		echo json_encode("error");
		exit;
	}
	
	/**
	 * 角色设置模块（保存角色）
	 */
	public function saveRole(){
		if($this->id == 0 ){ //数据异常，操作失败
			echo json_encode("error");
			exit;
		}
		$gc = D("group_code");
		@$gc->where(array("g_id"=>$this->id))->delete();//先删除对应的权限关系
		$codeList = explode(",",$this->codeString);
		if(!empty($codeList)){
			foreach ($codeList as $code){
				@$gc->insert(array(
						'g_id'=>$this->id,
						'cf_code'=>$code,
						'gc_createtime'=>date("Y-m-d H:i:s")
				));
			}
		}
		$userList = explode(",",$this->userString);
		$sysuser = D("sysuser");
		if(!empty($userList)){
			foreach ($userList as $userId){
				@$sysuser->where(array("u_id"=>$userId))->update(array("g_id"=>0));
			}
		}
		echo json_encode("success");
		exit;
	}
	
	
	/**
	 *获取用户模块数据
	 */
	public function getModel(){
		$point = D("code_func");
		$list = $point -> order("cf_code asc") -> select();
		echo json_encode($list);
		exit;
	}
	
	/**
	 *删除用户模块数据
	 */
	public function deleteModel() {
		if($this->id == 0){
			echo json_encode("error");
			exit;
		}
		$point = D("code_func");
		$state = $point->where(array("cf_id"=>$this->id))->delete();
		
		if($state == false){
			echo json_encode("error");
			exit;
		}
		echo json_encode("success");
		exit;
	}
	
	
	/**
	 * 停用/启用模块（0：启用；1：停用）
	 */
	public function editModel() {
		if($this->id == 0 || $this->flag == -1){ //数据异常，操作失败
			echo json_encode("error");
			exit;
		}
		$point = D("code_func");
		$state = $point->where(array("cf_id"=>$this->id))->update(array("cf_flag"=>$this->flag));
		if($state != false){
			$gc = D('group_code');
			$code =  get_var_value("code");
			if(substr($code, -3) == '000'){
				$gc -> where('cf_code like "'.substr($code, 0,5).'%"') -> update(array('g_flag' => $this->flag));
			}else{
				$gc -> where('cf_code ="'.$code.'"') -> update(array('g_flag' => $this->flag));
			}
			
			echo json_encode("success");
			exit;
		}
		echo json_encode("error");
		exit;
	}
	
	
	/**
	 * 根据ID获取模块信息
	 */
	public function getModelById() {
		$point = D("code_func");
		$bo = $point->where(array("cf_id"=>$this->id))->find();
		echo json_encode($bo);
		exit;
	}
	
	/**
	 * 保存模块信息
	 */
	public function saveModel() {
		if($this->id == 0){
			echo json_encode("error");
			exit;
		}
		$point = D("code_func");
		$mcode = get_var_value("mcode");
		$mname = get_var_value("mname");
		$time = date("Y-m-d H:i:s");
		if($mcode && $mname){
			$state = $point->where(array("cf_id"=>$this->id))->update(array(
					"cf_code" => $mcode,
					"cf_name" => $mname,
					"u_updatetime" => $time
			));
			if($state !=  false){
				echo json_encode("success");
				exit;
			}
		} 
		echo json_encode("error");
		exit;
	} 
	
	/**
	 * 保存模块信息
	 */
	public function addModel() {
		$point = D("code_func");
		$mcode = get_var_value("mcode");
		$mname = get_var_value("mname");
		$time = date("Y-m-d H:i:s");
		if($mcode && $mname){
			$state = $point->insert(array(
					"cf_code" => $mcode,
					"cf_name" => $mname,
					"cf_createtime" =>$time,
					"cf_updatetime" => $time
					
			));
			if ($state !=  false) {
				echo json_encode("success");
				exit;
			} else {
				echo json_encode("添加失败");
				exit;
			}
		} else {
			echo json_encode("不能为空!");
			exit;
		}
		
	}
	
	/**
	 * 添加用户组
	 */
	public function addGroup() {
		$point = D("groups");
		$group = get_var_value("group");
		$time = date("Y-m-d H:i:s");
		if($group){
			$state = $point->insert(array(
					"g_name" => $group,
					"g_flag" => $g_flag,
					"g_createtime" =>$time,
					"g_updatetime" => $time
					
			));
			if ($state !=  false) {
				echo json_encode("success");
				exit;
			} else {
				echo json_encode("添加失败");
				exit;
			}
		} else {
			echo json_encode("不能为空!");
			exit;
		}
		
	}
	
	/**
	 * 删除用户组
	 */
	public function deleteGroup() {
		$group = D('groups');
		$sysuser = D('sysuser');
		$gcode = D('group_code');
		$id = get_var_value('id');
		if($id) {
			@$gp =	$group -> where('g_id = "' .$id.'"') -> delete();
			@$su = $sysuser -> where('g_id ="' .$id.'"') -> delete();
			@$gc = $gcode -> where('g_id="'.$id.'"') -> delete();
			// if($gp && $su && $gc) {
				// echo json_encode('success');
			// }else {
				// echo json_encode('error');
			// }
			echo json_encode('success');
		}else {
			echo json_encode('error');
		}
	}
	
}
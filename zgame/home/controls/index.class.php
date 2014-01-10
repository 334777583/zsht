<?php
/**
 * FileName: index.class.php
 * Description: 用户登录函数
 * Author: xiaochengcheng
 * Date: 2013-3-18 14:31:04
 * Version: 1.00
 **/
class index {
	/**
	 * 用户名
	 * @var string
	 */
	private $username;
	
	/**
	 * 密码
	 * @var string
	 */
	private $password;
	
	/**
	 * 验证码
	 * @var string
	 */
	private $code;
	
	/**
	 * 错误信息
	 * @var string
	 */
	private $msg = array('usermsg' => '','passmsg' => '');
	
	/**
	 * 用户信息
	 * @var array
	 */
	private $user;
	
	
	/**
	 * 显示登录页
	 */
	public function index(){
		$this->assign("msg",$this->msg);
		$this->assign("username",$this->username);
		$this->display("common/login");
	}

	/**
	 * 检查用户登录
	 */
	public function login(){
		$user = D("sysuser");
		$gcode = D("group_code");

		if(isset($_SESSION["user2"]) && $_SESSION["user2"] != ''){			//已经登录且session没过期
			$this->assign("user",unserialize($_SESSION["user2"]));
			$this->display("common/homepage");
		}else if(!$this->verify()){		//不满足验证条件，返回登陆页
				$this->assign("msg",$this->msg);
				$this->assign("username",$this->username);
				$this->display("common/login");
		}else{
			$bo =  $user->where(array("u_flag"=>0,"u_name"=>$this->username,"u_password"=>md5($this->password . "!@#@#")))->find();
			if(isset($bo["u_id"])){
				$codeList = $gcode->field("cf_code")->where(array("g_id"=>$bo["g_id"], 'g_flag' => 0))->select();
				$codeNum = array();
				foreach($codeList as $code){
					$codeNum[] = $code["cf_code"];
				}
				$user = array(
						"username"=>$bo["u_name"],
						"uid"=>$bo["u_id"],
						"code"=>$codeNum
				);
				$_SESSION['user2'] = serialize($user);
				parent::writeFile("[login]    time:[".date('Y-m-d H:i:s')."] user:[".$bo['u_name']."] ip:[".parent::get_ip()."]\r\n");
				$this->assign("user",$user);
				$this->display("common/homepage");
			}else{		//登录信息错误
				$this->msg["usermsg"] = "用户名或者密码错误!";
				$this->assign("msg",$this->msg);
				$this->assign("username",$this->username);
				$this->display("common/login");
			}
		}
	}
	
	/**
	 * 验证表单数据
	 */
	private function verify(){
		$flag = true;
		$this->username = get_var_value("username");
		$this->password = get_var_value("password");
		if(!isset($this->username) || !isset($this->password)){
			$flag = false;
		}else if(isset($this->username) && empty($this->username)){
			$this->msg["usermsg"] = "用户名不能为空";
			$flag = false;
		}else if(isset($this->password) && empty($this->password)){
			$this->msg["passmsg"] = "密码不能为空";
			$flag = false;
		}
		return $flag;
	} 
	
	/**
	 * 退出登录
	 */
	public function  loginout(){
		$user = unserialize($_SESSION['user2']);
		parent::writeFile("[loginout] time:[".date('Y-m-d H:i:s')."] user:[".$user['username']."] ip:[".parent::get_ip()."]\r\n");
		unset($_SESSION["user2"]);
		session_destroy();
		$this->index();
	}

}
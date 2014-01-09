<?php
/**
 * @description: Mysql 数据库操作类
 * @file: mysql.php
 * @author: Kim
 * @charset: UTF-8
 * @time: 2012-12-19 15:04:34
 * @version 1.0
**/

class DB extends kim 
{
	protected $dbhost = '';        	//服务器地址
	protected $dbname = '';        	//数据库名称
	protected $dbusername = '';   	 //连接账号
	protected $dbpassword = '';    		//连接密码
	protected $setnames = 'utf8';      //数据库编码
	protected $sql = '';
	protected $tabName = '';
	protected $where = '';
	protected $order = '';
	protected $limit = '';
	protected $fieldList;
	protected $obj;

	public function __construct($dbname='',$host='',$user='',$pass='')
	{
		$this -> dbname = $dbname; 
		$this -> dbhost = $host;
		$this -> dbusername = $user;
		$this -> dbpassword = $pass;
		if(!empty($this -> obj)){
			return $this -> obj;
		}
		$this -> connect();
	}

	protected function connect()
	{
		$openconn = mysql_connect($this->dbhost,$this->dbusername,$this->dbpassword ) or die("Mysql connect failed");
		$this->query("SET NAMES '".$this->setnames."'",$openconn);
		mysql_select_db($this->dbname,$openconn);
		$this -> obj = $openconn;
	}

	protected function query($sql,$handle){
		common::writeFile('['.date('Y-m-d H:i:s').'] [SQL:'.$sql.']'."\r\n");
		return mysql_query($sql,$handle);
	}

	protected function returnSql()
	{
		$array_result="";
		$db_result=$this->query($this -> sql,$this -> obj);
		if($db_result){
			$array_result=mysql_fetch_array($db_result);	
			mysql_free_result($db_result);
			return $array_result;		
		}else{
			// echo ;
			common::printlog('SQL 语法错误! <br>'.$this->sql .'<br>');
			return false;
		}
	}

	protected function executeSql()     
	{    
		$result=$this->query($this -> sql,$this-> obj );
		
		if(!$result){
			// echo ;
			common::printlog("出错了:" . $this -> sql."");
			return false;
		}else{
			return true;	
		}
	}

	protected function sumcount()     
	{    
		$result=$this->query($this -> sql,$this-> obj );
		
		if(!$result){
			common::printlog("出错了:" . $this -> sql."");
			return false;
		}else{
			 $r = mysql_fetch_assoc($result);
			 return $r['count'];
		}
	}

	protected function insertSql()     
	{    
		$result=$this->query($this -> sql,$this-> obj );
		
		if(!$result){
			common::printlog("出错了:" . $this -> sql."");
			return false;
		}else{
			return mysql_insert_id();	
		}
	}

	protected function arraySql()
	{
		$array_result="";
		$db_result = $this->query($this -> sql,$this -> obj);
		if($db_result){
			$i = 0 ;
			while($row = mysql_fetch_assoc($db_result)){
				$array_result[$i]=$row;
				$i++;
			}
			mysql_free_result($db_result);
			return $array_result;
		}else{
			common::printlog("出错了:" . $this -> sql."");
			return false;
		}
	}

	private function setTable(){
		$result = $this->query("desc {$this->tabName}",$this -> obj);
		$fields=array();
		while($row = mysql_fetch_assoc($result)){
			if($row["Key"]=="PRI"){
				$fields["pri"]=strtolower($row["Field"]);
			}else{
				$fields[]=strtolower($row["Field"]);
			}
		}
		if(!array_key_exists("pri", $fields)){
			$fields["pri"]=array_shift($fields);		
		}
		$this->fieldList=$fields;
	}


	private function updte_result()    
	{
		$db_result = $this->query($this -> sql,$this -> obj);
		if(!$db_result){
			common::printlog("出错了:" . $this -> sql."");
			return false;
		}
		return mysql_affected_rows();
	}

	public function __call($name, $arguments){
		common::printlog('your call function '.$name.' and arguments '.$arguments.' is not exist ');
	}

	public function __toString(){
		return 'this is Mysql class';
	}

	public function __destruct()     
	{
		if($this -> obj != null)
		{
			mysql_close($this -> obj);
			unset($this);
		}
	} 
	
	public function return_sql(){
		return $this->sql;
	}
	
   /**
	* FunctionName: table
	* Description: 设置表名
	* Author: Kim
	* Return: Object
	* @param string 
	* Date: 2012年12月19日14:59:10
	**/
	public function table($name){
		$this -> tabName = $name;
		$this -> setTable();
		return $this;
	}
	
	/**
	* FunctionName: where
	* Description: 设置where
	* Author: Kim
	* Return: Object
	* @param string 
	* Date: 2012年12月19日14:59:04
	**/
	public function where($where){
		$this -> where = ' where '.$where;
		return $this;
	}

	/**
	* FunctionName: where
	* Description: 设置where
	* Author: Kim
	* Return: Object
	* @param string 
	* Date: 2012年12月19日14:59:04
	**/
	public function order($order){
		$this -> order = ' ORDER BY '.$order;
		return $this;
	}

	/**
	* FunctionName: where
	* Description: 设置where
	* Author: Kim
	* Return: Object
	* @param String 
	* Date: 2012年12月19日14:59:04
	**/
	public function limit($limit){
		$this -> limit = ' LIMIT '.$limit;
		return $this;
	}

	/**
	* FunctionName: query
	* Description: 发送一条数据库语句
	* Author: Kim
	* Return: Array 多维数组
	* @param String
	* Date: 2012年12月19日14:59:04
	**/
	public function fquery($sql)     
	{
		$this -> sql = $sql;
		return $this->arraySql();
	}

	/**
	* FunctionName: insert
	* Description: 插入数据库
	* Author: Kim
	* Return: Boolean
	* @param:  Array
	* Date: 2012年12月19日14:59:04
	**/
	public function insert($array=null){
		if($array == null){
			return false;
		}
		$str = '';
		foreach($array as $val){
			$str .= ($str == '') ? '"'.$val.'"' : ',"'.$val.'"' ; 
		}
		$this -> sql = "INSERT INTO {$this->tabName} (".implode(',', array_keys($array)).") VALUES (".$str. ")";

		return $this->insertSql();
	}

	/**
	* FunctionName: insert_s
	* Description: 插入多条数据
	* Author: Kim
	* Return: Boolean
	* @param:  Array
	* Date: 2012年12月19日14:59:04
	**/
	function insert_s($arr_key=NULL,$arr_val=NULL){
		if($arr_key == null || $arr_val == NULL){
			return false;
		}
		$str = '';
		foreach($arr_key as $val){
			$str .= ($str == '') ? ''.$val.'' : ','.$val.'' ; 
		}
		
		$string = '';
		$i = 0;
		if(count($arr_val) % count($arr_key) != 0){
			return false;
		}
		$len = count($arr_val)-1;
		if(count($arr_key) <= 1){
			foreach($arr_val as $val){
				if($string == ''){
						$string .= '("'.$val.'"';
				}else if($len == $i){
					$string .= '),("'.$val.'")';
				}else{
					$string .= '),("'.$val.'"';
				}
				$i++;
			}
		}else{
			foreach($arr_val as $val){
				if( ($i % count($arr_key)) == 0){
					if($string == ''){
						$string .= '("'.$val.'"';
					}else{
						$string .= '),("'.$val.'"';
					}
				}else{
					if($len == $i){
						$string .= ',"'.$val.'")';
					}else{
						$string .= ',"'.$val.'"' ; 
					}				
				}
				$i++;
			}
		}
		$this -> sql = "INSERT INTO {$this->tabName} (".$str.") VALUES ".$string;
		return $this->insertSql();
	}

	/**
	* FunctionName: delete
	* Description: 删除动作
	* Author: Kim
	* Return: Boolean
	* @param:  Null
	* Date: 2012年12月19日14:59:04
	**/
	public function delete(){
		$this -> sql = "DELETE FROM {$this->tabName}{$this->where}{$this->order}{$this->limit}";
		return $this-> executeSql();
	}
	
	/**
	* FunctionName: delete
	* Description: 查询动作
	* Author: Kim
	* Return: Array多维数组
	* @param:  Null
	* Date: 2012年12月19日14:59:04
	**/
	public function select(){
		$this -> sql = "SELECT ".implode(',', $this -> fieldList)." FROM {$this->tabName}{$this->where}{$this->order}{$this->limit}";

		return $this->arraySql();
	}
	
	/**
	* FunctionName: find
	* Description: 查询动作
	* Author: Kim
	* Return: Array一维数组
	* @param:  Null
	* Date: 2012年12月19日14:59:04
	**/
	public function find(){
		$this -> sql = "SELECT ".implode(',', $this -> fieldList)." FROM {$this->tabName}{$this->where}{$this->order}{$this->limit}";

		return $this->returnSql();
	}

	/**
	* FunctionName: r_count
	* Description: 查询动作
	* Author: Kim
	* Return: Array一维数组
	* @param:  Null
	* Date: 2012年12月19日14:59:04
	**/
	public function r_count(){
		$this -> sql = "SELECT COUNT(".$this -> fieldList[0].") AS count FROM {$this->tabName}{$this->where}";
		return $this->sumcount();
	}
	
	/**
	* FunctionName: update
	* Description: 更新动作
	* Author: Kim
	* Return: Interge 返回影响条数
	* @param:  Array
	* Date: 2012年12月19日14:59:04
	**/
	public function update($array=null){
		if($array == null){
			return false;
		}
		$val = '';
		if(is_array($array)){
			foreach($array as $key=>$val2){
				$val .= ($val == '') ? $key.'="'.$val2.'"': ', '.$key.'="'.$val2.'"';
			}
		}else{
			$val = $array;
		}
		$this -> sql = "UPDATE {$this->tabName} SET {$val}{$this->where}";

		return $this -> updte_result();
	}
}
?>
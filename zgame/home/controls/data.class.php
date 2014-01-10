<?php
class data{
	/**
	 * 初始化数据
	 */
	public function init(){
		$userobj = D("sysuser");
		if($this->user = $userobj->isLogin()){
			if(!in_array("00501400", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
	
	
	public function show() {
		$db_list = array();
		$point = D('gamedb');
		
		$list = $point -> where('g_flag = 1') -> select();
		if($list != '') {
			$db_list['all'] = '全部';
			foreach($list as $db) {
				$db_list[$db['g_id']] = $db['g_name'];
			}
		}
		
		$backup = D('backup');
		$blist = $backup -> where('b_isdel = 0') -> order('b_time desc') -> select();
		foreach ($blist as $k => $b) {
			$blist[$k]['b_size'] = tosize($b['b_size']);
		}
		$this->assign('db', $db_list);
		$this->assign('backup', $blist);
		$this->display('system/data_bak');
	}
	
	//备份数据库
	public function backup() {
		$select_db = get_var_value("s_db");			//数据库选择
		$lx_type = get_var_value("r_group");		//导出类型
		$fs_type = get_var_value("s_group");		//导出方式
		
	
		$info = array();
		$biaoshi = 0;
		if($select_db) {
			if($select_db == 'all') {
				$point = D('gamedb');
				$list = $point -> where('g_flag = 1') -> select();
				if($list != '') {
					$temp = new mysqlexport(HOST, USER, PASS, DBNAME, $lx_type);
					$te = $temp -> getfile();
					if(!is_array($te)){
						$biaoshi = $te;
					}else{
						$info[] = $temp -> getfile();
						foreach($list as $item) {
							$mysql = new mysqlexport(HOST, USER, PASS, GNAME.$item['g_id'], $lx_type);
							$s = $mysql -> getfile();
							if(!is_array($s)){
								$biaoshi = $s;	
								break;
							}
							$info[] = $mysql -> getfile();
							
						}
					}
				}
			}else {
				$mysql = new mysqlexport(HOST, USER, PASS, GNAME.$select_db, $lx_type);
				$s = $mysql -> getfile();
				if(!is_array($s)){
					$biaoshi = $s;	
					break;
				}
				$info[] = $mysql -> getfile();
			}
		}
		
		if(!$biaoshi){
			$db = D('backup');
			foreach($info as $val){
				$db -> insert(array('b_ip'=>'127.0.0.1','b_name'=>$val[0],'b_type'=>$lx_type,'b_size'=>$val[1],'b_time'=>date('Y-m-d H:i:s')));
			}
			if($fs_type == "1") {
				$this->somefile($info);
			}else {
				echo '<script>alert("备份成功！");</script>';
				$this->show();
			}
		}else{
			echo '<script>alert("备份失败！");</script>';
			$this->show();
		}
	}
	
	
	//备份指定的远程数据库
	public function backupByconf() {
		$lx_type = get_var_value("r_group");		//导出类型
		$fs_type = get_var_value("s_group");		//导出方式
		
		$address = get_var_value("address");		//IP地址
		$port = get_var_value("port");				//端口
		$username = get_var_value("username");		//用户名
		$password = get_var_value("password");		//密码
		$database = get_var_value("database");		//数据库
		$info = array();
		if($address && $port && $username && $database) {
			$temp = new mysqlexport($address, $username, $password, $database, $lx_type);
			$te = $temp -> getfile();
			if(is_array($te)){
				$info[] = $temp -> getfile();
				$db = D('backup');
				$db -> insert(array('b_ip'=>$address,'b_name'=>$te[0],'b_type'=>$lx_type,'b_size'=>$te[1],'b_time'=>date('Y-m-d H:i:s')));
				if($fs_type == "1") {
					$this->somefile($info);
				}else {
					echo '<script>alert("备份成功！");</script>';
					$this->show();
				}
			}else {
				 echo '<script>alert("备份失败！");</script>';
				 $this->show();
			}
		}else {
			 echo '<script>alert("请补全数据库信息！");</script>';
			$this->show();
		}
	
	}
	
	/**
	 * 删除文件 
	 */
	public function delete() {
		$filename = get_var_value('filename');
		$id = get_var_value('id');
		if($filename && $id) {
			$file_path =  $file_path =  dirname(dirname(dirname(dirname(__FILE__)))) .'/public/backup/'.$filename;
			if(@unlink($file_path)) {
				$db = D('backup');
				@$db -> where('b_id = '. $id) -> update(array('b_isdel' => 1));
				echo json_encode('success');
			} else  {
				echo json_encode('error');
			}
		} else {
			echo '1';
		}	
	}

	public function download(){
		$file_name = get_var_value("f");		//文件名称
		$file_path =  dirname(dirname(dirname(dirname(__FILE__)))) .'/public/backup/';
		//echo $file_path . $file_name;exit;
		$path = $file_path . $file_name;
		$file_size = filesize ($path);
		if (file_exists($path)){
			header( "Pragma: public" );
			header( "Expires: 0" );
			header( 'Content-Encoding: none' );
			header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
			header( "Cache-Control: public" );
			header( "Content-type: application/octet-stream\n" );
			header( "Content-Description: File Transfer" );
			header( 'Content-Disposition: attachment; filename=' . $file_name );
			header( "Content-Transfer-Encoding: binary" );
			header( 'Content-Length: ' .$file_size);
			header( 'X-Sendfile : '. $path);
			// echo filesize ( $file_path . $file_name );
			// var_dump(readfile ( $file_path . $file_name ));
			flush();
			if ($fd = fopen($path, 'rb')) {
				session_write_close();
				while (!feof($fd)) {
					print (fread($fd, 65535));
					flush();
					ob_flush();  
				}
				fclose($fd);
			}

		}else{
			$this->show();
		}
	}

	public function somefile($file_name){
		// $url = 'http://'.$_SERVER['SERVER_ADDR'].'/brophp/index.php/data/download/f/';
		if(is_array($file_name)){
			foreach ($file_name as $value) {
				echo '<script>window.open("'.DOWNLOAD.$value[0].'");</script>';
			}
			$this->show();
		}else{
			echo 'false';
		}

	}
	
}
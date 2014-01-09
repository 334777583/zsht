<?php
/**
 * FileName: userlevel.class.php
 * Description:新进用户等级分布
 * Author: hjt
 * Date : 2013-8-28 14:00:34
 * Version:1.00
 */
class userlevel{
	/**
	 * 用户数据
	 * @var Array
	 */
	public $user;

	
	 /**
	*初始化数据
	*/
	public function __construct(){
		if(!$this->user = autoCheckLogin::isLogin()){
			echo 'not available!';
			exit();
		}else{
			if(!in_array('00401300', $this->user['code'])){
				echo 'not available!';
				exit();
			}
		}
	}
	
	  
	 /**
	* FunctionName: getChartData
	* Description: 查询当天的等级分布
	* Author: jan,tao
	* Date:2013-9-4 14:25:16
	*/
	public function getLevel() {
		$ip = get_var_value('ip');
		$endday = get_var_value('endday');
		if($ip) {
			$result = array();			//统计结果
			$data = array();			//返回给页面的结果（经过组装后）
			$Levdata = array(); 		//返回统计等级页面的结果
			
			if(true) {	
				global $t_conf;
				
				list($sip) = autoConfig::getConfig($ip);
				$point = F($t_conf[$sip]['db'], $t_conf[$sip]['ip'], $t_conf[$sip]['user'], $t_conf[$sip]['password'], $t_conf[$sip]['port']);//链接对应数据表
				if(!$point){
					echo json_encode(array(
						'error' => '数据库连接失败！'
					));
					exit;
				}else{
					
					//统计等级sql整理
					$Count_arr = array(
						'OeTn' => array(//查询1~10级的数据
							1 => 10
						),
						'OeTo' => array(//查询10~20级的数据
							10 => 20
						),
						'ToTr' => array(//查询20~30级的数据
							20 => 30
						),
						'TrFo' => array(//查询30~40级的数据
							30 => 40
						),
						'FoFv' => array(//查询40~50级的数据
							40 => 50
						),
						'FvSx' => array(//查询50~60级的数据
							50 => 60
						),
						'SxSv' => array(//查询60~70级的数据
							60 => 70
						),
						'SvEg' => array(//查询70~80级的数据
							70 => 80
						),
						'EgMax' => array(//查询80以上的数据
							80 => 'Max'
						)
					);
					$Sql_Str = '';//初始化
					foreach($Count_arr as $key => $value){//组建sql语句
						foreach($value as $k => $v){
							if($v == 'Max'){//当查询80以上时候 只查level > 80
								$Sql_Str .= ' count(if(o_level > '.$k.',o_level,not null)) as '.$key;
							}else{//其他的查询区间
								$Sql_Str .= ' count(if(o_level >= '.$k.' and o_level <= '.$v.',o_level,not null)) as '.$key.',';
							}
						}
					}
					$obj = D(GNAME.$ip);
					$CountSql = 'select '.$Sql_Str.' from online_sec where o_date <= "'.$endday.'"';
					$result = $obj -> fquery($CountSql);//统计等级段数据
					$i = 0;
					foreach($Count_arr as $key => $value){   //重组数组
						foreach($value as $k => $v){
							$pie_lev[$i]['lev_tit'] =$k.'-'.$v;		//等级分段
							$pie_lev[$i]['lev_val'] = $result[0][$key];      
							$i++;
						}
					}
					
					$CountL_Sql = 'select level , count(level) count from t_player group by level';
					$level = $point -> fquery($CountL_Sql);//统计等级数据
				}
				if(!empty($result)){
					$data = $result;
					foreach($result as $key => $value){
						foreach($value as $k => $v){
							$data[$k] =$v;
						}
						$result[$key]['Time'] = date('Y-m-d');//日期
					}
					$data['Time'] = date('Y-m-d');//日期
				}
				
				echo json_encode(array('result' => $data,'col_lev' => $level,'pie_lev'=>$pie_lev));
				exit;
			} else {
				echo '1';
			}
		}
	}
	
}
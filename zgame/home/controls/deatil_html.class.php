<?php
/**
 * FileName: deatil_html.class.php
 * Description:生成静态页面
 * Author: kim
 * Date:2013-5-15 15:52:36
 * Version:1.00
 */
class deatil_html{
	/**
	 * 登录用户信息
	 */
	private $user;
	
	
	/**
	 * 初始化数据
	 */
	public function init(){
		$userobj = D("sysuser");
		if($this->user = $userobj->isLogin()){
			if(!in_array("00400800", $this->user["code"])){
				$this->display("public/noauth");
				exit();
			}
		}
	}
	

	
	public function deatil_get(){
		$ip = get_var_value('ip');
		$type = get_var_value('type');
		$name = $ip.'dialog'.$type;
		$point = D('html');
		
		$f = $point -> where('h_name ="'.$name.'"') -> find();
		
		if(!$f) {
			echo json_encode('error');
			exit;
		}

		$f['h_content'] = htmlspecialchars_decode(htmlspecialchars_decode($f['h_content']));
		
		$url = 'http://'.$_SERVER['SERVER_ADDR'].'/brophp/public/html/'.$name.'.html';
		
		$content = file_get_contents($url);
		
		echo json_encode(array(
			'info' => $f,
			'content' => $content	
		));
		exit;
	}


	public function deatil_post(){
		$c = $_POST['content'];
		$ip = get_var_value('ip');
		$type = get_var_value('type');
		
		$name = $ip.'dialog'.$type;
		
		$result = 'success';

		if($c){
			
			$c = htmlspecialchars($c);
			
			$point = D('html');
			
			$flag = $point -> where('h_name ="'.$name.'"') -> find();
		
			if($flag){
				$u = $point -> where('h_name ="'.$name.'"') -> update(array(
					'h_width' => $w,
					'h_height' => $h,
					'h_body' => $b,
					'h_content' => $c,
					'h_server' => $ip,
					'h_creatime' => date('Y-m-d H:i:s')
				));
				
				if(!$u){
					$result = 'fail';
				}
			} else {
				$i = $point -> insert(array(
					'h_name' => $name,
					'h_width' => $w,
					'h_height' => $h,
					'h_body' => $b,
					'h_content' => $c,
					'h_server' => $ip,
					'h_creatime' => date('Y-m-d H:i:s')
				));
				
				if(!$i){
					$result = 'fail';
				}
			}
			
			
			$content = $this->cH($name, $c);
			$this->asPage($name, $c);
		}
		
		echo json_encode(array(
				'status' => $result,
				'content'=> $content	 
			));
		exit;	
	}



	/**
	 *	生成HTML，用于查看效果
	**/ 
	private function cH($name, $content){
		$r = 0;
		$dir = dirname(dirname(dirname(dirname(__FILE__))));
		if($name){
			
			$c = $this->filter_html($content, 'html');
			
			$name = $dir.'/public/html/'.$name.'.html';
			// $str = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\r\n";
			// $str .= '<html xmlns="http://www.w3.org/1999/xhtml">'."\r\n";
			// $str .= '<head>'."\r\n";
			// $str .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'."\r\n";
			// $str .= '<meta http-equiv="refresh" content="0" />'."\r\n";
			// $str .= '<title>公告</title>'."\r\n";

			// $str .= '<style type="text/css">'."\r\n";
			// $str .= '#body {height:450px; width:500px;'."\r\n";
			// $str .= '}'."\r\n";
			// $str .= '* {color:#fff; word-wrap: break-word; SCROLLBAR-ARROW-COLOR: #fff200; BACKGROUND-REPEAT: repeat-x; SCROLLBAR-BASE-COLOR: #b3b3b3;}'."\r\n";
			// $str .= '</style>'."\r\n";

			// $str .= '</head>'."\r\n";
			// $str .= '<body id="body" style="margin:0px;padding:0px;">'."\r\n";
			
			// $str .= $c."\r\n";
			// $str .= '</body>'."\r\n";
			// $str .= '</html>'."\r\n";
			$boolean = file_put_contents($name, $c);
			if($boolean) $r=1;	
		}
		return $c;
	}
	
	/**
	 *	生成HTML，用于传递给AS展示（ps：因为只能传递仅有的几个标签）
	**/ 
	private function asPage($name, $content){
		$r = 0;
		$dir = dirname(dirname(dirname(dirname(__FILE__))));
		if($name){
			$c = $this->filter_html($content, 'as');
			
			$name = $dir.'/public/html/'.$name.'_as.html';
			
			$boolean = file_put_contents($name,$c);
			if($boolean) $r=1;	
		}
		return $r;
	}
	
	/**
	 *	过滤处理html
	**/ 
	private function filter_html($content, $type= 'html'){
		$c = htmlspecialchars_decode($content);			
		$c = strip_tags($c,'<a><br><b><i><u><font><strong><em><span>');		//不被去除的字符列表
		
		$pattern = array("/[\r\n\t]*/",	"/<br \/>/", "/<strong>/", "/<\/strong>/", "/<em>/", "/<\/em>/", "/<span/", "/<\/span>/");							
		$replacement = array(null, '<br/>', '<b>', '</b>', '<i>', '</i>', '<font', '</font>');
		$c = preg_replace($pattern, $replacement, $c);
		
		preg_match_all('/<font ([^>]*)>/', $c, $matches);
		
		$font_arr = array();
		
		if(isset($matches[1])) {
			foreach($matches[1] as $match) {
				$font_attr = "";
				if(!empty($match)){
					preg_match('/style=\"(.*)\"/', $match, $styles);
					if(isset($styles[1])){
						$arr = explode(';', $styles[1]); 
						foreach($arr as $item){
							if(!empty($item)) {
								list($attr_name, $attr_value) = explode(':', $item);
								if($attr_name == 'font-size'){
									$attr_name = 'size';
									$size = intval(rtrim($attr_value, 'px'));
									if($type == 'as') {
										switch($size) {
											case 9 : $deal_size = 12;break;
											case 10 : $deal_size = 14;break;
											case 12 : $deal_size = 15;break;
											case 14 : $deal_size = 16;break;
											case 16 : $deal_size = 21;break;
											case 18 : $deal_size = 22;break;
											case 24 : $deal_size = 24;break;
											case 32 : $deal_size = 31;break;
											default : $deal_size = 12;			//默认21号字体
										}
									} else {
										switch($size) {
											case 9 : $deal_size = 2;break;
											case 10 : $deal_size = 2;break;
											case 12 : $deal_size = 3;break;
											case 14 : $deal_size = 3;break;
											case 16 : $deal_size = 4;break;
											case 18 : $deal_size = 5;break;
											case 24 : $deal_size = 6;break;
											case 32 : $deal_size = 6;break;
											default : $deal_size = 12;			//默认21号字体
										}
									}
									$attr_value = "'".$deal_size."' ";
								} else if($attr_name == 'font-family') {
									$attr_name = 'face';
									$attr_value = "'".rtrim($attr_value)."' ";
								} else if($attr_name == 'color') {
									$attr_name = 'color';
									$attr_value = "'".rtrim($attr_value)."'style='color:{$attr_value}!important;'";
								}else {
									$attr_name = '';
									$attr_value ='';
								}
								if($attr_name != '' && $attr_value != '') {
									$font_attr .= $attr_name .'=' . $attr_value;
								}
							}
						}	
						
					}
				}
				$font_arr[]  = '<font '. $font_attr .'>';
			}
		}
		
		$font_pattern = '/<font style=[^>]*>/';
		
		if(!empty($font_arr)) {
			foreach($font_arr as $font) {
				@$c = preg_replace($font_pattern, $font, $c, 1);
			}
		}
		
		return $c;
		
	}
}
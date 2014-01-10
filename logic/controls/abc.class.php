<?php
/**
 * FileName: system.class.php
 * Description:用户管理权限
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-3-21 下午2:45:14
 * Version:1.00
 */
class abc{
	
	public function index(){
		//$format = "v3/I2";  
		$sysuser = F('game','192.168.0.64','root','san!23@@#');
		$state = $sysuser -> table('player_table') -> where('GUID=640000728') -> find();
		$data = $state['packagedata'];//$state['packagedata'];
		// echo strlen($data);
		// $length = 1 + 4 + 2 + 8;
		
		print_r($this->packdate($data));
		exit();
		// $array = unpack("v2", substr( $data, 0, 4 ) ); 
		// print_r($array);
		// $ItemSum = $array[2]; //总物品数
		// $offset = 4;
		// echo "总物品数:$ItemSum<br/><br/>";
		// for( $i = 0; $i <  $ItemSum; $i++)
		// {
			// $array = unpack("v", substr( $data, $offset, strlen($data) -  $offset) );  
			// $offset += 2;
			// $CellId = $array[1];
			// print_r($CellId);
			// echo "物品位置:$CellId<br/>";
			
			// $array = unpack("I3", substr( $data, $offset, strlen($data) -  $offset)  );  
			// $offset += 12;
			// print_r($array);
			// $ItemId = $array[2];
			// $ItemCount = $array[3];
			// echo "ItemId:$ItemId,ItemCount:$ItemCount<br/>";
			
			// $array = unpack("c", substr( $data, $offset, strlen($data) -  $offset)  ); 
			// $offset += 1;
			// echo "是否绑定:$array[1]<br/>";
			
			// $array = unpack("c", substr( $data, $offset, strlen($data) -  $offset)  );  
			// print_r($array );
			// $offset += 1;
			// $offset += 8 * $array[1];
			
			// $array = unpack("c", substr( $data, $offset, strlen($data) -  $offset)  );  
			// $offset += 1;
			// $offset += 8 * $array[1];
			
			// echo "<br/><br/>";

		// }

	}
	
	
	public function packdate($data){
		if(strlen($data) <= 0){
			return false;
		}
		$A = array();
		$T = unpack("v2", substr( $data, 0, 4 ) ); 	
		$Sum = $T[2]; //总物品数
		$offset = 4;
		for( $i = 0; $i < $Sum; $i++ ){
			$array = unpack("v", substr( $data, $offset, strlen($data) -  $offset) );  
			$offset += 2;
			$A[$i]['CellId'] = $array[1];
			
			$array = unpack("I3", substr( $data, $offset, strlen($data) -  $offset)  );  
			$offset += 12;
			$A[$i]['ItemId'] = $array[2];
			$A[$i]['ItemCount'] = $array[3];
			
			$array = unpack("c", substr( $data, $offset, strlen($data) -  $offset)  ); 
			$offset += 1;
			$A[$i]['bind'] = $array[1];
			
			$array = unpack("c", substr( $data, $offset, strlen($data) -  $offset)  );  
			$offset += 1;
			$offset += 8 * $array[1];
			
			$array = unpack("c", substr( $data, $offset, strlen($data) -  $offset)  );  
			$offset += 1;
			$offset += 8 * $array[1];
		}
		return $A;
	}
}
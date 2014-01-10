<?php
/* 
 * Author:kim
 * Decription:导出数据库表结构 
 * Date : 2013-6-3 16:08:24 
 */


class mysqlexport{
    
    private $db = NULL;
    private $table = array();
    private $dbname = '' ;
    private $str = '';
    private $sql_file = '';
    private $sqlmax = 2;
    private $filename;
    private $filepath;
    private $boolean;
    private $is_success = 0;

    function __construct($ip,$user,$pass,$db,$boolean=1,$port='3306',$row=0,$char='utf8'){
        ini_set('memory_limit','-1');
        ini_set('max_execution_time', '-1');
        $this -> dbname = $db;
        $this -> boolean = $boolean;    //0导出结构，1导出结构and数据
        if($row){
            $this->sqlmax = (int)$row;
        }
        if($port != '3306'){
            $ip = $ip.':'.$port;
        }
        $this -> db = mysql_connect($ip,$user,$pass) or die("连接数据库失败！");
        mysql_query("SET NAMES '".$char."'", $this -> db );

        $a = mysql_select_db( $db , $this -> db );
        if(!$a){
            $this -> is_success = 1;
            return ;
        }
        $this -> go();
    }

    private function go(){

        $array_result = array();
        $result = mysql_query("show tables", $this -> db );
        if(!empty($result)){
            while($row = mysql_fetch_assoc($result)){
                $array_result[]=$row;
            }
            mysql_free_result($result);
            $this -> table = $array_result;
            $this -> desc();
        }else{
            $this -> is_success = 2;
        }

    }

    private function desc(){
      
        foreach ($this->table as $key => $value) {

            $this -> str .= 'DROP TABLE IF EXISTS `'.$value['Tables_in_'. $this -> dbname].'`;'."\r\n";
                       
            $result = mysql_query("show create table ".$value['Tables_in_'. $this -> dbname], $this -> db );
            while($row = mysql_fetch_assoc($result)){
                  $this -> str .= $row['Create Table'].';';
            }

            if($this -> boolean){
                 $this -> str .= "\r\n";
                $this -> str .= $this -> return_sql($value['Tables_in_'. $this -> dbname]);
            }
           

            $this -> str .= "\r\n\r\n\r\n";

            mysql_free_result($result);

        }

        if($this -> str){
            $this -> creatfile();
        }
    }

    private function return_sql($table){
        $arr = array();
        $result = mysql_query("select * from ".$table, $this -> db);
        if(!$result){
            $this -> is_success = 3;
            return ;
        }
        $end = '';
        $str = '';
        $c = 0;
        while($row = mysql_fetch_assoc($result)){
            $temp = '(';
            $sum = count($row)-1;
            $b = 0;

            foreach ($row as $key => $value) {
                if($b ==  $sum)
                    $temp .= "'".addslashes($value)."'";
                else
                     $temp .= "'".addslashes($value)."',";
                $b ++;
            }

            $temp .= ')';
            
            if($c > $this->sqlmax){
                 $str = rtrim($str, ",");
                $end .= 'INSERT INTO `'.$table.'` VALUES '.$str.";\r\n";
                $str = '';
                $c = 0;
            }

            $str .=  $temp.',';
            $c ++ ;
        }
        $c = 0;
        if($str){
            $str = rtrim($str, ",");
            $end .= 'INSERT INTO `'.$table.'` VALUES '.$str.";\r\n";
            $str = '';
        }

        return  $end;
    }

    private function creatfile(){
        $t = date('Y-m-d H:i:s');
        $a = <<<EOF
/*
PHP auto create sql file

Source Database       : $this->dbname
Author                : kim         

Date: $t


Error Decription :  "Got a packet bigger than 'max_allowed_packet' bytes" 
                    "MySQL server has gone away"
                    config my.ini at last add max_allowed_packet=200M or more
*/



EOF;

    $this -> writeFile($a.$this->str);

    }


    private function writeFile($str,$mode='w'){
        if(!$this -> sql_file){
            $this -> sql_file = dirname(__FILE__).'/../public/backup';
        }
        $filename = $this->dbname.'_'.date('YmdHis').'.sql';
        $this->filename = $filename;
        $oldmask = @umask(0);
        $this -> filepath = $this -> sql_file.'/'.$filename;
        $file = $this -> sql_file.'/'.$filename;
        $fp = fopen($file,$mode);
        flock($fp, 3);
        if(!$fp){
            Return false;
        }else{
            fwrite($fp,$str);
            fclose($fp);
            @umask($oldmask);
            Return true;
        }
    }

    public function getfile(){
        if($this -> is_success){
            return $this -> is_success;
        }else{
            return array($this -> filename,filesize($this -> filepath));
        }
    }


}

// $a = new mysqlexport('localhost','root','','test');

// print_r($a->getfile());
?>
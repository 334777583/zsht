<?php
/**
 * FileName: index.php
 * Description: 项目入口文件
 * Author: kim
 * Date: 2013-4-7 10:34:08
 * Version: 1.00
 **/
// print_r($_SERVER);

define('ServicePath',dirname($_SERVER["SCRIPT_FILENAME"]).'/');

require(ServicePath.'core/logic.class.php');


?>
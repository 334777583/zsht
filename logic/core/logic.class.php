<?php
/**
 * FileName: logic.class.php
 * Description: 项目逻辑文件
 * Author: kim
 * Date: 2013-4-7 10:34:03
 * Version: 1.00
 **/
define('Core', ServicePath.'core/');
define('LogPath',ServicePath.'log/');
define('AClass',ServicePath.'class/');
define('AFuntion',ServicePath.'function/');
define('Control',ServicePath.'controls/');
define('Dome',$_SERVER['SERVER_NAME']);
define('Some',$_SERVER['SCRIPT_NAME']);
session_start();
require(ServicePath.'config.inc.php');
require(Core.'common.class.php');
require(Core.'kim.class.php');
require(Core.'db'.DRIVER.'.class.php');
require(Core.'function.inc.php');

$funfile = ServicePath."function/functions.inc.php";
if(file_exists($funfile))
	include $funfile;
common::parseUrl();
common::referer();
common::init();

?>
<?php 
/**
 * [WeEngine System] Copyright (c) 2013 WE7.CC
 * $sn: htdocs/extension.php : v a428285fe6f8 : 2014/03/22 07:02:30 : veryinf $
 */
define('IN_SYS', true);
require 'source/bootstrap.inc.php';
checklogin();

if(empty($_W['isfounder'])) {
	$actions = array('service');
} else {
	$actions = array('module', 'theme', 'service');
}
$action = in_array($action, $actions) ? $action : 'module';

$controller = 'extension';
require router($controller, $action);


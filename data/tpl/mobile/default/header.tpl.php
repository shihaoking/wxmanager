<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<head>
	<title><?php  if($title) { ?><?php  echo $title;?><?php  } else { ?><?php  if(!empty($_W['account']['name'])) { ?><?php  echo $_W['account']['name'];?><?php  } ?><?php  } ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<!-- Mobile Devices Support @begin -->
	<meta content="application/xhtml+xml;charset=UTF-8" http-equiv="Content-Type">
	<meta content="no-cache,must-revalidate" http-equiv="Cache-Control">
	<meta content="no-cache" http-equiv="pragma">
	<meta content="0" http-equiv="expires">
	<meta content="telephone=no, address=no" name="format-detection">
	<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes" /> <!-- apple devices fullscreen -->
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
	<!-- Mobile Devices Support @end -->
	<meta name="keywords" content="微擎,微信,微信公众平台" />
	<meta name="description" content="微信公众平台自助引擎，简称微擎，微擎是一款免费开源的微信公众平台管理系统。" />
	<script type="text/javascript" src="./resource/script/jquery-1.7.2.min.js"></script>
	<?php  if($bootstrap_type == 3) { ?>
	<link type="text/css" rel="stylesheet" href="./themes/mobile/default/style/bootstrap.css">
	<script type="text/javascript" src="./themes/mobile/default/script/bootstrap.min.js"></script>
	<?php  } else { ?>
	<link type="text/css" rel="stylesheet" href="./resource/style/bootstrap.css">
	<script type="text/javascript" src="./resource/script/bootstrap.js"></script>
	<?php  } ?>
	<link type="text/css" rel="stylesheet" href="./resource/style/font-awesome.min.css" />
	<link type="text/css" rel="stylesheet" href="./themes/mobile/default/style/common.mobile.css">
	<script type="text/javascript" src="./resource/script/cascade.js"></script>
	<script type="text/javascript" src="./themes/mobile/default/script/jquery.touchwipe.js"></script>
	<script type="text/javascript" src="./themes/mobile/default/script/swipe.js"></script>
	<!--[if IE 7]>
	<link rel="stylesheet" href="./resource/style/font-awesome-ie7.min.css">
	<![endif]-->
</head>
<body>
<?php  if(empty($main_off)) { ?><div class="main"><?php  } ?>
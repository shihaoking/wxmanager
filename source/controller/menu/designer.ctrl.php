<?php
/**
 * [WeEngine System] Copyright (c) 2013 WE7.CC
 */
defined('IN_IA') or exit('Access Denied');
$current['designer'] = ' class="current"';
checkaccount();

require_once IA_ROOT . '/source/class/account.class.php';

$acc = WeAccount::create($_W['weid']);
if(empty($acc)) {
	message('非法访问');
}

$menusetcookie = 'menuset-' . $_W['weid'];
if($_W['ispost']) {
	if($_GPC['do'] == 'remove') {
		$ret = $acc->menuDelete();
		if(is_error($ret)) {
			message($ret['message'], 'refresh');
		} else {
			isetcookie($menusetcookie, '', -500);
			message('已经成功删除菜单，请重新创建。', 'refresh');
		}
	}
	if($_GPC['do'] == 'refresh') {
		isetcookie($menusetcookie, '', -500);
		message('已清空缓存，将重新从公众平台接口获取菜单信息。', 'refresh');
	}
	require model('rule');
	$mDat = $_GPC['do'];
	$mDat = htmlspecialchars_decode($mDat);
	$menus = json_decode($mDat, true);
	if(!is_array($menus)) {
		message('操作非法.');
	}
	foreach($menus as &$m) {
		$m['name'] = urlencode($m['name']);
		if(is_array($m['sub_button'])) {
			foreach($m['sub_button'] as &$s) {
				$s['name'] = urlencode($s['name']);
			}
		}
	}
	$ms = array();
	$ms['button'] = $menus;
	$ret = $acc->menuCreate($ms);
	if(is_error($ret)) {
		message($ret['message'], 'refresh');
	} else {
		isetcookie($menusetcookie, '', -500);
		message('已经成功创建菜单. ', create_url('menu'));
	}
}
$dat = $_GPC[$menusetcookie];
$dat = htmlspecialchars_decode($dat);
$menus = @json_decode($dat, true);
if(empty($menus) || !is_array($menus)) {
	$menus = $acc->menuQuery();
	if(is_error($menus)) {
		message($menus['message'], 'refresh');
	}
}
if(empty($menus) || !is_array($menus)) {
	message('获取菜单数据失败，请重试！');
}
if(is_string($menus['menu'])) {
	$menus['menu'] = @json_decode($menus['menu'], true);
}
if(!is_array($menus['menu'])) {
	$menus['menu'] = array();
}
isetcookie($menusetcookie, json_encode($menus), 86400);

if(is_array($menus['menu']['button'])) {
	foreach($menus['menu']['button'] as &$m) {
		if(isset($m['key'])) {
			$m['forward'] = $m['key'];
		}
		if(is_array($m['sub_button'])) {
			foreach($m['sub_button'] as &$s) {
				$s['forward'] = $s['key'];
			}
		}
	}
}
template('menu/designer');

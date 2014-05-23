<?php 
/**
 * 云服务诊断
 * [WeEngine System] Copyright (c) 2013 WE7.CC
 */

if(empty($_W['isfounder'])) {
	message('访问非法.');
}

if(checksubmit()) {
	include model('setting');
	setting_save('', 'site');
	message('成功清除站点记录.', 'refresh');
}
template('cloud/diagnose');

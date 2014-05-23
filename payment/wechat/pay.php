<?php
/**
 * [WeEngine System] Copyright (c) 2013 WE7.CC
 * $sn: htdocs/payment/wechat/pay.php : v abc6abb4cbaf : 2014/03/20 06:54:44 : veryinf $
 */
define('IN_MOBILE', true);
require '../../source/bootstrap.inc.php';

$params = $_GPC['params'];
$wOpt = @json_decode(base64_decode($params), true);
?>
<script type="text/javascript">
document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
	WeixinJSBridge.invoke('getBrandWCPayRequest', {
		'appId' : '<?php echo $wOpt['appId'];?>',
		'timeStamp': '<?php echo $wOpt['timeStamp'];?>',
		'nonceStr' : '<?php echo $wOpt['nonceStr'];?>',
		'package' : '<?php echo $wOpt['package'];?>',
		'signType' : '<?php echo $wOpt['signType'];?>',
		'paySign' : '<?php echo $wOpt['paySign'];?>'
	}, function(res) {
		if(res.err_msg == 'get_brand_wcpay_request:ok') {
		} else {
			alert('启动微信支付失败, 请检查你的支付参数. 详细错误为: ' + res.err_msg);
		}
		history.go(-1);
	});
}, false);
</script>
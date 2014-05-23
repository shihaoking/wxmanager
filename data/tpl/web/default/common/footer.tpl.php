<?php defined('IN_IA') or exit('Access Denied');?>	<div id="footer">
		<span class="pull-left">
			<p><?php  if(empty($_W['setting']['copyright']['footerleft'])) { ?>Powered by <b>IdeaMax</b> v<?php echo IMS_VERSION;?> &copy; 2014 <?php  } else { ?><?php  echo $_W['setting']['copyright']['footerleft'];?><?php  } ?></p>
		</span>
		<span class="pull-right">
			<p><?php  if(empty($_W['setting']['copyright']['footerright'])) { ?><?php  } else { ?><?php  echo $_W['setting']['copyright']['footerright'];?><?php  } ?></p>
		</span>
	</div>
	<div class="emotions" style="display:none;"></div>
	<?php  echo $_W['setting']['copyright']['statcode'];?>
</body>
</html>

<?php defined('IN_IA') or exit('Access Denied');?><?php  include template('common/header', TEMPLATE_INCLUDEPATH);?>
<script type="text/javascript" src="./resource/script/jquery-ui-1.10.3.min.js"></script>
<script type="text/javascript">
	var pIndex = 1;
	var currentEntity = null;
	$(function(){
		$('tbody.mlist').sortable({handle: '.icon-move'});
		$('.smlist').sortable({handle: '.icon-move'});
		$('.mlist .hover').each(function(){
			$(this).data('do', $(this).attr('data-do'));
			$(this).data('url', $(this).attr('data-url'));
			$(this).data('forward', $(this).attr('data-forward'));
		});
		$('.mlist .hover .smlist div').each(function(){
			$(this).data('do', $(this).attr('data-do'));
			$(this).data('url', $(this).attr('data-url'));
			$(this).data('forward', $(this).attr('data-forward'));
		});
		$(':radio[name="ipt"]').click(function(){
			if(this.checked) {
				if($(this).val() == 'url') {
					$('#url-container').show();
					$('#forward-container').hide();
				} else {
					$('#url-container').hide();
					$('#forward-container').show();
				}
			}
		});
		$('#dialog').modal({backdrop: 'static', keyboard: false, show: false});
		$('#dialog').on('hide', saveMenuAction);
	});
	function addMenu() {
		if($('.mlist .hover').length >= 3) {
			return;
		}
		var html = '<tr class="hover">'+
						'<td>'+
							'<div>'+
								'<input type="text" class="span4" value=""> &nbsp; &nbsp; '+
								'<a href="javascript:;" class="icon-move" title="拖动调整此菜单位置"></a> &nbsp; '+
								'<a href="javascript:;" onclick="setMenuAction($(this).parent().parent().parent());" class="icon-edit" title="设置此菜单动作"></a> &nbsp; '+
								'<a href="javascript:;" onclick="deleteMenu(this)" class="icon-remove-sign" title="删除此菜单"></a> &nbsp; '+
								'<a href="javascript:;" onclick="addSubMenu($(this).parent().next());" title="添加子菜单" class="icon-plus-sign" title="添加菜单"></a> '+
							'</div>'+
							'<div class="smlist"></div>'+
						'</td>'+
					'</tr>';
		$('tbody.mlist').append(html);
	}
	function addSubMenu(o) {
		if(o.find('div').length >= 5) {
			return;
		}
		var html = '' +
				'<div style="margin-top:20px;padding-left:80px;background:url(\'./resource/image/bg_repno.gif\') no-repeat -245px -545px;">'+
					'<input type="text" class="span3" value=""> &nbsp; &nbsp; '+
					'<a href="javascript:;" class="icon-move" title="拖动调整此菜单位置"></a> &nbsp; '+
					'<a href="javascript:;" onclick="setMenuAction($(this).parent());" class="icon-edit" title="设置此菜单动作"></a> &nbsp; '+
					'<a href="javascript:;" onclick="deleteMenu(this)" class="icon-remove-sign" title="删除此菜单"></a> '+
				'</div>';
		o.append(html);
	}
	function deleteMenu(o) {
		if($(o).parent().parent().hasClass('smlist')) {
			$(o).parent().remove();
		} else {
			$(o).parent().parent().parent().remove();
		}
	}
	function setMenuAction(o) {
		if(o == null) return;
		if(o.find('.smlist div').length > 0) {
			return;
		}
		currentEntity = o;
		$('#ipt-url').val($(o).data('url'));
		$('#ipt-forward').val($(o).data('forward'));
		if($(o).data('do') != 'forward') {
			$(':radio').eq(0).attr('checked', 'checked');
		} else {
			$(':radio').eq(1).attr('checked', 'checked');
		}
		$(':radio:checked').trigger('click');
		$('#dialog').modal('show');
	}
	function saveMenuAction(e) {
		var o = currentEntity;
		var t = $(':radio:checked').val();
		t = t == 'url' ? 'url' : 'forward';
		if(o == null) return;
		$(o).data('do', t);
		$(o).data('url', $('#ipt-url').val());
		$(o).data('forward', $('#ipt-forward').val());
	}
	function saveMenu() {
		if($('.span4:text').length > 3) {
			message('不能输入超过 3 个主菜单才能保存.', '', 'error');
			return;
		}
		if($('.span4:text,.span3:text').filter(function(){ return $.trim($(this).val()) == '';}).length > 0) {
			message('存在未输入名称的菜单.', '', 'error');
			return;
		}
		if($('.span4:text').filter(function(){ return $.trim($(this).val()).length > 5;}).length > 0) {
			message('主菜单的名称长度不能超过5个字.', '', 'error');
			return;
		}
		if($('.span3:text').filter(function(){ return $.trim($(this).val()).length > 8;}).length > 0) {
			message('子菜单的名称长度不能超过8个字.', '', 'error');
			return;
		}
		var dat = '[';
		var error = false;
		$('.mlist .hover').each(function(){
			var name = $.trim($(this).find('.span4:text').val()).replace(/"/g, '\"');
			var type = $(this).data('do') != 'forward' ? 'view' : 'click';
			var url = $(this).data('url');
			if(!url) {
				url = '';
			}
			var forward = $.trim($(this).data('forward'));
			if(!forward) {
				forward = '';
			}
			dat += '{"name": "' + name + '"';
			if($(this).find('.smlist div').length > 0) {
				dat += ',"sub_button": [';
				$(this).find('.smlist div').each(function(){
					var sName = $.trim($(this).find('.span3:text').val()).replace(/"/g, '\"');
					var sType = $(this).data('do') != 'forward' ? 'view' : 'click';
					var sUrl = $(this).data('url');
					if(!sUrl) {
						sUrl = '';
					}
					var sForward = $.trim($(this).data('forward'));
					if(!sForward) {
						sForward = '';
					}
					dat += '{"name": "' + sName + '"';
					if((sType == 'click' && sForward == '') || (sType == 'view' && !sUrl)) {
						message('子菜单项 “' + sName + '”未设置对应规则.', '', 'error');
						error = true;
						return false;
					}
					if(sType == 'click') {
						dat += ',"type": "click","key": "' + encodeURIComponent(sForward) + '"';
					}
					if(sType == 'view') {
						dat += ',"type": "view","url": "' + sUrl + '"';
					}
					dat += '},';
				});
				if(error) {
					return false;
				}
				dat = dat.slice(0,-1);
				dat += ']';
			} else {
				if((type == 'click' && forward == '') || (type == 'view' && !url)) {
					message('菜单 “' + name + '”不存在子菜单项, 且未设置对应规则.', '', 'error');
					error = true;
					return false;
				}
				if(type == 'click') {
					dat += ',"type": "click","key": "' + encodeURIComponent(forward) + '"';
				}
				if(type == 'view') {
					dat += ',"type": "view","url": "' + url + '"';
				}
			}
			dat += '},';
		});
		if(error) {
			return;
		}
		dat = dat.slice(0,-1);
		dat += ']';
		$('#do').val(dat);
		$('#form')[0].submit();
	}
</script>
<style type="text/css">
	.table-striped td{padding-top: 10px;padding-bottom: 10px}
	a{font-size:14px;}
	a:hover, a:active{text-decoration:none; color:red;}
	.hover td{padding-left:10px;}
</style>
<div class="main">
	<div class="form form-horizontal">
		<h4>菜单设计器 <small>编辑和设置微信公众号码, 必须是服务号才能编辑自定义菜单。</small></h4>
		<table class="tb table-striped">
			<tbody class="mlist">
			<?php  if(!empty($menus['menu']['button'])) { ?>
			<?php  if(is_array($menus['menu']['button'])) { foreach($menus['menu']['button'] as $row) { ?>
			<tr class="hover" data-do="<?php echo $row['type'] == 'click' ? 'forward' : 'view';?>" data-url="<?php  echo $row['url'];?>" data-forward="<?php  echo $row['forward'];?>">
					<td>
						<div>
							<input type="text" class="span4" value="<?php  echo $row['name'];?>"> &nbsp; &nbsp;
							<a href="javascript:;" class="icon-move" title="拖动调整此菜单位置"></a> &nbsp;
							<a href="javascript:;" onclick="setMenuAction($(this).parent().parent().parent());" class="icon-edit" title="设置此菜单动作"></a> &nbsp;
							<a href="javascript:;" onclick="deleteMenu(this)" class="icon-remove-sign" title="删除此菜单"></a> &nbsp;
							<a href="javascript:;" onclick="addSubMenu($(this).parent().next());" title="添加子菜单" class="icon-plus-sign" title="添加菜单"></a>
						</div>
						<div class="smlist">
							<?php  if(!empty($row['sub_button'])) { ?>
							<?php  if(is_array($row['sub_button'])) { foreach($row['sub_button'] as $btn) { ?>
							<div style="margin-top:20px;padding-left:80px;background:url('./resource/image/bg_repno.gif') no-repeat -245px -545px;" data-do="<?php echo $btn['type'] == 'click' ? 'forward' : 'view';?>" data-url="<?php  echo $btn['url'];?>" data-forward="<?php  echo $btn['forward'];?>">
								<input type="text" class="span3" value="<?php  echo $btn['name'];?>"> &nbsp; &nbsp;
								<a href="javascript:;" class="icon-move" title="拖动调整此菜单位置"></a> &nbsp;
								<a href="javascript:;" onclick="setMenuAction($(this).parent());" class="icon-edit" title="设置此菜单动作"></a> &nbsp;
								<a href="javascript:;" onclick="deleteMenu(this)" class="icon-remove-sign" title="删除此菜单"></a>
							</div>
							<?php  } } ?>
							<?php  } ?>
						</div>
					</td>
				</tr>
			<?php  } } ?>
			<?php  } ?>
			</tbody>
		</table>
		<div class="well well-small" style="margin-top:20px;">
			<a href="javascript:;" onclick="addMenu();">添加菜单 <i class="icon-plus-sign" title="添加菜单"></i></a> &nbsp; &nbsp; &nbsp;  <span class="help-inline">可以使用 <i class="icon-move"></i> 进行拖动排序</span>
		</div>

		<h4>操作 <small>设计好菜单后再进行保存操作</small></h4>
		<table class="tb">
			<tbody>
				<tr>
					<td>
						<input type="button" value="保存菜单结构" class="btn btn-primary span3" onclick="saveMenu();"/>
						<span class="help-block">保存当前菜单结构至公众平台, 由于缓存可能需要在24小时内生效</span>
					</td>
				</tr>
				<tr>
					<td>
						<input type="button" value="删除" class="btn btn-primary span3" onclick="$('#do').val('remove');$('#form')[0].submit();" />
						<div class="help-block">清除自定义菜单</div>
					</td>
				</tr>
				<tr>
					<td>
						<input type="button" value="刷新" class="btn btn-primary span3" onclick="$('#do').val('refresh');$('#form')[0].submit();" />
						<div class="help-block">重新从公众平台获取菜单信息</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<form action="" method="post" id="form"><input id="do" name="do" type="hidden" /></form>
<div id="dialog" class="modal hide">
	<div class="modal-header">
		<h3>选择要执行的操作</h3>
	</div>
	<div id="url">
		<div class="well">
			<label class="radio inline">
				<input type="radio" name="ipt" value="url" checked="checked"> 链接
			</label>
			<label class="radio inline">
				<input type="radio" name="ipt" value="forward"> 模拟关键字
			</label>
			<hr />
			<div id="url-container">
				<input class="span6" id="ipt-url" type="text" value="http://" />
				<span class="help-block">指定点击此菜单时要跳转的链接（注：链接需加http://）</span>
				<span class="help-block"><strong>注意: 由于接口限制. 如果你没有网页oAuth接口权限, 这里输入链接直接进入微站个人中心时将会有缺陷(有可能获得不到当前访问用户的身份信息. 如果没有oAuth接口权限, 建议你使用图文回复的形式来访问个人中心)</strong></span>
			</div>
			<div id="forward-container" class="hide">
				<input class="span6" id="ipt-forward" type="text" />
				<span class="help-block">指定点击此菜单时要执行的操作, 你可以在这里输入关键字, 那么点击这个菜单时就就相当于发送这个内容至微擎系统</span>
				<span class="help-block"><strong>这个过程是程序模拟的, 比如这里添加关键字: 优惠券, 那么点击这个菜单是, 微擎系统相当于接受了粉丝用户的消息, 内容为"优惠券"</strong></span>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<a href="javascript:;" class="pull-right btn btn-primary span2" data-dismiss="modal" aria-hidden="true">保存</a>
	</div>
</div>
<?php  include template('common/footer', TEMPLATE_INCLUDEPATH);?>

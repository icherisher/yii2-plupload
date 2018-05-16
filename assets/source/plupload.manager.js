"use strict"

$(function () {
	if ($('#plupload-modal').length == 0) {
		var html = '<div class="modal fade" id="plupload-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">' +
			'  <div class="modal-dialog" role="document">' +
			'    <div class="modal-content">' +
			'      <div class="modal-header">' +
			'        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
			'        <h4 class="modal-title" id="myModalLabel">上传列表</h4>' +
			'      </div>' +
			'      <div class="modal-body">' +
			'<table class="table table-striped">' +
			'<tr>' +
			'<td>1</td>' +
			'<td>IPA文件</td>' +
			'<td><i class="glyphicon glyphicon-remove"></i></td>' +
			'</tr>' +
			'</table>' +
			'      </div>' +
			'      <div class="modal-footer">' +
			'        <button type="button" class="btn btn-primary plupload-browse">浏览</button>' +
			'        <button type="button" class="btn btn-primary">上传</button>' +
			'        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>' +
			'      </div>' +
			'    </div>' +
			'  </div>' +
			'</div>';
		$('body').prepend(html);
	}
});
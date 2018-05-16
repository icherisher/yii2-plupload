<?php
/**
 * plupload组件
 * User: haojy
 * Email: hao.jingyang@163.com
 * Date: 2018/5/9 17:09
 */

namespace icherisher\plupload\components;

use yii\helpers\Json;
use yii\helpers\Html;
use yii\widgets\InputWidget;
use icherisher\plupload\assets\PluploadAsset;

class PluploadWidget extends InputWidget
{
	/**
	 * plupload事件
	 * @link https://www.plupload.com/docs/
	 * @var array
	 */
	public $events = [];
	
	/**
	 * plupload配置项
	 * @link https://www.plupload.com/docs/
	 * @var array
	 */
	public $settings = [];
	
	/**
	 * 自动上传
	 * @var bool
	 */
	public $auto_upload = false;
	
	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		
		if (!isset($this->options['readonly'])) {
			$this->options['readonly'] = true;
		}
		
		if (!isset($this->options['class'])) {
			$this->options['class'] = 'form-control';
		}
		
		if (!isset($this->options['placeholder'])) {
			$this->options['placeholder'] = '请上传文件...';
		}
		
		if (!isset($this->settings['browse_button'])) {
			$this->settings['browse_button'] = $this->options['id'] . '-browse';
		}
		
		if (!isset($this->settings['url'])) {
			$this->settings['url'] = '/plupload/default';
		}
		
		if (!isset($this->settings['chunk_size'])) {
			$this->settings['chunk_size'] = '2M';
		}
		
		$this->settings['unique_names'] = true;
		
		if (!isset($this->events['PostInit'])) {
			$js = 'document.getElementById("' . $this->options['id'] . '-remove").onclick= function(){
						if(confirm("确定要清除吗?")){
							document.getElementById("' . $this->options['id'] . '").value="";
							document.getElementById("' . $this->options['id'] . '-visible").value="";
						}
						return false;
					}';
			
			$js .= PHP_EOL;
			
			if (!$this->auto_upload) {
				$js .= 'document.getElementById("' . $this->options['id'] . '-upload").onclick=function(){
							if(up.files.length==0){
								alert("请选择要上传的文件");
								return false;
							}
							if(up.state == 1){
								up.start();
							}
							return false;
						}';
			}
			
			$this->events['PostInit'] = 'function(up){' . $js . '}';
		}
		
		if (!isset($this->events['FilesAdded'])) {
			$js = '';
			if ($this->auto_upload) {
				$js = 'up.start();return false;';
			}
			
			$this->events['FilesAdded'] = 'function(up, files){
				var html = "";
				var len = files.length;
				for(var i=0; i<len; i++){
					html += "<tr><td>"+ (i+1) +"</td><td>"+ files[i].name +"</td>";
					html += "</tr>";
				}
				document.getElementById("' . $this->options['id'] . '-selected").innerHTML=html;
				document.getElementById("' . $this->options['id'] . '-progress").innerHTML="";
				document.getElementById("' . $this->options['id'] . '").value="";
				document.getElementById("' . $this->options['id'] . '-visible").value="";
				' . $js . '
			}';
		}
		
		if (!isset($this->events['UploadProgress'])) {
			$this->events['UploadProgress'] = 'function(up, file){
				var progress = up.total.percent==100?"上传完成":up.total.percent+"%";
				document.getElementById("' . $this->options['id'] . '-progress").innerHTML=progress;
			}';
		}
		
		if (!isset($this->events['FileUploaded'])) {
			$this->events['FileUploaded'] = 'function(up, file, resp){
				if(resp.status==200 && resp.response){
					var el =document.getElementById("' . $this->options['id'] . '");
					el.value += resp.response + "|";
				}
			}';
		}
		
		if (!isset($this->events['UploadComplete'])) {
			$this->events['UploadComplete'] = 'function(up, files){
				var value = "";
				var len = files.length;
				for(var i=0; i<len; i++){
					value += files[i].name +"、";
				}
				document.getElementById("' . $this->options['id'] . '-visible").value=value;
				up.splice(0, len+1);
			}';
		}
	}
	
	/**
	 * @inheritdoc
	 */
	public function run()
	{
		echo '<div class="input-group">';
		
		$options = $this->options;
		$options['id'] .= '-visible';
		$value = $this->hasModel() ? Html::getAttributeValue($this->model, $this->attribute) : $this->value;
		echo Html::textInput(null, $value, $options);
		
		if ($this->hasModel()) {
			echo Html::activeHiddenInput($this->model, $this->attribute, $this->options);
		} else {
			echo Html::hiddenInput($this->name, $this->value, $this->options);
		}
		
		echo '<div class="input-group-addon btn btn-default" data-toggle="modal"
				data-target="#' . $this->options['id'] . '-modal">
				<i class="glyphicon glyphicon-folder-open"></i>
			</div>';
		echo '<div class="input-group-addon btn btn-default" id="' . $this->options['id'] . '-remove">
				<i class="glyphicon glyphicon-remove"></i>
			</div>';
		
		echo '</div>';
		
		$this->registerAssets();
	}
	
	/**
	 * 注册资源
	 */
	public function registerAssets()
	{
		$view = $this->getView();
		PluploadAsset::register($view);
		
		$uploadBtn = '';
		if (!$this->auto_upload) {
			$uploadBtn = '<button type="button" class="btn btn-primary" id="' . $this->options['id'] . '-upload">上传</button>';
		}
		
		$modal = $this->options['id'] . '-modal';
		$js = '
		if ($("#p' . $modal . '").length == 0) {
			var html = \'<div class="modal fade" id="' . $modal . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">\' +
			\'  <div class="modal-dialog" role="document">\' +
			\'    <div class="modal-content">\' +
			\'      <div class="modal-header">\' +
			\'        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>\' +
			\'        <h4 class="modal-title" id="myModalLabel">上传列表</h4>\' +
			\'      </div>\' +
			\'      <div class="modal-body">\' +
			\'			<table class="table table-striped">\'+
			\'			<theader><tr><td width="20%">序号</td><td>文件名称</td></tr></theader>\' +
			\'			<tbody id="' . $this->options['id'] . '-selected"></tbody>\' +
			\'			</table>\' +
			\'      </div>\' +
			\'      <div class="modal-footer">\' +
			\'        <span class="text-primary" id="' . $this->options['id'] . '-progress"></span>\' +
			\'        <button type="button" class="btn btn-primary" id="' . $this->options['id'] . '-browse">浏览</button>\' +
			\'      ' . $uploadBtn . '</div>\' +
			\'    </div>\' +
			\'  </div>\' +
			\'</div>\';
			$(\'body\').prepend(html);
		}
		';
		$view->registerJs($js);
		
		
		// new plupload.Uploader()
		$settings = trim(Json::encode($this->settings), '{}');
		$events = '';
		if ($this->events) {
			$events .= 'init:{';
			foreach ($this->events as $e => $callback) {
				$events .= $e . ':' . $callback . ',';
			}
			$events .= '}';
		}
		$js = "new plupload.Uploader({{$settings},{$events}}).init();";
		$view->registerJs($js);
	}
	
	
}
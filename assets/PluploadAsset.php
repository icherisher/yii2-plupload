<?php
/**
 * User: haojy
 * Email: icherish.hao@gmail.com
 * Date: 2018/5/10 11:28
 */

namespace icherisher\plupload\assets;

use yii\web\AssetBundle;

class PluploadAsset extends AssetBundle
{
	public $sourcePath = __DIR__ . '/source';
	
	public $js = [
		'plupload-2.3.6/js/plupload.full.min.js',
		// 'plupload.manager.js'
	];
	
	public $depends = [
		'yii\web\JqueryAsset',
		'yii\bootstrap\bootstrapPluginAsset'
	];
	
}
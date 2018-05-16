<?php

namespace common\plupload\controllers;

use yii\web\Controller;
use Yii;

/**
 * Default controller for the `plupload` module
 */
class DefaultController extends Controller
{
	public $enableCsrfValidation = false;
	
	/**
	 * Renders the index view for the module
	 * @return string
	 */
	public function actionIndex()
	{
		return Yii::$app->pluploadManager->saveFile();
	}
}

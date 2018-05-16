<?php
/**
 * User: haojy
 * Email: icherish.hao@gmail.com
 * Date: 2018/5/15 14:37
 */

namespace icherisher\plupload\components;

use Yii;
use yii\base\Component;

class PluploadManager extends Component
{
	/**
	 * @var string    上传文件的存储目录
	 */
	public $path = '/tmp';
	
	/**
	 * @var int 文件权限
	 */
	public $fileMode = 0777;
	
	
	/**
	 * 接收并保存文件
	 * @param string $fileDataName 指定文件上传时文件域的名称，默认为file
	 * @return bool|string    false|文件唯一标识
	 */
	public function saveFile($fileDataName = 'file')
	{
		$chunk = Yii::$app->request->getBodyParam('chunk');
		$chunks = Yii::$app->request->getBodyParam('chunks');
		
		$filename = Yii::$app->request->getBodyParam('name');
		$filepath = $this->path . DIRECTORY_SEPARATOR . $filename;
		if (!$out = @fopen("{$filepath}.part", $chunks ? 'ab' : 'wb')) {
			return false;
		}
		
		if (!empty($_FILES)) {
			if ($_FILES[$fileDataName]['error'] or !is_uploaded_file($_FILES[$fileDataName]['tmp_name'])) {
				return false;
			}
			
			if (!$in = @fopen($_FILES[$fileDataName]['tmp_name'], 'rb')) {
				return false;
			}
		} else {
			if (!$in = @fopen('php://input', 'rb')) {
				return false;
			}
		}
		
		while ($buff = fread($in, 4096)) {
			fwrite($out, $buff);
		}
		@fclose($out);
		@fclose($in);
		
		if (!$chunks or $chunk == $chunks - 1) {
			if (rename("{$filepath}.part", $filepath)) {
				chmod($filepath, $this->fileMode);
				
				$identify = uniqid();
				if (\Yii::$app->getCache()->set($identify, $filepath)) {
					return $identify;
				}
			}
		}
		return false;
	}
	
	/**
	 * 根据标识获取文件
	 * @param string $identify 标识符
	 * @return bool|mixed    文件路径
	 */
	public function getFileByIdentify($identify)
	{
		$filepath = \Yii::$app->getCache()->get($identify);
		return is_file($filepath) ? $filepath : false;
	}
	
}
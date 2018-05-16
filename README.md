yii2-plupload
=============
A Yii2 module/widget for upload files

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist icherisher/yii2-plupload "*"
```

or add

```
"icherisher/yii2-plupload": "*"
```

to the require section of your `composer.json` file.

Configuration
-------------

To use this extension, you have to configure the Connection class in your application configuration:

```php

return [
	'components'=>[
		// ...
		'pluploadManager' => [
			'class' => 'icherisher\plupload\components\PluploadManager'
		],
	],
	
	'modules'=>[
		// ...
		'plupload' => [
			'class' => 'icherisher\plupload\Moudle',
		],
	]
];

```



Usage
-----

Once the extension is installed, simply use it in your code by  :

```php

use icherisher\plupload\components\PluploadWidget;

// with ActiveForm & model
echo $form->field($model, 'attribute')->widget(PluploadWidget::class, [
	'options'=>[],
	'settings'=>[],
	'events'=>[],
	....
]); 

// without model
echo PluploadWidget::widget([
	'name'=>'attr_name',
	'value'=>'',
	'options'=>[],
	'settings'=>[],
	'events'=>[],
	....
]);


```


Documentation
-------------

For 2.3.6：[https://www.plupload.com/docs/](https://www.plupload.com/docs/)





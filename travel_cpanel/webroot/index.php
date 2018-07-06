<?php
	define('DS',DIRECTORY_SEPARATOR);
	define('ROOT',dirname(dirname(__FILE__)));
	define('VIEWS_PATH',ROOT.DS.'views');
	define('UPLOAD_PATH',ROOT.DS.'webroot'.DS.'uploads');
	define('UPLOAD_URL','uploads');
	define('NO_IMAGE_URL','img/no_image.png');
	
	require_once(ROOT.DS.'lib'.DS.'init.php');
	
	session_start();
	
	App::run($_SERVER['REQUEST_URI']);
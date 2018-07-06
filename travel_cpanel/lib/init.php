<?php
	function init_autoload($class_name){
		$lib_path		= ROOT.DS.'lib'.DS.strtolower($class_name).'.class.php';
		$controller_path= ROOT.DS.'controllers'.DS.str_replace('controller','',strtolower($class_name)).'.controller.php';
		$model_path		= ROOT.DS.'models'.DS.str_replace('model','',strtolower($class_name)).'.model.php';

		if(file_exists($lib_path)){
			require_once($lib_path);
		}
		elseif(file_exists($controller_path)){
			require_once($controller_path);
		}
		elseif(file_exists($model_path)){
			require_once($model_path);
		}
		else{
			throw new Exception('Faild to include class: '.$class_name);
		}
	}

	spl_autoload_register('init_autoload');

	require_once(ROOT.DS.'config'.DS.'config.php');

	function __($key, $default_value = ''){
		return Lang::get($key, $default_value);
	}

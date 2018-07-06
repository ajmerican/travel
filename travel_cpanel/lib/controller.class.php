<?php
	class Controller{
		protected $data;
		protected $model;
		protected $params;
		
		public function __construct($data = array()){
			$this->data		= $data;
			$this->params	= App::getRouter()->getParams();
		}
		
		public function getData(){
			return $this->data;
		}
		
		public function getModel(){
			return $this->model;
		}
		
		public function getParams(){
			return $this->params;
		}
		
		public function getRequest($key,$defalutValue = null,$filter = true) {
			if(isset($_REQUEST[$key]) && !is_array($_REQUEST[$key]))
			{
				$safeValue	= (isset($_REQUEST[$key])) ? trim(strip_tags($_REQUEST[$key])) : '';
			}
			else
			{
				$safeValue	= (isset($_REQUEST[$key])) ? $_REQUEST[$key] : '';
			}
			
			if($filter)
			{
				$safeValue = str_replace(array('\\',':','?','*','"',"'",'<','>','|','(',')','{','}','&'), '', $safeValue);//'/',
			}
			
			return ($safeValue != '') ? $safeValue : $defalutValue;
		}

		public  function getPost($key,$defalutValue = null,$filter = false) {
			if(isset($_POST[$key]) && !is_array($_POST[$key]))
			{
				$safeValue	= (isset($_POST[$key])) ? trim(strip_tags($_POST[$key])) : '';
			}
			else
			{
				$safeValue	= (isset($_POST[$key])) ? $_POST[$key] : '';
			}
			
			if($filter)
			{
				$safeValue = str_replace(array('\\','/',':','?','*','"',"'",'<','>','|','(',')','{','}','&'), '', $safeValue);
			}
			
			return ($safeValue != '') ? $safeValue : $defalutValue;
		}
	}
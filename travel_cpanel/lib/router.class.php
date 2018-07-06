<?php
	class Router{
		protected $uri;
		protected $controller;
		protected $action;
		protected $params;
		protected $route;
		protected $method_prefix;
		protected $language;

		public function __construct($uri){
			$uri		= str_replace(Config::get('base_dir'),'',$uri);
			$this->uri	= urldecode(trim($uri,'/'));

			//Get defaults
			$routes				= Config::get('routes');
			$this->route		= Config::get('default_route');
			$this->method_prefix= isset($routes[$this->route]) ? $routes[$this->route] : '';
			$this->language		= Config::get('default_language');
			$this->controller	= Config::get('default_controller');
			$this->action		= Config::get('default_action');

			$uri_parts			= explode('?', $this->uri);

			//Get path like /lng/controller/action/param1/param2/param3/..../..../....
			$path				= $uri_parts[0];
			$path_parts			= explode('/',$path);

			if(count($path_parts)){
				//remove first element if you are in localhost
				/* if(in_array($_SERVER['REMOTE_ADDR'], Config::get('local_ips'))){
					array_shift($path_parts);
				} */

				//get route or language at first element
				if(in_array(strtolower(current($path_parts)), array_keys($routes))){
					$this->route		= strtolower(current($path_parts));
					$this->method_prefix= isset($routes[$this->route]) ? $routes[$this->route] : '';
					array_shift($path_parts);
				} elseif(in_array(strtolower(current($path_parts)), Config::get('languages'))){
					$this->language	= strtolower(current($path_parts));
					array_shift($path_parts);
				}

				//get Controller - Next Element of array
				if(current($path_parts)){
					$this->controller	= strtolower(current($path_parts));
					array_shift($path_parts);
				}

				//get Action - Next Element of array
				if(current($path_parts)){
					$this->action	= strtolower(current($path_parts));
					array_shift($path_parts);
				}

				//get Params - all the rest elements
				$this->params	= $path_parts;
			}
		}

		public function getUri(){
			return $this->uri;
		}

		public function getController(){
			return $this->controller;
		}

		public function getAction(){
			return $this->action;
		}

		public function getParams(){
			return $this->params;
		}

		public function getRoute(){
			return $this->route;
		}

		public function getMethodPrefix(){
			return $this->method_prefix;
		}

		public function getLanguage(){
			return $this->language;
		}

		public static function redirect($location){
			if (!filter_var($location, FILTER_VALIDATE_URL)) {
				$location	= Config::get('base_dir').$location;
			}

			header("Location: $location");
			exit;
		}

		public static function link($alias = ''){
			return Config::get('base_dir').trim( $alias, "/" );
		}

		public static function referer($alias = ''){
			$url = htmlspecialchars($_SERVER['HTTP_REFERER']);

			if(isset($url) && $url != '')
			{
				return $url;
			}
			else
			{
				return $this->link($alias);
			}
		}
	}

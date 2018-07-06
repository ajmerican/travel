<?php
	class App{
		protected static $router;
		protected static $myPermissions;
		public static $db;

		public static function getRouter(){
			return self::$router;
		}

		public static function run($uri){
			self::$router	= new Router($uri);
			self::$db		= new DB(Config::get('db.host'),Config::get('db.user'),Config::get('db.password'),Config::get('db.db_name'));

			Lang::load(self::$router->getLanguage());

			$controller_class	= ucfirst(self::$router->getController()).'Controller';
			$controller_method	= strtolower(self::$router->getMethodPrefix().self::$router->getAction());

			$layout				= self::$router->getRoute();

			if(Session::get('user_id') <= 0){
				/* echo $controller_class;
				exit; */
				if($controller_method != 'login'){
					//echo $controller_method;exit;
					Router::redirect('user/login');
				}

				$layout = 'login';
			}

			//Calling Controller's method
			$controller_object	= new $controller_class();

			if(method_exists($controller_object,$controller_method)){
				//Controller's action return view path
				$view_path	= $controller_object->$controller_method();
				$view_object= new View($controller_object->getData(),$view_path);
				$content	= $view_object->render();
			}else{
				throw new Exception('Method **'.$controller_method.'** of class **'.$controller_class.'** does not exist.');
			}

			$layout_path		= VIEWS_PATH.DS.$layout.'.html';
			$layout_view_object	= new View(compact('content'),$layout_path);
			echo $layout_view_object->render();
		}

		public static function date($mysql_date, $format = null){
			$format	= ($format) ? $format : Config::get('date_format');
			return date($format,strtotime($mysql_date));
		}

		public static function datePHP2MySql($date){
			return date('Y-m-d',strtotime(str_replace('/','-',$date)));
		}

		// check if a permission is set
		public static function can($permission) {
			if(Session::get('super_admin') || Session::get('center_admin')){
				return true;
			} else {
				return isset(self::$myPermissions[$permission]);
			}
		}

		public static function getAge($date){
			$from = new DateTime($date); //Formate: yyyy-mm-dd
			$to   = new DateTime('today');
			return $from->diff($to);
		}
	}

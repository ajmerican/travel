<?php
	class Session{
		protected static $flash_message;

		public static function setFlash($message){
			self::$flash_message = $message;
		}

		public static function hasFlash(){
			return !is_null(self::$flash_message);
		}

		public static function flash(){
			echo self::$flash_message;
			self::$flash_message = null;
		}

		public static function set($key, $value){
			$_SESSION[$key] = $value;
		}

		public static function get($key){
			if(isset($_SESSION[$key])){
				return $_SESSION[$key];
			}
		}

		public static function delete($key){
			if($_SESSION[$key]){
				unset($_SESSION[$key]);
			}
		}

		public static function destroy(){
			session_destroy();
		}

		public static function setMessage($msg)
		{
			$_SESSION['msg'][] = $msg;
			return true;
		}

		public static function getMessage($message = array())
		{
			$msg	= '';
			$isMsg	= false;

			if(count($message) > 0)
			{
				foreach($message AS $key => $val)
				{
					if(isset($val['type']) && $val['type'] != '' && isset($val['message']) && $val['message'] != '')
					{
						$msg.= '<div class="alert alert-'.$val['type'].'"><button class="close" data-close="alert"></button><span>'.$val['message'].'</span></div>';
					}
				}

				$isMsg	= true;
			}

			if(isset($_SESSION['msg']) && count($_SESSION['msg']) > 0)
			{
				foreach($_SESSION['msg'] AS $key => $val)
				{
					if(isset($val['type']) && $val['type'] != '' && isset($val['message']) && $val['message'] != '')
					{
						$msg.= '<div class="alert alert-'.$val['type'].'"><button class="close" data-close="alert"></button><span>'.$val['message'].'</span></div>';
					}
				}

				unset($_SESSION['msg']);
				$_SESSION['msg']	= array();
				$isMsg	= true;
			}

			if(!$isMsg)
			{
				$msg.= '<div class="alert alert-success" style="display:none;"></div>';
			}

			return $msg;
		}
	}

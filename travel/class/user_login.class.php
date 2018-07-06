<?php
	class userLogin {
		//Specific field
		public $db;
		public $table;

		public $id;
		public $name;

		public $email;
		public $status;

		//more fields here
		public function __construct() {
			global $lib,$db;
			$this->db = $db;
			$this->table = TBL_PREFIX.'users';
			$this->tableurm = TBL_PREFIX.'user_role_map';
			$this->id = 0;

			if(isset($_SESSION[SESSION_PREFIX.'_user']))
			{
				$this->id	 	= $_SESSION[SESSION_PREFIX.'_user']->user_id;
				$this->name 	= $_SESSION[SESSION_PREFIX.'_user']->user_name;
				$this->email 	= $_SESSION[SESSION_PREFIX.'_user']->email;
				$this->status 	= $_SESSION[SESSION_PREFIX.'_user']->status;
			}

			if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '')
			{
				if(!preg_match('/login.php/i',$_SERVER['HTTP_REFERER']) && !preg_match('/logout.php/i',$_SERVER['HTTP_REFERER']))
				{
					$_SESSION['redirctURL']	= $_SERVER['HTTP_REFERER'];
				}
			}
		}

		public function all() {
			$q		= "SELECT * FROM ".$this->table;
			$result = $this->db->query($q);
			return $result;
		}

		public function rows($where = '') {
			$q		= "SELECT * FROM ".$this->table." WHERE 1=1 ".$where;
			$result = $this->db->query($q);
			return $result;
		}

		public function userrole($where = ''){
			$q		= "SELECT * FROM ".$this->tableurm." WHERE 1=1 ".$where;
			$result = $this->db->query($q);
			return $result;
		}

		public function isAdmin($user_id = 0)
		{
			$user_id = ($user_id > 0) ? $user_id : $this->id;

			$q		= "SELECT * FROM ".$this->tableurm." WHERE user_id = $user_id AND role_id = 1";
			$result = $this->db->query($q);

			if($result)
			{
				if($result->num_rows>0)
				{
					return true;
				}
			}

			return false;
		}

		public function isUser($user_id)
		{
			$q		= "SELECT * FROM ".$this->tableurm." WHERE user_id = $user_id";
			$result = $this->db->query($q);
			if($result)
			{
				if($result->num_rows>0)
				{
					return true;
				}
			}

			return false;
		}

		public function delete($id)
		{
			$sql = "DELETE FROM `".$this->table."` WHERE `id` IN ($id)";exit;
			if ($this->db->query($sql) === TRUE) {
				return true;
			} else {
				return "Error deleting record(s): " . $this->db->error;
			}
		}

		public function dologin($email, $pass, $remember = false)
		{
			global $lib;

			if($email != '' && $pass != '')
			{
				$whereRoll= '';
				$where		= " AND `user_name` = '".$this->db->link->real_escape_string($email)."' AND  `password` = '".$this->db->link->real_escape_string(MD5(SALT.$pass))."' AND `status`='1'";
				$result		= $this->rows($where);

				if(!isset($result[0]['user_id']) || $result[0]['user_id'] <= 0)
				{
					$msg	= array('type' => 'danger', 'message' => 'Please check your username / password or you maybe incative');
					$lib->setMessage($msg);
				}
				else
				{
					$this->id	 	= $result[0]['user_id'];
					$this->name 	= $result[0]['user_name'];
					$this->email 	= $result[0]['email'];
					$this->status 	= $result[0]['status'];

					if (!empty($remember) && $remember!=false) {
						setcookie('username', $result[0]['email'], time()+3600*24*30);
					}

					$_SESSION[SESSION_PREFIX.'_user'] = (object)$result[0];
					return true;
				}
			}
			else
			{
				$msg	= array('type' => 'danger', 'message' => 'Please enter valid email and password.');
				$lib->setMessage($msg);
			}
		}

		public function doGuestlogin()
		{
			$row	= new stdClass();
			$row->id	 	= 0;
			$row->name 	= 'Guest';
			$row->email 	= 'guest';
			$row->status 	= 1;

			$this->id	 	= $row->id;
			$this->name 	= $row->name;
			$this->email 	= $row->email;
			$this->status 	= $row->status;

			$_SESSION[SESSION_PREFIX.'_user'] = $row;
			return true;
		}

		public function autologin($path = '')
		{
			if(isset($_COOKIE['username']))
			{
				global $lib;

				$where		= " AND email = '".$lib->db->real_escape_string($_COOKIE['username'])."' AND status='1'";
				$result		= $this->rows($where);

				if($result->num_rows > 0){

					$row = $result->fetch_object();

					$this->id	 	= $row->id;
					$this->name 	= $row->name;
					$this->email 	= $row->email;
					$this->status 	= $row->status;

					$_SESSION[SESSION_PREFIX.'_user'] = $row;
				}
			}
		}

		private function getRedirctURL($redirctTo = '')
		{
			if(isset($_SESSION['redirctURL']) && $_SESSION['redirctURL'] != '')
			{
				return $_SESSION['redirctURL'];
			}
			else
			{
				return ($redirctTo != '') ? $redirctTo : MY_ACCOUNT_URL;
			}
		}

		public function checklogin()
		{
			if(!isset($this->email))
			{
				//global $lib;

				//$msg	= array('type' => 'danger', 'message' => 'Your session has expired. Please log in again.');
				//$lib->setMessage($msg);
				//header("Location:login.php");
				//exit;

				return false;
			}

			return true;
		}

		function checkLoginOnLogin()
		{
			if(isset($this->email) && $this->email != '')
			{
				header("Location:".$this->getRedirctURL());
				exit;
			}
		}

		public function passwordmail($mailid,$password,$user)
		{
			$to 		= $mailid;
			$subject 	= " YOUR PASSWORD FOR CATFIGHT24x7 ";
			$message	= "Your Username is '".$user."' \n\n";
			$message.= "Your Password is '".$password."'";
			$header 	= "catfight24x7@gmail.com";

			if(!mail($to,$subject,$message,$header))
			{
				return false;
			}
			return true;
		}

		public function getUserName()
		{
			if(isset($this->name) && $this->name!='')
			{
				return 'Welcome '.ucfirst($this->name);
			}
			return null;
		}

		public function forgotPassword($email)
		{
			global $lib;

			if($email != '')
			{
				$where		= " AND email ='".$lib->db->real_escape_string($email)."' AND status='1' ";
				$result		= $this->rows($where);
				$row		= $result->fetch_object();

				if($result->num_rows <= 0)
				{
					$msg	= array('type' => 'danger', 'message' => 'Please check your Email ID or you maybe incative');
					$lib->setMessage($msg);
				}
				else
				{
					$query = 'UPDATE `'.$this->table.'` SET `updated` = NOW(), `updated_by` = 0, password = ? WHERE id = ?';

					$stmt = $this->db->stmt_init();

					if($stmt->prepare($query))
					{
						$newPas	=	md5($this->generateRandomString(5));

						if(!$stmt->bind_param('si', $newPas, $this->id))
						{
							$msg	= array('type' => 'danger', 'message' => TECH_ERROR);
							$lib->setMessage($msg);
							return false;
							//return $stmt->error;
						}

						if($stmt->execute())
						{
							$mailid 	= $row->email;
							$name 		= $row->name;

							$subject	= 'YOUR NEW PASSWORD OF '.SITE_TITLE;
							$htmlBody   = "Dear $name, \n Your Password is $newPas";

							$lib->sendmail($subject,$htmlBody,$mailid,$name);

							$msg	= array('type' => 'success', 'message' => 'We send new password to your registered email id.');
							$lib->setMessage($msg);

							return true;
						}
						else
						{
							$msg	= array('type' => 'danger', 'message' => TECH_ERROR);
							$lib->setMessage($msg);
							return false;
							//return $stmt->error;
						}
					}
					else
					{
						$msg	= array('type' => 'danger', 'message' => TECH_ERROR);
						$lib->setMessage($msg);
						return false;
						//return $stmt->error;
					}
				}
			}
			else
			{
				$msg	= array('type' => 'danger', 'message' => 'Please Enter Email Id');
				$lib->setMessage($msg);
			}
		}

		private function generateRandomString($length = 10)
		{
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
		}
	}

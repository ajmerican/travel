<?php
	class BookingModel extends Model{
		private static $tbl = 'booking';

		public function getList($only_published = false){
			$sql	= 'SELECT * FROM `'.self::$tbl.'` WHERE 1 = 1';

			if($only_published){
				$sql.= ' AND `status` = 1';
			}

			if(!Session::get('super_admin')){
				$sql.= ' AND `u_id` = '.Session::get('user_id');
			}

			return $this->db->query($sql);
		}

		public function getById($id){
			$id		= (int)$id;
			$sql	= "SELECT * FROM `".self::$tbl."` WHERE id = '{$id}' LIMIT 1";
			$result = $this->db->query($sql);

			if(isset($result[0])){
				$return	= $result[0];

			} else {
				$return = array(
							'id'					=> null,
							'status'	=> '',
							'created_by'	=> '',
							'updated_by'	=> '',
							'created_date'	=> '',
							'updated_date'	=> '',
							'u_id'					=> 0,
							'travel_agent_id'	=> 0,
							'customer_id'	=> 0,
							'hotel_name'	=> '',
							'from_city'	=> '',
							'to_city' => '',
							'date'					=> null,
							'time_from'	=> '',
							'time_to'	=> '',
							'flight_id'	=> '',
							'portal_pnr_no'	=> '',
							'pnr_no'	=> '',
							'promocode'	=> '',
							'payment_method_id'	=> '',
							'payment_status'	=> 0
						);
			}

			$user	= new UserModel();

			$return['cust'] = $user->getById($return['u_id']);

			return $return;
		}

		public function save($data, $id = null, $center_id = null){
			if(!isset($data['first_name'])){
				return false;
			}

			$id	= (int)$id;
			foreach($data as $key => $val){
				if($key	!= 'savecontbtn' && $key != 'id' && $key != 'savebtn'){
					$fields[$key] = $this->db->escape($val);
				}
			}

			//Unset fields if needed
			unset($fields['cpassword']);
			unset($fields['password']);

			//Format fields if needed
			if($data['password'] != ''){
				$fields['password'] = $this->db->escape(md5(Config::get('salt').$data['password']));
			}

			$now 	= date('Y-m-d h:i:s');

			if(!$center_id){
				$center_id	= (Session::get('center_id') && Session::get('center_id') > 0) ? Session::get('center_id') : 1;
			}

			if(!$id){ //Add New Record
				$sql	= "INSERT INTO `".self::$tbl."` SET ";

				foreach($fields AS $key => $val){
					if($val == 'NOW()') {
						$sql.= "{$key} = {$val},";
					} else {
						$sql.= "{$key} = '{$val}',";
					}
				}

				$sql.= "`created` = '{$now}', `created_by` = ".Session::get('user_id').", `status` = 1";

				$sql = rtrim($sql, ',');
				$sql.= ";";

			} else { //Update Record
				$sql	= "UPDATE ".self::$tbl." SET ";

				foreach($fields AS $key => $val){
					if($val == 'NOW()') {
						$sql.= "{$key} = {$val},";
					} else {
						$sql.= "{$key} = '{$val}',";
					}
				}

				$sql.= "`updated` = '{$now}', `updated_by` = ".Session::get('user_id');

				$sql = rtrim($sql, ',');
				$sql.= " WHERE user_id = {$id};";
			}

			$this->db->query($sql);

			if($id <= 0){
				$id = $this->db->getLastID();
			}

			return $id;
		}

		public function saveProfile($data){
			if(!isset($data['first_name'])){
				return false;
			}

			$id	= Session::get('user_id');

			foreach($data as $key => $val){
				if($key	!= 'savecontbtn' && $key != 'id' && $key != 'savebtn'){
					$fields[$key] = $this->db->escape($val);
				}
			}

			//Unset fields if needed
			unset($fields['cpassword']);
			unset($fields['password']);

			//Format fields if needed
			if($data['password'] != ''){
				$fields['password'] = $this->db->escape(md5(Config::get('salt').$data['password']));
			}

			if(!$id){ //Add New Record
				return false;
			} else { //Update Record
				$sql	= "UPDATE ".self::$tbl." SET ";

				foreach($fields AS $key => $val){
					if($val == 'NOW()') {
						$sql.= "{$key} = {$val},";
					} else {
						$sql.= "{$key} = '{$val}',";
					}
				}

				$now 	= date('Y-m-d h:i:s');
				$center_id	= (Session::get('center_id') && Session::get('center_id') > 0) ? Session::get('center_id') : 1;
				$sql.= "`updated` = '{$now}', `updated_by` = ".Session::get('user_id').",AND center_id = ".$center_id;


				$sql = rtrim($sql, ',');
				$sql.= " WHERE user_id = {$id};";
			}

			$this->db->query($sql);

			return $id;
		}

		public function delete($id){
			$id		= (int)$id;
			$sql	= "DELETE FROM `".self::$tbl."` WHERE user_id = {$id};";

			return $this->db->query($sql);
		}

		public function getRoles(){
			$obj	= new RoleModel();
			return $obj->getList(true);
		}

		public function resetPassword($user_id,$user_email){
			$newPassword = $this->generateRandomString(5);
			$password4Db = $this->db->escape(md5(Config::get('salt').$newPassword));

			if(!$user_id){ //Add New Record
				return false;
			} else { //Update Record
				$sql	= "UPDATE ".self::$tbl." SET ";
				$now 	= date('Y-m-d h:i:s');
				$sql.= "`updated` = '{$now}', `updated_by` = ".$user_id.", `password` = '".$password4Db."'";
				$sql = rtrim($sql, ',');
				$sql.= " WHERE user_id = {$user_id};";
			}

			$result = $this->db->query($sql);

			if($result){
				$body	= "Dear Booking,<br />
				Your password reset successfully!<br />
				Your new password is <b>".$newPassword."</b><br /><br />
				<i>".Config::get('site_name')."</i>";
				return true;
				return $this->sendMail($user_email,'New password - '.Config::get('site_name'),$body);
			}else{
				return false;
			}

			return $id;
		}
	}

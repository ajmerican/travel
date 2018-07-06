<?php
	//https://www.sitepoint.com/role-based-access-control-in-php/
	class RoleModel extends Model{
		private static $tbl = 'roles';

		public function getList($only_published = false){
			$sql	= 'SELECT * FROM `'.self::$tbl.'` WHERE 1 = 1';

			if($only_published){
				$sql.= ' AND `status` = 1';
			}

			return $this->db->query($sql);
		}

		public function getById($id){
			$id		= (int)$id;
			$sql	= "SELECT * FROM `".self::$tbl."` WHERE role_id = '{$id}' LIMIT 1";
			$result = $this->db->query($sql);

			if(isset($result[0])){
				$return	= $result[0];

			} else {
				$return = array(
							'role_id'	=> null,
							'name'		=> '',
							'code'		=> ''
						);
			}

			$return['permissions']	= $this->getPermissionsForRole($id);

			return $return;
		}

		public function getByCode($code){
			$return = array(
						'role_id'	=> null,
						'name'		=> '',
						'code'		=> ''
					);

			$code	= strtolower(trim($code));

			if($code != ''){
				$sql	= "SELECT * FROM `".self::$tbl."` WHERE `code` = '{$code}' LIMIT 1";
				$result = $this->db->query($sql);

				if(isset($result[0])){
					$return	= $result[0];
				}
			}

			return $return;
		}

		public function getPermissionsForRole($id,$permission_id=null){
			$id		= (int)$id;

			$sql	= "SELECT p.*, r.`role_id`, r.`user_permission_id` FROM `permissions` AS p
						LEFT JOIN `role_permissions` AS r ON r.`permission_id`= p.`permission_id` AND r.`role_id` = {$id} ";

			if($permission_id > 0){
				$sql.= ' WHERE p.permission_id = '.$permission_id;
			}

			return $this->db->query($sql);
		}

		public function save($data, $id = null){
			if(!isset($data['name'])){
				return false;
			}

			$id										= (int)$id;

			foreach($data as $key => $val){
				if($key	!= 'savecontbtn' && $key != 'id' && $key != 'savebtn'){
					$fields[$key] = $this->db->escape($val);
				}
			}

			if(isset($data['role_permission'])){
				unset($fields['role_permission']);
			}

			$now 	= date('Y-m-d h:i:s');
			
			if(!$id){ //Add New Record
				$sql	= "INSERT INTO `".self::$tbl."` SET ";

				foreach($fields AS $key => $val){
					$sql.= "{$key} = '{$val}',";
				}

				$sql.= "`created` = '{$now}', `created_by` = ".Session::get('user_id');

				$sql = rtrim($sql, ',');
				$sql.= ";";

			} else { //Update Record
				$sql	= "UPDATE ".self::$tbl." SET ";

				foreach($fields AS $key => $val){
					$sql.= "{$key} = '{$val}',";
				}

				$sql.= "`updated` = '{$now}', `updated_by` = ".Session::get('user_id');

				$sql = rtrim($sql, ',');
				$sql.= " WHERE role_id = {$id};";
			}

			$this->db->query($sql);

			if($id <= 0){
				$id = $this->db->getLastID();
			}

			if($id > 0){
				$this->deletePermissions($id);

				if(isset($data['role_permission']) && count($data['role_permission'])){
					foreach($data['role_permission'] as $key => $val){
						if($data['role_permission'][$key] != ''){
							$this->addPermission($id, $data['role_permission'][$key]);
						}
					}
				}
			}

			return $id;
		}

		private function addPermission($role_id, $permission_id){
			$now 	= date('Y-m-d h:i:s');

			$sql	= "INSERT INTO `role_permissions` SET
							`role_id`		= ".$this->db->escape($role_id).",
							`permission_id`	= '".$this->db->escape($permission_id)."',
							`created`		= '{$now}',
							`created_by` = ".Session::get('user_id');

			return $this->db->query($sql);
		}

		private function deletePermissions($id = null){

			if($id > 0){
				$sql	= 'DELETE FROM `role_permissions` WHERE `role_id` = '.$id;
				return $this->db->query($sql);
			}

			return false;
		}

		public function delete($id){
			$id		= (int)$id;
			$sql	= "DELETE FROM `".self::$tbl."` WHERE role_id = {$id};";

			return $this->db->query($sql);
		}

		public function getUserPermissions(){
			$return	= array();

			if(Session::get('user_id') > 0)
			{
				$sql	= "SELECT * FROM `permissions` WHERE `permission_id` IN (SELECT `permission_id` FROM `role_permissions` WHERE `role_id` IN (SELECT `role_id` FROM `users` WHERE `user_id` = '".Session::get('user_id')."')) ";

				$result = $this->db->query($sql);

				foreach($result AS $key => $val){
					$return[$val['alias']] = $val['name'];
				}
			}

			return $return;
		}
	}

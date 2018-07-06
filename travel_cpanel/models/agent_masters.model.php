<?php
	class Agent_mastersModel extends Model{
		private static $tbl = 'agent_master';

		public function getList($only_published = false){
			$sql	= 'SELECT * FROM `'.self::$tbl.'` WHERE 1';

			if($only_published){
				$sql.= ' AND `status` = 1';
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
							'created_by'	=> '',
'created_date'	=> '',
'updated_by'	=> '',
'updated_date'	=> '',
'agent_code'	=> '',
'agent_name'	=> '',
'agent_mobile'	=> '',
'agent_emailiD'	=> '',
'agent_language'	=> '',
'agent_address1'	=> '',
'agent_address2'	=> '',
'agent_city'	=> '',
'agent_country'	=> '',
'agent_PIN'	=> '',
'agent_aaddress1'	=> '',
'agent_baddress2'	=> '',
'agent_bcity'	=> '',
'agent_bCountry'	=> '',
'agent_accountType'	=> '',
'agent_logo'	=> '',
'agent_active'	=> '',
'agenttype_id'	=> '',
'agent_addressconnection'	=> '',
'agent_connectiontype'	=> '',
'agent_noofusers'	=> '',
'agent_isverify'	=> '',
						);
			}

			$agenttypes	= new Agent_typesModel();
			$atypes			= $agenttypes->getList();

			$arg = array(
						'selected'	=> $return['agenttype_id'],
						'name'		=> 'agenttype_id',
						'val'		=> 'id',
						'text'			=> 'agenttype_name',
						'attr'      => 'required'
					);
			$return['agenttypes']			= HTML::toSelect($atypes,$arg);

			return $return;
		}

		public function save($data, $id = null){
			if(!isset($data['agent_name'])){
				return false;
			}

			$id										= (int)$id;

			foreach($data as $key => $val){
				if($key	!= 'savecontbtn' && $key != 'id' && $key != 'savebtn'){
					$fields[$key] = $this->db->escape($val);
				}
			}

			if(!isset($fields['agent_isverify']) || $fields['agent_isverify'] == ''){
				$fields['agent_isverify']	= 'No';
			}

			if(isset($_FILES['agent_logo'])){
				//Uploads
				$image_name	= $this->upload($_FILES['agent_logo']);

				if($image_name) {
					$fields['agent_logo']			= $image_name;
				}
			}

			if(!$id){ //Add New Record
				$sql	= "INSERT INTO `".self::$tbl."` SET ";

				foreach($fields AS $key => $val){
					$sql.= "{$key} = '{$val}',";
				}

				$sql.= "`created` = NOW(), `created_by` = ".Session::get('user_id').",";

				$sql = rtrim($sql, ',');
				$sql.= ";";

			} else { //Update Record
				$sql	= "UPDATE ".self::$tbl." SET ";

				foreach($fields AS $key => $val){
					$sql.= "{$key} = '{$val}',";
				}

				$sql.= "`updated` = NOW(), `updated_by` = ".Session::get('user_id').",";

				$sql = rtrim($sql, ',');
				$sql.= " WHERE id = {$id};";
			}

			//echo $sql;
			//exit;
			$this->db->query($sql);

			if($id <= 0){
				$id = $this->db->getLastID();
			}

			return $id;
		}

		public function delete($id){
			$id		= (int)$id;
			$sql	= "DELETE FROM `".self::$tbl."` WHERE id = {$id};";

			return $this->db->query($sql);
		}
	}

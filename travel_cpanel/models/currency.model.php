<?php
	class CurrencyModel extends Model{
		private static $tbl = 'currencies';

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
							'name'	=> '',
							'symbol'	=> '',
							'code'	=> '',
							'rate'	=> '',
							'decimals'	=> '',
							'symbol_placement'	=> '',
							'primary_order'	=> '',
							'is_default'	=> '',
							'is_active'	=> '',
						);
			}

			return $return;
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

			if(!isset($fields['is_default']) || $fields['is_default'] == ''){
				$fields['is_default']	= 'No';
			}else{
				$fields['is_default']	= 'Yes';
			}

			if(!isset($fields['is_active']) || $fields['is_active'] == ''){
				$fields['is_active']	= 'No';
			}else{
				$fields['is_active']	= 'Yes';
			}

			if(!$id){ //Add New Record
				$sql	= "INSERT INTO `".self::$tbl."` SET ";

				foreach($fields AS $key => $val){
					$sql.= "{$key} = '{$val}',";
				}

				$sql = rtrim($sql, ',');
				$sql.= ";";

			} else { //Update Record
				$sql	= "UPDATE ".self::$tbl." SET ";

				foreach($fields AS $key => $val){
					$sql.= "{$key} = '{$val}',";
				}

				$sql = rtrim($sql, ',');
				$sql.= " WHERE id = {$id};";
			}

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

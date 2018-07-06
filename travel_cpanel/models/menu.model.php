<?php
	class MenuModel extends Model{
		private static $tbl = 'menus';

		public function getList($only_published = false, $parent_id = null){
			$sql	= 'SELECT * FROM `'.self::$tbl.'` WHERE 1';

			if($only_published){
				$sql.= ' AND `status` = 1';
			}

			if($parent_id !== null){
				$sql.= ' AND `parent_id` = '.$parent_id;
			}
			//echo $sql;
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
							'title'	=> '',
							'order'	=> '',
							'link'	=> '',
							'parent_id'	=> 0,
						);
			}

			$records= $this->getList();

			$arg = array(
						'selected'=> $return['parent_id'],
						'name'		=> 'parent_id',
						'text'		=> 'title',
					);
			$return['menus']			= HTML::toSelect($records,$arg);

			return $return;
		}

		public function save($data, $id = null){
			if(!isset($data['title'])){
				return false;
			}

			$id										= (int)$id;

			foreach($data as $key => $val){
				if($key	!= 'savecontbtn' && $key != 'id' && $key != 'savebtn'){
					$fields[$key] = $this->db->escape($val);
				}
			}

			//Format fields if needed
			if($data['parent_id'] == ''){
				$fields['parent_id'] = 0;
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

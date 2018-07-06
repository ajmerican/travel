<?php
	class CouponModel extends Model{
		private static $tbl = 'coupons';

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
				$return['startdate']	= date('d/m/Y',strtotime($result[0]['startdate']));
$return['expirationdate']	= date('d/m/Y',strtotime($result[0]['expirationdate']));

			} else {
				$return = array(
							'id'					=> null,
							'code'	=> '',
'value'	=> '',
'startdate'	=> '',
'expirationdate'	=> '',
'maxuses'	=> '',
'uses'	=> '',
'forever'	=> '',

						);
			}

			return $return;
		}

		public function save($data, $id = null){
			if(!isset($data['code'])){
				return false;
			}

			$id										= (int)$id;

			foreach($data as $key => $val){
				if($key	!= 'savecontbtn' && $key != 'id' && $key != 'savebtn'){
					$fields[$key] = $this->db->escape($val);
				}
			}

			$fields['startdate']	= $this->db->escape(date('Y-m-d',strtotime(str_replace('/','-',$data['startdate']))));
$fields['expirationdate']	= $this->db->escape(date('Y-m-d',strtotime(str_replace('/','-',$data['expirationdate']))));


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

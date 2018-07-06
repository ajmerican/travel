<?php
	class Special_offerModel extends Model{
		private static $tbl = 'special_offers';

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
				$return['offer_from']	= date('d/m/Y',strtotime($result[0]['offer_from']));
				$return['offer_to']	= date('d/m/Y',strtotime($result[0]['offer_to']));
			} else {
				$return = array(
							'id'					=> null,
							'title'	=> '',
'description'	=> '',
'price'	=> '',
'offer_from'	=> '',
'offer_to'	=> '',
'forever'	=> '',
'slug'	=> '',
'offer_order'	=> '',
'thumb'	=> '',
'phone'	=> '',
'email'	=> '',

						);
			}

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

			if(isset($_FILES['thumb'])){
				//Uploads
				$image_name	= $this->upload($_FILES['thumb']);

				if($image_name) {
					$fields['thumb']			= $image_name;
				}
			}

			$fields['offer_from']	= $this->db->escape(date('Y-m-d',strtotime(str_replace('/','-',$data['offer_from']))));
			$fields['offer_to']	= $this->db->escape(date('Y-m-d',strtotime(str_replace('/','-',$data['offer_to']))));

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

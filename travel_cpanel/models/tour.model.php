<?php
	class TourModel extends Model{
		private static $tbl = 'tours';

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
				$return['featured_from']	= date('d/m/Y',strtotime($result[0]['featured_from']));
				$return['featured_to']	= date('d/m/Y',strtotime($result[0]['featured_to']));
			} else {
				$return = array(
							'id'					=> null,
							'title'	=> '',
'slug'	=> '',
'description'	=> '',
'stars'	=> '',
'is_featured'	=> '',
'featured_from'	=> '',
'featured_to'	=> '',
'owned_by'	=> '',
'type'	=> '',
'location'	=> '',
'latitude'	=> '',
'longitude'	=> '',
'mapaddress'	=> '',
'basic_price'	=> '',
'basic_discount'	=> '',
'max_adults'	=> '',
'max_child'	=> '',
'max_infant'	=> '',
'adult_price'	=> '',
'child_price'	=> '',
'infant_price'	=> '',
'adult_status'	=> '',
'child_status'	=> '',
'infant_status'	=> '',
'days'	=> '',
'nights'	=> '',
'meta_title'	=> '',
'meta_keywords'	=> '',
'meta_desc'	=> '',
'amenities'	=> '',
'exclusions'	=> '',
'payment_opt'	=> '',
'privacy'	=> '',
'tour_order'	=> '',
'related'	=> '',
'comm_fixed'	=> '',
'comm_percentage'	=> '',
'tax_fixed'	=> '',
'tax_percentage'	=> '',
'email'	=> '',
'phone'	=> '',
'website'	=> '',
'fulladdress'	=> '',
'featured_forever'	=> '',
'thumbnail_image'	=> '',
'module'	=> '',

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

			if(!isset($fields['is_featured']) || $fields['is_featured'] == ''){
				$fields['is_featured']	= 'No';
			}else{
				$fields['is_featured']	= 'Yes';
			}

			if(isset($_FILES['thumbnail_image'])){
				//Uploads
				$image_name	= $this->upload($_FILES['thumbnail_image']);

				if($image_name) {
					$fields['thumbnail_image']			= $image_name;
				}
			}

			$fields['featured_from']	= $this->db->escape(date('Y-m-d',strtotime(str_replace('/','-',$data['featured_from']))));
			$fields['featured_to']	= $this->db->escape(date('Y-m-d',strtotime(str_replace('/','-',$data['featured_to']))));

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

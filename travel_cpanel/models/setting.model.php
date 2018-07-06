<?php
	class SettingModel extends Model{
		private static $tbl = 'settings';

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
							'user'	=> '',
'site_title'	=> '',
'home_title'	=> '',
'site_url'	=> '',
'ssl_url'	=> '',
'tag_line'	=> '',
'site_name'	=> '',
'address'	=> '',
'phone'	=> '',
'copyright'	=> '',
'seo_status'	=> '',
'keywords'	=> '',
'meta_description'	=> '',
'header_logo_img'	=> '',
'footer_logo_img'	=> '',
'favicon_img'	=> '',
'currency_sign'	=> '',
'currency_code'	=> '',
'google'	=> '',
'mapApi'	=> '',
'javascript'	=> '',
'reviews'	=> '',
'gallery_approve'	=> '',
'video_uploads'	=> '',
'default_lang'	=> '',
'multi_lang'	=> '',
'date_f'	=> '',
'date_f_js'	=> '',
'license_key'	=> '',
'local_key'	=> '',
'secret_key'	=> '',
'default_city'	=> '',
'default_theme'	=> '',
'offline_message'	=> '',
'site_offline'	=> '',
'multi_curr'	=> '',
'booking_expiry'	=> '',
'booking_cancellation'	=> '',
'coupon_code_length'	=> '',
'coupon_code_type'	=> '',
'secure_admin_key'	=> '',
'secure_admin_status'	=> '',
'secure_supplier_key'	=> '',
'secure_supplier_status'	=> '',
'allow_registration'	=> '',
'user_reg_approval'	=> '',
'allow_supplier_registration'	=> '',
'default_gateway'	=> '',
'searchbox'	=> '',
'rss'	=> '',
'updates_check'	=> '',
'restrict_website'	=> '',
'mobile_pic_status'	=> '',
'mobile_pic_url'	=> '',
'mobile_redirect_status'	=> '',
'mobile_redirect_url'	=> '',
'mobileApiKey'	=> '',

						);
			}

			return $return;
		}

		public function save($data, $id = null){
			$id										= (int)$id;

			foreach($data as $key => $val){
				if($key	!= 'savecontbtn' && $key != 'id' && $key != 'savebtn'){
					$fields[$key] = $this->db->escape($val);
				}
			}

			if(isset($_FILES['header_logo_img'])){
				//Uploads
				$image_name	= $this->upload($_FILES['header_logo_img']);

				if($image_name) {
					$fields['header_logo_img']			= $image_name;
				}
			}

			if(isset($_FILES['footer_logo_img'])){
				//Uploads
				$image_name	= $this->upload($_FILES['footer_logo_img']);

				if($image_name) {
					$fields['footer_logo_img']			= $image_name;
				}
			}

			if(isset($_FILES['favicon_img'])){
				//Uploads
				$image_name	= $this->upload($_FILES['favicon_img']);

				if($image_name) {
					$fields['favicon_img']			= $image_name;
				}
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

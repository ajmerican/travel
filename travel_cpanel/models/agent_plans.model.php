<?php
	class Agent_plansModel extends Model{
		private static $tbl = 'agent_plan';

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
				$return['aplan_activatedate']	= date('d/m/Y',strtotime($result[0]['aplan_activatedate']));
				$return['aplan_expiredate']	= date('d/m/Y',strtotime($result[0]['aplan_expiredate']));

			} else {
				$return = array(
							'id'					=> null,
'category_id'	=> '',
'agent_ID'	=> 0,
'aplan_activatedate'	=> '',
'aplan_expiredate'	=> '',
'aplan_commission'	=> '',
'aplan_valuelimit'	=> '',
'aplan_balance'	=> '',

						);
			}

			$catObj	= new CategorysModel();
			$cats			= $catObj->getList();

			$arg = array(
						'selected'	=> $return['category_id'],
						'name'		=> 'category_id',
						'attr'      => 'required'
					);
			$return['categories']			= HTML::toSelect($cats,$arg);

			$obj		= new Agent_mastersModel();
			$records= $obj->getList();

			$arg = array(
						'selected'=> $return['agent_ID'],
						'name'		=> 'agent_ID',
						'text'		=> 'agent_name',
						'attr'    => 'required'
					);
			$return['agents']			= HTML::toSelect($records,$arg);

			return $return;
		}

		public function save($data, $id = null){
			if(!isset($data['agent_ID'])){
				return false;
			}

			$id										= (int)$id;

			foreach($data as $key => $val){
				if($key	!= 'savecontbtn' && $key != 'id' && $key != 'savebtn'){
					$fields[$key] = $this->db->escape($val);
				}
			}

			$fields['aplan_activatedate']	= $this->db->escape(date('Y-m-d',strtotime(str_replace('/','-',$data['aplan_activatedate']))));
$fields['aplan_expiredate']	= $this->db->escape(date('Y-m-d',strtotime(str_replace('/','-',$data['aplan_expiredate']))));


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

<?php
	require_once 'database.class.php';

	class MySqliDB extends DBBase
	{
		public function __construct() {
			$this->connect();
		}

		public function __destruct() {
			$this->close();
		}

		public function connect() {
			$this->link = new mysqli($this->host, $this->user, $this->pass, $this->dbname, $this->port);

			if($this->link->connect_errno)
			{
				$subject	= 'MySQLi Connection fail';
				$body		= "Failed to connect to MySQL: (" . $this->link->connect_errno . ") " . $this->link->connect_error;

				$this->reportError($subject, $body);
				return false;
			}

			$this->link->set_charset("utf8");
		}

		private function reportError($subject, $body)
		{
			global $lib;
			//$lib->sendAdminMail('Sheds Direct Error', $subject.'<br />'.$body);
			echo 'Sheds Direct Error', $subject, '<br />' , $body;
		}

		public function escape($str=''){
			return $this->link->real_escape_string($str);
		}

		public function errno() {
			return $this->link->errno;
		}

		public function error() {
			return $this->link->error;
		}

		/*public function query($query) {
			$this->last_sql = $query;
			return $this->link->query($query);
		}*/

		public function query($sql){
			if(!$this->link){
				return false;
			}
			$this->last_sql = $sql;
			$result = $this->link->query($sql);

			if(mysqli_error($this->link)){
				throw new Exception(mysqli_error($this->link));
			}

			if(is_bool($result)){
				return $result;
			}

			$data	= array();

			while($row = mysqli_fetch_assoc($result)){
				$data[]	= $row;
			}

			return $data;
		}

		public function fetch_array($result, $array_type = MYSQL_BOTH) {
			return $result->fetch_array($array_type);
			/* free result set after using this function*/
			$result->free();
		}

		public function fetch_row($result) {
			return $result->fetch_row();
			/* free result set  after using this function*/
			$result->close();
		}

		public function fetch_assoc($result) {
			return $result->fetch_assoc();
			/* free result set  after using this function*/
			$result->free();
		}

		public function fetch_object($result)  {
			return $result->fetch_object();
			/* free result set  after using this function*/
			$result->close();
		}

		public function fetch_all($result, $array_type = MYSQL_BOTH)  {
			$rows = $result->fetch_all($array_type);
			/* free result set */
			$result->close();

			return $rows;
		}

		public function num_rows($result) {
			return $result->num_rows;
			/* close result set  after using this function*/
			$result->close();
		}

		public function update($tbl, $paras = array(), $where = '')
		{
			if($where == ''){
				return false;
			}

			$stmt	= $this->link->stmt_init();

			$query	= 'UPDATE `'.$tbl.'` SET ';

			$types	= array();
			$i		= 1;

			foreach($paras AS $key => $val)
			{
				/* Bind parameters. Types: s = string, i = integer, d = double,  b = blob */
				$types[] = $val['type'];

				$bind_name = 'bind' . $i;
				$$bind_name = $this->link->real_escape_string($val['value']);

				/* $subject	= 'Debug only';
				$body		= $query.' and PARA = '.print_r($paras,true).' ON fetch_from_SP()';
				$this->reportError($subject, $body); */

				$values[$i] = &$$bind_name;

				$query.= '`'.$val['field'].'` = ?,';

				$i++;
			}

			$query	= rtrim($query,',').' WHERE '.$where.';';

			if($stmt->prepare($query))
			{
				if(is_array($paras) && count($paras) > 0)
				{
					$values[0] = implode($types);

					ksort($values);
					call_user_func_array(array($stmt,'bind_param'),$values);
				}

				if(!$stmt->execute())
				{
					$subject	= 'Execute Statement Fail';
					$body		= "MySQL Error: (" . $this->errno() . ") " . $this->error().' ON '.$query.' and PARA = '.print_r($paras,true).' ON update()';
					$this->reportError($subject, $body);
				}

				$stmt->close();
			}
			else
			{
				$subject	= 'Prepare Statement Fail';
				$body		= "MySQL Error: (" . $this->errno() . ") " . $this->error().' ON '.$query.' and PARA = '.print_r($paras,true).' ON update()';
				$this->reportError($subject, $body);
			}

			return true;
		}

		public function insert($tbl, $paras = array())
		{
			$stmt	= $this->link->stmt_init();
			$newId= 0;
			$query= 'INSERT INTO `'.$tbl.'` SET ';

			$types	= array();
			$i		= 1;

			foreach($paras AS $key => $val)
			{
				/* Bind parameters. Types: s = string, i = integer, d = double,  b = blob */
				$types[] = $val['type'];

				$bind_name = 'bind' . $i;
				$$bind_name = $this->link->real_escape_string($val['value']);

				/* $subject	= 'Debug only';
				$body		= $query.' and PARA = '.print_r($paras,true).' ON fetch_from_SP()';
				$this->reportError($subject, $body); */

				$values[$i] = &$$bind_name;

				$query.= '`'.$val['field'].'` = ?,';

				$i++;
			}

			$query	= rtrim($query,',').';';

			if($stmt->prepare($query))
			{
				if(is_array($paras) && count($paras) > 0)
				{
					$values[0] = implode($types);

					ksort($values);
					call_user_func_array(array($stmt,'bind_param'),$values);
				}

				if(!$stmt->execute())
				{
					$subject	= 'Execute Statement Fail';
					$body		= "MySQL Error: (" . $this->errno() . ") " . $this->error().' ON '.$query.' and PARA = '.print_r($paras,true).' ON insert()';
					$this->reportError($subject, $body);
				}else{
					$newId = $stmt->insert_id;
				}

				$stmt->close();
			}
			else
			{
				$subject	= 'Prepare Statement Fail';
				$body		= "MySQL Error: (" . $this->errno() . ") " . $this->error().' ON '.$query.' and PARA = '.print_r($paras,true).' ON update()';
				$this->reportError($subject, $body);
			}

			return $newId;
		}

		public function execSP($query,$paras = array())
		{
			$stmt	= $this->link->stmt_init();
			$return = false;

			if($stmt->prepare($query))
			{
				if(is_array($paras) && count($paras) > 0)
				{
					$types	= array();
					$i		= 1;

					foreach($paras AS $key => $val)
					{
						/* Bind parameters. Types: s = string, i = integer, d = double,  b = blob */
						$types[] = $val['type'];

						$bind_name = 'bind' . $i;

						$$bind_name = $this->link->real_escape_string($val['value']);
						$values[$i] = &$$bind_name;

						$i++;
					}

					$values[0] = implode($types);

					ksort($values);
					call_user_func_array(array($stmt,'bind_param'),$values);
				}

				if($stmt->execute())
				{
					$return = true;
				}
				else
				{
					$subject	= 'Execute Statement Fail';
					$body		= "MySQL Error: (" . $this->errno() . ") " . $this->error().' ON '.$query.' with PARA = '.print_r($paras,true).' ON execSP()';
					$this->reportError($subject, $body);
				}

				while ($this->link->more_results()) // flush multi_queries
				{
				  if (!$this->link->next_result()) break;
				}

				$stmt->close();
			}
			else
			{
				$subject	= 'Prepare Statement Fail';
				$body		= "MySQL Error: (" . $this->errno() . ") " . $this->error().' ON '.$query.' and PARA = '.print_r($paras,true).' ON execSP()';
				$this->reportError($subject, $body);
			}

			return $return;
		}

		public function fetch_from_SP($query,$paras = array())
		{
			$stmt	= $this->link->stmt_init();
			$ret	= array();

			if($stmt->prepare($query))
			{
				if(is_array($paras) && count($paras) > 0)
				{
					$types	= array();
					$i		= 1;

					foreach($paras AS $key => $val)
					{
						/* Bind parameters. Types: s = string, i = integer, d = double,  b = blob */
						$types[] = $val['type'];

						$bind_name = 'bind' . $i;
						$$bind_name = $this->link->real_escape_string($val['value']);

						/* $subject	= 'Debug only';
						$body		= $query.' and PARA = '.print_r($paras,true).' ON fetch_from_SP()';
						$this->reportError($subject, $body); */

						$values[$i] = &$$bind_name;

						$i++;
					}

					$values[0] = implode($types);

					ksort($values);
					call_user_func_array(array($stmt,'bind_param'),$values);
				}

				if($stmt->execute())
				{
					$stmt->store_result();

					$meta = $stmt->result_metadata();
					$var2	= array();

					while ($field = $meta->fetch_field()) {
						$var = $field->name;
						$var2[] = $field->name;
						$$var = null;
						$parameters[$field->name] = &$$var;
					}

					if($this->link->more_results())
					call_user_func_array(array($stmt, 'bind_result'), $parameters);

					$i		= 0;

					while($stmt->fetch())
					{
						//return $parameters;
						foreach($var2 AS $key => $val)
						{
							$ret[$i][$val] = $parameters[$val];
						}

						$i++;
					}
				}
				else
				{
					$subject	= 'Execute Statement Fail';
					$body		= "MySQL Error: (" . $this->errno() . ") " . $this->error().' ON '.$query.' and PARA = '.print_r($paras,true).' ON fetch_from_SP()';
					$this->reportError($subject, $body);
				}

				while ($this->link->more_results()) // flush multi_queries
				{
				  if (!$this->link->next_result()) break;
				}

				$stmt->close();
			}
			else
			{
				$subject	= 'Prepare Statement Fail';
				$body		= "MySQL Error: (" . $this->errno() . ") " . $this->error().' ON '.$query.' and PARA = '.print_r($paras,true).' ON fetch_from_SP()';
				$this->reportError($subject, $body);
			}

			return $ret;
		}

		public function close() {
			return $this->link->close();
		}
	}

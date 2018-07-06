<?php
	class DB{
		protected $connection;
		
		public function __construct($host, $user, $password, $db_name){
			$this->connection = new mysqli($host, $user, $password, $db_name);
			
			if($this->connection->connect_errno){				
				throw new Exception('MySQLi Connection fail!');
			}
		}
		
		public function query($sql){
			if(!$this->connection){
				return false;
			}
			
			$result = $this->connection->query($sql);
			
			if(mysqli_error($this->connection)){
				throw new Exception(mysqli_error($this->connection));
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
		
		public function escape($str){
			return mysqli_real_escape_string($this->connection,$str);
		}
		
		public function getLastID(){
			if(!$this->connection){
				return false;
			}
			
			return $this->connection->insert_id;
		}
	}
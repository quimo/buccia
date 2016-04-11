<?php
	
	class Demo extends Database {
	
		public function __construct() {
			$conn = Connection::xml('db');
			$this->db = parent::__construct($conn);
		}
		
		public function getAllData() {
			return $this->query("SELECT * FROM users");
		}
		
		
	}
?>	
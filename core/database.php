<?php

/*
	
 .o8                                        o8o            
"888                                        `"'            
 888oooo.  oooo  oooo   .ooooo.   .ooooo.  oooo   .oooo.   
 d88' `88b `888  `888  d88' `"Y8 d88' `"Y8 `888  `P  )88b  
 888   888  888   888  888       888        888   .oP"888  
 888   888  888   888  888   .o8 888   .o8  888  d8(  888  
 `Y8bod8P'  `V88V"V8P' `Y8bod8P' `Y8bod8P' o888o `Y888""8o 
                                                           
*/ 

	class Database {
	
		private $db;
	
		protected function __construct($conn) {
			$this->db = $conn;
			return $this->db;
		}
		
		/* 
			insert 
			inserisce un record 	
		*/
		protected function insert($table, $data, $id=0, $idKey='') {
			$fields = ($id) ?  $idKey.', ' : '';
			$question = ($id) ? '?, ' : '';
			$values = array();
			if ($id) $values[] = $id;
			foreach ($data as $key => $value) {
				$fields .= $key.", ";
				$question .= "?, ";
				$values[] = $value;
			}	
			$fields = substr($fields,0,-2);
			$question = substr($question,0,-2);
			$query = "INSERT INTO $table ($fields) VALUES ($question)";
			$this->db->prepare($query)->execute($values);
			if (!$id) $id = $this->db->lastInsertId();
			return $id;
		}
		
		/* 	
			update
			modifica un record 	
		*/
		protected function update($table, $data, $id, $idKey = 'id') {
			$fields = '';
			$values = array();
			foreach ($data as $key => $value) {
				$fields .= "$key = ?, ";
				$values[] = $value;
			}
			$fields = substr($fields,0,-2);
			$query = "UPDATE $table SET $fields WHERE $idKey = $id";
			return $this->db->prepare($query)->execute($values);
		}
		
		/* 	delete
			elimina un seti di record 	
		*/
		protected function delete($table, $ids) {
			if (!$ids) return false;
			$query = "DELETE FROM $table WHERE id IN ($ids)";
			return $this->db->prepare($query)->execute();
		}
		
		/* 	
			search
			controlla se i dati passati sono presenti
			con fetchColumn(0) si assume che la prima colonna sia l'id della tabella
		*/
		protected function search($table, $data) {
			$string = '1 AND ';
			$values = array();
			foreach ($data as $key => $value) {
				$string .= "$key = ? AND ";
				$values[] = $value;
			}
			$string = substr($string,0,-5);
			$query = "SELECT * FROM $table WHERE $string LIMIT 1";
			$prepared = $this->db->prepare($query);
			$prepared->execute($values);
			$id = $prepared->fetchColumn(0);
			if ($id !== false) return $id;
			return false;
		}
		
		/* 	
			query
			recupera i dati
		*/
		protected function query($query) {
			$dummy = array();
			$prepared = $this->db->prepare($query);
			$prepared->execute();
			 while ($row = $prepared->fetch(PDO::FETCH_ASSOC)) {
				$dummy[] = $row;
			 }
			if (!empty($dummy)) return $dummy;
			return false;
		}
		
		/*	
			count
			ritorna la dimensione del recordset estratto dalla query
		*/
		protected function count($table, $where='') {
			$where_sentence = " WHERE 1";
			if ($where) $where_sentence .= " AND $where";
			$prepared = $this->db->prepare("SELECT COUNT(*) as number FROM {$table}{$where_sentence}");
			$prepared->execute();
			while ($row = $prepared->fetch(PDO::FETCH_ASSOC)) {
				return $row['number'];
			}
		}
		
	}
?>
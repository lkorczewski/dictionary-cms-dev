<?php

namespace DCMS;

use \Database\Database;

require_once 'database/database.php';

class Data {
	
	protected $database;
	
	//------------------------------------------------------------------------
	// constructor
	//------------------------------------------------------------------------
	
	function __construct(Database $database){
		$this->database = $database;
	}
	
	function create_storage(&$log){
		$actions = [
			'create_editor_storage',
			'fill_editor_storage',
		];
		
		// allowing continuing previous logs
		if(!is_array($log)){
			$log = [];
		}
		
		foreach($actions as $action){
			$result = $this->$action();
			$log[] = [
				'action' => $action,
				'result' => $result,
			];
			
			if($result === false){
				return false;
			}
		}
		
		return true;
	}
	
	//------------------------------------------------------------------------
	// creating editor storage
	//------------------------------------------------------------------------
	
	function create_editor_storage(){
		$query =
			'CREATE TABLE IF NOT EXISTS `editors` (' .
			'  `editor_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT \'editor identifier\',' .
			'  `login` varchar(32) COLLATE utf8_bin NOT NULL COMMENT \'login\',' .
			'  `password` varchar(40) COLLATE utf8_bin NOT NULL COMMENT \'password\',' .
			'  `adding_users` tinyint(1) NOT NULL COMMENT \'permission to add users\',' .
			'  PRIMARY KEY (`editor_id`),' .
			'  KEY `login` (`login`)' .
			') ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin' .
			';';
		$result = $this->database->execute($query);
		
		return $result;
	}
	
	//------------------------------------------------------------------------
	// fill editor storage
	//------------------------------------------------------------------------
	
	function fill_editor_storage(){
		
		$result = $this->add_editor('super_editor', 'dictionary', 1);
		
		return $result;
	}
	
	//------------------------------------------------------------------------
	// getting editor
	//------------------------------------------------------------------------
	
	function get_editor($login, $password){
		
		$hashed_password = $this->hash_password($password);
		
		$query =
			'SELECT *' .
			' FROM editors' .
			" WHERE login = '{$this->database->escape_string($login)}'" .
			"  AND password = '$hashed_password'" .
			';';
		$result = $this->database->fetch_one($query);
		
		if($result === false) return false;
		
		return $result;
	}
	
	//------------------------------------------------------------------------
	
	function add_editor($editor, $password, $adding_users){
		
		$hashed_password = $this->hash_password($password);
				
		$query =
			'INSERT INTO `editors` (`login`, `password`, `adding_users`) VALUES' .
			' (' .
				'\'' . $this->database->escape_string($editor) . '\',' .
				'\'' . $this->database->escape_string($hashed_password) . '\',' .
				($adding_users === 1 ? '1' : '0') .
			' )' .
			';';
		$result = $this->database->execute($query);
		
		return $result;
	}
	
	//------------------------------------------------------------------------
	
	function delete_editor($editor, $password){
		/* to do */
	}
	
	//------------------------------------------------------------------------
	
	private function hash_password($password){
		return sha1($password);
	}
	
}


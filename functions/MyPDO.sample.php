<?php

class MyPDO extends PDO{
	
	private $_dsn = 'sqlsrv:server=localhost;database=database;Encrypt=false';
	private $_user = 'user';
	private $_passwd = 'password';
	private $_encode = 'UTF-8';
	private $_stmt;
	private $_success;

	function __construct(){
		try{	
			parent::__construct($this->_dsn, $this->_user, $this->_passwd);
			$this->_setEncode();
		}catch(Exception $e){
			print_r($e);
		}	
	}
	
	private function _setEncode(){
		//$this->query("SET NAMES '{$this->_encode}'");
		ini_set('mssql.charset', $this->_encode);
		ini_set( 'display_errors', 0 );
	}
	
	function bindQuery($sql, array $bind = []){
		$this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$this->_stmt = $this->prepare($sql);
		$this->_bind($bind);
		$this->_stmtStat = $this->_stmt->execute();
		
		// PHP8.1
		if(str_starts_with(strtoupper($sql),'SELECT')){
			return $this->_stmt->fetchAll();
		}
	}
	
	function bindQueryNextRowset($sql, array $bind = []){
		$this->_stmt = $this->prepare($sql);
		$this->_bind($bind);
		$this->_stmt->nextRowset();
		return $this->_stmt->fetchAll();
	}
	
	private function _bind($bind){
		foreach($bind as $key => $value){
			$this->_stmt->bindValue($key, $value, is_numeric($value)?PDO::PARAM_INT:PDO::PARAM_STR);
		}
	}
		
	function GetStmtStat(){
		return $this->_stmtStat;
	}
	
	function error(){
		$error = $this->_stmt->errorInfo();
		echo 'errorCode: '.$error[0].'</br>';
		echo 'errorString: '.$error[2].'</br>';
	}
	
}

?>
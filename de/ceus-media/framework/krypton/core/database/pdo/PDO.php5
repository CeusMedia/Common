<?php
/**
 *	This is a Code-Completion only file
 *	(For use with ZDE or other IDEs)
 *
 *	Do NOT include() or require() this to your code
 *
 *	@author			Matthias Leuffen
 *	@package		mv2.core.database
 */
/**
 *	This is a Code-Completion only file
 *	(For use with ZDE or other IDEs)
 *
 *	Do NOT include() or require() this to your code
 *
 *	@author			Matthias Leuffen
 *	@package		mv2.core.database
 */
class PDO
{
	/**
	  * Error Constants
	  *
	  */
	const ERR_ALREADY_EXISTS = 0;
	const ERR_CANT_MAP = 0;
	const ERR_NOT_FOUND = 0;
	const ERR_SYNTAX = 0;
	const ERR_CONSTRAINT = 0;
	const ERR_MISMATCH = 0;
	const ERR_DISCONNECTED = 0;
	const ERR_NONE = 0;

	/**
	  * Attributes (to use in PDO::setAttribute() as 1st Parameter)
	  *
	  */
	const ATTR_ERRMODE = 0;
	const ATTR_TIMEOUT = 0;
	const ATTR_AUTOCOMMIT = 0;
	const ATTR_PERSISTENT = 0;

	// Values for ATTR_ERRMODE
	const ERRMODE_EXCEPTION = 0;
	const ERRMODE_WARNING = 0;

	const FETCH_ASSOC = 0;
	const FETCH_NUM = 0;
	const FETCH_OBJ = 0;

	public function __construct($uri, $user, $pass, $optsArr) {
	}

	/**
	  * Prepare Statement: Returns PDOStatement
	  *
	  * @param string $prepareString
	  * @return PDOStatement
	  */
	public function prepare ($prepareString) {
	}
	public function query ($queryString) {
	}
	public function quote ($input) {
	}
	public function exec ($statement) {
	}
	public function lastInsertId() {
	}
	public function beginTransaction () {
	}
	public function commit () {
	}
	public function rollBack () {
	}
	public function errorCode () {
	}
	public function errorInfo () {
	}	
   }
  
class PDOStatement
{
	public function bindValue ($no, $value) {
	}
	public function fetch () {
	}
	public function nextRowset () {
	}
	public function execute() {
	}
	public function errorCode () {
	}
	public function errorInfo () {
	}
	public function rowCount () {
	}
	public function setFetchMode ($mode) {
	}
	public function columnCount () {
	}
}
?>

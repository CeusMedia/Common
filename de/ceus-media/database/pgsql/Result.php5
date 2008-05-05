<?php
/**
 *	PostgreSQLResult
 *
 *	@package		database
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.4
 */
/**
 *	PostgreSQLResult
 *
 *	@package		database
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.4
 */
class Database_pgSQL_Result
{
	/**	@var	int		current		pointer for fetched rows */
	/**	@var	array	object		list of all fetched rows */
	var $current;
	var $objects;

	/**
	 *	Constructor.
	 *
	 *	@access		public
	 *	@return		void
	 */
	function __construct()
	{
		$this->current = 0;
		$this->objects = array ();
	}

	/**
	 *	Returns found row in this result.
	 *
	 *	@access		public
	 *	@return		Object
	 */
	function FetchObject ()
	{
		return $this->objects [$this->current];
	}

	/**
	 *	Returns next found row in this result.
	 *
	 *	@access		public
	 *	@return		Object
	 */
	function FetchNextObject ()
	{
		if ($this->current < $this->RecordCount ())
		{
			$this->current ++;
			return $this->objects [$this->current-1];
		}
		else return false;
	}

	/**
	 *	Returns the number found rows in this result.
	 *
	 *	@access		public
	 *	@return		int
	 */
	function RecordCount ()
	{
		return sizeof ($this->objects);
	}
}
?>
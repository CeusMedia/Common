<?php
/**
 *	MySQL Result.
 *	@package		database.mysql
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.6
 */
/**
 *	MySQL Result.
 *	@package		database.mysql
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.6
 */
class Database_MySQL_Result
{
	/**	@var	int		current		pointer for fetched rows */
	protected $current;
	/**	@var	array	object		list of all fetched rows */
	public $objects;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->current = 0;
		$this->objects = array();
	}

	/**
	 *	Returns next found row in this result.
	 *	@access		public
	 *	@return		Object
	 */
	public function fetchArray()
	{
		if( isset( $this->objects[$this->current] ) )
		{
			$obj = $this->objects[$this->current];
			return $obj->getPairs();
		}
		else return false;
	}

	/**
	 *	Returns next found row in this result.
	 *	@access		public
	 *	@return		Object
	 */
	public function fetchNextArray()
	{
		if( $this->current < $this->RecordCount() )
		{
			$this->current ++;
			$obj = $this->objects[$this->current-1];
			return $obj->getPairs();
		}
		else return false;
	}

	/**
	 *	Returns next found row in this result.
	 *	@access		public
	 *	@return		Object
	 */
	public function fetchNextObject()
	{
		if( $this->current < $this->RecordCount() )
		{
			$this->current ++;
			return $this->objects[$this->current-1];
		}
		else return false;
	}

	/**
	 *	Returns next found row in this result.
	 *	@access		public
	 *	@return		Object
	 */
	public function fetchNextRow()
	{
		if( $this->current < $this->RecordCount() )
		{
			$this->current ++;
			$obj = $this->objects[$this->current-1];
			return $obj->getValues();
		}
		else return false;
	}

	/**
	 *	Returns found row in this result.
	 *	@access		public
	 *	@return		Object
	 */
	public function fetchObject()
	{
		if( isset( $this->objects [$this->current] ) )
			return $this->objects [$this->current];
		return false;
	}

	/**
	 *	Returns next found row in this result.
	 *	@access		public
	 *	@return		Object
	 */
	public function fetchRow()
	{
		if( isset( $this->objects[$this->current] ) )
		{
			$obj = $this->objects[$this->current];
			return $obj->getValues();
		}
		else return false;
	}

	/**
	 *	Returns the number found rows in this result.
	 *	@access		public
	 *	@return		int
	 */
	public function recordCount()
	{
		return count( $this->objects );
	}
}
?>
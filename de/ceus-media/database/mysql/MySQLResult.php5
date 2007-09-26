<?php
/**
 *	MySQLResult
 *
 *	@package		database
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.4
 */
/**
 *	MySQLResult
 *
 *	@package		database
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.4
 */
class MySQLResult
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
	public function __construct ()
	{
		$this->current = 0;
		$this->objects = array ();
	}

	/**
	 *	Returns next found row in this result.
	 *
	 *	@access		public
	 *	@return		Object
	 */
	function FetchArray ()
	{
		if( isset( $this->objects [$this->current] ) )
		{
			$obj = $this->objects [$this->current];
			return $obj->getPairs();
		}
		else return false;
	}

	/**
	 *	Returns next found row in this result.
	 *
	 *	@access		public
	 *	@return		Object
	 */
	function FetchNextArray ()
	{
		if ($this->current < $this->RecordCount ())
		{
			$this->current ++;
			$obj = $this->objects [$this->current-1];
			return $obj->getPairs();
		}
		else return false;
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
	 *	Returns next found row in this result.
	 *
	 *	@access		public
	 *	@return		Object
	 */
	function FetchNextRow ()
	{
		if ($this->current < $this->RecordCount ())
		{
			$this->current ++;
			$obj = $this->objects [$this->current-1];
			return $obj->getValues();
		}
		else return false;
	}

	/**
	 *	Returns found row in this result.
	 *
	 *	@access		public
	 *	@return		Object
	 */
	function FetchObject ()
	{
		if( isset( $this->objects [$this->current] ) )
			return $this->objects [$this->current];
		return false;
	}

	/**
	 *	Returns next found row in this result.
	 *
	 *	@access		public
	 *	@return		Object
	 */
	function FetchRow ()
	{
		if( isset( $this->objects [$this->current] ) )
		{
			$obj = $this->objects [$this->current];
			return $obj->getValues();
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
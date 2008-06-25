<?php
/**
 *	Result Object for Database Result.
 *	All Result Rows can be iterated like an Array.
 *	@package		database
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.6
 */
/**
 *	Result Object for Database Result.
 *	All Result Rows can be iterated like an Array.
 *	@package		database
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.6
 */
class Database_Result implements Countable, Iterator
{
	/**	@var		int			$cursor			Internal Pointer to current Row */
	protected $cursor;
	/**	@var		array		$rows			List of all fetched Rows */
	public $rows;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->cursor = 0;
		$this->rows = array();
	}

	/**
	 *	Returns Size of Dictionary.
	 *	@access		public
	 *	@return		int
	 */
	public function count()
	{
		return count( $this->rows );
	}
	
	/**
	 *	Returns current Value.
	 *	@access		public
	 *	@return		mixed
	 */
	public function current()
	{
		return $this->rows[$this->key()];		
	}

	/**
	 *	Returns next found row in this result.
	 *	@access		public
	 *	@return		Object
	 */
	public function fetchArray()
	{
		if( isset( $this->rows[$this->cursor] ) )
		{
			$row = $this->rows[$this->cursor];
			return $row->getPairs();
		}
		else return FALSE;
	}

	/**
	 *	Returns next found row in this result.
	 *	@access		public
	 *	@return		Object
	 */
	public function fetchNextArray()
	{
		if( $this->cursor < $this->RecordCount() )
		{
			$this->cursor ++;
			$row = $this->rows[$this->cursor-1];
			return $row->getPairs();
		}
		else return FALSE;
	}

	/**
	 *	Returns next found row in this result.
	 *	@access		public
	 *	@return		Object
	 */
	public function fetchNextObject()
	{
		if( $this->cursor < $this->RecordCount() )
		{
			$this->cursor ++;
			return $this->rows[$this->cursor-1];
		}
		else return FALSE;
	}

	/**
	 *	Returns next found row in this result.
	 *	@access		public
	 *	@return		Object
	 */
	public function fetchNextRow()
	{
		if( $this->cursor < $this->RecordCount() )
		{
			$this->cursor ++;
			$row = $this->rows[$this->cursor-1];
			return $row->getValues();
		}
		else return FALSE;
	}

	/**
	 *	Returns found row in this result.
	 *	@access		public
	 *	@return		Object
	 */
	public function fetchObject()
	{
		if( isset( $this->rows[$this->cursor] ) )
			return $this->rows[$this->cursor];
		return FALSE;
	}

	/**
	 *	Returns next found row in this result.
	 *	@access		public
	 *	@return		Object
	 */
	public function fetchRow()
	{
		if( isset( $this->rows[$this->cursor] ) )
		{
			$row = $this->rows[$this->cursor];
			return $row->getValues();
		}
		else return FALSE;
	}

	/**
	 *	Returns current Key.
	 *	@access		public
	 *	@return		mixed
	 */
	public function key()
	{
		$keys	= array_keys( $this->rows );
		return $keys[$this->cursor];
	}
	
	/**
	 *	Selects next Pair.
	 *	@access		public
	 *	@return		void
	 */
	public function next()
	{
		$this->cursor++;
	}	

	/**
	 *	Returns the number found rows in this result.
	 *	@access		public
	 *	@return		int
	 */
	public function recordCount()
	{
		return count( $this->rows );
	}

	/**
	 *	Resets Pair Pointer.
	 *	@access		public
	 *	@return		void
	 */
	public function rewind()
	{
		$this->cursor	= 0;
	}
	
	/**
	 *	Indicates whether Pair Pointer is valid.
	 *	@access		public
	 *	@return		bool
	 */
	public function valid()
	{
		return $this->cursor < $this->count();
	}	
}
?>
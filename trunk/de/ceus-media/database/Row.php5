<?php
/**
 *	Result Row Object for Database Result Sets.
 *	All Rows Pairs can be iterated like an Array.
 *	@package		database
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.6
 */
/**
 *	Result Row Object for Database Result Sets.
 *	All Rows Pairs can be iterated like an Array.
 *	@package		database
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.6
 */
class Database_Row implements Countable, Iterator
{
	/**	@var		int			$cursor			Internal Pointer to current Row */
	protected $____cursor;
	/**	@var		array		$pairs			Internal associative Array of Row Pairs */
	protected $____pairs;

	/**
	 *	Returns Amount of Columns.
	 *	@access		public
	 *	@return		int
	 */
	public function getColCount ()
	{
		return count( get_object_vars( $this ) ) / 2;
	}

	/**
	 *	Returns Keys of Columns.
	 *	@access		public
	 *	@return		array
	 */
	public function getKeys()
	{
		if( !isset( $this->____pairs ) )
			$this->getPairs();
		return array_keys( $this->____pairs );
	}
	
	/**
	 *	Returns Pairs of Row.
	 *	@access		public
	 *	@param		bool		$assoc		Flag: return associative Array
	 *	@return		array
	 */
	public function getPairs()
	{
		if( !isset( $this->____pairs ) )
		{
			foreach( get_object_vars( $this ) as $key => $value )
				if( is_string( $key ) )
					if( strpos( $key, "____" ) === FALSE )
						$this->____pairs[$key] = $value;
		}
		return $this->____pairs;
	}

	/**
	 *	Returns Values of Column by its Key.
	 *	@access		public
	 *	@param		$string		$key		Column Key
	 *	@return		string
	 */
	public function getValue( $key )
	{
		if( !isset( $this->____pairs ) )
			$this->getPairs();
		if( !array_key_exists( $key, $this->____pairs ) )
			throw new Exception( 'Pair Key "'.$key.'" is not set in Row.' );
		if( !isset( $this->$key ) )
			return NULL;
		return $this->$key;
	}

	/**
	 *	Returns Values of Columns.
	 *	@access		public
	 *	@return		array
	 */
	public function getValues()
	{
		if( !isset( $this->____pairs ) )
			$this->getPairs();
		return array_values( $this->____pairs );
	}
	
	/**
	 *	Returns Size of Dictionary.
	 *	@access		public
	 *	@return		int
	 */
	public function count()
	{
		return $this->getColCount();
	}
	
	/**
	 *	Returns current Value.
	 *	@access		public
	 *	@return		mixed
	 */
	public function current()
	{
		if( !isset( $this->____pairs ) )
			$this->getPairs();
		$values	= array_values( $this->____pairs );
		return $values[$this->____cursor];		
	}

		/**
	 *	Returns current Key.
	 *	@access		public
	 *	@return		mixed
	 */
	public function key()
	{
		if( !isset( $this->____pairs ) )
			$this->getPairs();
		$keys	= array_keys( $this->____pairs );
		return $keys[$this->____cursor];
	}
	
	/**
	 *	Selects next Pair.
	 *	@access		public
	 *	@return		void
	 */
	public function next()
	{
		$this->____cursor++;
	}	

	/**
	 *	Resets Pair Pointer.
	 *	@access		public
	 *	@return		void
	 */
	public function rewind()
	{
		$this->____cursor	= 0;
	}
	
	/**
	 *	Indicates whether Pair Pointer is valid.
	 *	@access		public
	 *	@return		bool
	 */
	public function valid()
	{
		return $this->____cursor < $this->count() - 1;
	}	

}
?>
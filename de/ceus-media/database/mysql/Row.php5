<?php
/**
 *	MySQL Row.
 *	@package		database.mysql
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.6
 */
/**
 *	MySQL Row.
 *	@package		database.mysql
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.6
 */
class Database_MySQL_Row
{
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
		$keys = array();
		foreach( get_object_vars( $this ) as $key => $value )
			if( is_string( $key ) )
				$keys[] = $key;
		return $keys;
	}
	
	/**
	 *	Returns Pairs of Row.
	 *	@access		public
	 *	@param		bool		$assoc		Flag: return associative Array
	 *	@return		array
	 */
	public function getPairs( $assoc = true )
	{
		$pairs = array();
		foreach( get_object_vars( $this ) as $key => $value )
			if( is_string( $key ) )
					$pairs[$key] = $value;
		return $pairs;
	}

	/**
	 *	Returns Values of Column by its Key.
	 *	@access		public
	 *	@param		$string		$key		Column Key
	 *	@return		string
	 */
	public function getValue ($key)
	{
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
		$values = array();
		foreach( get_object_vars( $this ) as $key => $value )
			if( is_string( $key ) )
				$values[] = $value;
		return $values;
	}
}
?>
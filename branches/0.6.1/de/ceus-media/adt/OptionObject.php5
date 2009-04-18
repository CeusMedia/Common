<?php
/**
 *	Base Object with options.
 *	@package		adt
 *	@implements		ArrayAccess
 *	@implements		Countable
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.2005
 *	@version		0.6
 */
/**
 *	Base Object with options.
 *	@package		adt
 *	@implements		ArrayAccess
 *	@implements		Countable
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.2005
 *	@version		0.6
 */
class ADT_OptionObject implements ArrayAccess, Countable
{
	/**	@var		array		$options		Associative Array of options */
	protected $options	= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$options		Associative Array of options
	 *	@return		void
	 */
	public function __construct( $options = array() )
	{
		if( !is_array( $options ) )
			throw new InvalidArgumentException( 'Initial Options must be an Array or Pairs.' );

		foreach( $options as $key => $value )
		{
			if( is_int( $key ) )
			{
				throw new InvalidArgumentException( 'Initial Options must be an associative Array of Pairs.' );
			}
		}
		$this->options	= array();
		foreach( $options as $key => $value )
			$this->options[$key] = $value;
	}

	/**
	 *	Removes all set Options.
	 *	@access		public
	 *	@return		bool
	 */
	public function clearOptions()
	{
		if( !count( $this->options ) )
			return FALSE;
		$this->options	= array();
		return TRUE;
	}
	
	/**
	 *	Returns the Number of Options.
	 *	@access		public
	 *	@return		int
	 */
	public function count()
	{
		return count( $this->options );
	}
	
	/**
	 *	Declares a Set of Options.
	 *	@access		public
	 *	@param		array		$optionKeys		List of Option Keys
	 *	@return		void
	 */
	public function declareOptions( $optionKeys = array() )
	{
		if( !is_array( $optionKeys ) )
			throw new InvalidArgumentException( 'Option Keys must be an Array.' );
		foreach( $optionKeys as $key )
		{
			if( !is_string( $key ) )
				throw new InvalidArgumentException( 'Option Keys must be an Array List of Strings.' );
			$this->options[$key]	= NULL;
		}
	}

	/**
	 *	Returns an Option Value by Option Key.
	 *	@access		public
	 *	@param		string		$key			Option Key
	 *	@return		mixed
	 */
	public function getOption( $key, $throwException = TRUE )
	{
		if( !$this->hasOption( $key ) )
		{
			if( $throwException )
				throw new OutOfRangeException( 'Option "'.$key.'" is not defined.' );
			else
				return NULL;
		}
		return $this->options[$key];
	}

	/**
	 *	Returns associative Array of all set Options.
	 *	@access		public
	 *	@return		array
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 *	Indicated whether a option is set or not.
	 *	@access		public
	 *	@param		string		$key			Option Key
	 *	@return		bool
	 */
	public function hasOption( $key )
	{
		return isset( $this->options[$key] );
	}
	
	/**
	 *	Indicates whether a Key is existing.
	 *	@access		public
	 *	@param		string		$key			Option Key
	 *	@return		bool
	 */
	public function offsetExists( $key )
	{
		return $this->hasOption( $key );
	}
	
	/**
	 *	Return a Value of Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key			Option key
	 *	@return		mixed
	 */
	public function offsetGet( $key )
	{
		return $this->getOption( $key );
	}
	
	/**
	 *	Sets Value of Key in Dictionary.
	 *	@access		public
	 *	@param		string		$key			Option Key
	 *	@param		string		$value			Option Value
	 *	@return		void
	 */
	public function offsetSet( $key, $value )
	{
		return $this->setOption( $key, $value );
	}
	
	/**
	 *	Removes a Value from Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key			Option Key
	 *	@return		void
	 */
	public function offsetUnset( $key )
	{
		return $this->removeOption( $key );
	}

	/**
	 *	Removes an option by its key.
	 *	@access		public
	 *	@param		string		$key			Option Key
	 *	@return		bool
	 */
	public function removeOption( $key )
	{
		if( !$this->hasOption( $key ) )
			return FALSE;
		unset( $this->options[$key] );
		return TRUE;
	}
	
	/**
	 *	Sets an options.
	 *	@access		public
	 *	@param		string		$key			Option Key
	 *	@param		mixed		$value			Option Value
	 *	@return		bool
	 */
	public function setOption( $key, $value )
	{
		if( isset( $this->options[$key] ) && $this->options[$key] === $value )
			return FALSE;
		$this->options[$key] = $value;
		return TRUE;
	}
}
?>
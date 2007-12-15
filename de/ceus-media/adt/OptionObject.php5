<?php
/**
 *	Base Object with options.
 *	@package		adt
 *	@implements		ArrayAccess
 *	@implements		Countable
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.2005
 *	@version		0.6
 */
/**
 *	Base Object with options.
 *	@package		adt
 *	@implements		ArrayAccess
 *	@implements		Countable
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
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
		$this->options	= array();
		foreach( $options as $key => $value )
			$this->setOption( $key, $value );
	}

	/**
	 *	Removes all options.
	 *	@access		public
	 *	@return		void
	 */
	public function clearOptions()
	{
		foreach( $this->options as $key => $value )
			$this->removeOption( $key );
	}
	
	/**
	 *	Returns the Amount of Options.
	 *	@access		public
	 *	@return		void
	 */
	public function count()
	{
		return count( $this->options );
	}
	
	/**
	 *	Returns an option by its key.
	 *	@access		public
	 *	@param		string		$key			Option key
	 *	@return		mixed
	 */
	public function getOption( $key )
	{
		if( $this->hasOption( $key ) )
			return $this->options[$key];
		return NULL;
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
	 *	@param		string		$key			Option key
	 *	@return		bool
	 */
	public function hasOption( $key )
	{
		return isset( $this->options[$key] );
	}
	
	/**
	 *	Indicates whether a Key is existing.
	 *	@access		public
	 *	@param		string		$key			Option key
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
	 *	@param		string		$key			Option key
	 *	@param		string		$value			Option value
	 *	@return		void
	 */
	public function offsetSet( $key, $value )
	{
		return $this->setOption( $key, $value );
	}
	
	/**
	 *	Removes a Value from Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key				Option key
	 *	@return		void
	 */
	public function offsetUnset( $key )
	{
		return $this->removeOption( $key );
	}

	/**
	 *	Removes an option by its key.
	 *	@access		public
	 *	@param		string		$key			Option key
	 *	@return		bool
	 */
	public function removeOption( $key )
	{
		if( !$this->hasOption( $key ) )
			return false;
		unset( $this->options[$key] );
		return true;
	}
	
	/**
	 *	Sets an options.
	 *	@access		public
	 *	@param		string		$key			Option key
	 *	@param		mixed		$value			Option value
	 *	@return		bool
	 */
	public function setOption( $key, $value )
	{
		if( $this->getOption( $key ) == $value )
			return false;
		$this->options[$key] = $value;
		return true;
	}
}
?>
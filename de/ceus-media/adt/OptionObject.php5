<?php
/**
 *	Base Object with options.
 *	@package		adt
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.2005
 *	@version		0.5
 */
/**
 *	Base Object with options.
 *	@package		adt
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.2005
 *	@version		0.5
 */
class OptionObject
{
	/**	@var	array	_options		Associative Array of options */
	private $_options	= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		options		Associative Array of options
	 *	@return		void
	 */
	public function __construct( $options = array() )
	{
		$this->_options	= array();
		foreach( $options as $key => $value )
			$this->setOption( $key, $value );
	}

	/**
	 *	Returns an option by its key.
	 *	@access		public
	 *	@param		string		key		Option key
	 *	@return		mixed
	 */
	public function getOption( $key )
	{
		if( $this->hasOption( $key ) )
			return $this->_options[$key];
		return NULL;
	}
	
	/**
	 *	Returns associative Array of all set Options.
	 *	@access		public
	 *	@return		array
	 */
	public function getOptions()
	{
		return $this->_options;
	}

	/**
	 *	Sets an options.
	 *	@access		public
	 *	@param		string		key			Option key
	 *	@param		mixed		value		Option value
	 *	@return		void
	 */
	public function setOption( $key, $value )
	{
		$this->_options[$key] = $value;
	}
	
	/**
	 *	Indicated whether a option is set or not.
	 *	@access		public
	 *	@param		string		key			Option key
	 *	@return		bool
	 */
	public function hasOption( $key )
	{
		return isset( $this->_options[$key] );
	}
	
	/**
	 *	Removes an option by its key.
	 *	@access		public
	 *	@param		string		key			Option key
	 *	@return		void
	 */
	public function removeOption( $key )
	{
		unset( $this->_options[$key] );
	}
	
	/**
	 *	Removes all options.
	 *	@access		public
	 *	@return		void
	 */
	public function clearOptions()
	{
		foreach( $this->_options as $key => $value )
			$this->removeOption( $key );
	}
}
?>
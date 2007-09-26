<?php
/**
 *	Cookie Management.
 *	@package		protocol
 *	@subpackage		http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.07.2005
 *	@version		0.1
 */
/**
 *	Cookie Management.
 *	@package		protocol
 *	@subpackage		http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.07.2005
 *	@version		0.1
 */
class Cookie
{
	/**	@var	array	$cookie_data		reference to Cookie data */
	var $_cookie_data;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->_cookie_data =& $_COOKIE;
	}

	/**
	 *	Returns a setting by its key name.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@return		mixed
	 */
	function get( $key )
	{
		if( isset( $this->_cookie_data [$key] ) )
			return $this->_cookie_data [$key];
		return NULL;
	}
	
	/**
	 *	Returns all settings of this Cookie.
	 *	@access		public
	 *	@return		array
	 */
	function getAll()
	{
		return $this->_cookie_data;
	}

	/**
	 *	Writes a setting to Cookie.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@param		string		$value		Value of setting
	 *	@return		void
	 */
	function set( $key, $value )
	{
		$this->_cookie_data[$key] =& $value;
		setcookie( $key, $value );
	}
		
	/**
	 *	Deletes a setting of Cookie.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@return		void
	 */
	function remove( $key )
	{
		if( isset( $this->_cookie_data[$key] ) )
			unset( $this->_cookie_data[$key] );	
	}
}
?>
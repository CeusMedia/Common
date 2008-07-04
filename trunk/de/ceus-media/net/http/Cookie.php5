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
class Net_HTTP_Cookie
{
	/**	@var	array	$cookie_data		reference to Cookie data */
	protected $data;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->data =& $_COOKIE;
	}

	/**
	 *	Returns a setting by its key name.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@return		mixed
	 */
	public function get( $key )
	{
		if( isset( $this->data[$key] ) )
			return $this->data[$key];
		return NULL;
	}
	
	/**
	 *	Returns all settings of this Cookie.
	 *	@access		public
	 *	@return		array
	 */
	public function getAll()
	{
		return $this->data;
	}

	/**
	 *	Writes a setting to Cookie.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@param		string		$value		Value of setting
	 *	@return		void
	 */
	public function set( $key, $value )
	{
		$this->data[$key] =& $value;
		setcookie( $key, $value );
	}
		
	/**
	 *	Deletes a setting of Cookie.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@return		void
	 */
	public function remove( $key )
	{
		if( !isset( $this->data[$key] ) )
			return FALSE;
		unset( $this->data[$key] );	
#		setcookie( $key );
		return TRUE;
	}
}
?>
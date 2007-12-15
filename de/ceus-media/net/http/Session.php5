<?php
/**
 *	Session Management.
 *	@package		net.http
 *	@implements		ArrayAccess
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Session Management.
 *	@package		net.http
 *	@implements		ArrayAccess
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class Net_HTTP_Session implements ArrayAccess, Countable
{
	/**	@var	array	$data		Reference to session data */
	protected $data;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $sessionName = "sid" )
	{
		session_name( $sessionName );
		session_start();
		$this->data =& $_SESSION;
	}
	
	/**
	 *	Destructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __destruct()
	{
		session_write_close();
	}

	/**
	 *	Clears Session.
	 *	@access		public
	 *	@return		void
	 */
	public function clear()
	{
		foreach( $this->getAll() as $key => $value )
			$this->remove( $key );
	}
	
	/**
	 *	Returns Amount of stored Information.
	 *	@access		public
	 *	@return		void
	 */
	public function count()
	{
		return count( $this->data );
	}
	
	/**
	 *	Returns a setting by its key name.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@return		mixed
	 */
	public function get( $key )
	{
		if( $this->has( $key ) )
			return $this->data [$key];
		return NULL;
	}
	
	/**
	 *	Returns all settings of this session.
	 *	@access		public
	 *	@return		array
	 */
	public function getAll()
	{
		return $this->data;
	}

	/**
	 *	Returns current Session ID.
	 *	@access		public
	 *	@return		string
	 */
	public function getSessionID()
	{
		return session_id();
	}

	/**
	 *	Returns current Session Name.
	 *	@access		public
	 *	@return		string
	 */
	public function getSessionName()
	{
		return session_name();
	}

	/**
	 *	Indicates whether a setting is set by its key name.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@return		string
	 */
	public function has( $key )
	{
		return isset( $this->data[$key] );
	}

	/**
	 *	Indicates whether a Key is existing.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@return		bool
	 */
	public function offsetExists( $key )
	{
		return $this->has( $key );
	}
	
	/**
	 *	Return a Value of Session by its Key.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@return		mixed
	 */
	public function offsetGet( $key )
	{
		return $this->get( $key );
	}
	
	/**
	 *	Sets Value of Key in Session.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@param		string		$value		Value of setting
	 *	@return		void
	 */
	public function offsetSet( $key, $value )
	{
		return $this->set( $key, $value );
	}
	
	/**
	 *	Removes a Value from Session by its Key.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@return		void
	 */
	public function offsetUnset( $key )
	{
		return $this->remove( $key );
	}

	/**
	 *	Deletes a setting of session.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@return		bool
	 */
	public function remove( $key )
	{
		if( !$this->has( $key ) )
			return FALSE;
		unset( $this->data[$key] );	
		return TRUE;
	}
	
	/**
	 *	Writes a setting to session.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@param		string		$value		Value of setting
	 *	@return		bool
	 */
	public function set( $key, $value )
	{
		if( isset( $this->data[$key] ) && $this->data[$key] == $value )
			return FALSE;
		$this->data[$key] = $value;
		return TRUE;
	}
}
?>
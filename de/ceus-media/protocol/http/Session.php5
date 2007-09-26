<?php
/**
 *	Session Management.
 *	@package		protocol
 *	@subpackage		http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Session Management.
 *	@package		protocol
 *	@subpackage		http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Session
{
	/**	@var	array	$_session_data		Reference to session data */
	var $_session_data;
	/**	@var	bool		$_open				Flag: Session is opened */
	var $_open	= false;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
	}

	/**
	 *	Clears current Partition of Session.
	 *	@access		public
	 *	@return		void
	 */
	function clear()
	{
		foreach( $this->_session_data as $key => $value )
			unset( $this->_session_data[$key] );
	}
	
	/**
	 *	Closes Session and writes Session Data.
	 *	@access		public
	 *	@return		void
	 */
	function close()
	{
		session_write_close();
		$this->_open = false;
	}

	/**
	 *	Returns a setting by its key name.
	 *	@access		public
	 *	@param		string		$key			Key name of setting
	 *	@return		mixed
	 */
	function get( $key )
	{
		if( $this->has( $key ) )
			return $this->_session_data [$key];
		return NULL;
	}
	
	/**
	 *	Returns all settings of this session.
	 *	@access		public
	 *	@return		array
	 */
	function getAll()
	{
		return $this->_session_data;
	}

	/**
	 *	Returns current Session ID.
	 *	@access		public
	 *	@return		string
	 */
	function getSessionID()
	{
		return session_id();
	}

	/**
	 *	Returns current Session Name.
	 *	@access		public
	 *	@return		string
	 */
	function getSessionName()
	{
		return session_name();
	}

	/**
	 *	Indicates whether a setting is set by its key name.
	 *	@access		public
	 *	@param		string		$key			Key name of setting
	 *	@return		string
	 */
	function has( $key )
	{
		return isset( $this->_session_data[$key] );
	}

	/**
	 *	Open Sessions.
	 *	@access		public
	 *	@param		string		$session_name	Name of Session
	 *	@return		string
	 */
	function openSession( $session_name = 'sid' )
	{
		$this->_setSessionName( $session_name );
		session_start();
		$this->_session_data =& $_SESSION;
		$this->_open = true;
	}

	/**
	 *	Deletes a setting of session.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@return		void
	 */
	function remove( $key )
	{
		if( $this->has( $key ) )
			unset( $this->_session_data[$key] );	
	}
	
	/**
	 *	Writes a setting to session.
	 *	@access		public
	 *	@param		string		$key		Key name of setting
	 *	@param		string		$value		Value of setting
	 *	@return		void
	 */
	function set( $key, $value )
	{
		$this->_session_data[$key] = $value;
	}
	
	/**
	 *	Sets current Session Name.
	 *	@access		public
	 *	@param		string		$name		Name of Session
	 *	@return		void
	 */
	function _setSessionName( $name )
	{
		session_name( $name );
	}
}
?>
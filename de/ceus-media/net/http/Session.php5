<?php
import( 'de.ceus-media.adt.list.Dictionary' );
/**
 *	Session Management.
 *	@package		net.http
 *	@extends		ADT_List_Dictionary
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Session Management.
 *	@package		net.http
 *	@extends		ADT_List_Dictionary
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class Net_HTTP_Session extends ADT_List_Dictionary
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $sessionName = "sid" )
	{
		session_name( $sessionName );
		session_start();
		$this->pairs =& $_SESSION;
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
		$this->pairs	= array();
#		foreach( $this->pairs as $key => $value )
#			unset( $this->pairs[$key] );
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
}
?>
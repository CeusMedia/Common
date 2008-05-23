<?php
import( 'de.ceus-media.adt.list.Dictionary' );
/**
 *	Session Management.
 *	@package		net.http
 *	@extends		ADT_List_Dictionary
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.07.2005
 *	@version		0.6
 */
/**
 *	Session Management.
 *	@package		net.http
 *	@extends		ADT_List_Dictionary
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.07.2005
 *	@version		0.6
 */
class Net_HTTP_PartitionSession extends ADT_List_Dictionary
{
	/**	@var	array		$session			Reference to Session with Partitions */
	protected $session;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$partitionName		Partition of Session Data
	 *	@param		string		$sessionName		Name of Session ID
	 *	@return		void
	 */
	public function __construct( $partitionName, $sessionName = "sid" )
	{
		session_name( $sessionName );
		session_start();
		$this->session	=& $_SESSION;
		$ip = getEnv( 'REMOTE_ADDR' );
		if( !isset( $this->session['ip'] ) )
			$this->session['ip'] = $ip;
		else if( $this->session['ip'] != $ip )								//  HiJack Attempt
		{
			session_regenerate_id();
			$this->session =& $_SESSION;
			foreach( $this->session as $key => $value )
				unset( $this->session[$key] );
			$this->session['ip'] = $ip;
		}
		unset( $this->pairs );
		if( !isset( $_SESSION['partitions'][$partitionName] ) )
			$_SESSION['partitions'][$partitionName]	= array();
		$this->pairs =& $_SESSION['partitions'][$partitionName];
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
	 *	Clears current Partition of Session.
	 *	@access		public
	 *	@return		void
	 */
	public function clear()
	{
		$this->pairs	= array();
		$this->session['ip'] = getEnv( 'REMOTE_ADDR' );
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
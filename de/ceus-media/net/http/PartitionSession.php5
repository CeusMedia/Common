<?php
import( 'de.ceus-media.net.http.Session' );
/**
 *	Session Management.
 *	@package		net.http
 *	@extends		Net_HTTP_Session
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.07.2005
 *	@version		0.5
 */
/**
 *	Session Management.
 *	@package		net.http
 *	@extends		Net_HTTP_Session
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.07.2005
 *	@version		0.5
 */
class Net_HTTP_PartitionSession extends Net_HTTP_Session
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
		$this->data =& $this->session['partitions'][$partitionName];
	}

	/**
	 *	Clears current Partition of Session.
	 *	@access		public
	 *	@return		void
	 */
	public function clear()
	{
		parent::clear();
		$this->session['ip'] = getEnv( 'REMOTE_ADDR' );
	}
}
?>
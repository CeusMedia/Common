<?php
/**
 *	Management for session data with partitions.
 *	Helpful and more secure if several applications are storing data with same session.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			26.07.2005
 */
/**
 *	Management for session data with partitions.
 *	Helpful and more secure if several applications are storing data with same session.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			26.07.2005
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
	 *	@param		string		$domain				Domain to set cookie for
	 *	@return		void
	 */
	public function __construct( $partitionName, $sessionName = "sid", $domain = NULL )
	{
		//  set session cookie name
		@session_name( $sessionName );
		//  a domain has been specified
		if( strlen( trim( $domain ) ) )
			//  set cookie domain
			ini_set( 'session.cookie_domain', trim( strtolower( $domain ) ) );
		//  start cookie handler
		@session_start();
		//  copy session data resource
		$this->session	=& $_SESSION;
		//  get client IP address
		$ip = getEnv( 'REMOTE_ADDR' );
		//  IP has not been noted before
		if( !isset( $this->session['ip'] ) )
			//  store IP in session
			$this->session['ip'] = $ip;
		//  Session hijacking attempt
		else if( $this->session['ip'] != $ip )
		{
			//  generate new session ID
			session_regenerate_id();
			//  copy new session data resource
			$this->session =& $_SESSION;
			//  iterate session data keys
			foreach( array_keys( $this->session ) as $key )
				//  remove all session data
				unset( $this->session[$key] );
			//  store IP in session
			$this->session['ip'] = $ip;
		}
		//  clear local data pair map
		unset( $this->pairs );
		//  partition is not opened yet
		if( !isset( $_SESSION['partitions'][$partitionName] ) )
			//  create new partition in session
			$_SESSION['partitions'][$partitionName]	= array();
		//  copy session partition reference
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
#		foreach( $this->pairs as $key => $value )
#			$this->remove( $key );
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

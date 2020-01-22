<?php
/**
 *	Management for session data with partitions.
 *	Helpful and more secure if several applications are storing data with same session.
 *
 *	Copyright (c) 2007-2018 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			26.07.2005
 *	@version		$Id$
 */
/**
 *	Management for session data with partitions.
 *	Helpful and more secure if several applications are storing data with same session.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@extends		ADT_List_Dictionary
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			26.07.2005
 *	@version		$Id$
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
		session_name( $sessionName );											//  set session cookie name
		if( strlen( trim( $domain ) ) )											//  a domain has been specified
			ini_set( 'session.cookie_domain', trim( strtolower( $domain ) ) );	//  set cookie domain
		@session_start();														//  start cookie handler
		$this->session	=& $_SESSION;											//  copy session data resource
		$ip = getEnv( 'REMOTE_ADDR' );											//  get client IP address
		if( !isset( $this->session['ip'] ) )									//  IP has not been noted before
			$this->session['ip'] = $ip;											//  store IP in session
		else if( $this->session['ip'] != $ip )									//  Session hijacking attempt
		{
			session_regenerate_id();											//  generate new session ID
			$this->session =& $_SESSION;										//  copy new session data resource
			foreach( array_keys( $this->session ) as $key )						//  iterate session data keys
				unset( $this->session[$key] );									//  remove all session data
			$this->session['ip'] = $ip;											//  store IP in session
		}
		unset( $this->pairs );													//  clear local data pair map
		if( !isset( $_SESSION['partitions'][$partitionName] ) )					//  partition is not opened yet
			$_SESSION['partitions'][$partitionName]	= array();					//  create new partition in session
		$this->pairs =& $_SESSION['partitions'][$partitionName];				//  copy session partition reference
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
?>

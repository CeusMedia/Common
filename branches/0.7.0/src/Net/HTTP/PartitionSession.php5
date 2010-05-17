<?php
/**
 *	Session Management.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		Net.HTTP
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			26.07.2005
 *	@version		$Id$
 */
/**
 *	Session Management.
 *	@category		cmClasses
 *	@package		Net.HTTP
 *	@extends		ADT_List_Dictionary
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
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
		foreach( $this->pairs as $key => $value )
			unset( $this->pairs[$key] );
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
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
 *	@package		net.http
 *	@extends		ADT_List_Dictionary
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
import( 'de.ceus-media.adt.list.Dictionary' );
/**
 *	Session Management.
 *	@category		cmClasses
 *	@package		net.http
 *	@extends		ADT_List_Dictionary
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
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
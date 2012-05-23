<?php
/**
 *	Partitioned Cookie Management.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceusmedia.com)
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
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			11.08.2005
 *	@version		$Id$
 */
/**
 *	Partitioned Cookie Management.
 *	@category		cmClasses
 *	@package		Net.HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			11.08.2005
 *	@version		$Id$
 */
class Net_HTTP_PartitionCookie extends Net_HTTP_Cookie
{
	/**	@var	array	$data			Cookie Data in Partition */
	protected $data;
	/**	@var	string	$partition		Name of Partition in Cookie */
	protected $partition;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct ( $partition )
	{
		$this->partition = $partition;
		$pairs	= array();
		if( isset( $_COOKIE[$partition] ) )
			$this->data	= json_decode( $_COOKIE[$partition], TRUE );
	}

	/**
	 *	Returns a Cookie by its key.
	 *	@access		public
	 *	@param		string		$key		Key of Cookie
	 *	@return		mixed
	 */
	public function get( $key )
	{
		if( isset( $this->data[$key] ) )
			return $this->data[$key];
		return NULL;
	}
	
	/**
	 *	Returns all Cookies of this PartitionCookie.
	 *	@access		public
	 *	@return		array
	 */
	public function getAll()
	{
		return $this->data;
	}

	public function has( $key )
	{
		return isset( $this->data[$key] );	
	}

	/**
	 *	Sets a Cookie to this PartitionCookie.
	 *	@access		public
	 *	@param		string		$key		Key of Cookie
	 *	@param		string		$value		Value of Cookie
	 *	@return		void
	 */
	public function set( $key, $value )
	{
		$this->data[$key] = $value;
		$this->save();
	}

	/**
	 *	Saves PartitionCookie by sending to Browser.
	 *	@access		protected
	 *	@return		void
	 */
	protected function save()
	{
		error_log( json_encode( $this->data )."\n", 3, 'cookie.log' );
		setCookie( $this->partition, json_encode( $this->data ) );
	}
		
	/**
	 *	Deletes a Cookie of this PartitionCookie.
	 *	@access		public
	 *	@param		string		$key		Key of Cookie
	 *	@return		void
	 */
	public function remove ($key )
	{
		if( !isset( $this->data[$key] ) )
			return;
		unset( $this->data[$key] );	
		$this->save();
	}
}
?>
<?php
/**
 *	Header for HTTP Requests.
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
 *	@package		net.http.request
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Header for HTTP Requests.
 *	@category		cmClasses
 *	@package		net.http.request
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class Net_HTTP_Request_Header
{
	/**	@var		string		$key		Key of Header */
	protected $key;
	/**	@var		string		$value		Value of Header */
	protected $value;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$key		Key of Header
	 *	@param		string		$value		Value of Header
	 *	@return		void
	 */
	public function __construct( $key, $value )
	{
		$this->key		= $key;
		$this->value	= $value;	
	}
	
	/**
	 *	Returns a representative string of Header.
	 *	@access		public
	 *	@return		string
	 */
	public function toString()
	{
		if( !trim( $this->key ) )
			return "";
		
		$list	= array();
		$parts	= explode( "-", $this->key );
		foreach( $parts as $part )
			$list[]	= ucFirst( strtolower( $part ) );
		$key	= implode( "-", $list );
		$string	= $key.": ".$this->value."\r\n";
		return $string;
	}
}
?>
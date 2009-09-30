<?php
/**
 *	Data Object for HTTP Headers.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		net.http
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6.8
 */
/**
 *	Data Object of HTTP Headers.
 *	@package		net.http
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6.8
 */
class Net_HTTP_Header
{
	/**	@var		string		$name		Name of Header */
	protected $name;
	/**	@var		string		$value		Value of Header */
	protected $value;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$name		Name of Header
	 *	@param		string		$value		Value of Header
	 *	@return		void
	 */
	public function __construct( $name, $value )
	{
		$this->setName( $name );
		$this->value	= $value;	
	}

	/**
	 *	Returns set Header Name.
	 *	@access		public
	 *	@return		string		Header Name
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 *	Returns set Header Value.
	 *	@access		public
	 *	@return		string		Header Value
	 */
	public function getValue()
	{
		return $this->value;
	}

	public function setName( $name )
	{
		if( !trim( $name ) )
			throw new InvalidArgumentException( 'Header name cannot be empty' );
		$this->name	= strtolower( $name );
	}

	public function setValue( $value )
	{
		$this->value	= $value;
	}
	
	/**
	 *	Returns a representative string of Header.
	 *	@access		public
	 *	@return		string
	 */
	public function toString()
	{
		return $this->name.": ".$this->value;
	}
}
?>
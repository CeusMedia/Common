<?php
/**
 *	Data Object for HTTP Headers.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		Net.HTTP.Header
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.1
 *	@version		$Id$
 */
/**
 *	Data Object of HTTP Headers.
 *	@category		cmClasses
 *	@package		Net.HTTP.Header
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.1
 *	@version		$Id$
 */
class Net_HTTP_Header_Field
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
		$this->setValue( $value );
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
	 *	@return		string|array	Header Value or Array of qualified Values
	 */
	public function getValue( $qualified = FALSE )
	{
		if( $qualified )
			return $this->decodeQualifiedValues ( $this->value );
		return $this->value;
	}

	public static function decodeQualifiedValues( $values, $sortByLength = TRUE ){
		$pattern	= '/^(\S+)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/iU';
		$values		= preg_split( '/,\s*/', $values );
		$codes		= array();
		foreach( $values as $value )
			if( preg_match ( $pattern, $value, $matches ) )
				$codes[$matches[1]]	= isset( $matches[2] ) ? (float) $matches[2] : 1.0;
		$map	= array();
		foreach( $codes as $code => $quality ){
			if( !isset( $map[(string)$quality] ) )
				$map[(string)$quality]	= array();
			$map[(string)$quality][strlen( $code)]	= $code;
			if( $sortByLength )
				krsort( $map[(string)$quality] );													//  sort inner list by code length
		}
		krsort( $map );																				//  sort outer list by quality
		$list	= array();
		foreach( $map as $quality => $codes )														//  reduce map to list
			foreach( $codes as $code )
				$list[$code]	= (float) $quality;
		return $list;
	}

	public function setName( $name )
	{
		if( !trim( $name ) )
			throw new InvalidArgumentException( 'Field name cannot be empty' );
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
		$name	= mb_convert_case( $this->name, MB_CASE_TITLE );
		return $name.": ".$this->value;
	}

	public function __toString()
	{
		return $this->toString();
	}
}
?>
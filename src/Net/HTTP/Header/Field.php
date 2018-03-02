<?php
/**
 *	Data Object for HTTP Headers.
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
 *	@package		CeusMedia_Common_Net_HTTP_Header
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.1
 *	@version		$Id$
 */
/**
 *	Data Object of HTTP Headers.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Header
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
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
	 *	Tries to decode qualified values into a map of values ordered by their quality.
	 *	Alias for Net_HTTP_Header_Field_Parser::decodeQualifiedValues.
	 *	@static
	 *	@access		public
	 *	@param		string		$string			String of qualified values to decode
	 *	@param		boolean		$sortByLength	Flag: assume longer key as more qualified for keys with same quality (default: FALSE)
	 *	@return		array		Map of qualified values ordered by quality
	 */
	static public function decodeQualifiedValues( $values, $sortByLength = TRUE )
	{
		return Net_HTTP_Header_Field_Parser::decodeQualifiedValues( $values, $sortByLength );
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
		if( function_exists( 'mb_convert_case' ) )
			$name	= mb_convert_case( $this->name, MB_CASE_TITLE );
		else
			$name	= str_replace( " ", "-", ucwords( str_replace( "-", " ", $this->name ) ) );
		return $name.": ".$this->value;
	}

	public function __toString()
	{
		return $this->toString();
	}
}
?>

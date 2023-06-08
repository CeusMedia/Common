<?php
/**
 *	Data Object for HTTP Headers.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@copyright		2015-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP\Header;

use CeusMedia\Common\Net\HTTP\Header\Field\Parser as FieldParser;
use InvalidArgumentException;

/**
 *	Data Object of HTTP Headers.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Header
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Field
{
	/**	@var		string		$name		Name of Header */
	protected string $name;

	/**	@var		string|int	$value		Value of Header */
	protected $value;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string				$name		Name of Header
	 *	@param		string|int|float	$value		Value of Header
	 *	@return		void
	 */
	public function __construct( string $name, $value )
	{
		$this->setName( $name );
		$this->setValue( $value );
	}

	/**
	 *	Tries to decode qualified values into a map of values ordered by their quality.
	 *	Alias for Net_HTTP_Header_Field_Parser::decodeQualifiedValues.
	 *	@static
	 *	@access		public
	 *	@param		string		$values			String of qualified values to decode
	 *	@param		boolean		$sortByLength	Flag: assume longer key as more qualified for keys with same quality (default: FALSE)
	 *	@return		array		Map of qualified values ordered by quality
	 */
	public static function decodeQualifiedValues( string $values, bool $sortByLength = TRUE ): array
	{
		return FieldParser::decodeQualifiedValues( $values, $sortByLength );
	}

	/**
	 *	Returns set Header Name.
	 *	@access		public
	 *	@return		string		Header Name
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 *	Returns set Header Value.
	 *	@access		public
	 *	@return		string|int|array	Header Value or Array of qualified Values
	 */
	public function getValue( $qualified = FALSE )
	{
		if( $qualified && is_string( $this->value ) )
			return static::decodeQualifiedValues( $this->value);
		return $this->value;
	}

	public function setName( string $name ): self
	{
		if( !trim( $name ) )
			throw new InvalidArgumentException( 'Field name cannot be empty' );
		$this->name	= strtolower( $name );
		return $this;
	}

	/**
	 *	Converts to string, internally.
	 *	@param		string|int|float	$value
	 *	@return		self
	 */
	public function setValue( $value ): self
	{
		if( !is_scalar( $value ) )
			throw new InvalidArgumentException( 'Header value must be scalar (string, integer or float)' );
		$this->value	= (string) $value;
		return $this;
	}

	/**
	 *	Returns a representative string of Header.
	 *	@access		public
	 *	@return		string
	 */
	public function toString(): string
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

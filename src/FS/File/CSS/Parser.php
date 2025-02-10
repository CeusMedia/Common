<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Parses a CSS string or file and creates a structure of ADT_CSS_* objects.
 *
 *	Copyright (c) 2011-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\CSS;

use CeusMedia\Common\ADT\CSS\Property as CssProperty;
use CeusMedia\Common\ADT\CSS\Rule as CssRule;
use CeusMedia\Common\ADT\CSS\Sheet as CssSheet;
use CeusMedia\Common\FS\File\Reader as FileReader;
use Exception;

/**
 *	Parses a CSS string or file and creates a structure of ADT_CSS_* objects.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Parser
{
	/**
	 *	Parses a CSS file and returns sheet structure statically.
	 *	@access		public
	 *	@param		string			$fileName	Relative or absolute file URI
	 *	@return		CssSheet
	 *	@throws		Exception
	 */
	public static function parseFile( string $fileName ): CssSheet
	{
		return self::parseString( FileReader::load( $fileName ) );
	}

	/**
	 *	Parses CSS properties inside a rule and returns a list of property objects.
	 *	@access		protected
	 *	@param		string			$string		String of CSS rule properties
	 *	@return		array			List of property objects
	 */
	protected static function parseProperties( string $string ): array
	{
		$list	= [];
		foreach( explode( ';', trim( $string ) ) as $line ){
			if( !trim( $line ) )
				continue;
			$parts	= explode( ':', $line );
			$key	= array_shift( $parts );
			$value	= trim( implode( ':', $parts ) );
			$list[]	= new CssProperty( $key, $value );
		}
		return $list;
	}

	/**
	 *	Parses a CSS string and returns sheet structure statically.
	 *	@access		public
	 *	@param		string			$string		CSS string
	 *	@return		CssSheet
	 *	@throws		Exception
	 */
	public static function parseString( string $string ): CssSheet
	{
		if( substr_count( $string, "{" ) !== substr_count( $string, "}" ) )
			throw new Exception( 'Invalid parenthesis' );
		$string	= preg_replace( '/\/\*.+\*\//sU', '', $string );
		$string	= preg_replace( '/(\t|\r|\n)/s', '', $string );
		$state	= (int) ( $buffer = $key = '' );
		$sheet	= new CssSheet();
		for( $i=0; $i<strlen( $string ); $i++ ){
			$char = $string[$i];
			if( !$state && $char == '{' ){
				$state	= (boolean) ( $key = trim( $buffer ) );
				$buffer	= '';
			}
			else if( $state && $char == '}' ){
				$properties	= self::parseProperties( $buffer );
				foreach( explode( ',', $key ) as $selector )
					$sheet->addRule( new CssRule( $selector, $properties ) );
				$state	= (boolean) ($buffer = '');
			}
			else
				$buffer	.= $char;
		}
		return $sheet;
	}
}

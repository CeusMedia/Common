<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Formats JSON String.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_ADT_JSON
 *	@author			Umbrae <umbrae@gmail.com>
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT\JSON;

use InvalidArgumentException;

/**
 *	Formats JSON String.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_JSON
 *	@author			Umbrae <umbrae@gmail.com>
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Unit Test
 *	@deprecated		use json_encode( $data, JSON_PRETTY_PRINT ) instead
 *	@todo			support and implement 3 strategies: native, own, json5
 */
class Pretty
{
	/**
	 *	Formats JSON String.
	 *	@access		public
	 *	@static
	 *	@param		string		$json			JSON String or Object to format
	 *	@param		boolean		$validateSource	Flag: validate JSON string beforehand, default: no
	 *	@return		string
	 *	@throws		InvalidArgumentException
	 */
	public static function print( string $json, bool $validateSource = FALSE ): string
	{
		$tab			= "  ";
		$content		= "";
		$indentLevel	= 0;
		$inString		= FALSE;

		if( $validateSource )
			if( json_decode( $json ) === FALSE )
				throw new InvalidArgumentException( 'JSON String is not valid.' );

		$len	= strlen( $json );
		for( $c=0; $c<$len; $c++ ){
			$char	= $json[$c];
			switch( $char ){
				case '{':
				case '[':
					$content .= $char;
					if( !$inString ){
						$content .= "\n".str_repeat( $tab, $indentLevel + 1 );
						$indentLevel++;
					}
					break;
				case '}':
				case ']':
					if( !$inString ){
						$indentLevel--;
						$content .= "\n".str_repeat( $tab, $indentLevel );
					}
					$content .= $char;
					break;
				case ',':
					$content .= $inString ? $char : ",\n" . str_repeat( $tab, $indentLevel );
					break;
				case ':':
					$content .= $inString ? $char : ": ";
					break;
				case '"':
					if( $c > 0 && $json[$c-1] != '\\' )
						$inString = !$inString;
					$content .= $char;
					break;
				default:
					$content .= $char;
					break;
			}
		}
		return $content;
	}
}

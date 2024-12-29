<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Parser for HTTP Header Fields.
 *
 *	Copyright (c) 2017-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net_HTTP_Header_Field
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2017-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP\Header\Field;

use CeusMedia\Common\Net\HTTP\Header\Field as HeaderField;
use InvalidArgumentException;

/**
 *	Parser for HTTP Header Fields.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Header_Field
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2017-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Parser
{
	/**
	 *	Tries to decode qualified values into a map of values ordered by their quality.
	 *
	 *	@static
	 *	@access		public
	 *	@param		string		$qualifiedValues			String of qualified values to decode
	 *	@param		boolean		$sortByLength	Flag: assume longer key as more qualified for keys with same quality (default: FALSE)
	 *	@return		array		Map of qualified values ordered by quality
	 */
	public static function decodeQualifiedValues( string $qualifiedValues, bool $sortByLength = FALSE ): array
	{
		$pattern	= '/^(\S+)(?:;\s*q=(0(?:\.\d{1,3})?|1(?:\.0{1,3})?))?$/iU';
		$parts		= preg_split( '/,\s*/', $qualifiedValues );
		$codes		= [];
		foreach( $parts as $part )
			if( preg_match ( $pattern, $part, $matches ) )
				$codes[$matches[1]]	= isset( $matches[2] ) ? (float) $matches[2] : 1.0;
		$map	= [];
		foreach( $codes as $code => $quality ){
			if( !isset( $map[(string)$quality] ) )
				$map[(string)$quality]	= [];
			$map[(string)$quality][strlen( $code)]	= $code;
			if( $sortByLength )
				//  sort inner list by code length
				krsort( $map[(string)$quality] );
		}
		//  sort outer list by quality
		krsort( $map );
		$list	= [];
		//  reduce map to list
		foreach( $map as $quality => $codes )
			foreach( $codes as $code )
				$list[$code]	= (float) $quality;
		return $list;
	}

	/**
	 *	Parses a header field string into a header field object.
	 *
	 *	@static
	 *	@access		public
	 *	@param		string		$headerFieldString		String to header field to parse
	 *	@param		boolean		$decodeQualifiedValues	Flag: decode qualified values (default: FALSE)
	 *	@return		HeaderField							Header field object
	 *	@throws		InvalidArgumentException			If given string is not a valid header field
	 */
	public static function parse( string $headerFieldString, bool $decodeQualifiedValues = FALSE ): HeaderField
	{
		if( !preg_match( '/^\S+:\s*.+$/', trim( $headerFieldString ) ) )
		 	throw new InvalidArgumentException( 'Given string is not an HTTP header' );
		[$key, $value]	= explode( ':', trim( $headerFieldString ), 2 );
		$value	= trim( $value );
		if( $decodeQualifiedValues )
			$value	= self::decodeQualifiedValues( $value );
		return new HeaderField( trim( $key ), $value );
	}
}

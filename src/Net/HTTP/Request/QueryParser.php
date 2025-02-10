<?php
/**
 *	Parser for HTTP Request Query Strings, for example given by mod_rewrite or own formats.
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
 *	@package		CeusMedia_Common_Net_HTTP_Request
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP\Request;

use InvalidArgumentException;

/**
 *	Parser for HTTP Request Query Strings, for example given by mod_rewrite or own formats.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Request
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class QueryParser
{
	/**
	 *	Parses Query String and returns an Array statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$query		Query String to parse, eg. a=word&b=123&c
	 *	@param		string		$separatorPairs		Separator Sign between Parameter Pairs
	 *	@param		string		$separatorPair		Separator Sign between Key and Value
	 *	@return		array
	 */
	public static function toArray( string $query, string $separatorPairs = '&', string $separatorPair = '=' ): array
	{
		$list	= [];
		//  cut query into pairs
		$pairs	= explode( $separatorPairs, $query );
		//  iterate all pairs
		foreach( $pairs as $pair ){
			//  remove surrounding whitespace
			$pair	= trim( $pair );
			//  empty pair
			if( !$pair )
				//  skip to next
				continue;

			//  default, if no value attached
			$key		= $pair;
			//  default, if no value attached
			$value		= NULL;
			$pattern	= '@^(\S+)'.$separatorPair.'(\S*)$@U';
			//  separator sign found -> value attached
			if( preg_match( $pattern, $pair ) ){
				//  prepare matches array
				$matches	= [];
				//  find all parts
				preg_match_all( $pattern, $pair, $matches );
				//  key is first part
				$key	= $matches[1][0];
				//  value is second part
				$value	= $matches[2][0];
			}
			//  is there a key at all ?
			if( !preg_match( '@^[^'.$separatorPair.']@', $pair ) )
				//  no, key is empty
				throw new InvalidArgumentException( 'Query is invalid.' );

			//  key is ending on [] -> array
			if( preg_match( "/\[\]$/", $key ) ){
				//  remove [] from key
				$key	= preg_replace( "/\[\]$/", "", $key );
				//  array for key is not yet set in list
				if( !isset( $list[$key] ) )
					//  set up array for key in list
					$list[$key]	= [];
				//  add value for key in array in list
				$list[$key][]	= $value;
			}
			//  key is just a string
			else
				//  set value for key in list
				$list[$key]	= $value;
		}
		//  return resulting list
		return $list;
	}
}

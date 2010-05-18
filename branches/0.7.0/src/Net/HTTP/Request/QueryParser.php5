<?php
/**
 *	Parser for HTTP Request Query Strings, for example given by mod_rewrite or own formats.
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
 *	@package		Net.HTTP.Request
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			02.11.2008
 *	@version		$Id$
 */
/**
 *	Parser for HTTP Request Query Strings, for example given by mod_rewrite or own formats.
 *	@category		cmClasses
 *	@package		Net.HTTP.Request
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			02.11.2008
 *	@version		$Id$
 */
class Net_HTTP_Request_QueryParser
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
	public static function toArray( $query, $separatorPairs = "&", $separatorPair = "=" )
	{
		$list	= array();
		$pairs	= explode( $separatorPairs, $query );									//  cut query into pairs
		foreach( $pairs as $pair )														//  iterate all pairs
		{
			$pair	= trim( $pair );													//  remove surrounding whitespace 
			if( !$pair )																//  empty pair
				continue;																//  skip to next

			$key		= $pair;														//  default, if no value attached
			$value		= NULL;															//  default, if no value attached
			$pattern	= '@^(\S+)'.$separatorPair.'(\S*)$@U';
			if( preg_match( $pattern, $pair ) ) 										//  separator sign found -> value attached
			{
				$matches	= array();													//  prepare matches array
				preg_match_all( $pattern, $pair, $matches );							//  find all parts
				$key	= $matches[1][0];												//  key is first part
				$value	= $matches[2][0];												//  value is second part
			}
			if( !preg_match( '@^[^'.$separatorPair.']@', $pair ) ) 						//  is there a key at all ?
				throw new InvalidArgumentException( 'Query is invalid.' );				//  no, key is empty

			if( preg_match( "/\[\]$/", $key ) )											//  key is ending on [] -> array
			{
				$key	= preg_replace( "/\[\]$/", "", $key );							//  remove [] from key
				if( !isset( $list[$key] ) )												//  array for key is not yet set in list
					$list[$key]	= array();												//  set up array for key in list
				$list[$key][]	= $value;												//  add value for key in array in list
			}
			else																		//  key is just a string
				$list[$key]	= $value;													//  set value for key in list
		}
		return $list;																	//  return resulting list
	}
}
?>
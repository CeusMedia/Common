<?php
/**
 *	Parser for HTTP Request Query Strings, for example given by mod_rewrite or own formats.
 *	@package		net.http.request
 *	@author			Christian Würker <Christian.Wuerker@ceus-media.de>
 *	@since			02.11.2008
 *	@version		0.1
 */
/**
 *	Parser for HTTP Request Query Strings, for example given by mod_rewrite or own formats.
 *	@package		net.http.request
 *	@author			Christian Würker <Christian.Wuerker@ceus-media.de>
 *	@since			02.11.2008
 *	@version		0.1
 */
class Net_HTTP_Request_QueryParser
{
	/**
	 *	Parses Query String and returns an Array statically.
	 *	@access		public
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
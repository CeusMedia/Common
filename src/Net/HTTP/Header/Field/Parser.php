<?php
/**
 *	Parser for HTTP Header Fields.
 *
 *	Copyright (c) 2017-2018 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net_HTTP_Header_Field
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2017-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.8.3.4
 */
/**
 *	Parser for HTTP Header Fields.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Header_Field
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2017-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.8.3.4
 */
class Net_HTTP_Header_Field_Parser{

	/**
	 *	Tries to decode qualified values into a map of values ordered by their quality.
	 *
	 *	@static
	 *	@access		public
	 *	@param		string		$string			String of qualified values to decode
	 *	@param		boolean		$sortByLength	Flag: assume longer key as more qualified for keys with same quality (default: FALSE)
	 *	@return		array		Map of qualified values ordered by quality
	 */
	static public function decodeQualifiedValues( $qualifiedValues, $sortByLength = FALSE ){
		$pattern	= '/^(\S+)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/iU';
		$parts		= preg_split( '/,\s*/', $qualifiedValues );
		$codes		= array();
		foreach( $parts as $part )
			if( preg_match ( $pattern, $part, $matches ) )
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

	/**
	 *	Parses a header field string into a header field object.
	 *
	 *	@static
	 *	@access		public
	 *	@param		string		$headerFieldString		String to header field to parse
	 *	@param		boolean		$decodeQualifiedValues	Flag: decode qualified values (default: FALSE)
	 *	@return		Net_HTTP_Header_Field				Header field object
	 *	@throws		InvalidArgumentException			If given string is not a valid header field
	 */
	static public function parse( $headerFieldString, $decodeQualifiedValues = FALSE ){
		if( !preg_match( '/^\S+:\s*.+$/', trim( $headerFieldString ) ) )
		 	throw new InvalidArgumentException( 'Given string is not an HTTP header' );
		list( $key, $value )	= preg_split( '/:/', trim( $headerFieldString ), 2 );
		$value	= trim( $value );
		if( $decodeQualifiedValues )
			$value	= self::decodeQualifiedValues( $value );
		return new Net_HTTP_Header_Field( trim( $key ), $value );
	}
}

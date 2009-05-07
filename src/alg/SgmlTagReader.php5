<?php
/**
 *	Parses SGML based Tags (also HTML, XHTML and XML).
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		alg
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			02.08.2008
 *	@version		0.1
 */
/**
 *	Parses SGML based Tags (also HTML, XHTML and XML).
 *	@package		alg
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			02.08.2008
 *	@version		0.1
 */
class Alg_SgmlTagReader
{
	const TRANSFORM_LOWERCASE	= 1;
	const TRANSFORM_UPPERCASE	= 2;

	/**
	 *	Returns Node Name from Tag.
	 *	@access		public
	 *	@static
	 *	@param		string		$string			String containing exactly 1 SGML based Tag
	 *	@return		string
	 */
	public static function getNodeName( $string, $transform = 0 )
	{
		$data	= self::getTagData( $string );
		switch( $transform )
		{
			case self::TRANSFORM_LOWERCASE:	return strtolower( $data['nodename'] );
			case self::TRANSFORM_UPPERCASE:	return strtoupper( $data['nodename'] );
			default:					return $data['nodename'];
		}
	}
		
	/**
	 *	Returns Attributes from a Tag.
	 *	@access		public
	 *	@static
	 *	@param		string		$string			String containing exactly 1 SGML based Tag
	 *	@return		array
	 */
	public static function getAttributes( $string, $transformKeys = 0 )
	{
		$data	= self::getTagData( $string, $transformKeys );
		return $data['attributes'];
	}

	/**
	 *	Returns Content from a Tag.
	 *	@access		public
	 *	@static
	 *	@param		string		$string			String containing exactly 1 SGML based Tag
	 *	@return		string
	 */
	public static function getContent( $string )
	{
		$data	= self::getTagData( $string );
	}

	/**
	 *	Returns all Information from a Tag.
	 *	@access		public
	 *	@static
	 *	@param		string		$string			String containing exactly 1 SGML based Tag
	 *	@return		array
	 */
	public static function getTagData( $string, $transformKeys = 0 )
	{
		$string		= trim( $string );
		$attributes	= array();
		$content	= "";
		$nodename	= "";
		
		if( preg_match( "@^<([a-z]+)@", $string, $results ) )
			$nodename	= $results[1];
		if( preg_match( "@>([^<]*)<@", $string, $results ) )
			$content	= $results[1];
		if( preg_match_all( '@ (\S+)="([^"]+)"@', $string, $results ) )
		{
			$array	= array_combine( $results[1], $results[2] );
			foreach( $array as $key => $value )
			{
				if( $transformKeys == self::TRANSFORM_LOWERCASE )
					$key	= strtolower( $key );
				else if( $transformKeys == self::TRANSFORM_UPPERCASE )
					$key	= strtoupper( $key );
				$attributes[$key]	= $value;
			}
		}
		if( preg_match_all( "@ (\S+)='([^']+)'@", $string, $results ) )
		{
			$array	= array_combine( $results[1], $results[2] );
			foreach( $array as $key => $value )
			{
				if( $transformKeys == self::TRANSFORM_LOWERCASE )
					$key	= strtolower( $key );
				else if( $transformKeys == self::TRANSFORM_UPPERCASE )
					$key	= strtoupper( $key );
				$attributes[$key]	= $value;
			}
		}
		return array(
			'nodename'		=> $nodename,
			'content'		=> $content,
			'attributes'	=> $attributes
		);
	}
	
/*	public static function transformAttributeValues( $attributes, $transform, $keys = array() )
	{
		$list	= array();
		foreach( $attributes as $key => $value )
		{
			if( !in_array( $key, $keys ) )
				continue;
			if( $transform == self::TRANSFORM_LOWERCASE )
				$value	= strtolower( $value );
			else if( $transform == self::TRANSFORM_UPPERCASE )
				$value	= strtoupper( $value );
			$list[$key]	= $value;
		}
		return $list;
	}*/
}
?>
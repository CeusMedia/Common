<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Parses SGML based Tags (also HTML, XHTML and XML).
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg;

/**
 *	Parses SGML based Tags (also HTML, XHTML and XML).
 *	@category		Library
 *	@package		CeusMedia_Common_Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class SgmlTagReader
{
	public const TRANSFORM_NO			= 0;
	public const TRANSFORM_LOWERCASE	= 1;
	public const TRANSFORM_UPPERCASE	= 2;

	/**
	 *	Returns Attributes from a Tag.
	 *	@access		public
	 *	@static
	 *	@param		string		$string			String containing exactly 1 SGML based Tag
	 *	@param		int			$transformKeys	Flag: transform Attribute Keys
	 *	@return		array
	 */
	public static function getAttributes( string $string, int $transformKeys = self::TRANSFORM_NO ): array
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
	public static function getContent( string $string ): string
	{
		$data	= self::getTagData( $string );
		return $data['content'];
	}

	/**
	 *	Returns Node Name from Tag.
	 *	@access		public
	 *	@static
	 *	@param		string		$string			String containing exactly 1 SGML based Tag
	 *	@param		int			$transform		Flag: transform Attribute Keys
	 *	@return		string
	 */
	public static function getNodeName( string $string, int $transform = self::TRANSFORM_NO ): string
	{
		$data	= self::getTagData( $string );
		switch( $transform ){
			case self::TRANSFORM_LOWERCASE:
				return strtolower( $data['nodename'] );
			case self::TRANSFORM_UPPERCASE:
				return strtoupper( $data['nodename'] );
			default:
				return $data['nodename'];
		}
	}

	/**
	 *	Returns all Information from a Tag.
	 *	@access		public
	 *	@static
	 *	@param		string		$string			String containing exactly 1 SGML based Tag
	 *	@param		int			$transformKeys	Flag: transform Attribute Keys
	 *	@return		array
	 */
	public static function getTagData( string $string, int $transformKeys = self::TRANSFORM_NO ): array
	{
		$string		= trim( $string );
		$attributes	= [];
		$content	= '';
		$nodename	= '';

		if( preg_match( "@^<([a-z]+)@", $string, $results ) )
			$nodename	= $results[1];
		if( preg_match( "@>([^<]*)<@", $string, $results ) )
			$content	= $results[1];
		if( preg_match_all( '@ (\S+)="([^"]+)"@', $string, $results ) ){
			$array	= array_combine( $results[1], $results[2] );
			foreach( $array as $key => $value ){
				if( $transformKeys == self::TRANSFORM_LOWERCASE )
					$key	= strtolower( $key );
				else if( $transformKeys == self::TRANSFORM_UPPERCASE )
					$key	= strtoupper( $key );
				$attributes[$key]	= $value;
			}
		}
		if( preg_match_all( "@ (\S+)='([^']+)'@", $string, $results ) ){
			$array	= array_combine( $results[1], $results[2] );
			foreach( $array as $key => $value ){
				if( $transformKeys == self::TRANSFORM_LOWERCASE )
					$key	= strtolower( $key );
				else if( $transformKeys == self::TRANSFORM_UPPERCASE )
					$key	= strtoupper( $key );
				$attributes[$key]	= $value;
			}
		}
		return [
			'nodename'		=> $nodename,
			'content'		=> $content,
			'attributes'	=> $attributes,
		];
	}

/*	public static function transformAttributeValues( $attributes, $transform, $keys = [] )
	{
		$list	= [];
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

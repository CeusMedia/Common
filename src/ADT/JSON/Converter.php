<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
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
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT\JSON;

use InvalidArgumentException;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			code doc
 *	@todo			unit test
 *	@deprecated		use json_decode( $string, TRUE ) instead
 */
class Converter
{
	public static function convertToArray( string $json ): array
	{
		$json	= json_decode( $json );
		if( $json === FALSE )
			throw new InvalidArgumentException( 'JSON String is not valid.' );
		$array	= [];
		self::convertToArrayRecursive( $json, $array );
		return $array;
	}

	/**
	 *	@param		object|array		$node
	 *	@param		array				$array
	 *	@param		string|int|NULL		$name
	 *	@return		void
	 */
	protected static function convertToArrayRecursive( $node, &$array, $name = NULL )
	{
		if( $name ){
			if( is_object( $node ) )
				foreach( get_object_vars( $node ) as $key => $value )
					self::convertToArrayRecursive( $value, $array[$name], $key );
			else
				$array[$name]	= $node;
		}
		else{
			if( is_object( $node ) )
				foreach( get_object_vars( $node ) as $key => $value )
					self::convertToArrayRecursive( $value, $array, $key );
			else
				$array	= $node;
		}
	}
}

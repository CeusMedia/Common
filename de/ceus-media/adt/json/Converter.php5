<?php
/**
 *	...
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
 *	@package		adt.json
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 */
/**
 *	...
 *	@package		adt.json
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@todo			code doc
 *	@todo			unit test
 *	@deprecated		use json_decode( $string, TRUE ) instead
 */
class ADT_JSON_Converter
{
	public static function convertToArray( $json )
	{
		if( is_string( $json ) )
		{
			$json	= json_decode( $json );
			if( $json === FALSE )
				throw new InvalidArgumentException( 'JSON String is not valid.' );
		}
		$array	= array();
		self::convertToArrayRecursive( $json, $array );
		return $array;
	}

	protected static function convertToArrayRecursive( $node, &$array, $name = NULL )
	{
		if( $name )
		{
			if( is_object( $node ) )
				foreach( get_object_vars( $node ) as $key => $value )
					self::convertToArrayRecursive( $value, $array[$name], $key );
			else
				$array[$name]	= $node;
		}
		else
		{
			if( is_object( $node ) )
				foreach( get_object_vars( $node ) as $key => $value )
					self::convertToArrayRecursive( $value, $array, $key );
			else
				$array	= $node;
		}
	}
}
?>
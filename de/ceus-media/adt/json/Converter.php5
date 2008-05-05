<?php
class ADT_JSON_Converter
{
	public static function convertToArray( $json )
	{
		if( is_string( $json ) )
			$json	= json_decode( $json );
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
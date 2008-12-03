<?php
class ALG_Preg_Match
{
	public static function accept( $pattern, $string, $modifiers = NULL )
	{
		if( !is_string( $pattern ) )
			throw new InvalidArgumentException( 'First parameter must be a String ('.gettype( $pattern ).' given).' );
		if( !is_string( $string ) )
			throw new InvalidArgumentException( 'Second parameter must be a String ('.gettype( $string ).' given).' );
		if( !is_string( $modifiers ) )
		{
			switch( gettype( $modifiers ) )
			{
				case 'string':
					$modifiers	= (string) $modifiers;
					break;
				case 'array':
					$modifiers	= implode( "", array_values( $modifiers ) );
				case 'object':
					if( is_a( $modifiers, 'ADT_List_Dictionary' ) )
					{
						$modifiers	= implode( "", array_values( $modifiers->getAll() ) );
						break;
					}
				default:
					throw new InvalidArgumentException( 'Modifiers must be a String, Array or Dictionary.' );
			}
		}
		$pattern	= str_replace( "/", "\/", $pattern );
		$match		= @preg_match( "/".$pattern."/".(string) $modifiers, $string, $matches );
		if( $match === FALSE )
			throw new InvalidArgumentException( 'Pattern "'.$pattern.'" is invalid.' );
		if( $match )
			return $matches[0];
		return NULL;
	}
}
?>
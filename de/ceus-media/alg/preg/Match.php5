<?php
/**
 *	Matches String against regular expression.
 *	@package		alg.preg
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@since			03.12.2008
 *	@version		0.1
 *	@see			http://de.php.net/preg_match
 */
/**
 *	Matches String against regular expression.
 *	@package		alg.preg
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@since			03.12.2008
 *	@version		0.1
 *	@see			http://de.php.net/preg_match
 */
class ALG_Preg_Match
{
	/**
	 *	Indicates whether a String matches a regular expression.
	 *	@access		public
	 *	@param		string		$pattern		Regular expression, pattern String
	 *	@param		string		$string			String to test
	 *	@param		array		$modifiers		String, Array of Dictionary or Modifiers
	 *	@return		bool
	 */
	public static function accept( $pattern, $string, $modifiers = NULL )
	{
		if( !is_string( $pattern ) )
			throw new InvalidArgumentException( 'First parameter must be a String ('.gettype( $pattern ).' given).' );
		if( !is_string( $string ) )
			throw new InvalidArgumentException( 'Second parameter must be a String ('.gettype( $string ).' given).' );

		switch( gettype( $modifiers ) )
		{
			case 'NULL':
				$modifiers	= "";
				break;
			case 'string':
				break;
			case 'array':
				$modifiers	= implode( "", array_values( $modifiers ) );
				break;
			case 'object':
				if( is_a( $modifiers, 'ADT_List_Dictionary' ) )
				{
					$modifiers	= implode( "", array_values( $modifiers->getAll() ) );
					break;
				}
			default:
				throw new InvalidArgumentException( 'Modifiers must be a String, Array or Dictionary.' );
		}
		$pattern	= str_replace( "/", "\/", $pattern );
		$match		= @preg_match( "/".$pattern."/".(string) $modifiers, $string, $matches );
		if( $match === FALSE )
			throw new InvalidArgumentException( 'Pattern "'.$pattern.'" is invalid.' );
		if( $match )
			return $matches[0];
		return FALSE;
	}
}
?>
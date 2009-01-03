<?php
/**
 *	Converter for Strings using different ways of Camel Case.
 *	@package			alg
 *	@author				Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since				20.10.2008
 *	@version			0.1
 */
/**
 *	Converter for Strings using different ways of Camel Case.
 *	@package			alg
 *	@author				Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since				20.10.2008
 *	@version			0.1
 */
class Alg_CamelCase
{
	protected static $regExp	= '/^(.*)[\-\_ ](.*)$/';
	public static $lowercaseFirst	= NULL;
	public static $lowercaseLetter	= NULL;

	/**
	 *	Convert a String to Camel Case, removing all spaces and underscores and capitalizing all Words.
	 *	@access		public
	 *	@param		string		$string		String to convert
	 *	@param		bool		$startLow	Flag: convert first Word also to uppercase, use static default if NULL
	 *	@return		string
	 */
	public static function convert( $string, $lowercaseFirst = NULL, $lowercaseLetter = NULL )
	{
		$lowercaseFirst		= is_null( $lowercaseFirst ) ? self::$lowercaseFirst : $lowercaseFirst;
		$lowercaseLetter	= is_null( $lowercaseLetter ) ? self::$lowercaseLetter : $lowercaseLetter;

		if( $lowercaseLetter === TRUE )
			$string	= strToLower( $string );

		if( $lowercaseFirst === TRUE )
			$string[0]	= strToLower( $string[0] );
		else if( $lowercaseFirst === FALSE )
			$string	= ucFirst( $string );

		while( preg_match( self::$regExp, $string, $matches ) )
		  $string	= $matches[1].ucfirst( $matches[2] );
		return $string;
	}
}
?>
<?php
/**
 *	Sniffer for Character Sets accepted by a HTTP Request.
 *	@package		net.http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.08.2005
 *	@version		0.6
 */
/**
 *	Sniffer for Character Sets accepted by a HTTP Request.
 *	@package		net.http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.08.2005
 *	@version		0.6
 */
class Net_HTTP_CharsetSniffer
{
	/**	@var		$pattern	Reg Ex Pattern */
	protected static $pattern	= '/^([0-9a-z-]+)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i';

	/**
	 *	Returns prefered allowed and accepted Character Set from HTTP_ACCEPT_CHARSET.
	 *	@access		public
	 *	@param		array	$allowed		Array of Character Sets supported and allowed by the Application
	 *	@param		string	$default		Default Character Sets supported and allowed by the Application
	 *	@return		string
	 */
	public static function getCharset( $allowed, $default = false )
	{
		$accepted	= getEnv( 'HTTP_ACCEPT_CHARSET' );
		return self::getCharsetFromString( $accepted, $allowed, $default );
	}
	
	/**
	 *	Returns prefered allowed and accepted Character Set from String.
	 *	@access		public
	 *	@param		array	$allowed		Array of Character Sets supported and allowed by the Application
	 *	@param		string	$default		Default Character Sets supported and allowed by the Application
	 *	@return		string
	 */
	public static function getCharsetFromString( $string, $allowed, $default = false )
	{
		if( !$default)
			$default = $allowed[0];
		if( !$string )
			return $default;
		$accepted	= preg_split( '/,\s*/', $string );
		$currentCharset	= $default;
		$currentQuality	= 0;
		foreach( $accepted as $accept )
		{
			if( !preg_match ( self::$pattern, $accept, $matches ) )
				continue;
			$charsetQuality	= isset( $matches[2] ) ? (float) $matches[2] : 1.0;
			if( $charsetQuality > $currentQuality )
			{
				$currentCharset	= strtolower( $matches[1] );
				$currentQuality	= $charsetQuality;
			}
		}
		return $currentCharset;
	}
}
?>
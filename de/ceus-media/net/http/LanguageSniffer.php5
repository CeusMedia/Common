<?php
/**
 *	Sniffer for Languages accepted by a HTTP Request.
 *	@package		net.http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.08.2005
 *	@version		0.6
 */
/**
 *	Sniffer for Languages accepted by a HTTP Request.
 *	@package		net.http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.08.2005
 *	@version		0.6
 */
class Net_HTTP_LanguageSniffer
{
	/**	@var		$pattern	Reg Ex Pattern */
	protected static $pattern	= '/^([a-z]{1,8}(?:-[a-z]{1,8})*)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i';

	/**
	 *	Returns prefered allowed and accepted Language from HTTP_ACCEPT_LANGUAGE.
	 *	@access		public
	 *	@param		array	$allowed		Array of Languages supported and allowed by the Application
	 *	@param		string	$default		Default Languages supported and allowed by the Application
	 *	@return		string
	 */
	public static function getLanguage( $allowed, $default = false )
	{
		$accept	= getEnv( 'HTTP_ACCEPT_LANGUAGE' );
		return self::getLanguageFromString( $accept, $allowed, $default );
	}

	/**
	 *	Returns prefered allowed and accepted Language from String.
	 *	@access		public
	 *	@param		array	$allowed		Array of Languages supported and allowed by the Application
	 *	@param		string	$default		Default Languages supported and allowed by the Application
	 *	@return		string
	 */
	public static function getLanguageFromString( $string, $allowed, $default = false )
	{
		if( !$default)
			$default = $allowed[0];
		if( !$string )
			return $default;
		$accepted	= preg_split( '/,\s*/', $string );
		$currentLanguage	= $default;
		$currentQuality		= 0;
		foreach( $accepted as $accept )
		{
			if( !preg_match( self::$pattern, $accept, $matches ) )
				continue;
			$languageCode = explode ( '-', $matches[1] );
			$languageQuality =  isset( $matches[2] ) ? (float) $matches[2] : 1.0;
			while( count( $languageCode ) )
			{
				if( in_array( strtolower( join( '-', $languageCode ) ), $allowed ) )
				{
					if( $languageQuality > $currentQuality )
					{
						$currentLanguage	= strtolower( join( '-', $languageCode ) );
						$currentQuality		= $languageQuality;
						break;
					}
				}
				array_pop( $languageCode );
			}
		}
		return $currentLanguage;
	}
}
?>
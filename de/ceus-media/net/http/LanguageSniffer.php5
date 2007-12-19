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
	/**
	 *	Returns prefered allowed and accepted Language.
	 *	@access		public
	 *	@param		array	$allowed		Array of Languages supported and allowed by the Application
	 *	@param		string	$default		Default Languages supported and allowed by the Application
	 *	@return		string
	 */
	public function getLanguage( $allowed, $default = false )
	{
		if( !$default)
			$default = $allowed[0];
		$pattern		= '/^([a-z]{1,8}(?:-[a-z]{1,8})*)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i';
		$accepted	= getEnv( 'HTTP_ACCEPT_LANGUAGE' );
		if( !$accepted )
			return $default;
		$accepted	= preg_split( '/,\s*/', $accepted );
		$curr_lang	= $default;
		$curr_qual	= 0;
		foreach( $accepted as $accept )
		{
			if( !preg_match ( $pattern, $accept, $matches ) )
				continue;
			$lang_code = explode ( '-', $matches[1] );
			$lang_quality =  isset( $matches[2] ) ? (float) $matches[2] : 1.0;
			while( count( $lang_code ) )
			{
				if( in_array( strtolower( join( '-', $lang_code ) ), $allowed ) )
				{
					if( $lang_quality > $curr_qual )
					{
						$curr_lang	= strtolower( join( '-', $lang_code ) );
						$curr_qual	= $lang_quality;
						break;
					}
				}
				array_pop ($lang_code);
			}
		}
		return $curr_lang;
	}
}
?>
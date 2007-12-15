<?php
/**
 *	Sniffer for Character Sets accepted by a HTTP Request.
 *	@package		net.http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.08.2005
 *	@version		0.5
 */
/**
 *	Sniffer for Character Sets accepted by a HTTP Request.
 *	@package		net.http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.08.2005
 *	@version		0.5
 */
class Net_HTTP_CharsetSniffer
{
	/**
	 *	Returns prefered allowed and accepted Character Set.
	 *	@access		public
	 *	@param		array	$allowed		Array of Character Sets supported and allowed by the Application
	 *	@param		string	$default		Default Character Sets supported and allowed by the Application
	 *	@return		string
	 */
	function getCharset( $allowed, $default = false )
	{
		if( !$default)
			$default = $allowed[0];
		$pattern		= '/^([0-9a-z-]+)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i';
		$accepted	= getEnv( 'HTTP_ACCEPT_CHARSET' );
		if( !$accepted )
			return $default;
		$accepted	= preg_split( '/,\s*/', $accepted );
		$curr_charset	= $default;
		$curr_qual	= 0;
		foreach( $accepted as $accept)
		{
			if( !preg_match ( $pattern, $accept, $matches) )
				continue;
			$charset_code	= explode ( '-', $matches[1] );
			$charset_quality	=  isset( $matches[2] ) ? (float)$matches[2] : 1.0;
			while (count ($charset_code))
			{
				if( in_array( strtolower( $charset_code ), $allowed ) )
				{
					if( $charset_quality > $curr_qual )
					{
						$curr_charset	= strtolower( $charset_code );
						$curr_qual	= $lcharset_quality;
						break;
					}
				}
				array_pop ($charset_code);
			}
		}
		return $curr_charset;
	}
}
?>
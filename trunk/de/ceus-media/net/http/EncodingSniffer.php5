<?php
/**
 *	Sniffer for Encoding Methods accepted by a HTTP Request.
 *	@package		net.http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.08.2005
 *	@version		0.6
 */
/**
 *	Sniffer for Encoding Methods accepted by a HTTP Request.
 *	@package		net.http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.08.2005
 *	@version		0.6
 */
class Net_HTTP_EncodingSniffer
{
	/**
	 *	Returns prefered allowed and accepted Encoding Method.
	 *	@access		public
	 *	@param		array	$allowed		Array of Encoding Methods supported and allowed by the Application
	 *	@param		string	$default		Default Encoding Methods supported and allowed by the Application
	 *	@return		string
	 */
	public static function getEncoding( $allowed, $default = NULL )
	{
		if( !$default)
			$default = $allowed[0];
		else if( !in_array( $default, $allowed ) )
			throw new InvalidArgumentException( 'Default Encoding Method must be an allowed Encoding Method.' );
		
		$pattern	= '/^([a-z]+)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i';
		$accepted	= getEnv( 'HTTP_ACCEPT_ENCODING' );
		if( !$accepted )
			return $default;
		$accepted		= preg_split( '/,\s*/', $accepted );
		$currentCode	= $default;
		$currentQuality	= 0;
		foreach( $accepted as $accept )
		{
			if( !preg_match ( $pattern, $accept, $matches ) )
				continue;
			$codeQuality	=  isset( $matches[2] ) ? (float) $matches[2] : 1.0;
			if( in_array( $matches[1], $allowed ) )
			{
				if( $codeQuality > $currentQuality )
				{
					$currentCode	= $matches[1];
					$currentQuality	= $codeQuality;
				}
			}
		}
		return $currentCode;
	}
}
?>
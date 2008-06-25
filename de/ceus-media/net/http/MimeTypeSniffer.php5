<?php
/**
 *	Sniffer for Mime Types accepted by a HTTP Request.
 *	@package		net.http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.08.2005
 *	@version		0.6
 */
/**
 *	Sniffer for Mime Types accepted by a HTTP Request.
 *	@package		net.http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.08.2005
 *	@version		0.6
 */
class Net_HTTP_MimeTypeSniffer
{
	/**
	 *	Returns prefered allowed and accepted Mime Types.
	 *	@access		public
	 *	@param		array	$allowed		Array of Mime Types supported and allowed by the Application
	 *	@param		string	$default		Default Mime Types supported and allowed by the Application
	 *	@return		string
	 */
	public function getMimeType( $allowed, $default = false )
	{
		if( !$default)
			$default = $allowed[0];
		$pattern		= '@^([a-z\*\+]+(/[a-z\*\+]+)*)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$@i';
		$accepted	= getEnv( 'HTTP_ACCEPT' );
		if( !$accepted )
			return $default;
		$accepted	= preg_split( '/,\s*/', $accepted );
		$curr_mime	= $default;
		$curr_qual	= 0;
		foreach( $accepted as $accept)
		{
			if( !preg_match ( $pattern, $accept, $matches ) )
				continue;
			$mime_code = explode ( '/', $matches[1] );
			$mime_quality =  isset( $matches[3] ) ? (float) $matches[3] : 1.0;
			while( count( $mime_code ) )
			{
				if( in_array( strtolower( join( '/', $mime_code ) ), $allowed ) )
				{
					if( $mime_quality > $curr_qual )
					{
						$curr_mime	= strtolower( join( '-', $mime_code ) );
						$curr_qual	= $mime_quality;
						break;
					}
				}
				array_pop( $mime_code );
			}
		}
		return $curr_mime;
	}
}
?>
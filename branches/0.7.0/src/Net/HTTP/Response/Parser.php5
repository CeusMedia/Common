<?php
class Net_HTTP_Response_Parser
{
	public static function fromRequest( Net_HTTP_Request $request )
	{
		$response	= $request->send();
		return self::fromString( $response );
	}

	public static function fromString( $string )
	{
		$string	= trim( $string );
		$lines	= explode( "\r\n", $string );
		$state	= 0;
		$data	= '';
		foreach( $lines as $line )
		{
			if( !$state )
			{
				$pattern	= '/^([A-Z]+)\/([0-9.]+) ([0-9]{3}) ?(.+)?/';
				if( !preg_match( $pattern, $line ) )
					throw new Exception( 'Invalid response' );
				$matches	= array();
				preg_match_all( $pattern, $line, $matches );
				$response	= new Net_HTTP_Response( $matches[1][0], $matches[2][0] );
				$response->setStatus( $matches[3][0] );
				$state	= 1;
			}
			else if( $state == 1 )
			{
				if( !trim( $line ) )
				{
					$state	= 2;
					continue;
				}
				$pattern	= '/([a-z-]+):\s(.+)/i';
				if( !preg_match( $pattern, $line ) )
					throw new InvalidArgumentException( 'Invalid header: '.$line );
				$parts	= explode( ":", $line );
				$key	= array_shift( $parts );
				$value	= trim( implode( ':', $parts ) );
				$response->headers->addHeader( new Net_HTTP_Header( $key, $value ) );
			}
			else if( $state == 2 )
			{
				$data	.= $line."\r\n";
			}
		}
		$response->setBody( $data );
		return $response;
	}
}
?>

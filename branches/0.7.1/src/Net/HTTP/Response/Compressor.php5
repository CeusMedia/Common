<?php
class Net_HTTP_Response_Compressor
{

	public static function compressResponse( Net_HTTP_Response $response, $type = NULL, $sendLengthHeader = TRUE )
	{
		if( !$type )
			return;
		$response->setBody( self::compressString( $response->getBody(), $type ) );
		$response->addHeaderPair( 'Content-Encoding', $type );							//  send Encoding Header
		if( $sendLengthHeader )
		{
			$length		= strlen( $response->getBody() );
			$response->addHeaderPair( 'Content-Length', $length, TRUE );				//  send Content-Length Header
		}
	}

	public static function compressString( $content, $type = NULL )
	{
		switch( $type )
		{
			case NULL:
				return $content;
			case 'deflate':
				return gzcompress( $content );											//  compress Content
			case 'gzip':
				return gzencode( $content );											//  compress Content
			default:																	//  no valid Compression Method set
				throw new InvaligArgumentException( 'Compression "'.$type.'" is not supported' );
		}
	}
}
?>

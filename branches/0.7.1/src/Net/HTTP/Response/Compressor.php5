<?php
class Net_HTTP_Response_Compressor
{

	public static function compressResponse( Net_HTTP_Response $response, $type = NULL, $sendLengthHeader = TRUE )
	{
		if( !$type )
			return;
		self::compressString( $content, $type );
		$response->addHeader( new Net_HTTP_Header( 'Content-Encoding', $type ) );		//  send Encoding Header
		if( $sendLengthHeader )
			$response->addHeader( strlen( $response->getBody() ), TRUE );				//  send Content-Length Header
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

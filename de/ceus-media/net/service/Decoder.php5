<?php
/**
 *	Decompresses and decodes Service Response Strings in several Formats.
 *	Can be overwritten to extend with further Formats or Compression Methods.
 *	@package		net.service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.04.2009
 *	@version		0.1
 */
/**
 *	Decompresses and decodes Service Response Strings in several Formats.
 *	Can be overwritten to extend with further Formats or Compression Methods.
 *	@package		net.service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.04.2009
 *	@version		0.1
 *	@todo			fix: not reproducable errors using gzuncompress on format 'txt'
 */
class Net_Service_Decoder
{
	/**	@var		array		$compressionTypes	List of supported Compression Types */
	protected $compressionTypes	= array(
		'deflate'	=> 'gzipUncompress',
		'gzip'		=> 'gzipDecode',
	);

	public $formats	= array(
		'json'	=> 'decodeJson',
		'php'	=> 'decodePhp',
		'wddx'	=> 'decodeWddx',
		'xml'	=> 'decodeXml',
	);

	/**
	 *	Decodes Responses in using Methods assigned to Format.
	 *	@access		public
	 *	@param		string		$response			Response Content as serialized String
	 *	@param		string		$format				Format of Serial (json|php|wddx|xml|rss|txt|...)
	 *	@return		mixed
	 */
	public function decodeResponse( $response, $format )
	{
		if( !array_key_exists( $format, $this->formats ) )						//  other Formats like Text or HTML
			return $response;													//  bypass Response Content undecoded

		ob_start();																		//  open Buffer for PHP Error Messages
		$method		= $this->formats[$format];									//  get Name of Method to decode Response
		$structure	= $this->$method( $response );								//  call Method to decode Response

		$data		= $structure['data'];										//  Extract Response Data
		if( $structure['status'] == "exception" )								//  Response contains an Exception
			throw new Exception( $data['type'].": ".$data['message'] );			//  throw Exception and carry Message 

		$output	= ob_get_clean();												//  close Buffer for PHP Error Messages
		if( $structure === FALSE )												//  could not decode
			return $output;														//  return Error Message instead
		return $data;															//  return decoded Response Data
	}

	/**
	 *	Decodes Response String encoded in JSON.
	 *	@access		protected
	 *	@param		string		$response			Response Content as serialized String
	 *	@return		array
	 */
	protected function decodeJson( $response )
	{
		$structure	= json_decode( $response, TRUE );							//  try to decode JSON Response
		return $structure;
	}

	/**
	 *	Decodes Response String encoded as PHP Serial.
	 *	@access		protected
	 *	@param		string		$response			Response Content as serialized String
	 *	@return		array
	 */
	protected function decodePhp( $response )
	{
		$structure	= unserialize( $response );									//  try to decode PHP Response
		if( $structure && $structure instanceof Exception )						//  Response is Exception
			throw $structure;													//  throw Response Exception
		return $structure;
	}
		
	/**
	 *	Decodes Response String encoded as WDDX Package.
	 *	@access		protected
	 *	@param		string		$response			Response Content as serialized String
	 *	@return		array
	 */
	protected function decodeRss( $response )
	{
		$content	= XML_RSS_Parser::parse( $response );
		$structure	= array(
			'status'	=> "data",
			'data'		=> $content,
		);
		return $structure;
	}
		
	/**
	 *	Decodes Response String encoded as WDDX Package.
	 *	@access		protected
	 *	@param		string		$response			Response Content as serialized String
	 *	@return		array
	 */
	protected function decodeWddx( $response )
	{
		$structure	= wddx_deserialize( $response );							//  try to decode WDDX Response
		return $structure;
	}
		
	/**
	 *	Decodes Response String encoded in XML.
	 *	@access		protected
	 *	@param		string		$response			Response Content as serialized String
	 *	@return		array
	 */
	protected function decodeXml( $response )
	{
		$doc	= new SimpleXmlElement( $response );
		if( strtolower( $doc->status ) == "exception" )
			throw new Exception( $doc->data->type.": ".$doc->data->message );
		$structure	= array(
			'status'	=> $doc->status,
			'data'		=> $doc->data,
		);
		return $structure;
	}

	/**
	 *	Decompresses compressed Response Content.
	 *	@access		public
	 *	@param		string		$content			Response Content, compressed
	 *	@param		string		$type				Compression Type used for compressing Response
	 *	@param		int			$fallback			Flag: use first Method of Type not found
	 *	@return		string
	 */
	public function decompressResponse( $content, $type, $fallback = FALSE )
	{
		if( !array_key_exists( $type, $this->compressionTypes ) )
		{
			if( $fallback )
				$type	= $this->compressionTypes[0];
			else
				throw new Exception( 'Decompression Method "'.$type.'" is not supported.' );
		}
		ob_start();
		$method		= $this->compressionTypes[$type];							//  get Name of Method to decompress Response Content
		$result		= $this->$method( $content );								//  call Method to decompress Response Content
		$output		= ob_get_clean();											//  close Buffer for PHP Error Messages
		if( $result === FALSE && $output )										//  could not decompress
			throw new RuntimeException( $output );								//  throw Exception and carry Error Message 
		return $result;															//  return decompressed Response Content
	}

	/**
	 *	Decompresses gzipped String. Function is missing in some PHP Win Builds.
	 *	@access		protected
	 *	@param		string		$data				Data String to be decompressed
	 *	@return		string
	 */
	protected function gzipDecode( $data )
	{
		if( function_exists( 'gzdecode' ) )
			return gzdecode( $data );
		$flags	= ord( substr( $data, 3, 1 ) );
		$headerlen		= 10;
		$extralen		= 0;
		if( $flags & 4 )
		{
			$extralen	= unpack( 'v' ,substr( $data, 10, 2 ) );
			$extralen	= $extralen[1];
			$headerlen	+= 2 + $extralen;
		}
		if( $flags & 8 ) 												// Filename
			$headerlen = strpos( $data, chr( 0 ), $headerlen ) + 1;
		if( $flags & 16 )												// Comment
			$headerlen = strpos( $data, chr( 0 ), $headerlen ) + 1;
		if( $flags & 2 )												// CRC at end of file
			$headerlen	+= 2;
		return gzinflate( substr( $data, $headerlen ) );
	}
	
	/**
	 *	Decompresses deflated String.
	 *	@access		protected
	 *	@param		string		$data				Data String to be decompressed
	 *	@return		string
	 */
	protected function gzipUncompress( $content )
	{
		return gzuncompress( $content );
	}
}
?>
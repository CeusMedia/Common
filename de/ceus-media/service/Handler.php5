<?php
import( 'de.ceus-media.net.http.request.Response' );
/**
 *	Service Handlers for HTTP Requests.
 *	@package		service
 *	@uses			Net_HTTP_Request_Response
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.6
 */
/**
 *	Service Handlers for HTTP Requests.
 *	@package		service
 *	@uses			Net_HTTP_Request_Response
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.6
 */
class Service_Handler
{
	/**	@var		string		$charset				Character Set of Response */
	public $charset	= "utf-8";		
	/**	@var		array		$compressionTypes		List of supported Compression Types */
	protected $compressionTypes	= array(
		'deflate',
		'gzip',
	);
	/**	@var		array		$contentTypes			Array of supported Content Types */
	protected $contentTypes	= array(
		'html'		=> "text/html",
		'json'		=> "text/javascript",
		'php'		=> "text/html",
		'txt'		=> "text/html",
		'xml'		=> "text/xml",
		'rss'		=> "application/rss+xml",
		'atom'		=> "application/atom+xml",
		'wddx'		=> "text/xml",
	);

	/**
	 *	Constructor.
	 *	@param		Service_Point	$servicePoint		Services Class
	 *	@param		array			$availableFormats	Available Response Formats
	 *	@return		void
	 */
	public function __construct( Service_Point $servicePoint, $availableFormats )
	{
		$this->servicePoint		= $servicePoint;
		$this->availableFormats	= $availableFormats;
	}

	/**
	 *	Handles Service Call by sending HTTP Response and returns Length of Response Content.
	 *	@param		array			$requestData			Request Array or Object
	 *	@param		bool			$serializeException		Flag: serialize Exceptions instead of throwing
	 *	@return		int
	 */
	public function handle( $requestData, $serializeException = false )
	{
		if( empty( $requestData['service'] ) )
			throw new InvalidArgumentException( 'No Service Name given.' );
		try
		{
			ob_start();
			$service	= $requestData['service'];
			$format		= $requestData['format'];
			$response	= $this->servicePoint->callService( $service, $format, $requestData );
			$exception	= ob_get_clean();
			if( $exception )
				throw new RuntimeException( $exception );
			$format		= $format ? $format : $this->servicePoint->getDefaultServiceFormat( $service );
			$compress	= isset( $requestData['compressResponse'] ) ? strtolower( $requestData['compressResponse'] ) : "";
			if( $compress )
			{
				if( !in_array( $compress, $this->compressionTypes ) )
					$compress	= $this->compressionTypes[0];
				$response	= self::compressResponse( $response, $compress );
			}
			return $this->sendResponse( $response, $format, $compress );

		}
		catch( Exception $e )
		{
			if( $serializeException )
				$message	= serialize( $e );
			else
				$message	= $e->getMessage();
			return $this->sendResponse( $message );
		}
	}

	/**
	 *	Compresses Response String using one of the supported Compressions.
	 *	@access		protected
	 *	@param		string			$content		Content of Response
	 *	@param		string			$type			Compression Type
	 *	@return		string
	 */
	protected static function compressResponse( $content, $type )
	{
		switch( $type )
		{
			case 'deflate':
				$content	= gzcompress( $content );
				break;
			case 'gzip':
				$content	= gzencode( $content );
				break;
			default:
		}
		return $content;
	}

	/**
	 *	Sends HTTP Response with Headers.
	 *	@access		protected
	 *	@param		string			$content		Content of Response
	 *	@return		int
	 */
	protected function sendResponse( $content, $format = "html", $compressionType = NULL )
	{
		if( !array_key_exists( $format, $this->contentTypes ) )
			throw new InvalidArgumentException( 'Content Type for Response Format "'.$format.'" is not defined.' );
		$contentType	= $this->contentTypes[$format];
		if( $this->charset )
			$contentType	.= "; charset=".$this->charset;

		$response	= new Net_HTTP_Request_Response();
		$response->write( $content );
		$response->addHeader( 'Last-Modified', date( 'r' ) );
		$response->addHeader( 'Cache-Control', "no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0" );
		$response->addHeader( 'Pragma', "no-cache" );
		$response->addHeader( 'Content-Type', $contentType );
		$response->addHeader( 'Content-Length', strlen( $content ) );
		if( $compressionType )
			$response->addHeader( 'Content-Encoding', $compressionType );
		
		return $response->send();
	}
}
?>
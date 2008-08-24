<?php
import( 'de.ceus-media.net.http.request.Response' );
/**
 *	Service Handlers for HTTP Requests.
 *	@package		net.service
 *	@uses			Net_HTTP_Request_Response
 *	@uses			UI_HTML_Exception_TraceViewer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.6
 */
/**
 *	Service Handlers for HTTP Requests.
 *	@package		net.service
 *	@uses			Net_HTTP_Request_Response
 *	@uses			UI_HTML_Exception_TraceViewer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.6
 */
class Net_Service_Handler
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
	 *	@param		Net_Service_Point	$servicePoint		Services Class
	 *	@param		array				$availableFormats	Available Response Formats
	 *	@return		void
	 */
	public function __construct( Net_Service_Point $servicePoint, $availableFormats )
	{
		$this->servicePoint		= $servicePoint;
		$this->availableFormats	= $availableFormats;
	}

	/**
	 *	Handles Service Call by sending HTTP Response and returns Length of Response Content.
	 *	@param		array			$requestData			Request Array (or Object with ArrayAccess Interface)
	 *	@param		bool			$serializeException		Flag: serialize Exceptions instead of throwing
	 *	@return		int
	 */
	public function handle( $requestData, $serializeException = FALSE )
	{
		if( empty( $requestData['service'] ) )
			throw new InvalidArgumentException( 'No Service Name given.' );


		//  --  CALL SERVICE  --  //
		$service	= $requestData['service'];
		try
		{
			$format		= ( isset( $requestData['format'] ) && $requestData['format'] ) ? $requestData['format'] : $this->servicePoint->getDefaultServiceFormat( $service );
			$serializeException	= strtolower( $format ) == "php";
			ob_start();
			
			if( isset( $requestData['argumentsGivenByServiceCaller'] ) )
			{
				$parameters	= array_keys( $this->servicePoint->getServiceParameters( $service ) );
				$arguments	= unserialize( stripslashes( $requestData['argumentsGivenByServiceCaller'] ) );
				for( $i=0; $i<count( $arguments ); $i++ )
					$requestData[$parameters[$i]]	= $arguments[$i];
				unset( $requestData['argumentsGivenByServiceCaller'] );
			}
			$response	= $this->servicePoint->callService( $service, $format, $requestData );
			$errors		= ob_get_clean();
			if( $errors )
				throw new RuntimeException( $errors );
			return $this->sendResponse( $requestData, $response, $format );
		}
		catch( ServiceParameterException $e )
		{
			$response	= $e->getMessage();
			return $this->sendResponse( $requestData, $response );
		}
		catch( ServiceException $e )
		{
			$response	= $e->getMessage();
			return $this->sendResponse( $requestData, $response );
		}
		catch( PDOException $e )
		{
			import( 'de.ceus-media.ui.html.exception.TraceViewer' );
			$response	= UI_HTML_Exception_TraceViewer::buildTrace( $e, 2 );
			return $this->sendResponse( $requestData, $response );
		}
		catch( Exception $e )
		{
			$response	= $e->getMessage();
			if( isset( $requestData['showExceptions'] ) )
			{
				import( 'de.ceus-media.ui.html.exception.TraceViewer' );
				$response	= UI_HTML_Exception_TraceViewer::buildTrace( $e, 2 );
			}
			else if( $serializeException )
				$response	= serialize( $e );
			return $this->sendResponse( $requestData, $response );
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
	protected function sendResponse( $requestData, $content, $format = "html", $compressionType = NULL )
	{
		//  --  CONTENT TYPE  --  //
		if( !array_key_exists( $format, $this->contentTypes ) )
			throw new InvalidArgumentException( 'Content Type for Response Format "'.$format.'" is not defined.' );
		$contentType	= $this->contentTypes[$format];
		if( $this->charset )
			$contentType	.= "; charset=".$this->charset;

		//  --  COMPRESS CONTENT  --  //
		$compression	= isset( $requestData['compressResponse'] ) ? strtolower( $requestData['compressResponse'] ) : "";
		if( $compression )
		{
			if( !in_array( $compression, $this->compressionTypes ) )
				$compression	= $this->compressionTypes[0];
			$content	= self::compressResponse( $content, $compression );
		}

		//  --  BUILD RESPONSE  --  //
		$response	= new Net_HTTP_Request_Response();
		$response->write( $content );
		$response->addHeader( 'Last-Modified', date( 'r' ) );
		$response->addHeader( 'Cache-Control', "no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0" );
		$response->addHeader( 'Pragma', "no-cache" );
		$response->addHeader( 'Content-Type', $contentType );
#		$response->addHeader( 'Content-Length', strlen( $content ) );
		if( $compression )
			$response->addHeader( 'Content-Encoding', $compression );
		
		return $response->send();
	}
}
?>
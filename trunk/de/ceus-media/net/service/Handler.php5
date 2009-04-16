<?php
/**
 *	Service Handlers for HTTP Requests.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@package		net.service
 *	@extends		Net_Service_Response
 *	@uses			Net_HTTP_Request_Response
 *	@uses			UI_HTML_Exception_TraceViewer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.06.2007
 *	@version		0.6
 */
import( 'de.ceus-media.net.service.Response' );
import( 'de.ceus-media.net.http.request.Response' );
/**
 *	Service Handlers for HTTP Requests.
 *	@package		net.service
 *	@extends		Net_Service_Response
 *	@uses			Net_HTTP_Request_Response
 *	@uses			UI_HTML_Exception_TraceViewer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.06.2007
 *	@version		0.6
 */
class Net_Service_Handler extends Net_Service_Response
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
	 *	Handles Service Call by sending HTTP Response and returns Length of Response Content.
	 *	@param		array			$requestData			Request Array (or Object with ArrayAccess Interface)
	 *	@return		int
	 */
	public function handle( $requestData )
	{
		if( empty( $requestData['service'] ) )
			throw new InvalidArgumentException( 'No Service Name given.' );

		//  --  CALL SERVICE  --  //
		$service	= $requestData['service'];
		try
		{
			$format		= ( isset( $requestData['format'] ) && $requestData['format'] ) ? $requestData['format'] : $this->servicePoint->getDefaultServiceFormat( $service );
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
			if( trim( $errors ) )
				throw new RuntimeException( $errors );
			return $this->sendResponse( $requestData, $response, $format );
		}
		catch( Exception $e )
		{
			return $this->sendException( $requestData, $format, $e );
		}
	}

	/**
	 *	Encodes and responses an Exception as Data Array for requested Format.
	 *	@access		protected
	 *	@param		array			$requestData		Request Array (or Object with ArrayAccess Interface)
	 *	@param		string			$format				Requested Format
	 *	@param		Exception		$exception			Exception to encode
	 *	@return		int
	 */
	protected function sendException( $requestData, $format, $exception )
	{
		try
		{
			$response	= $this->convertToOutputFormat( $exception, $format, "exception" );
		}
		catch( Exception $e )
		{
			$response	= $exception->getMessage();
		}
		return $this->sendResponse( $requestData, $response, $format );
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
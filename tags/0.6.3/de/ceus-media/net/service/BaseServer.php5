<?php
import( 'de.ceus-media.net.service.Handler' );
import( 'de.ceus-media.net.service.Point' );
import( 'de.ceus-media.net.http.request.Receiver' );
import( 'de.ceus-media.ui.html.service.Index' );
class Net_Service_BaseServer extends UI_HTML_Service_Index
{
	public function __construct( $fileName = "services.xml", $allowedFormats = array() )
	{
		$requestHandler	= new Net_HTTP_Request_Receiver();
		$formatList		= array(
			'wddx',
			'php',
			'json',
			'xml',
			'rss',
			'atom',
			'txt'
		);
		if( is_array( $allowedFormats ) && count( $allowedFormats ) )
			$formatList	= $allowedFormats;

		try
		{
			$servicePoint	= new Net_Service_Point( $fileName );
			parent::__construct( $servicePoint, $formatList );
			if( $requestHandler->has( 'index' ) )
				$this->showServiceIndex( $servicePoint, $allowedFormats );
			$responseLength	= $this->handle( $requestHandler->getAll(), TRUE );
			$this->logRequest( $responseLength );
		}
		catch( Exception $e )
		{
			die( $e->getMessage() );
		}
	}
	
	protected function loadServiceClass( $className )
	{
		$class	= $this->services['services'][$serviceName]['class'];
		if( !class_exists( $class ) )
		{
			$parts	= explode( "_", $class );
			$last	= array_pop( $parts );
			$parts	= array_map( create_function( '$item', 'return strtolower( $item );' ), $parts );
			$path	= implode( ".",$parts  );
			$file	= $pathPrefix.$path.".".$last;
			import( $file );
		}
	}



	public function logRequest( $length )
	{
		error_log( time().":".$length."\n", 3, "response.log" );
	}

	/**
	 *	Builds _very_ simple Service List and exits.
	 *	Overwrite this Method to customize.
	 *	@access		protected
	 *	@param		Net_Service_Point	$servicePoint		Service Point Instance
	 *	@param		array				$allowedFormats		List of allowed Response Formats
	 *	@return		string
	 */
	protected function showServiceIndex( $servicePoint, $allowedFormats )
	{
		die( $this->getServiceList() );
	}
}
?>